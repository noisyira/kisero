<?php
use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */

?>

<ol class="breadcrumb">
    <li><a href="#/">Главная</a></li>
    <li><a href="#/dsp">Диспансерный учет</a></li>
    <li class="active">Информация</li>
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
                                <h2 class="detail-title">Информация о застрахованном</h2>
                            </div>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Фамилия:</strong>
                        </div>
                        <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml({{people.fam}})">{{people.fam}}</span>
                        </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Имя:</strong>
                        </div>
                        <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml({{people.im}})">{{people.im}}</span>
                        </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Отчество:</strong>
                        </div>
                        <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml({{people.ot}})">{{people.ot}}</span>
                        </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Дата рождения:</strong>
                        </div>
                        <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindDate(people.dr) | date:'dd.MM.yyyy' "></span>
                        </span>

                        <span class="value">
                            <span ng-switch on="people.w">
                                <span ng-switch-when="1"> М </span>
                                <span ng-switch-when="2"> Ж </span>
                            </span>
                        </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Контактный телефон <br> <small>(по системе ЕРЗ)</small>:</strong>
                        </div>
                        <div class="col-md-8">

                            <span class="value">
                        <a class=""
                           style="cursor: pointer"
                           ng-click="sendPhone()"
                        >  <span style="color: #0c0c0c;"> {{people.contact}} </span>
                            <i class="fa fa-phone-square" aria-hidden="true"></i>
                        </a>
                    </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Мобильный телефон <br> <small>(по системе ЕРЗ)</small>:</strong>
                        </div>
                        <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml(people.mobile)"></span>
                        </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Страховая компания:</strong>
                        </div>
                        <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml(people.SMONAME)"></span>
                        </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Прикрепление к МО:</strong>
                        </div>
                        <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml(people.NAMMO)"></span>
                        </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Адрес МО:</strong>
                        </div>
                        <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml(people.ADRESMO)"></span>
                        </span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- Шаблон опроса застрахованного лица -->
                    <?= $this->render('_questions'); ?>
                </div>
            </div>
        </div>
    </fieldset>
</div>