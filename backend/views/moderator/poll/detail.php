<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Подробно';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="detail-poll">
    <h4 class="text-right">
        <small>
            <?= Html::a('<i class="fa fa-download" aria-hidden="true"></i>&nbsp;Сохранить Excel', ['save-excel', 'id' => $_GET['id']])?>
        </small>
    </h4>

<div class="row">
    <?php  foreach ($company as $k => $items): ?>
        <div class="col-md-3">
        <ul class="list-group">
            <li class="list-group-item">
                <?= $k; ?>
            </li>
            <?php foreach ($items as $item): ?>
                <li class="list-group-item">
                    <span class="badge"><?= $item['count']; ?></span>
                    <?= $item['result']['name']; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        </div>
    <?php  endforeach; ?>
</div>

<div class="row">
    <div class="col-md-6">
        Количество опрошенных: <?= $count; ?>
        <table class="table table-bordered">
        <?php foreach ($list as $k => $data): ?>
            <tr>
                <td colspan="3" class="text-left" style="font-size: 18px;">
                    <i class="fa fa-question-circle-o" aria-hidden="true"></i>
                    <?= \app\models\PollQuestion::findOne($k)->value; ?>
                </td>
            </tr>

            <?php foreach ($data as $value): ?>
            <tr>
                <td width="40%">
                    <p style="padding-left: 20px">
                        <i class="fa fa-hashtag" aria-hidden="true"></i>
                        <?=  \app\models\PollQuestion::answerName($value['answer_key']);  ?>
                    &nbsp;&nbsp;&nbsp;
<!--                    <span class="badge">--><?//= $value['count']; ?><!--</span>-->
                    </p>
                </td>
                <td>
                    <div class="progress">
                        <div class="progress-bar progress-bar-warning"
                             role="progressbar"
                             aria-valuenow="<?= number_format(($value['count'] / $count) * 100, 2, '.', ' '); ?>"
                             aria-valuemin="0"
                             aria-valuemax="100"
                             style="min-width: 4em; width: <?=  number_format(($value['count'] / $count) * 100, 2, '.', ' '); ?>%">
                            <?=  number_format(($value['count'] / $count) * 100, 2, '.', ' '); ?>%
                            <span class="sr-only"><?= number_format(($value['count'] / $count) * 100, 2, '.', ' '); ?>% Complete (success)</span>
                        </div>
                    </div>

                </td>
            </tr>
            <?php endforeach; ?>

        <?php endforeach; ?>
        </table>
    </div>
</div>

</div>