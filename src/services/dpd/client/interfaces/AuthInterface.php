<?php
namespace mirocow\delivery\services\dpd\client\interfaces;

/**
 * Interface AuthInterface
 * @package mirocow\delivery\services\dpd\client\interfaces
 */
interface AuthInterface
{
    /**
     * @brief возвращает номер клиента
     * @return string
     */
    public function getClientNumber();

    /**
     * @brief Возвращает ключ авторизации к API
     * @return string
     */
    public function getSecretKey();

    /**
     * @brief Возвращает включен ли тестовый режим
     * @return boolean
     */
    public function isTestMode();
}
