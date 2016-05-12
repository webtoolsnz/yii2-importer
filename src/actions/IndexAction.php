<?php

namespace webtoolsnz\importer\actions;

use Yii;
use yii\base\Action;
use webtoolsnz\importer\models\Import;
use webtoolsnz\importer\ImporterController;

/**
 * Class IndexAction
 * @package webtoolsnz\importer\actions
 *
 * @property ImporterController $controller
 */
class IndexAction extends Action
{
    /**
     * @var string
     */
    public $view = '@importer/views/index';

    /**
     * @return string|\yii\web\Response
     */
    public function run()
    {
        $importClass = $this->controller->importClass;
        $model = new $importClass;
        $model->import_model = $this->controller->importModel;
        $dataProvider = $model->search();

        return $this->controller->render($this->view, [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }
}
