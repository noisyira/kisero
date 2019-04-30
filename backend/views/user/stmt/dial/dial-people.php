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


<div class="stmt-view">

    <a  class="btn btn-default btn-sm"
        ng-click="discard()"
        style="margin-bottom: 10px;"
    >
        <i class="fa fa-arrow-left" aria-hidden="true"></i> Вернуться назад
    </a>

    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Информация <span class="label label-tfoms-orange"></span></legend>

        <div cg-busy="{promise:myPromise,message:'Загрузка ',templateUrl:'/partials/busy.html'}">
            <div class="row">
                <div class="col-md-6">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mod-header">
                                <ul class="ops"></ul>
                                <h2 class="detail-title">Информация об обратившемся</h2>
                            </div>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Фамилия:</strong>
                        </div>
                        <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml(model.data.fam)"></span>
                        </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Имя:</strong>
                        </div>
                        <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml(model.data.im)"></span>
                        </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Отчетсво:</strong>
                        </div>
                        <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml(model.data.ot)"></span>
                        </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Дата рождения:</strong>
                        </div>
                        <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindDate(model.data.dr) | date:'dd-MM-yyyy' "></span>
                        </span>
                        </div>
                    </div>

<!--                    <div class="row pd5">-->
<!--                        <div class="col-md-4">-->
<!--                            <strong class="name">Адрес проживания:</strong>-->
<!--                        </div>-->
<!--                        <div class="col-md-8">-->
<!--                        <span class="value">-->
<!--                            <span ng-bind-html="bindHtml(model.people.addressLive)"></span>-->
<!--                        </span>-->
<!--                        </div>-->
<!--                    </div>-->

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Контактный телефон <br> <small>(по системе ЕРЗ)</small>:</strong>
                        </div>
                        <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml(model.data.mobile)"></span>
                        </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Страховая компания:</strong>
                        </div>
                        <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml(model.data.smo.NAME)"></span>
                        </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Прикрепление к МО:</strong>
                        </div>
                        <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml(model.data.mo.NAMMO)"></span>
                        </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Адрес МО:</strong>
                        </div>
                        <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml(model.data.mo.ADRESMO)"></span>
                        </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mod-header">
                                <ul class="ops"></ul>
                                <h2 class="detail-title">Действия</h2>
                            </div>
                        </div>
                    </div>

                    <div class="row" ng-repeat="action in model.data.actions" >
                        <div class="col-md-1">{{$index + 1}}</div>
                        <div class="col-md-3">{{action.value.label}}</div>
                        <div class="col-md-3">{{action.action_date}}</div>
                        <div class="col-md-5">{{action.action_comment}}</div>
                    </div>

                </div>

                <!-- Модуль опроса -->
                <div class="col-md-6">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mod-header">
                                <ul class="ops"></ul>
                                <h2 class="detail-title">Вопросы</h2>
                            </div>
                        </div>
                    </div>

                    <!-- Шаблон опроса застрахованного лица -->
                    <?= $this->render('_answer'); ?>

                </div>
            </div>

        </div>
    </fieldset>

</div>