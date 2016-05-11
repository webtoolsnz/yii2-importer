<?php

namespace webtoolsnz\importer\actions;

use webtoolsnz\importer\ImporterController;
use webtoolsnz\importer\models\Import;
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

        if ($model->getModelInstance()->deleteAfterImport) {
            $model->delete();
        } else {
            $model->status_id = Import::STATUS_COMPLETE;
            $model->update(false, ['status_id']);
            $class = $model->import_model;
            $class::deleteAll(['import_id' => $model->id]);
        }


        return $this->controller->render($this->view, ['results' => $results]);
    }
}
