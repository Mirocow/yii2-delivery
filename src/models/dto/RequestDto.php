<?php
namespace mirocow\delivery\models\dto;

use mirocow\delivery\components\dto\AbstractDto;

/**
 * Class RequestDto описывает модель запроса информации по адресу
 * @package mirocow\delivery\models\dto
 */
class RequestDto extends AbstractDto
{
    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $region;

    /**
     * @var string
     */
    public $distrcict;

    /**
     * @var string
     */
    public $city;

    /**
     * @var string
     */
    public $address;

    /**
     * @var int
     */
    public $company_percent = 5;

    /**
     * @var string
     */
    public $location_type;
}