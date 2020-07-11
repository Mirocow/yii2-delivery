<?php
namespace mirocow\delivery\services\dpd\services;

use mirocow\delivery\services\dpd\client\Auth;
use mirocow\delivery\services\dpd\client\SoapClient;
use mirocow\delivery\components\ServiceAbstract;
use DomainException;
use Exception;

/**
 * Class DPDCalculatorService служба расчета стоимости доставки
 * @package mirocow\delivery\components\dpd\services
 */
class DPDCalculatorService extends ServiceAbstract
{
    /**
     * @var string
     */
    protected $wsdl = '/services/calculator2?wsdl';

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
        $this->client = new SoapClient('http://' . env('DPD_HOST') . $this->wsdl, new Auth(env('DPD_NUMBER'), env('DPD_KEY'), $testMode));
    }

    /**
     * @brief рассчитать общую стоимость доставки по России и странам ТС.
     * @param array $params
     * @return array|bool|mixed|string
     * @throws Exception
     */
    public function getServiceCost(array $params)
    {
        return $this->client->invoke('getServiceCost2', $params, 'request', 'serviceCode');
    }

    /**
     * @brief рассчитать стоимость доставки по параметрам  посылок по России и странам ТС.
     * @param array $params
     * @return array|bool|mixed|string
     * @throws Exception
     */
    public function getServiceCostByParcels(array $params)
    {
        return $this->client->invoke('getServiceCostByParcels2', $params, 'request', 'serviceCode');
    }

    /**
     * @brief рассчитать общую стоимость доставки по международным направлениям
     * @param array $params
     * @return array|bool|mixed|string
     * @throws Exception
     */
    public function getServiceCostInternational(array $params)
    {
        return $this->client->invoke('getServiceCostInternational', $params, 'request');
    }
}
