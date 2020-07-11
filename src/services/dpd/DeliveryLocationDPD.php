<?php
namespace mirocow\delivery\services\dpd;

use mirocow\delivery\exceptions\ExceptionService;
use mirocow\delivery\contracts\DeliveryLocationInterface;
use mirocow\delivery\models\dto\RequestDto;
use mirocow\delivery\services\dpd\contract\Constants;
use mirocow\delivery\services\dpd\services\DPDLocationService;
use mirocow\delivery\models\dto\LocationDto;
use Yii;

/**
 * Class DeliveryLocationDPD
 * @package mirocow\delivery\components\dpd
 */
class DeliveryLocationDPD implements DeliveryLocationInterface
{
    /**
     * @var DPDLocationService
     */
    private $serDPDLocation;

    /**
     * DeliveryCalculationDPD constructor.
     * @param DPDLocationService $serDPDLocation
     */
    public function __construct(DPDLocationService $serDPDLocation)
    {
        $this->serDPDLocation = $serDPDLocation;
    }

    /**
     * @brief Получение id локации
     * @param RequestDto $dto
     * @return LocationDto[]|null
     */
    public function checkAddresses(RequestDto $dto) :? LocationDto
    {
        if(!isset($dto->country)){
            new ExceptionService(Yii::t('app', 'Country can`t be empty'));
        }

        if(!isset($dto->city)){
            new ExceptionService(Yii::t('app', 'City can`t be empty'));
        }

        $dto->location_type = Constants::LOCATION_TYPE_CITY;

        $location = $this->serDPDLocation->getLocation($dto);

        return new LocationDto($location);
    }
}