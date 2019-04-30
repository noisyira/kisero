<?php

use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */
?>

<ol class="breadcrumb">
    <li><a href="#/">Главная</a></li>
    <li><a href="#/eir263">ЕИР263</a></li>
    <li class="active">Отчёт</li>
</ol>

<a class="btn btn-default btn-sm" ng-click="discard()" style="margin-bottom: 10px;">
    <i class="fa fa-arrow-left" aria-hidden="true"></i> Вернуться назад
</a>

<div cg-busy="{promise:myPromise,message:'Загрузка ',templateUrl:'/partials/busy.html'}">

    <div class="row">
        <div class="col-md-offset-6 col-md-4">
            <!-- Date range example -->
            <div class="form-group">
                <div class="form-group col-xs-6">
                    <input type="text" class="form-control" ng-model="date.fromDate" data-date-format="MM-dd-yyyy" data-date-type="string" data-max-date="{{fromDate}}" placeholder="От" bs-datepicker>
                </div>
                <div class="form-group col-xs-6">
                    <input type="text" class="form-control" ng-model="date.toDate" data-date-format="MM-dd-yyyy" data-date-type="string" data-min-date="{{toDate}}" placeholder="До" bs-datepicker>
                </div>
            </div>
        </div>

        <div class="col-md-2 text-right">
            <button type="button"
                    class="btn btn-default"
                    data-animation="am-flip-x"
                    bs-dropdown aria-haspopup="true"
                    ng-click="reLoadReport(date)"
                    aria-expanded="false">
                Показать
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <tr>
                    <th>#</th>
                    <th>ФИО</th>
                    <th>СМО</th>
                    <th>Неявка на госпитализацию</th>
                    <th>Напоминание о госпитализации</th>
                    <th>Результат госпитализации</th>
                    <th>Всего</th>
                </tr>
                <tr ng-repeat="user in users">
                    <td>{{$index + 1}}</td>
                    <td>{{ user.fam }} {{ user.im }} {{ user.ot }} <br> <span class="small">{{ user.role }}</span> </td>
                    <td>{{ user.name }} <br> <span class="small">{{ user.subdivision }}</span> </td>
                    <td style="text-align: center;">{{ user.DANUL }}</td>
                    <td style="text-align: center;">{{ user.DPGOSP }}</td>
                    <td style="text-align: center;">{{ user.DPOGOSP }}</td>
                    <td style="text-align: center;">{{ user.total }}</td>
                </tr>
            </table>

            <span ng-show="users.length == 0">
                <p> Ничего не найдено! </p>
            </span>
        </div>
    </div>

</div>