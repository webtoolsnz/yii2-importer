<?php

namespace webtoolsnz\importer\actions;

use webtoolsnz\importer\ImporterController;
use Yii;
use yii\base\Action;
use webtoolsnz\importer\BaseImportModel;

/**
 * Class DeleteRecordAction
 * @package webtoolsnz\importer\actions
 *
 * @property ImporterController $controller
 */
class DeleteRecordAction extends Action
{
    /**
     * @param $id
     * @param $record_id
     * @return string|\yii\web\Response
     * @throws \yii\web\HttpException
     */
    public function run($id, $record_id)
    {
        if (!Yii::$app->request->isPost) {
            throw new \yii\web\HttpException(400, 'Invalid Request');
        }

        $model = $this->controller->findModel($id);
        $class = $model->import_model; /* @var BaseImportModel $class */
        $record = $class::findOne($record_id);
        $record->delete();

        return $this->controller->redirect(['review-errors', 'id' => $model->id]);
    }
}
