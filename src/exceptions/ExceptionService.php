<?php
namespace mirocow\delivery\exceptions;

use yii\base\Exception;
use yii\helpers\Json;

/**
 * Class ExceptionService
 * @package common\services\exception
 */
class ExceptionService extends Exception
{
    public function __construct($message, $code = 0, \Throwable $previous = null)
    {
        if(is_array($message)){
            $this->message = Json::encode($message);
        } else {
            $this->message = $message;
        }
    }
}
