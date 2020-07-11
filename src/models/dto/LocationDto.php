<?php
namespace mirocow\delivery\models\dto;

use mirocow\delivery\components\dto\AbstractDto;

/**
 * Class LocationDto
 * @package mirocow\delivery\models\dto
 */
class LocationDto extends AbstractDto
{
    /**
     * @var integer ID локации
     */
    public $location_id;

    /**
     * @var string Наименование локации
     */
    public $location_name;

    /**
     * Location constructor.
     * @param string $location_id
     */
    public function __construct($location_id, $location_name = '')
    {
        $this->location_id = $location_id;
        $this->location_name = $location_name;
    }
}
