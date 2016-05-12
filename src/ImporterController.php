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
     * The base class to represent the `import` table, overwrite this to your local `\app\models\Import` if needed.
     *
     * @var string
     */
    public $importClass = '\webtoolsnz\importer\models\Import';

    /**
     * @var string
     */
    public $importModel;

    /**
     * @var string
     */
    public $indexTitle = 'Import Records';

    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->view->title = $this->indexTitle;
        $this->view->params['breadcrumbs'][] = ['url' => 'index', 'label' => $this->indexTitle];

        return parent::beforeAction($action);
    }

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
        $importClass = $this->importClass;
        $model = $importClass::findOne($id);

        if (!$model) {
            throw new \yii\web\HttpException(400, 'Invalid Import ID');
        }

        return $model;
    }
}