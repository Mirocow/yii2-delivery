<?php
namespace mirocow\delivery;

use mirocow\delivery\contracts\DeliveryCalculationInterface;
use mirocow\delivery\contracts\DeliveryLocationInterface;
use mirocow\delivery\exceptions\ExceptionService;
use mirocow\delivery\exceptions\ExceptionServiceClient;
use mirocow\delivery\models\dto\CalculateDeliveryDto;
use mirocow\delivery\models\dto\LocationDto;
use mirocow\delivery\models\dto\PredictDto;
use mirocow\delivery\models\dto\RequestDto;
use mirocow\delivery\services\dpd\contract\Constants;
use mirocow\delivery\services\dpd\DeliveryCalculationDPD;
use mirocow\delivery\services\dpd\DeliveryLocationDPD;
use Yii;

/**
 * geo module definition class
 * @package mirocow\delivery
 */
class Module extends yii\base\Module
{
    public $dpd_host = 'ws.dpd.ru';
    public $dpd_number = '';
    public $dpd_key = '';
    public $dpd_ftp_host = 'ftp.dpd.ru';
    public $dpd_ftp_port = 21;
    public $dpd_ftp_user = '';
    public $dpd_ftp_password = '';

    /**
     * Вычисляет адрес доставки
     * @param RequestDto[] $requests
     * @param DeliveryLocationInterface|null $deliveryClass
     *
     * @return LocationDto[]|null
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function request(array $requests = [], $deliveryClass = null)
    {
        /**
         * По умолчанию выполняем расчет через сервис DPD
         */
        if(!$deliveryClass){
            $deliveryClass = DeliveryLocationDPD::class;
        }

        Yii::$container->setDefinitions([
            DeliveryLocationInterface::class => $deliveryClass
        ]);

        /** @var  DeliveryLocationInterface $location */
        $location = Yii::$container->get(DeliveryLocationInterface::class);

        $results = [];
        foreach ($requests as $dto) {
            if ($result = $location->checkAddresses($dto)) {
                $results[] = $result;
            }
        }
        return $results;
    }

    /**
     * @brief Вычисляет стоимость доставки
     * @param CalculateDeliveryDto $dto
     * @return PredictDto[]
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function calculate(CalculateDeliveryDto $dto, $deliveryClass = null)
    {
        /**
         * По умолчанию выполняем расчет через сервис DPD
         */
        if(!$deliveryClass){
            $deliveryClass = DeliveryCalculationDPD::class;
        }

        Yii::$container->setDefinitions([
            DeliveryCalculationInterface::class => $deliveryClass
        ]);

        /** @var  DeliveryCalculationInterface $calculator */
        $calculator = Yii::$container->get(DeliveryCalculationInterface::class);

        $serviceCodes = [
            Constants::SERVICE_CODE_DPD_ECONOMY,
            Constants::SERVICE_CODE_DPD_CLASSIC,
            Constants::SERVICE_CODE_DPD_ONLINE_EXPRESS,
            Constants::SERVICE_CODE_DPD_OPTIMUM,
        ];

        $results = [];
        $errors = [];
        foreach ($serviceCodes as $serviceCode) {
            try {
                /** @var PredictDto $result */
                if ($result = $calculator->getCostWithWeight($dto->fromLocation, $dto->toLocation, $dto->weight, $serviceCode)) {
                    $result->cost += ($dto->company_percent / 100) * $result->cost;
                    $results[] = $result;
                }
            } catch (ExceptionServiceClient $e) {
                $errors[] = $e->getMessage();
                continue;
            }
        }

        if($errors){
            throw new ExceptionService($errors);
        }

        return $results;
    }
}
