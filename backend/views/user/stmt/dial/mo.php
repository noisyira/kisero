<?php
use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */

?>

<style>
    .the-legend {
        border-style: none;
        border-width: 0;
        font-size: 14px;
        line-height: 20px;
        margin-bottom: 0;
    }
    .the-fieldset {
        border: 2px groove threedface #444;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
        box-shadow:  0px 0px 0px 0px #000;
    }
</style>

<ol class="breadcrumb">
    <li><a href="#/">Главная</a></li>
    <li><a href="#/dial">Журнал опроса</a></li>
    <li class="active">Настройки</li>
</ol>

<div cg-busy="{promise:myPromise,message:'Загрузка ',templateUrl:'/partials/busy.html'}">

<!-- Фильтры -->
<div class="row">
    <div class="col-md-12">
        <fieldset class="well the-fieldset">
            <legend class="the-legend" style="width: auto;">Фильтр</legend>
            <div class="row">
            <div class="col-md-4">
                <label>Период диспансеризации:&nbsp;</label>
                <button type="button" class="btn btn-default"
                        ng-model="filterList.pd"
                        data-html="1"
                        data-multiple="1"
                        all-none-buttons="true"
                        data-animation="am-flip-x"
                        placeholder="Выберите"
                        bs-options="period.value as period.label for period in periods"
                        bs-select>
                    Action <span class="caret"></span>
                </button>
            </div>

            <div class="col-md-4">
                <label>Статус:&nbsp;</label>
                <button type="button" class="btn btn-default"
                        style="white-space: normal;"
                        ng-model="filterList.status"
                        data-html="1"
                        data-multiple="1"
                        all-none-buttons="true"
                        data-animation="am-flip-x"
                        placeholder="Выберите"
                        bs-options="status.value as status.label for status in updates"
                        bs-select>
                    Action <span class="caret"></span>
                </button>
            </div>

            <div class="col-md-4">
                <label>Действие:&nbsp;</label>
                <button type="button" class="btn btn-default"
                        style="white-space: normal;"
                        ng-model="filterList.action"
                        data-html="1"
                        data-multiple="1"
                        all-none-buttons="true"
                        data-animation="am-flip-x"
                        placeholder="Выберите"
                        bs-options="action.value as action.label for action in actions"
                        bs-select>
                    Action <span class="caret"></span>
                </button>
            </div>
            </div>

            <div class="row">
                <div class="col-md-6 checkbox">
                    <label data-placement="bottom-left" data-type="info" data-animation="am-fade-and-scale" bs-tooltip="tooltip" bs-enabled="filterList.contact">
                        <input type="checkbox" ng-model="filterList.contact"> Только с контактным телефоном
                    </label>
                    <br>
                    <label data-placement="bottom-left" data-type="info" data-animation="am-fade-and-scale" bs-tooltip="tooltip" bs-enabled="filterList.actionDV">
                        <input type="checkbox" ng-model="filterList.actionDV"> Только ДВ1 (не прошедшие)
                    </label>
                </div>


                <div class="col-md-6 text-right">
                    <label class="control-label"></label><br>
                    <button class="btn btn-tfoms-blue" ng-click="search()">Поиск</button>
                    <button class="btn btn-default" ng-click="clearSearch ()">Очистить фильтр </button>
                </div>
            </div>
        </fieldset>
    </div>
</div>

<div class="row">
    <div class="col-md-12">

        <!-- Общая информация -->
        <div class="row">
            <div class="col-md-6">
                <div class="list-group">
                    <span href="#" class="list-group-item">
                        <h4 class="list-group-item-heading">Мед. учреждение</h4>
                        <p class="list-group-item-text">{{nameMo}}</p>
                    </span>
                </div>
            </div>

            <div class="col-md-5">
                <div class="list-group">
                    <span href="#" class="list-group-item">
                        <h4 class="list-group-item-heading">Количество:</h4>
                        <p class="list-group-item-text">{{total}}</p>
                    </span>
                </div>
            </div>

            <div class="col-md-1">
                <div class="list-group">
                      <h4 class="list-group-item-heading">
<!--                          <a href="save-dial-mo-people?mo={{paramId}}" class="btn btn-default" style="font-size: 34px;"><i class="fa fa-download" aria-hidden="true"></i> </a>-->
                          <a href="#" class="btn btn-default" style="font-size: 34px;"><i class="fa fa-download" aria-hidden="true"></i> </a>
                      </h4>
                </div>
            </div>
        </div>

        <!-- Таблица список застрахованных -->
        <div tasty-table
             bind-resource-callback="getResource"
             bind-init="init"
             bind-filters="filterBy"
        >
            <table class="table table-striped table-condensed">
                <thead tasty-thead></thead>
                <tbody>
                <tr ng-repeat="row in rows">
                    <td><span ng-bind-html="fioStmt(row)"></span></td>
                    <td>{{row.pd}}</td>
                    <td>{{row.result.status.name}}</td>
                    <td> <a style="border-bottom: 1px dashed" ng-href="#/dial-people/{{paramId}}/{{row.id}}"> просмотр </a> </td>
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
</div>