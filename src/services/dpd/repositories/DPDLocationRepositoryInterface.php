<?php
namespace mirocow\delivery\services\dpd\repositories;

use mirocow\delivery\services\dpd\dto\DPDLocationDto;

/**
 * Interface DPDLocationRepositoryInterface
 * @package mirocow\delivery\components\dpd\repositories
 */
interface DPDLocationRepositoryInterface
{
    /**
     * @brief поиск записи
     * @param array $conditions
     * @return DPDLocationDto|null
     */
    public function findOne($conditions) :? DPDLocationDto;

    /**
     * @brief поиск записей
     * @param array $conditions
     * @param integer $limit
     * @return DPDLocationDto[]
     */
    public function findAll($conditions = null, $limit = null) : array;

    /**
     * @brief создание новой записи
     * @param DPDLocationDto $dto
     * @return DPDLocationDto|null
     */
    public function create(DPDLocationDto $dto) :? DPDLocationDto;

    /**
     * @brief проверка наличия записи
     * @param $conditions
     * @return bool
     */
    public function exist($conditions) : bool;
}
