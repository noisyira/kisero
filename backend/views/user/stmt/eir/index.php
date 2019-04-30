<?php

use dee\angular\NgView;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $widget NgView */
?>

<ol class="breadcrumb">
    <li><a href="#/">Главная</a></li>
    <li class="active">ЕИР263</li>
</ol>

<div cg-busy="{promise:myPromise,message:'Загрузка ',templateUrl:'/partials/busy.html'}">
    <div class="row">
        <div class="col-md-4">
            <label>Медицинская организация</label><br>
            <ui-select multiple limit="5" tagging tagging-label="false"  ng-model="kodMO.selected" theme="bootstrap" ng-disabled="disabled" reset-search-input="true"
                       style="width: 300px;">
                <ui-select-match placeholder="Введите название МО ...">{{$item.KODMO}} </ui-select-match>
                <ui-select-choices repeat="address in MO | propsFilter: {KODMO: $select.search, NAMMO: $select.search}"
                                   refresh="getAsyncMo($select.search)" refresh-delay="300">
                    <div ng-bind-html="address.NAMMO | highlight: $select.search"></div>
                    <small>
                        Код МО: <span ng-bind-html="''+address.KODMO | highlight: $select.search"></span>
                    </small>
                </ui-select-choices>
                <ui-select-no-choice style="background: lightgoldenrodyellow;">
                    <span style="padding: 0 10px">
                        МО не найдена ...
                    </span>
                </ui-select-no-choice>
            </ui-select>
        </div>

        <div class="col-md-4">
            <label>Информирование</label><br>
            <button type="button" class="btn btn-default"
                    ng-model="selectedParam"
                    placeholder="Выберите ..."
                    data-html="1"
                    bs-options="param.value as param.label for param in params" bs-select>
                Action <span class="caret"></span>
            </button>
        </div>

        <div class="col-md-2">
            <label>Период</label><br>
            <button type="button" class="btn btn-default"
                    ng-model="selectedRange"
                    placeholder="Выберите ..."
                    data-html="1"
                    bs-options="r.value as r.label for r in range" bs-select>
                Action <span class="caret"></span>
            </button>
        </div>

        <div class="col-md-2" style="text-align: right;">
            <br>
            <button type="button"
                    class="btn btn-tfoms-blue"
                    data-animation="am-flip-x"
                    bs-dropdown aria-haspopup="true"
                    ng-click="SettingEir()"
                    aria-expanded="false">
                Сохранить
            </button>
        </div>

        <div class="col-md-12">
            <hr>
        </div>
    </div>

    <!--Информация -->
    <div class="row" ng-show="ready">
        <div class="col-md-4">
            <label>Выбранные мед. организации</label>
            <span ng-repeat="mo in kodMO.selected">
                <p class="text-muted">{{mo.NAMMO}}</p>
            </span>
        </div>


        <div class="col-md-4">
            <label>Указанный период</label>
            <p class="text-muted">
                {{dates.start | date:'dd-MM-yyyy' }} — {{dates.end | date:'dd-MM-yyyy' }}
            </p>
        </div>

        <div class="col-md-4">
        <label>Количество</label>
            <div class="row">
                <div class="col-md-6">
                    <span class="text-muted">Всего:</span>
                </div>
                <div class="col-md-4">
                    <span class="information">{{total}}</span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <span class="text-muted" style="padding-left: 20px;" >из них с телефоном:</span>
                </div>
                <div class="col-md-4">
                    <span class="information">{{phone}}</span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <span class="text-muted">Оповещено:</span>
                </div>
                <div class="col-md-4">
                    <span class="information">{{call}}</span>
                </div>
            </div>
        </div>

        <div class="col-md-12 text-center">
            <hr>
            <?= Html::a('Начать информирование', ['/user/stmt/index#/eir263/interview'], ['class' => 'btn btn-default btn-lg']);?>
        </div>
    </div>
</div>