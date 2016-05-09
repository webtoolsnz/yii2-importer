<?php

namespace webtoolsnz\importer\actions;

use webtoolsnz\importer\ImporterController;
use Yii;
use yii\base\Action;
use webtoolsnz\importer\models\Import;
use yii\web\Response;

/**
 * Class AbortAction
 * @package webtoolsnz\importer\actions
 *
 * @property ImporterController $controller
 */
class AbortAction extends Action
{
    /**
     * Route to be redirected to after aborting the import.
     * @var array
     */
    public $redirectTo = ['create'];

    /**
     * @param $id
     * @return array|string|Response
     * @throws \yii\web\HttpException
     */
    public function run($id)
    {
        if (!Yii::$app->request->isPost) {
            throw new \yii\web\HttpException(400, 'Invalid Request');
        }

        $model = $this->controller->findModel($id);
        $model->abort = 1;
        $model->update(false, ['abort']);

        if ($model->status_id !== Import::STATUS_RUNNING) {
            $model->delete();
        }

        return $this->controller->redirect($this->redirectTo);
    }
}
