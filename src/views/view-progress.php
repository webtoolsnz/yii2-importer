<?php
/**
 * @var yii\web\View $this
 * @var \webtoolsnz\importer\models\Import $model
 */

use yii\bootstrap\Html;
use webtoolsnz\importer\models\Import;

?>


<div class="panel panel-default">
    <div class="panel-heading">
        Importing <?= Html::encode($model->filename) ?>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <th>Status:</th>
                <td class="status-text"><?= $model->getStatus() ?></td>
            </tr>
            <tr>
                <th>Time Elapsed:</th>
                <td class="time-elapsed"><?= $model->getTimeElapsed() ?></td>
            </tr>
            <tr>
                <th>Progress:</th>
                <td class="percent"><?= $model->progress ?>%</td>
            </tr>
            <tr>
                <th>Errors:</th>
                <td class="error-count"><?=$model->error_count ?></td>
            </tr>
            <tr>
                <th>Records Processed:</th>
                <td class="rows-count"><?= $model->processed_rows.' \ '.$model->total_rows ?></td>
            </tr>
        </table>

        <?= \yii\bootstrap\Progress::widget([
            'percent' => $model->progress,
            'label' => $model->progress.'%',
            'barOptions' => ['class' => 'progress-bar-primary'],
            'options' => ['class' => 'active progress-striped']
        ]) ?>

        <div>
        <?= Html::a('Abort', ['abort', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => ['method' => 'POST'],
        ]) ?>
        </div>

    </div>
</div>


<?php

$running = Import::STATUS_RUNNING;

$js = <<<JS

var update = function () {
    $.getJSON('view-progress?id={$model->id}', function (json) {
        $('.status-text').text(json.status);
        $('.time-elapsed').text(json.time);
        $('.status-text').text(json.status);
        $('.percent').text(json.progress);
        $('.error-count').text(json.errors);
        $('.rows-count').text(json.processed);
        $('.progress-bar').css('width', json.progress);

        if (json.status_id != $running) {
            return window.location.reload();
        }

        window.setTimeout(update, 1000);
    });
};

update();

JS;

$this->registerJs($js);





