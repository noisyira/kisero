<?php
use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */

?>


<ol class="breadcrumb">
    <li><a href="#/">Главная</a></li>
    <li class="active">Диспансерный учет</li>
</ol>

<div class="stmt-view">
    <legend style="margin-bottom: 5px;">Диспансерный учет</legend>

    <div class="row">
        <div class="col-md-8">
            <label>Год диспансеризации:</label>
            <div class="btn-group" ng-model="filters.y" bs-checkbox-group>
                <label class="btn btn-default"><input type="checkbox" value="_17"> 2017 </label>
                <label class="btn btn-default"><input type="checkbox" value="_18"> 2018 </label>
                <label class="btn btn-default"><input type="checkbox" value="_19"> 2019 </label>
            </div>

            <?= Html::button('<i class="fa fa-filter" aria-hidden="true"></i> Применить', ['class' => 'btn btn-default', 'ng-click' => 'accept(filters)']); ?>
        </div>

        <div class="col-md-4 text-right">

        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-condensed">
                <thead></thead>
                <tbody>
                    <tr ng-repeat="item in listMO">
                        <td>
                            <h4>{{item.NAMMO}}</h4>
                            <small>{{item.ADRESMO}}</small>
                        </td>
                        <td style="text-align: center; vertical-align: middle;">
                            <h4>{{item.total}}</h4>
                        </td>
                        <td style="text-align: center; vertical-align: middle">
                            <?= Html::a('Перейти', ['/user/stmt/index#/dsp/{{item.mo}}'], ['class' => 'btn btn-default btn-xs', 'ng-if' => 'listMO.total != 0']);?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>