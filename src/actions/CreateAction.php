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
     * @return string|\yii\web\Response
     */
    public function run()
    {
        $model = new Import();
        $model->import_model = $this->controller->importModel;
        $model->setScenario(Import::SCENARIO_INSERT);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->controller->reRoute($model);
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}
