<?php
namespace mirocow\delivery\services\dpd\services;

use mirocow\delivery\models\dto\RequestDto;
use mirocow\delivery\components\ServiceAbstract;
use mirocow\delivery\services\dpd\dto\DPDLocationDto;
use mirocow\delivery\services\dpd\repositories\ar\DPDLocationRepository;

/**
 * Class DPDLocationService служба вычисляет адрес доставки
 * @package mirocow\delivery\components\dpd\services
 */
class DPDLocationService extends ServiceAbstract
{
    public function getLocation(RequestDto $dto):? DPDLocationDto
    {
        return (new DPDLocationRepository())->findOne([
            'location_type' => $dto->location_type,
            'country_name_lower' => $dto->country,
            'city_name_lower' => $dto->city
        ]);
    }
}