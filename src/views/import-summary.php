<?php
/**
 * @var yii\web\View $this
 * @var \webtoolsnz\importer\models\Import $model
 * @var array $results
 */

use yii\bootstrap\Html;

?>

<div class="panel panel-default">
    <div class="panel-heading">Import Summary</div>
    <div class="panel-body">

        <?php if (isset($results['error'])): ?>
            <div class="alert alert-danger">
                <h4>An error occured when importing your data!</h4>
                <p><?= Html::encode($results['error']) ?></p>
            </div>

        <?php else: ?>

        <table class="table table-bordered">

            <?php foreach ($results as $importQuery): /* @var \webtoolsnz\importer\ImportQuery $importQuery */ ?>
                <tr>
                    <th><?= $importQuery->description ?></th>
                    <td><?= $importQuery->results?></td>
                </tr>
            <?php endforeach ?>
        </table>
        <br>
        <?php endif ?>



        <?= Html::a('Abort', ['abort', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => ['method' => 'POST'],
        ]) ?>

        <?php if (!isset($results['error'])): ?>
        <p class="pull-right">
            <?= Html::a('Confirm and Import <i class="fa fa-chevron-right"></i>', ['confirm', 'id' => $model->id], [
                'class' => 'btn btn-primary',
                'data' => ['method' => 'POST']
            ]); ?>
        </p>
        <?php endif ?>

    </div>
</div>





