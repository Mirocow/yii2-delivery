<?php
namespace mirocow\delivery\services\dpd\services;

use mirocow\delivery\services\dpd\client\Auth;
use mirocow\delivery\services\dpd\client\SoapClient;
use mirocow\delivery\components\ServiceAbstract;
use DomainException;

/**
 * Class DPDGeographyService служба доступа к данным георграфии доставки
 * @package mirocow\delivery\components\dpd\services
 */
class DPDGeographyService extends ServiceAbstract
{
    /**
     * @var string
     */
    protected $wsdl = '/services/geography2?wsdl';

    /**
     * @var SoapClient
     */
    private $client;

    /**
     * DPDCalculatorService constructor.
     * @throws DomainException
     */
    public function __construct()
    {
        $testMode = (YII_ENV != 'prod' && !YII_ENV_DEV);
        $this->client = new SoapClient(env('DPD_HOST') . $this->wsdl, new Auth(env('DPD_NUMBER'), env('DPD_KEY'), $testMode));
    }

    /**
     * @brief
     * @param array $params
     * @return array|bool|mixed|string
     * @throws \Exception
     */
    public function getCitiesCashPay(array $params)
    {
        return $this->client->invoke('getCitiesCashPay', $params, 'request', 'serviceCode');
    }
}