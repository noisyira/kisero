<?php
use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */

?>

<ol class="breadcrumb">
    <li><a href="#/">Главная</a></li>
    <li><a href="#/dsp">Диспансерный учет</a></li>
    <li class="active">Список</li>
</ol>

<div class="stmt-view">

    <div cg-busy="{promise:myPromise,message:'Загрузка ',templateUrl:'/partials/busy.html'}">

    <div class="row">
        <div class="col-md-6">
            <div class="list-group">
                    <span href="#" class="list-group-item">
                        <h4 class="list-group-item-heading">Мед. учреждение</h4>
                        <p class="list-group-item-text">{{MO.NAMMO}}</p>
                    </span>
            </div>
        </div>

        <div class="col-md-6">
            <div class="list-group">
                    <span href="#" class="list-group-item">
                        <h4 class="list-group-item-heading">Количество:</h4>
                        <p class="list-group-item-text">{{total}}</p>
                    </span>
            </div>
        </div>
    </div>

        <!-- Таблица список застрахованных -->
        <div tasty-table
             bind-resource-callback="getResource"
             bind-init="init"
             bind-filters="filterBy">

            <table class="table table-striped table-condensed">
                <thead tasty-thead></thead>
                <tbody>
                <tr ng-repeat="row in rows">
                    <td style="padding: 0"> {{row.fam}} </td>
                    <td style="padding: 0"> {{row.group}} </td>
                    <td style="padding: 0">
                        <p> <a href data-animation="am-flip-x" bs-tooltip="row.DSNAME"> {{row.ds}} </a> </p>
                    </td>
                    <td style="padding: 0">
                        <p><?= Html::a('Просмотр', ['/user/stmt/index#/dsp/{{MO.KODMO}}/{{row.people_id}}'], ['class' => 'btn btn-default btn-xs', 'ng-if' => 'listMO.total != 0']);?></p>
                    </td>
                </tr>
                </tbody>
            </table>
            <div tasty-pagination
                 bind-items-per-page="itemsPerPage"
                 bind-list-items-per-page="listItemsPerPage"
            ></div>
        </div>
    </div>
</div>