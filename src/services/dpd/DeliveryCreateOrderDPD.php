<?php
namespace mirocow\delivery\services\dpd;

use mirocow\delivery\contracts\DeliveryCreateOrderInterface;
use mirocow\delivery\services\dpd\services\DPDCreateOrderService;

/**
 * Class DeliveryCalculationDPD
 * @package mirocow\delivery\components\dpd
 */
class DeliveryCreateOrderDPD implements DeliveryCreateOrderInterface
{
    /**
     * @var DPDCreateOrderService
     */
    private $serDPDCreateOrderService;

    /**
     * DeliveryCalculationDPD constructor.
     * @param DPDCreateOrderService $serDPDCreateOrderService
     */
    public function __construct(DPDCreateOrderService $serDPDCreateOrderService)
    {
        $this->serDPDCreateOrderService = $serDPDCreateOrderService;
    }
}