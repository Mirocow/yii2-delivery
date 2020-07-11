<?php
namespace mirocow\delivery\services\dpd\services;

use mirocow\delivery\services\dpd\client\Auth;
use mirocow\delivery\services\dpd\client\SoapClient;
use mirocow\delivery\components\ServiceAbstract;
use DomainException;

/**
 * Class DPDCreateOrderService служба заказа доставки
 * @package mirocow\delivery\components\dpd\services
 */
class DPDCreateOrderService extends ServiceAbstract
{
    protected $wdsl = 'http://ws.dpd.ru/services/order2?wsdl';

    /**
     * DPDCalculatorService constructor.
     * @throws DomainException
     */
    public function __construct()
    {
        $testMode = (YII_ENV != 'prod' && !YII_ENV_DEV);
        $this->client = new SoapClient($this->wdsl, new Auth(env('DPD_NUMBER'), env('DPD_KEY'), $testMode));
    }

    /**
     * Создает заказ в системе DPD
     *
     * @param  array $parms
     *
     * @return array
     */
    public function createOrder($parms)
    {
        return $this->client->invoke('createOrder', $parms, 'orders');
    }

    /**
     * Отменяет заказ
     *
     * @param  integer $internalNumber     Внутренний номер заказа
     * @param  integer $externalNumber     Номер заказа DPD
     * @param  string  $pickupDate         Дата приёма груза
     *
     * @return array
     */
    public function cancelOrder($internalNumber, $externalNumber, $pickupDate = false)
    {
        return $this->client->invoke('cancelOrder', array(
            'cancel' => array_filter(array(
                'orderNumberInternal' => $internalNumber,
                'orderNum'            => $externalNumber,
                'pickupdate'          => $pickupDate,
            )),
        ), 'orders');
    }

    /**
     * Проверяет статус заказа
     *
     * @param integer $internalNumber
     * @param string  $pickupDate
     *
     * @return array
     */
    public function getOrderStatus($internalNumber, $pickupDate = false)
    {
        return $this->client->invoke('getOrderStatus', array(
            'order' => array_filter(array(
                'orderNumberInternal' => $internalNumber,
                'datePickup' => $pickupDate,
            )),
        ), 'orderStatus');
    }

    /**
     * Получает файл накладной
     *
     * Если не заданы parcelCount или cargoValue, то при формировании файла выводятся параметры из заказа.
     *
     * @param  string  $orderNum    Номер заказа DPD
     * @param  int     $parcelCount Количество мест в заказе
     * @param  double  $cargoValue  Сумма объявленной ценности, руб.
     *
     * @return mixed
     */
    public function getInvoiceFile($orderNum, $parcelCount = false, $cargoValue = false)
    {
        $ret = $this->client->invoke('getInvoiceFile', array_filter(array(
            'orderNum'    => $orderNum,
            'parcelCount' => $parcelCount,
            'cargoValue'  => $cargoValue,
        )), 'request');

        return $ret;
    }

    /**
     * @param string $datePickup   Дата приёма груза
     * @param string $regularNum   Номер регулярного заказа DPD
     * @param string $cityPickupId Идентификатор города приёма груза в системе DPD
     * @param string $addressCode  Код адреса в информационных системах заказчика и DPD
     *
     * @return mixed
     */
    public function getRegisterFile($datePickup, $regularNum = false, $cityPickupId = false, $addressCode = false)
    {
        return $this->client->invoke('getRegisterFile', array_filter(array(
            'DATE_PICKUP'    => $datePickup,
            'REGULAR_NUM'    => $regularNum,
            'CITY_PICKUP_ID' => $cityPickupId,
            'ADDRESS_CODE'   => $addressCode,
        )), 'request');
    }
}
