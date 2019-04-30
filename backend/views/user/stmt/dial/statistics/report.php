<?php
use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */

?>

<ol class="breadcrumb">
    <li><a href="#/">Главная</a></li>
    <li><a href="#/statistics">Отчёты</a></li>
    <li class="active">Операторы</li>
</ol>

<div class="stmt-view">
    <h3>Операторы</h3>

    <div class="row">
        <div class="col-md-12">
            <div cg-busy="{promise:myPromise,message:'Загрузка ',templateUrl:'/partials/busy.html'}">
                <div tasty-table
                     bind-resource="resource"
                     bind-filters="search"
                     watch-resource="collection"
                     bind-theme="customTheme">
                    <table class="table table-striped table-bordered table-condensed">
                        <thead tasty-thead></thead>
                        <tbody>
                        <tr ng-repeat="row in rows">
                            <td><span ng-bind-html="fioStmt(row)"></span></td>
                            <td>{{row.total}}</td>
                            <td>{{row.success}}</td>
                            <td>{{row.recall}}</td>
                            <td>{{row.notcall}}</td>
                            <td>{{row.process}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>