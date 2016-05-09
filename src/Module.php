<?php
namespace webtoolsnz\importer;

use Yii;
use yii\base\BootstrapInterface;

/**
 * Class Module
 * @package webtoolsnz\importer
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * Bootstrap the console controllers.
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        Yii::setAlias('@importer', dirname(__DIR__).'/src');

        if ($app instanceof \yii\console\Application && !isset($app->controllerMap[$this->id])) {
            $app->controllerMap[$this->id] = [
                'class' => 'webtoolsnz\importer\console\DefaultController',
            ];
        }
    }
}
