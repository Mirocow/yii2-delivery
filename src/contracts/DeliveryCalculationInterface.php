<?php
namespace mirocow\delivery\contracts;

use mirocow\delivery\models\dto\LocationDto;
use mirocow\delivery\models\dto\PredictDto;

/**
 * @brief интерфейс описывает реализацию работы с сервисами расчета доставки
 * Interface DeliveryCalculationMethodInterface
 */
interface DeliveryCalculationInterface
{
    /**
     * @brief расчет стоимости доставки
     * @param LocationDto $fromLocation
     * @param LocationDto $toLocation
     * @param float|null $weight
     * @return PredictDto|null
     */
    public function getCostWithWeight(LocationDto $fromLocation, LocationDto $toLocation, float $weight, string $serviceCode) :? PredictDto;
}
