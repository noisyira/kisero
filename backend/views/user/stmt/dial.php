<?php
use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */

?>

<ol class="breadcrumb">
    <li><a href="#/">Главная</a></li>
    <li><a href="#/dial">Журнал опроса</a></li>
    <li class="active">Диспансеризация</li>
</ol>

<div class="stmt-index">
    <legend style="margin-bottom: 5px;">Список медицинских организаций</legend>
    <h4 class="text-right">
        <?= Html::a('<i class="fa fa-cog" aria-hidden="true"></i>&nbsp;&nbsp;Настройки', '#/dial-setting', ['class' => 'btn btn-default'])?>
    </h4>

    <div cg-busy="{promise:myPromise,message:'Загрузка ',templateUrl:'/partials/busy.html'}">
    <div class="row">
        <div class="col-md-8">
            <label>Год диспансеризации:</label>
            <div class="btn-group" ng-model="filters.y" bs-checkbox-group>
                <label class="btn btn-default"><input type="checkbox" value="_16"> 2016 </label>
                <label class="btn btn-default"><input type="checkbox" value="_17"> 2017 </label>
                <label class="btn btn-default"><input type="checkbox" value="_18"> 2018 </label>
                <label class="btn btn-default"><input type="checkbox" value="_19"> 2019 </label>
            </div>

            <?= Html::button('<i class="fa fa-filter" aria-hidden="true"></i> Применить', ['class' => 'btn btn-default', 'ng-click' => 'accept(filters)']); ?>
        </div>

        <div class="col-md-4 text-right">
            <!-- Inlined sibling dropdown -->
            <button type="button" class="btn btn-default" data-animation="am-flip-x" bs-dropdown aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-download" aria-hidden="true"></i> Сохранить
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="save-dial-people"><i class="fa fa-file-text-o"></i>&nbsp;&nbsp; .xml</a></li>
            </ul>
        </div>
    </div>

    <hr>

    <div class="row" ng-show="listMO">
        <div class="col-md-12">
            <table class="table table-striped table-condensed">
                <thead></thead>
                <tbody>
                <tr ng-repeat="(key, items) in listMO">
                    <td width="70%">
                        <h4>{{items.NAMMO}}</h4>
                        <small>{{items.ADRESMO}}</small>
                    </td>
                    <td style="text-align: center; vertical-align: middle; width=10%;">

                    </td>
                    <td style="text-align: center; vertical-align: middle;">
                        <h4>{{items.total}}</h4>
                    </td>
                    <td style="text-align: center; vertical-align: middle">
                        <?= Html::a('Перейти', ['/user/stmt/index#/dial-mo/{{items.code_mo}}'], ['class' => 'btn btn-default btn-xs', 'ng-if' => 'items.total != 0']);?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    </div>

</div>
