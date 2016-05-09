<?php

namespace webtoolsnz\importer\console;

use yii\console\Controller;
use webtoolsnz\importer\models\Import;

/**
 * Class DefaultController
 * @package webtoolsnz\importer\console
 */
class DefaultController extends Controller
{
    /**
     * @param $path
     */
    public function actionStart($id)
    {
        $model = Import::findOne($id); /* @var Import $model */

        if (!$model) {
            throw new \yii\console\Exception("Import Model not found: $id");
        }

        $model->process();
    }
}
