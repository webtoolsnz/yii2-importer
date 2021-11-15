<?php
/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \webtoolsnz\importer\models\Import $model
 */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$this->title = 'Pricing Loader | Import';
?>

<div class="panel">    
    <h3><?php \uconx\Output::encode($this->title); ?></h3>
    <div class="panel-body" style="padding: 0;">

        <?php $form = ActiveForm::begin([
            'id' => $model->formName(),
            'enableClientValidation' => false,
            'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>

        <?= $form->errorSummary($model); ?>

        <?= $form->field($model, 'file')->fileInput() ?>

        <div class="text-left">        
        <?= Html::submitButton('Upload', ['class' => 'btn btn-primary btn-sm']); ?>
        </div>

        <?php ActiveForm::end() ?>

    </div>
</div>
</div>