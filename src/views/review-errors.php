<?php
/**
 * @var yii\web\View $this
 * @var \webtoolsnz\importer\models\Import $model
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \webtoolsnz\importer\BaseImportModel $searchModel
 */

use yii\bootstrap\Html;
use yii\grid\GridView;

?>

<div class="alert alert-danger clearfix">
    <p class="pull-left">
        There are <strong><?= $dataProvider->getTotalCount() ?></strong> records containing errors, these need to be resolved. </p>

    <div class="pull-right">
        <?= Html::a('<span class="fa fa-exclamation-triangle"></span> Abort Import ', ['abort', 'id' => $model->id], [
            'class' => 'btn btn-xs btn-danger',
            'data' => ['method' => 'POST'],
        ]); ?>
    </div>
</div>


<div class="table-responsive">
    <?php \yii\widgets\Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'header' => 'Record',
                'format' => 'raw',
                'value' => function (\webtoolsnz\importer\BaseImportModel $m) {
                    return Html::a((string) $m, ['update-record', 'id' => $m->import_id, 'record_id' => $m->id]);
                }
            ],
            'import_error',
            [
                'format' => 'raw',
                'contentOptions' => ['class' => 'text-center'],
                'value' => function (\webtoolsnz\importer\BaseImportModel $m) {
                    return Html::a('Delete', ['delete-record', 'id' => $m->import_id, 'record_id' => $m->id], [
                        'class' => 'btn btn-xs btn-danger',
                        'data' => ['method' => 'POST'],
                    ]);
                }
            ],
        ],
    ]); ?>
    <?php \yii\widgets\Pjax::end(); ?>
</div>




