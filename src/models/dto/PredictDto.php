<?php
namespace mirocow\delivery\models\dto;

use mirocow\delivery\components\dto\AbstractDto;

/**
 * Class PredictDto
 * @package mirocow\delivery\models\dto
 */
class PredictDto extends AbstractDto
{
    /**
     * @var int стоимость доставки
     */
    public $cost = 0;

    /**
     * @var int срок доставки в днях
     */
    public $days = 0;

    /**
     * @var string
     */
    public $serviceCode;

    /**
     * @var string
     */
    public $serviceName;

    /**
     * Predict constructor.
     * @param $cost
     * @param $day
     */
    public function __construct($params = [])
    {
        $this->cost = $params['cost'] ?? 0;
        $this->days = $params['days'] ?? 0;
        $this->serviceCode = $params['serviceCode'] ?? 0;
        $this->serviceName = $params['serviceName'] ?? 0;
    }
}
