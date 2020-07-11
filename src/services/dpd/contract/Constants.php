<?php
namespace mirocow\delivery\services\dpd\contract;

/**
 * Class Constants
 * @package mirocow\delivery\components\dpd\contract
 */
class Constants
{
    /**
     * Типы доставок DPD
     * @brief https://www.dpd.ru/docs/ru/integration/ws-integration-guide.docx
     * @brief https://bitbucket.org/DPDinRussia/dpd.sdk
     */
    const SERVICE_CODE_DPD_BZP = 'BZP';
    const SERVICE_CODE_DPD_ECONOMY = 'ECN';
    const SERVICE_CODE_DPD_ECONOMY_CU = 'ECU';
    const SERVICE_CODE_DPD_CLASSIC = 'CUR';
    const SERVICE_CODE_DPD_EXPRESS = 'NDY';
    const SERVICE_CODE_DPD_ONLINE_EXPRESS = 'CSM';
    const SERVICE_CODE_DPD_OPTIMUM = 'PCL';
    const SERVICE_CODE_DPD_SHOP = 'PUP';
    const SERVICE_CODE_DPD_CLASSIC_IMPORT = 'DPI';
    const SERVICE_CODE_DPD_CLASSIC_XPORT = 'DPE';
    const SERVICE_CODE_DPD_MAX = 'MAX';
    const SERVICE_CODE_DPD_ONLINE_MAX = 'MXO';

    /**
     * Тип локации
     */
    const LOCATION_TYPE_CITY = 'г';
}
