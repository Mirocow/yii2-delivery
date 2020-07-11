<?php

namespace mirocow\delivery\components\dto;

use mirocow\delivery\exceptions\ExceptionService;

/**
 * Class AbstractDto
 * @package common\dto
 */
class AbstractDto
{
    /**
     * @brief Загружаем поля из объекта
     * @param $object
     */
    public function loadFromObject($object)
    {
        foreach (get_object_vars($object) as $name) {
            if (property_exists($this, $name)) {
                $this->$name = $object->$name;
            }
        }
    }

    /**
     * @brief Получить все данные DTO в виде массива
     * @return array
     */
    public function toArray()
    {
        $data = [];
        foreach (get_object_vars($this) as $name => $val) {
            $data[$name] = $val;
        }
        return $data;
    }

    /**
     * @brief создание DTO из значений массива
     * @param array $attributes
     * @return void
     * @throws \Exception
     */
    public static function createFromArray(array $attributes)
    {
        throw new ExceptionService(\Yii::t('app', 'Используй явное создание объекта "new Dto()"'));
    }
}
