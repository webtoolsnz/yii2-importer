<?php
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \webtoolsnz\importer\models\Import $model
 */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

?>


<div class="panel panel-default">
    <div class="panel-heading">Upload File</div>
    <div class="panel-body">

        <?php $form = ActiveForm::begin([
            'id' => $model->formName(),
            'enableClientValidation' => false,
            'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'file')->fileInput() ?>


        <div class="text-right">
        <?= Html::submitButton('Start Import <span class="glyphicon glyphicon-chevron-right"></span>',
            ['class' => 'btn btn-success btn-sm']); ?>
        </div>


        <?php ActiveForm::end() ?>

    </div>
</div>
</div>




