<?php

namespace mirocow\delivery\services\dpd\repositories\ar;

use mirocow\delivery\services\dpd\dto\DPDLocationDto;
use mirocow\delivery\services\dpd\models\DpdLocation;
use mirocow\delivery\services\dpd\repositories\DPDLocationRepositoryInterface;
use Yii;

/**
 * Class DPDLocationRepository
 * @package mirocow\delivery\services\dpd\repositories\ar
 */
class DPDLocationRepository implements DPDLocationRepositoryInterface
{
    /**
     * @inheritDoc
     * @param array $conditions
     * @return DPDLocationDto|null
     */
    public function findOne($conditions): ?DPDLocationDto
    {
        return $this->extractDto(DpdLocation::findOne($conditions));
    }

    /**
     * @inheritDoc
     * @param array $conditions
     * @param null $limit
     * @return array
     */
    public function findAll($conditions = null, $limit = null): array
    {
        $query = DpdLocation::find();
        if ($limit !== null) {
            $query->limit($limit);
        }
        return $this->extractDto($query->where($conditions)->all());
    }

    /**
     * @inheritDoc
     * @param $conditions
     * @return bool
     */
    public function exist($conditions): bool
    {
        return DpdLocation::find()->where($conditions)->exists();
    }

    /**
     * @inheritDoc
     * @param DPDLocationDto $dto
     * @return DPDLocationDto|null
     */
    public function create(DPDLocationDto $dto): ?DPDLocationDto
    {
        $model = new DpdLocation();
        $model->location_id = $dto->location_id;
        $model->location_type = $dto->location_type;
        $model->country_name = $dto->country_name;
        $model->country_code = $dto->country_code;
        $model->city_code = $dto->city_code;
        $model->city_name = $dto->city_name;
        $model->orig_name = $dto->orig_name;
        $model->country_name_lower = $dto->country_name_lower;
        $model->city_name_lower = $dto->city_name_lower;
        if (!$model->save()) {
            Yii::error([
                'msg' => 'Save. Ошибка сохранения Delivery (ar/DeliveryRepository::create)',
                'data' => [
                    'error' => $model->getErrors(),
                    'attr' => $model->attributes,
                    'attr_error' => $model->getAttributes(array_keys($model->getErrors())),
                ],
            ]);
            return null;
        }
        return $this->extractDto($model);
    }

    /**
     * @brief извлекает данные из модели
     * @param DpdLocation $model
     * @return DPDLocationDto
     */
    private function extractItemDto($model)
    {
        if ($model) {
            $dto = new DPDLocationDto();
            $dto->id = $model->id;
            $dto->location_id = $model->location_id;
            $dto->location_type = $model->location_type;
            $dto->country_name = $model->country_name;
            $dto->country_code = $model->country_code;
            $dto->city_code = $model->city_code;
            $dto->city_name = $model->city_name;
            $dto->orig_name = $model->orig_name;
            $dto->country_name_lower = $model->country_name_lower;
            $dto->city_name_lower = $model->city_name_lower;
            return $dto;
        }
        return null;
    }

    /**
     * @brief извлекает данные из списка моделей или одной модели
     * @param $models
     * @return DPDLocationDto[]|DPDLocationDto
     */
    private function extractDto($models)
    {
        if (is_array($models)) {
            $models = array_map(function ($model) {
                return $this->extractItemDto($model);
            }, $models);
        } else {
            $models = $this->extractItemDto($models);
        }
        return $models;
    }
}
