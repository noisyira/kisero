<?php
use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */

?>

<ol class="breadcrumb">
    <li><a href="#/">Главная</a></li>
    <li><a href="#/dial">Журнал опроса</a></li>
    <li class="active">Настройки</li>
</ol>

<div class="stmt-view" cg-busy="{promise:myPromise,message:'Загрузка ',templateUrl:'/partials/busy.html'}">

    <div class="row">
        <div class="col-md-12">
            <p>
                Выберите Мед. учреждения:
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">

                    <table class="table table-bordered table-responsive"  ng-repeat="(key, items) in model">
                        <tr>
                            <td colspan="2"><h4>{{key}}</h4></td>
                        </tr>
                        <tr ng-repeat="values in items">
                            <td style="text-align: center; vertical-align: middle;">
                                <input type="checkbox" ng-model="selection[values.KODMO]" name="group" id="{{values.KODMO}}" />
                            </td>
                            <td>
                                <label for="{{values.KODMO}}" style="cursor: pointer;">
                                    <strong>{{values.NAMMO}}</strong> <br>
                                    <small>{{values.ADRESMO}}</small>
                                </label>
                            </td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <div class="row" style="position: fixed; bottom: 0px; width: 100%; background: #ffffff none repeat scroll 0% 0%; z-index: 1; border-top: 1px solid #c0c0c0; padding-top: 10px;">
        <div class="col-md-12 text-center">
            <p>
                <?= Html::button('<i class="fa fa-cogs" aria-hidden="true"></i> Сохранить', ['class' => 'btn btn-tfoms-green', 'ng-click' => 'save(selection)']); ?>
            </p>
        </div>
    </div>

</div>