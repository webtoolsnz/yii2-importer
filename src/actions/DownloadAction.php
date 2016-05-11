<?php

namespace webtoolsnz\importer\actions;

use webtoolsnz\importer\ImporterController;
use Yii;
use yii\base\Action;

/**
 * Class DownloadAction
 * @package webtoolsnz\importer\actions
 *
 * @property ImporterController $controller
 */
class DownloadAction extends Action
{
    /**
     * @param $id
     * @return mixed
     * @throws \yii\web\HttpException
     */
    public function run($id)
    {
        $model = $this->controller->findModel($id);

        $response = Yii::$app->getResponse();
        $response->sendContentAsFile($model->data, $model->filename,[
            'mimeType' => 'text/csv',
        ]);

        return $response->send();
    }
}
