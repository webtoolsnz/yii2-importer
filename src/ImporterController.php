<?php
namespace webtoolsnz\importer;

use webtoolsnz\importer\models\Import;
use yii\web\Controller;

/**
 * Class ImporterController
 * @package webtoolsnz\importer
 */
class ImporterController extends Controller
{
    /**
     * @var string
     */
    public $importModel;

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => \webtoolsnz\importer\actions\IndexAction::className()
            ],
            'create' => [
                'class' => \webtoolsnz\importer\actions\CreateAction::className()
            ],
            'view-progress' => [
                'class' => \webtoolsnz\importer\actions\ViewProgressAction::className()
            ],
            'abort' => [
                'class' => \webtoolsnz\importer\actions\AbortAction::className()
            ],
            'review-errors' => [
                'class' => \webtoolsnz\importer\actions\ReviewErrorsAction::className()
            ],
            'update-record' => [
                'class' => \webtoolsnz\importer\actions\UpdateRecordAction::className(),
            ],
            'delete-record' => [
                'class' => \webtoolsnz\importer\actions\DeleteRecordAction::className(),
            ],
            'import-summary' => [
                'class' => \webtoolsnz\importer\actions\ImportSummaryAction::className(),
            ],
            'confirm' => [
                'class' => \webtoolsnz\importer\actions\ConfirmAction::className(),
            ],
            'view' => [
                'class' => \webtoolsnz\importer\actions\ViewAction::className()
            ],
            'download' => [
                'class' => \webtoolsnz\importer\actions\DownloadAction::className()
            ],
        ];
    }

    /**
     * @param Import $model
     * @return \yii\web\Response
     */
    public function reRoute(Import $model)
    {
        switch ($model->status_id) {
            case Import::STATUS_ERROR:
                $route = ['review-errors', 'id' => $model->id];
                break;
            case Import::STATUS_PENDING_IMPORT:
                $route = ['import-summary', 'id' => $model->id];
                break;
            case Import::STATUS_PENDING:
            case Import::STATUS_RUNNING:
                $route = ['view-progress', 'id' => $model->id];
                break;
            default:
                $route = ['create'];
        }

        return $this->redirect($route);
    }

    /**
     * @param $id
     * @return Import
     * @throws \yii\web\HttpException
     */
    public function findModel($id)
    {
        $model = Import::findOne($id);

        if (!$model) {
            throw new \yii\web\HttpException(400, 'Invalid Import ID');
        }

        return $model;
    }
}