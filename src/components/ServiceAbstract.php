<?php
namespace mirocow\delivery\components;

use mirocow\delivery\exceptions\ExceptionService;

/**
 * Class ServiceAbstract
 * @package mirocow\delivery
 */
abstract class ServiceAbstract
{
    /**
     * @var array
     */
    private $errors = [];

    /**
     * Вывести исключения
     * @param $message
     * @throws ExceptionService
     */
    protected function setException($message)
    {
        throw new ExceptionService($message);
    }

    /**
     * Добавить ошибку
     * @param $error
     * @param array $params
     */
    protected function addError($error, $params = [])
    {
        $this->errors[] = [
            'code' => $error,
            'params' => $params,
        ];
    }

    /**
     * Список ошибок
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Проверка наличия ошибки
     * @param $error
     * @return bool
     */
    public function issetError($error)
    {
        foreach ($this->errors as $item) {
            if ($item['code'] == $error) {
                return true;
            }
        }
        return false;
    }

    /**
     * Получаем параметры ошибки
     * @param $error
     * @return null
     */
    public function getFirstError($error)
    {
        foreach ($this->errors as $item) {
            if ($item['code'] == $error) {
                return $item['params'];
            }
        }
        return null;
    }
}
