<?php

namespace webtoolsnz\importer\actions;

use webtoolsnz\importer\ImporterController;
use Yii;
use yii\base\Action;
use webtoolsnz\importer\models\Import;
use yii\web\Response;

/**
 * Class ViewProgressAction
 * @package webtoolsnz\importer\actions
 *
 * @property ImporterController $controller
 */
class ViewProgressAction extends Action
{
    /**
     * @var string
     */
    public $view = '@importer/views/view-progress';

    /**
     * @param $id
     * @return array|string|Response
     * @throws \yii\web\HttpException
     */
    public function run($id)
    {
        $model = $this->controller->findModel($id);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'status' => $model->getStatus(),
                'status_id' => $model->status_id,
                'progress' => $model->progress.'%',
                'time' => $model->getTimeElapsed(),
                'errors' => $model->error_count,
                'processed' => $model->processed_rows.' \ '.$model->total_rows,
            ];
        }

        if ($model->status_id != Import::STATUS_RUNNING) {
            return $this->controller->reRoute($model);
        }

        return $this->controller->render($this->view, [
            'model' => $model,
        ]);
    }
}
