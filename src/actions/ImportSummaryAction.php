<?php

namespace webtoolsnz\importer\actions;

use webtoolsnz\importer\ImporterController;
use Yii;
use yii\base\Action;

/**
 * Class ImportSummaryAction
 * @package webtoolsnz\importer\actions
 *
 * @property ImporterController $controller
 */
class ImportSummaryAction extends Action
{
    /**
     * @var string
     */
    public $view = '@importer/views/import-summary';

    /**
     * @return bool
     */
    public function beforeRun()
    {
        Yii::$app->view->title = 'Import Summary';
        Yii::$app->view->params['breadcrumbs'][] = Yii::$app->view->title;

        return parent::beforeRun();
    }


    /**
     * @param $id
     * @return string
     * @throws \Exception
     * @throws \yii\web\HttpException
     */
    public function run($id)
    {
        $model = $this->controller->findModel($id);
        $results = $model->importRecords($commit = false);

        return $this->controller->render($this->view, [
            'model' => $model,
            'results' => $results,
        ]);
    }
}
