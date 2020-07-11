<?php
namespace mirocow\delivery\services\dpd\dto;

use mirocow\delivery\components\dto\AbstractDto;

/**
 * Class DPDLocationDto
 * @package mirocow\delivery\services\dpd\dto
 */
class DPDLocationDto extends AbstractDto
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var integer
     */
    public $location_id;

    /**
     * @var string
     */
    public $location_type;

    /**
     * @var string
     */
    public $country_name;

    /**
     * @var string
     */
    public $country_code;

    /**
     * @var integer
     */
    public $city_id;

    /**
     * @var string
     */
    public $city_code;

    /**
     * @var string
     */
    public $city_name;

    /**
     * @var string
     */
    public $orig_name;

    /**
     * @var string
     */
    public $country_name_lower;

    /**
     * @var string
     */
    public $city_name_lower;
}
