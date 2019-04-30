<?php
use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */
?>

<ol class="breadcrumb">
    <li><a href="#/">Главная</a></li>
    <li class="active">Мониторинг</li>
</ol>

<div cg-busy="{promise:myPromise,message:'Загрузка ',templateUrl:'/partials/busy.html'}">
    <div class="col-md-12">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <td>№</td>
                <td>Название</td>
                <td>Период</td>
                <td></td>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="(key, item) in model">
                <td style="text-align: left; vertical-align: middle; width: 10%"> {{key+1}} </td>
                <td style="text-align: left; vertical-align: middle"> {{item.name}} </td>
                <td style="text-align: left; vertical-align: middle">
                    {{item.range}}
                </td>
                <td style="text-align: center; vertical-align: middle">
                    <a href="monitoring?id={{item.id}}" class="btn btn-default"> <i class="fa fa-download" aria-hidden="true"></i> </a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>



