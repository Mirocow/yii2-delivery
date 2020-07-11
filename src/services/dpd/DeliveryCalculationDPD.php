<?php
namespace mirocow\delivery\services\dpd;

use mirocow\delivery\services\dpd\contract\Constants;
use mirocow\delivery\models\dto\PredictDto;
use mirocow\delivery\services\dpd\services\DPDCalculatorService;
use mirocow\delivery\contracts\DeliveryCalculationInterface;
use mirocow\delivery\models\dto\LocationDto;

/**
 * Class DeliveryCalculationDPD
 * @package mirocow\delivery\components\dpd
 */
class DeliveryCalculationDPD implements DeliveryCalculationInterface
{
    /**
     * @var DPDCalculatorService
     */
    private $serDPDCalculator;

    /**
     * DeliveryCalculationDPD constructor.
     * @param DPDCalculatorService $serDPDCalculator
     */
    public function __construct(DPDCalculatorService $serDPDCalculator)
    {
        $this->serDPDCalculator = $serDPDCalculator;
    }

    /**
     * @param LocationDto $fromLocation
     * @param LocationDto $toLocation
     * @param float|null $weight
     * @param string $serviceCode
     * @return PredictDto|null
     * @throws \Exception
     */
    public function getCostWithWeight(LocationDto $fromLocation, LocationDto $toLocation, float $weight, $serviceCode = Constants::SERVICE_CODE_DPD_ECONOMY): ?PredictDto
    {
        $params = [
            'selfPickup' => false, // Доставка от терминала
            'selfDelivery' => true, // Доставка до терминала
            'weight' => $weight,
            'serviceCode' => $serviceCode,
            'pickup' => [
                'cityId' => $fromLocation->location_id,
                'cityName' => $fromLocation->location_name,
            ],
            'delivery' => [
                'cityId' => $toLocation->location_id,
                'cityName' => $toLocation->location_name,
            ],
        ];

        // отправляем запрос к DPD
        $result = $this->serDPDCalculator->getServiceCost($params);
        if (isset($result[0]['cost']) && isset($result[0]['days'])) {
            return new PredictDto($result[0]);
        }
        return null;
    }
}
