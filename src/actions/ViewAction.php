<?php

namespace webtoolsnz\importer\actions;

use webtoolsnz\importer\ImporterController;
use Yii;
use yii\base\Action;
use webtoolsnz\importer\models\Import;
use yii\web\Response;

/**
 * Class ViewAction
 * @package webtoolsnz\importer\actions
 *
 * @property ImporterController $controller
 */
class ViewAction extends Action
{
    /**
     * @var string
     */
    public $view = '@importer/views/view';

    /**
     * @return bool
     */
    public function beforeRun()
    {
        Yii::$app->view->title = 'View Import';
        Yii::$app->view->params['breadcrumbs'][] = Yii::$app->view->title;

        return parent::beforeRun();
    }

    /**
     * @param $id
     * @return array|string|Response
     * @throws \yii\web\HttpException
     */
    public function run($id)
    {
        $model = $this->controller->findModel($id);

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}
