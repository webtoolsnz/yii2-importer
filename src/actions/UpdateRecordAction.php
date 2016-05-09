<?php

namespace webtoolsnz\importer\actions;

use webtoolsnz\importer\ImporterController;
use Yii;
use yii\base\Action;
use webtoolsnz\importer\models\Import;
use webtoolsnz\importer\BaseImportModel;
use webtoolsnz\importer\interfaces\ImportInterface;

/**
 * Class UpdateRecordAction
 * @package webtoolsnz\importer\actions
 *
 * @property ImporterController $controller
 */
class UpdateRecordAction extends Action
{
    /**
     * @var string
     */
    public $view = '@importer/views/update-record';

    /**
     * @param $id
     * @param $record_id
     * @return string|\yii\web\Response
     * @throws \yii\web\HttpException
     */
    public function run($id, $record_id)
    {
        $model = $this->controller->findModel($id);
        $class = $model->import_model;
        /* @var BaseImportModel $class */
        $record = $class::findOne($record_id);
        /* @var ImportInterface|BaseImportModel $record */

        $record->setScenario(Import::SCENARIO_IMPORT_VALIDATE);
        $record->validate();

        if ($record->load(Yii::$app->request->post())) {
            $record->import_status_id = BaseImportModel::STATUS_SUCCESS;
            $record->import_error = null;
            if ($record->save()) {
                return $this->controller->reRoute($model);
            }
        }

        return $this->controller->render($this->view, [
            'model' => $model,
            'fields' => array_values($record->getColumnMap()),
            'record' => $record
        ]);
    }
}
