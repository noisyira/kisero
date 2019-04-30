<?php
use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */

?>

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
                            <span ng-bind-html="bindHtml(model.fam)"></span>
                        </span>
                    </div>
                </div>

                <div class="row pd5">
                    <div class="col-md-4">
                        <strong class="name">Имя:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml(model.im)"></span>
                        </span>
                    </div>
                </div>

                <div class="row pd5">
                    <div class="col-md-4">
                        <strong class="name">Отчетсво:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml(model.ot)"></span>
                        </span>
                    </div>
                </div>

                <div class="row pd5">
                    <div class="col-md-4">
                        <strong class="name">Дата рождения:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindDate(model.dt) | date:'dd-MM-yyyy' "></span>
                        </span>
                    </div>
                </div>

                <div class="row pd5">
                    <div class="col-md-4">
                        <strong class="name">Адрес проживания:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml(model.addressLive)"></span>
                        </span>
                    </div>
                </div>

                <div class="row pd5">
                    <div class="col-md-4">
                        <strong class="name">Контактный телефон <br> <small>(по системе ЕРЗ)</small>:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml(model.phone)"></span>
                        </span>
                    </div>
                </div>

                <div class="row pd5">
                    <div class="col-md-4">
                        <strong class="name">Страховая компания:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml(model.smo)"></span>
                        </span>
                    </div>
                </div>

                <div class="row pd5">
                    <div class="col-md-4">
                        <strong class="name">Прикрепление к МО:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml(model.NAMMO)"></span>
                        </span>
                    </div>
                </div>

                <div class="row pd5">
                    <div class="col-md-4">
                        <strong class="name">Адрес МО:</strong>
                    </div>
                    <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindHtml(model.ADRESMO)"></span>
                        </span>
                    </div>
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

    <div class="row">
        <div class="col-md-offset-6 col-md-3">
            <button class="btn btn-sm btn-tfoms-red" type="button" ng-click="notAnswer()"> <i class="fa fa-times" aria-hidden="true"></i> Нет ответа</button>

            <button class="btn btn-sm btn-default" type="button" ng-click="reCall()"> <i class="fa fa-retweet" aria-hidden="true"></i> Перезвонить</button>
        </div>
        <div class="col-md-3 text-right">
            <button class="btn btn-sm btn-tfoms-green" type="button" ng-disabled="btnSc && !answer.fail" ng-click="save()"> <i class="fa fa-check" aria-hidden="true"></i> Сохранить</button>
            <button class="btn btn-sm btn-default" type="button" ng-click="nextCall()"> <i class="fa fa-arrow-right" aria-hidden="true"></i> Следующий</button>
        </div>
    </div>

    </div>
    </fieldset>

</div>