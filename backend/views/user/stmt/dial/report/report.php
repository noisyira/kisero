<?php
use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */

?>

<ol class="breadcrumb">
    <li><a href="#/">Главная</a></li>
    <li><a href="#/dial">Диспансеризация</a></li>
    <li class="active">Статистика</li>
</ol>

<div class="stmt-view">
    <form name="datepickerForm" class="form-inline" role="form">
        <!-- Basic example -->
        <div class="form-group" ng-class="{'has-error': datepickerForm.date.$invalid}">
            <label class="control-label"><i class="fa fa-calendar"></i> </label>
            <input type="text" class="form-control"
                   ng-model="selectedDateStart"
                   name="date-start"
                   data-date-format="dd-MM-yyyy"
                   data-date-type="string"
                   bs-datepicker>
        </div>
        <i class="fa fa-exchange" aria-hidden="true"></i>
        <!-- Custom example -->
        <div class="form-group" ng-class="{'has-error': datepickerForm.date2.$invalid}">
            <input type="text" class="form-control"
                   ng-model="selectedDateEnd"
                   name="date-end"
                   data-date-format="dd-MM-yyyy"
                   data-date-type="string"
                   bs-datepicker>
        </div>

        <div class="form-group">
            <input type="submit" ng-click="Submit()" value="Найти" class="btn btn-default">
        </div>
        <hr>
    </form>

    <div class="row" cg-busy="{promise:myPromise,message:'Загрузка ',templateUrl:'/partials/busy.html'}">
        <div class="col-md-6" ng-if="model">
            <p><strong>Общее количество звонков: {{ calls }} </strong></p>
            <ul class="list-group">
                <li class="list-group-item" ng-repeat="(key, value) in model">
                    <span class="badge">{{value.total}}</span>
                    {{value.name}}
                </li>
            </ul>
        </div>
    </div>
</div>