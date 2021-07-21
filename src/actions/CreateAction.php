<?php

namespace webtoolsnz\importer\actions;

use Yii;
use yii\base\Action;
use webtoolsnz\importer\models\Import;
use webtoolsnz\importer\ImporterController;

/**
 * Class IndexAction
 * @package webtoolsnz\importer\actions

 * @property ImporterController $controller
 */
class CreateAction extends Action
{
    /**
     * @var string
     */
    public $view = '@importer/views/create';

    /**
     * @return bool
     */
    public function beforeRun()
    {
        Yii::$app->view->title = 'Upload File';
        Yii::$app->view->params['breadcrumbs'][] = Yii::$app->view->title;

        return parent::beforeRun();
    }

    /**
     * @return string|\yii\web\Response
     */
    public function run()
    {
        $importClass = $this->controller->importClass;
        $model = new $importClass;
        $model->import_model = $this->controller->importModel;
        $model->setScenario(Import::SCENARIO_INSERT);

        $model->user_id = $this->controller->user_id;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->controller->reRoute($model);
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}
