<?php

namespace mirocow\delivery\bootstrap;

use mirocow\delivery\services\dpd\repositories\ar\DPDLocationRepository;
use mirocow\delivery\services\dpd\repositories\DPDLocationRepositoryInterface;
use Yii;
use yii\base\BootstrapInterface;

/**
 * @brief Создает зависимсти классов
 * Class Definitions
 * @package common\bootstrap
 */
class Definitions implements BootstrapInterface
{
    /**
     * @return array
     */
    private function getDefinitions()
    {
        return [
            DPDLocationRepositoryInterface::class => [
                'class' => DPDLocationRepository::class
            ],
        ];
    }

    /**
     * @inheritDoc
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        Yii::$container->setDefinitions($this->getDefinitions());
    }
}
