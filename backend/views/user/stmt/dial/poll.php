<?php
use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */

?>

<ol class="breadcrumb">
    <li><a href="#/">Главная</a></li>
    <li><a href="#/dial">Журнал опроса</a></li>
    <li class="active">Опрос застрахованных</li>
</ol>

<div class="stmt-view">
    <h3>Список текущих опросов</h3>

    <div class="row">
        <div class="col-md-12">
            <div cg-busy="{promise:myPromise,message:'Загрузка ',templateUrl:'/partials/busy.html'}">
            <div tasty-table bind-resource="resource" bind-filters="search" watch-resource="collection">
                <table class="table table-striped table-bordered table-condensed">
                    <thead tasty-thead></thead>
                    <tbody>
                    <tr ng-repeat="row in rows">
                        <td style="vertical-align: middle;"><span ng-bind-html="pollName(row)"></span></td>
                        <td style="text-align: center; vertical-align: middle;"><span ng-bind-html="pollRange(row)"></span></td>
                        <td style="text-align: center; vertical-align: middle;"><span ng-bind-html="pollStatus(row)"></span></td>
                        <td style="text-align: center; vertical-align: middle;">
                            <span> <a style="border-bottom: 1px dashed" ng-href="#poll/{{row.id}}"> просмотр </a></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
</div>