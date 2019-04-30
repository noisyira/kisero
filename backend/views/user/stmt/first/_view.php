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

    <div class="bs-callout bs-callout-info">
        <p>
            Для закрытия обращения заполните поле <code>«Принятые меры»</code> и нажмите «Закрыть обращение»<br>
            Обращение будет отправленно администратору контакт-центра для проверки и утверждения результата.
        </p>
    </div>

    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Регистрационная карта  <span class="label label-tfoms-orange"></span></legend>

        <div cg-busy="{promise:myPromise,message:message,backdrop:true,delay:0,minDuration:200}">
        
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mod-header">
                                <ul class="ops"></ul>
                                <h2 class="detail-title">Обращение</h2>
                            </div>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Регистрационный номер:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="value">
                                <span ng-bind-html="bindHtml(model.id)"></span>
                            </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Ответственный оператор:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="value">
                                <span ng-bind-html="bindFIO(model.user)"></span>
                            </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Дата обращения:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="value">
                                 <span ng-bind-html="bindDate(model.statement_date) | date:'dd-MM-yyyy HH:mm:ss' "></span>
                            </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Форма обращения:</strong>
                        </div>
                        <div class="col-md-8" ng-switch="model.form_statement" >
                                <span class="value" ng-switch-when = 0>
                                    Устное
                                </span>
                                <span class="value" ng-switch-when = 1>
                                    Письменное
                                </span>
                                <span ng-switch-default>
                                   Устное
                                </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Комментарий обращения:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="value">
                                 <span ng-bind-html="bindHtml(model.theme_statement_description)"></span>
                            </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Рассмотреть до:</strong>
                        </div>
                        <div class="col-md-8">
                        <span class="value">
                            <span ng-bind-html="bindDate(model.expired) | date:'dd-MM-yyyy' "></span>
                        </span>
                        </div>
                    </div>
                </div>


                <!--Инфо о обратившемся-->
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
                                <span ng-bind-html="bindHtml(model.deffered.fam)"></span>
                            </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Имя:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="value">
                                <span ng-bind-html="bindHtml(model.deffered.im)"></span>
                            </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Отчество:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="value">
                                <span ng-bind-html="bindHtml(model.deffered.ot)"></span>
                            </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Дата рождения:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="value">
                                <span ng-bind-html="bindDate(model.deffered.dt) | date:'dd-MM-yyyy' "></span>
                            </span>
                        </div>
                    </div>

                    <div class="row pd5">
                        <div class="col-md-4">
                            <strong class="name">Контактный телефон:</strong>
                        </div>
                        <div class="col-md-8">
                            <span class="value">
                                <span ng-bind-html="bindHtml(model.deffered.phone)"></span>
                            </span>
                        </div>
                    </div>

                    <!--если указан представитель-->
                    <span>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mod-header">
                                    <ul class="ops"></ul>
                                    <h2 class="detail-title">Представитель обратившегося</h2>
                                </div>
                            </div>
                        </div>

                        <div class="row pd5">
                            <div class="col-md-4">
                                <strong class="name">ФИО:</strong>
                            </div>
                            <div class="col-md-8">
                                <span class="value">
                                    <span class="value">
                                        <span ng-bind-html="bindHtml(model.deffered.add_fio)"></span>
                                    </span>
                                </span>
                            </div>
                        </div>

                        <div class="row pd5">
                            <div class="col-md-4">
                                <strong class="name">Телефон:</strong>
                            </div>
                            <div class="col-md-8">
                                <span class="value">
                                    <span ng-bind-html="bindHtml(model.deffered.add_phone)"></span>
                                </span>
                            </div>
                        </div>
                    </span>
                </div>
            </div>

            <div class="col-md-12" style="padding: 0">
                <div class="mod-header">
                    <ul class="ops"></ul>
                    <h2 class="detail-title">Связанные обращения</h2>
                </div>

                <div class="row">
                    <div class="col-md-12" ng-show="model.link">
                        <!--@TODO: Добавить связанные обращения-->
                    </div>

                    <div class="col-md-12" ng-show="!model.link">
                        <p>
                            Нет прикрепленных обращений
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-12" style="padding: 0">
                <div class="mod-header">
                    <ul class="ops"></ul>
                    <h2 class="detail-title">Прикрепленные файлы</h2>
                </div>

                <div class="row">
                    <div class="col-md-12" ng-show="files.length > 0">
                        <div class="row" ng-repeat=" (k, v) in files">
                            <div class="col-md-12">

                                <a ng-href="{{v.path.substring(v.path.search('/uploads'), v.path.length) }}/{{v.file_name}}" data-name="asdasda" download="{{v.file_name}}"><i class="fa fa-file-word-o" aria-hidden="true"></i>
                                    —
                                    {{v.file_name}}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" ng-show="files.length == 0">
                        <p>
                            Нет прикрепленных файлов
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-12" style="padding: 0" ng-if="record.length > 0">
                <div class="mod-header">
                    <ul class="ops"></ul>
                    <h2 class="detail-title">Запись</h2>
                </div>

                <div class="row" >
                    <div class="col-md-12">
                        <div class="panel-body" ng-repeat="(k, v) in record">
                            <div class="row">
                                <div class="col-md-2">
                                    <blockquote>
                                        <small>{{ v.split('_')[2].split('-')[0] }} </small>
                                    </blockquote>
                                </div>
                                <div class="col-md-6">
                                    <audio controls>
                                        <source src="{{v}}" type="audio/mp3">
                                    </audio>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div ng-show="!enableClose" class="col-md-12" style="padding: 0">
                <div class="mod-header">
                    <ul class="ops"></ul>
                    <h2 class="detail-title">Результат обращения</h2>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <label>Дата закрытия</label><br>
                        {{model.result.action_date}}
                    </div>
                    <div class="col-md-4">
                        <label>Действие</label><br>
                        {{model.result.action_name.name}}
                    </div>
                    <div class="col-md-4">
                        <label>Описание</label><br>
                        {{model.res_msg}}
                    </div>
                    <div class="col-md-2" ng-model="model.cash_back">
                        <label>Сумма возмещения</label><br>
                        {{model.cash_back}}
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12 text-right">
                        <a class="btn btn-sm btn-default"
                           ng-click="rework()"
                        >
                            Вернуть на доработку
                        </a>
                    </div>
                </div>
            </div>
            
       </div>
    </fieldset>

    <span ng-show="enableClose">
        <?= $this->render('_accept', [
            'widget' => $widget,
        ]) ?>
    </span>

</div>