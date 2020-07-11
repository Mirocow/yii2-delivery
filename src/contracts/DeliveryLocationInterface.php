<?php
namespace mirocow\delivery\contracts;

use mirocow\delivery\models\dto\LocationDto;
use mirocow\delivery\models\dto\RequestDto;

/**
 * @brief интерфейс описывает реализацию работы сервисом проверки адреса доставки
 * Interface DeliveryLocationInterface
 */
interface DeliveryLocationInterface
{
    /**
     * @brief Получение id локации
     * @param RequestDto $requests
     * @return LocationDto[]|null
     */
    public function checkAddresses(RequestDto $requests) :? LocationDto;
}
