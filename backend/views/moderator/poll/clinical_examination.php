<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $model app\models\DialPeople */
/* @var $searchModel backend\models\StatementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Диспансеризация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="statement-index">

    <div class="row">
        <div class="col-md-4">
            <div class="list-group">
                <a href="#" class="list-group-item">
                    <i style="font-size:16px;" class="fa fa-heartbeat" aria-hidden="true"></i> &nbsp;&nbsp;Подлежат диспансеризации:
                    <span class="pull-right text-muted small">
<!--                        <em>--><?//= $total['all']; ?><!-- (--><?//= $total['all_call']; ?><!--)</em>-->
                        <em>1032614(57)</em>
                    </span>
                </a>
                <a href="#" class="list-group-item">
                    <i style="font-size:16px;" class="fa fa-calendar-check-o" aria-hidden="true"></i> &nbsp;&nbsp;-&nbsp;&nbsp;Прошли
<!--                    <span class="pull-right text-muted small"><em>--><?//= $total['all_success']; ?><!--</em></span>-->
                    <span class="pull-right text-muted small"><em>3</em></span>
                </a>
                <a href="#" class="list-group-item">
                    <i style="font-size:16px;" class="fa fa-calendar-times-o" aria-hidden="true"></i> &nbsp;&nbsp;-&nbsp;&nbsp;Отказались
<!--                    <span class="pull-right text-muted small"><em>--><?//= $total['all_cancel']; ?><!--</em> </span>-->
                    <span class="pull-right text-muted small"><em>0</em> </span>
                </a>
                <a href="#" class="list-group-item">
                    <i style="font-size:16px;" class="fa fa-calendar" aria-hidden="true"></i> &nbsp;&nbsp;-&nbsp;&nbsp;Планируют
<!--                    <span class="pull-right text-muted small"><em>--><?//= $total['all_range']; ?><!--</em></span>-->
                    <span class="pull-right text-muted small"><em>13</em></span>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="list-group">
                <a href="#" class="list-group-item">
                    <?= Html::img('@web/img/favicons-ingos.png', ['class' => 'pull-left img-responsive']); ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;Ингосстрах-М
                    <span class="pull-right text-muted small">
<!--                        <em>--><?//= $total['ingos']; ?><!-- (--><?//= $total['ingos_call']; ?><!--)</em>-->
                        <em>743525(31)</em>
                    </span>
                </a>
                <a href="#" class="list-group-item">
                    <i style="font-size:16px;" class="fa fa-calendar-check-o" aria-hidden="true"></i> &nbsp;&nbsp;-&nbsp;&nbsp;Прошли
<!--                    <span class="pull-right text-muted small"><em>--><?//= $total['ingos_success']; ?><!--</em></span>-->
                    <span class="pull-right text-muted small"><em>2</em></span>
                </a>
                <a href="#" class="list-group-item">
                    <i style="font-size:16px;" class="fa fa-calendar-times-o" aria-hidden="true"></i> &nbsp;&nbsp;-&nbsp;&nbsp;Отказались
<!--                    <span class="pull-right text-muted small"><em>--><?//= $total['ingos_cancel']; ?><!--</em></span>-->
                    <span class="pull-right text-muted small"><em>0</em></span>
                </a>
                <a href="#" class="list-group-item">
                    <i style="font-size:16px;" class="fa fa-calendar" aria-hidden="true"></i> &nbsp;&nbsp;-&nbsp;&nbsp;Планируют
<!--                    <span class="pull-right text-muted small"><em>--><?//= $total['ingos_range']; ?><!--</em></span>-->
                    <span class="pull-right text-muted small"><em>2</em></span>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="list-group">
                <a href="#" class="list-group-item">
                    <?= Html::img('@web/img/favicons-vtb.png', ['class' => 'pull-left img-responsive']); ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;ВТБ Медицинское Страхование
                    <span class="pull-right text-muted small">
<!--                        <em>--><?//= $total['vtb']; ?><!-- (--><?//= $total['vtb_call']; ?><!--)</em>-->
                        <em>289087(20)</em>
                    </span>
                </a>
                <a href="#" class="list-group-item">
                    <i style="font-size:16px;" class="fa fa-calendar-check-o" aria-hidden="true"></i> &nbsp;&nbsp;-&nbsp;&nbsp;Прошли
<!--                    <span class="pull-right text-muted small"><em>--><?//= $total['vtb_success']; ?><!--</em></span>-->
                    <span class="pull-right text-muted small"><em>1</em></span>
                </a>
                <a href="close-people?smo=100" target="_blank"  class="list-group-item">
                    <i style="font-size:16px;" class="fa fa-calendar-times-o" aria-hidden="true"></i> &nbsp;&nbsp;-&nbsp;&nbsp;Отказались
<!--                    <span class="pull-right text-muted small"><em>--><?//= $total['vtb_cancel']; ?><!--</em></span>-->
                    <span class="pull-right text-muted small"><em>0</em></span>
                </a>
                <a href="#" class="list-group-item">
                    <i style="font-size:16px;" class="fa fa-calendar" aria-hidden="true"></i> &nbsp;&nbsp;-&nbsp;&nbsp;Планируют
<!--                    <span class="pull-right text-muted small"><em>--><?//= $total['vtb_range']; ?><!--</em></span>-->
                    <span class="pull-right text-muted small"><em>8</em></span>
                </a>
            </div>
        </div>
    </div>
</div>
