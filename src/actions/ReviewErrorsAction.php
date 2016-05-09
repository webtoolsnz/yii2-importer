<?php

namespace webtoolsnz\importer\actions;

use webtoolsnz\importer\ImporterController;
use Yii;
use yii\base\Action;
use webtoolsnz\importer\models\Import;
use webtoolsnz\importer\BaseImportModel;

/**
 * Class ReviewErrorsAction
 * @package webtoolsnz\importer\actions
 *
 * @property ImporterController $controller
 */
class ReviewErrorsAction extends Action
{
    /**
     * @var string
     */
    public $view = '@importer/views/review-errors';

    /**
     * @param $id
     * @return string
     * @throws \Exception
     * @throws \yii\web\HttpException
     */
    public function run($id)
    {
        $model = $this->controller->findModel($id);

        $searchModel = $model->getModelInstance();
        $searchModel->import_id = $model->id;
        $searchModel->import_status_id = BaseImportModel::STATUS_ERROR;
        $dataProvider = $searchModel->search();

        if ($dataProvider->getTotalCount() == 0) {
            $model->status_id = Import::STATUS_PENDING_IMPORT;
            $model->update(false, ['status_id']);
            return $this->controller->reRoute($model);
        }

        return $this->controller->render($this->view, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
        ]);
    }
}
