<?php

namespace webtoolsnz\importer\actions;

use webtoolsnz\importer\ImporterController;
use Yii;
use yii\base\Action;

/**
 * Class ConfirmAction
 * @package webtoolsnz\importer\actions
 *
 * @property ImporterController $controller
 */
class ConfirmAction extends Action
{
    /**
     * @var string
     */
    public $view = '@importer/views/confirm';

    /**
     * @param $id
     * @return string
     * @throws \Exception
     * @throws \yii\web\HttpException
     */
    public function run($id)
    {
        if (!Yii::$app->request->isPost) {
            throw new \yii\web\HttpException(400, 'Invalid Request');
        }

        $model = $this->controller->findModel($id);
        $results = $model->importRecords($commit = true);
        $model->delete();

        return $this->controller->render($this->view, ['results' => $results]);
    }
}
