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

    <a  class="btn btn-default btn-sm"
        ng-click="discard()"
        style="margin-bottom: 10px;"
    >
        <i class="fa fa-arrow-left" aria-hidden="true"></i> Вернуться назад
    </a>
    <div cg-busy="{promise:myPromise,message:'Загрузка ',templateUrl:'/partials/busy.html'}"></div>
    <div tasty-table bind-resource="resource" bind-filters="search" watch-resource="collection">
        <table class="table table-striped table-bordered table-condensed">
            <thead tasty-thead></thead>
            <tbody>
            <tr ng-repeat="row in rows">
                <td><span ng-bind-html="fioStmt(row)"></span></td>
                <td><span ng-bind-html="row.user_fio || '(не указан)'"></span></td>
                <td><span ng-bind-html="row.smo"></span></td>
                <td><span ng-bind-html="row.description"></span></td>
                <td ng-switch="row.result">
                    <span class="label label-default" ng-switch-when="0">Ожидает опроса</span>
                    <span class="label label-tfoms-green" ng-switch-when="1">Опрос проведен</span>
                    <span class="label label-tfoms-orange" ng-switch-when="2">Отказался</span>
                    <span class="label label-danger" ng-switch-when="10">Не отвечает на звонок</span>
                    <span class="label label-tfoms-orange" ng-switch-when="11">Перезвонить</span>
                    <span class="label label-default" ng-switch-when="12">Обрабатывается</span>
                    <span class="label label-default" ng-switch-default="">Новое</span>
                </td>
                <td>
                    <span> <a style="border-bottom: 1px dashed" ng-href="#poll/edit/{{row.id}}"> открыть </a></span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    </div>
</div>