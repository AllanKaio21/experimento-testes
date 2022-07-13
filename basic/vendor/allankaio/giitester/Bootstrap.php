<?php

namespace allankaio\giitester;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if ($app->hasModule('gii')) {
            if (!isset($app->getModule('gii')->generators['easyii-gii'])) {
                $app->getModule('gii')->generators['easyii-gii-model'] = 'allankaio\giitester\model\Generator';
                $app->getModule('gii')->generators['easyii-gii-crud']['class'] = 'allankaio\giitester\crud\Generator';
                $app->getModule('gii')->generators['easyii-gii-tests'] = 'allankaio\giitester\tests\Generator';
                $app->getModule('gii')->generators['easyii-gii-migration'] = 'allankaio\giitester\migration\Generator';
            }
        }
    }
}
