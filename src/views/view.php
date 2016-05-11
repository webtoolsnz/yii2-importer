<?php
/**
 * @var yii\web\View $this
 * @var \webtoolsnz\importer\models\Import $model
 */

use yii\bootstrap\Html;

?>


<div class="panel panel-default">
    <div class="panel-heading">
        Review <?= Html::encode($model->filename) ?>
    </div>
    <div class="panel-body">
        <table class="table">
            <tr>
                <th>Status:</th>
                <td class="status-text"><?= $model->getStatus() ?></td>
            </tr>
            <tr>
                <th>Progress:</th>
                <td class="percent"><?= $model->progress ?>%</td>
            </tr>
            <tr>
                <th>Errors:</th>
                <td class="error-count"><?= $model->error_count ?></td>
            </tr>
            <tr>
                <th>Records Processed:</th>
                <td class="rows-count"><?= $model->processed_rows . ' \ ' . $model->total_rows ?></td>
            </tr>
        </table>

        <div>
            <?= Html::a('Download', ['download', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
        </div>

    </div>
</div>





