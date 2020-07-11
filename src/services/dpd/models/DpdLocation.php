<?php
namespace mirocow\delivery\services\dpd\models;

use Yii;

/**
 * @brief Class DpdLocation
 * @package common\models
 *
 * @property integer id
 * @property integer location_id
 * @property string location_type
 * @property string country_name
 * @property string country_code
 * @property string city_code
 * @property string city_name
 * @property string orig_name
 * @property string country_name_lower
 * @property string city_name_lower
 */
class DpdLocation extends \yii\db\ActiveRecord
{
    const ATTR_ID = 'id';
    const ATTR_LOCATION_ID = 'location_id';
    const ATTR_LOCATION_TYPE = 'location_type';
    const ATTR_COUNTRY_NAME = 'country_name';
    const ATTR_COUNTRY_CODE = 'country_code';
    const ATTR_CITY_NAME = 'city_name';
    const ATTR_CITY_CODE = 'city_code';
    const ATTR_ORIG_NAME = 'orig_name';
    const ATTR_COUNTRY_NAME_LOWER = 'country_name_lower';
    const ATTR_CITY_NAME_LOWER = 'city_name_lower';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dpd_location}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [];
    }

    /**
     * @inheritDoc
     * @return array
     */
    public function attributeLabels()
    {
        return [
            static::ATTR_ID => Yii::t('app', 'ИД'),
            static::ATTR_LOCATION_ID => Yii::t('app', 'ИД локации в системе'),
            static::ATTR_LOCATION_TYPE => Yii::t('app', 'Тип локации в системе'),
            static::ATTR_COUNTRY_NAME => Yii::t('app', 'Страна'),
            static::ATTR_COUNTRY_CODE => Yii::t('app', 'Код страны'),
            static::ATTR_CITY_CODE => Yii::t('app', 'Код города'),
            static::ATTR_CITY_NAME => Yii::t('app', 'Город'),
            static::ATTR_ORIG_NAME => Yii::t('app', 'Адрес'),
            static::ATTR_COUNTRY_NAME_LOWER => Yii::t('app', 'Страна в нижнем регистре'),
            static::ATTR_CITY_NAME_LOWER => Yii::t('app', 'Город в нижнем регистре'),
        ];
    }
}
