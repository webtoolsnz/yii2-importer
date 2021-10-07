<?php
/**
 * @var yii\web\View $this
 * @var \webtoolsnz\importer\models\Import $model
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \webtoolsnz\importer\BaseImportModel $searchModel
 */

use yii\bootstrap\Html;
use yii\grid\GridView;
use webtoolsnz\importer\models\Import;

?>

<p class="text-right">
    <?= Html::a('<span class="glyphicon glyphicon-plus"></span> New Import', ['create'], ['class' => 'btn btn-success']) ?>
</p>
<div class="table-responsive">
    <?php \yii\widgets\Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $model,
        'columns' => [
            [
                'attribute' => 'filename',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(
                        $model->filename,
                        [
                            $model->status_id == Import::STATUS_COMPLETE ? 'view' : 'view-progress',
                            'id' => $model->id
                        ], ['data-pjax' => 0]
                    );

                }
            ],
            'created_at:datetime',
            [
                'attribute' => 'user_id',
                'value' => function ($model) {
                    return $model->user_id ?? null;
                }
            ],
            [
                'attribute' => 'status_id',
                'filter' => Import::getStatuses(),
                'value' => function ($model) {
                    return $model->getStatus();
                }
            ],
        ]
    ]); ?>
    <?php \yii\widgets\Pjax::end(); ?>
</div>




