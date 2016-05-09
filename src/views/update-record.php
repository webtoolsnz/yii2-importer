<?php
/**
 * @var yii\web\View $this
 * @var \webtoolsnz\importer\models\Import $model
 * @var \webtoolsnz\importer\BaseImportModel $record
 * @var array $fields
 */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;


?>


<?php $form = ActiveForm::begin([
    'id' => 'Campaign',
    'layout' => 'horizontal',
    'enableClientValidation' => false,
]); ?>

<?php echo $form->errorSummary($model); ?>

<div class="panel panel-default">
    <div class="panel-heading clearfix">
        <h3 class="panel-title pull-left">Update Record</h3>
    </div>

    <div class="panel-body">
        <?php foreach ($fields as $field): ?>
            <?= $form->field($record, $field) ?>
        <?php endforeach ?>
    </div>
</div>



<?= Html::a('Cancel', ['review-errors', 'id' => $model->id], ['class' => 'btn btn-default']) ?>


<p class="pull-right">
    <?= Html::submitButton('<span class="glyphicon glyphicon-check"></span> Save', [
        'id' => 'save-' . $record->formName(),
        'class' => 'btn btn-primary'
    ]); ?>
</p>

<?php ActiveForm::end(); ?>
