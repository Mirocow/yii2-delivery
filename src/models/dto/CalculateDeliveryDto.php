<?php
namespace mirocow\delivery\models\dto;

use mirocow\delivery\components\dto\AbstractDto;

/**
 * Class CalculateDeliveryDto
 * @package mirocow\delivery\models\dto
 */
class CalculateDeliveryDto extends AbstractDto
{
    /**
     * @var LocationDto
     */
    public $fromLocation;

    /**
     * @var LocationDto
     */
    public $toLocation;

    /**
     * @var float
     */
    public $weight = 0;

    /**
     * @var int
     */
    public $company_percent = 5;
}
