<?php
use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */

?>

<ol class="breadcrumb">
    <li><a href="#/">Главная</a></li>
    <li><a href="#/poll">Журнал опроса</a></li>
    <li class="active">Опрос застрахованных</li>
</ol>

<div class="stmt-view">
    <div class="row">
        <div class="col-md-12">
            <div cg-busy="{promise:myPromise,message:'Загрузка ',templateUrl:'/partials/busy.html'}">
            <div ng-if="resource.rows.length > 0" tasty-table bind-resource="resource" bind-filters="search" watch-resource="collection">
                <table class="table table-striped table-bordered table-condensed">
                    <thead tasty-thead style="text-align: center;"></thead>
                    <tbody>
                    <tr ng-repeat="row in rows">
                        <td style="vertical-align: middle;"><span ng-bind-html="pollName(row)"></span></td>
                        <td style="text-align: center; vertical-align: middle;"><span ng-bind-html="pollRange(row)"></span></td>
                        <td style="text-align: center; vertical-align: middle;"><span ng-bind-html="pollStatus(row)"></span></td>
                        <td style="text-align: center; vertical-align: middle;">
                            <span> <a style="border-bottom: 1px dashed" ng-href="#poll/list/{{row.id}}"> просмотр </a></span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="" ng-if="!resource.rows.length > 0 && myPromise">

            </div>
            </div>
        </div>
    </div>
</div>