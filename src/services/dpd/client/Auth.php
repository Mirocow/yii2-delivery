<?php
namespace mirocow\delivery\services\dpd\client;

use mirocow\delivery\services\dpd\client\interfaces\AuthInterface;

/**
 * Класс реализует доступ к метода API
 * @package mirocow\delivery\services\dpd\client
 */
class Auth implements AuthInterface
{
    /**
     * @var string номер клиента DPD
     */
    protected $clientNumber;

    /**
     * @var string секретный ключ DPD
     */
    protected $secretKey;

    /**
     * @var bool режим запросов
     */
    protected $testMode;

    /**
     * Auth constructor.
     * @param $clientNumber
     * @param $secretKey
     * @param bool $testMode
     */
    public function __construct($clientNumber, $secretKey, $testMode = false)
    {
        $this->clientNumber        = $clientNumber;
        $this->secretKey           = $secretKey;
        $this->testMode            = (bool) $testMode;
    }

    /**
     * @brief возвращает номер клиента DPD
     * @return string
     */
    public function getClientNumber()
    {
        return $this->clientNumber;
    }

    /**
     * @brief возвращает токен авторизации DPD
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * @brief проверяет включен ли режим тестирования
     * @return boolean
     */
    public function isTestMode()
    {
        return (bool) $this->testMode;
    }

    /**
     * @brief конвертирует переданный uri в соответствии с тестовым режимом
     * @param string $uri
     * @return string
     */
    public function resolveWsdl($uri)
    {
        if ($this->testMode) {
            return str_replace('ws.dpd.ru', 'wstest.dpd.ru', $uri);
        }
        return $uri;
    }
}
