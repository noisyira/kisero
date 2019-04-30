<?php
use dee\angular\NgView;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $widget NgView */
?>

<ol class="breadcrumb">
    <li><a href="#/">Главная</a></li>
    <li><a href="#/eir263">ЕИР263</a></li>
    <li class="active">Оповещение</li>
</ol>

<a class="btn btn-default btn-sm"
   ng-click="discard()"
   style="margin-bottom: 10px;">
    <i class="fa fa-arrow-left" aria-hidden="true"></i> Вернуться назад
</a>

<div cg-busy="{promise:myPromise,message:'Загрузка ',templateUrl:'/partials/busy.html'}">

    <div style="text-align: right;" ng-switch on="setting.params">
        <div ng-switch-when="DPOGOSP">
            <strong>Результат госпитализации</strong>
        </div>
        <div ng-switch-when="DANUL">
            <strong>Неявка пациента на госпитализацию </strong>
        </div>
        <div ng-switch-when="DPGOSP">
            <strong>Оповещение о начале госпитализации </strong>
        </div>
        <div ng-switch-default></div>
    </div>

    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Направление: {{ pacient.NNAPR }} <span>( <em>{{pacient.DNAPR}}</em> )</span>
        </legend>

        <div class="row">
            <div class="col-md-6">

                <div class="row pd5">
                    <div class="col-md-4">
                        <strong class="name"> ФИО пациента:</strong>
                    </div>
                    <div class="col-md-8">
                    <span class="value">
                       {{pacient.FAM}}&nbsp;{{pacient.IM}}&nbsp;{{pacient.OT}}
                    </span>
                    </div>
                </div>

                <div class="row pd5">
                    <div class="col-md-4">
                        <strong class="name"> Номер полиса:</strong>
                    </div>
                    <div class="col-md-8">
                    <span class="value">
                       {{pacient.NPOLIS}}
                    </span>
                    </div>
                </div>

                <div class="row pd5">
                    <div class="col-md-4">
                        <strong class="name"> Дата рождения:</strong>
                    </div>
                    <div class="col-md-8">
                    <span class="value">
                        {{pacient.DR}} &nbsp; {{pacient.sex}}
                    </span>
                    </div>
                </div>

                <div class="row pd5">
                    <div class="col-md-4">
                        <strong class="name"> Полных лет:</strong>
                    </div>
                    <div class="col-md-8">
                    <span class="value">
                        {{pacient.age}}
                    </span>
                    </div>
                </div>

                <div class="row pd5">
                    <div class="col-md-4">
                        <strong class="name"> Контактный телефон:</strong>
                    </div>
                    <div class="col-md-8">
                    <span class="value">
                        <a class=""
                            style="cursor: pointer"
                            ng-click="sendPhone()"
                        >  <span style="color: #0c0c0c;"> {{pacient.TEL}} </span>
                            <i class="fa fa-phone-square" aria-hidden="true"></i>
                        </a>
                    </span>
                    </div>
                </div>

                <div class="row pd5">
                    <div class="col-md-4">
                        <strong class="name"> Диагноз: </strong>
                    </div>
                    <div class="col-md-8" >
                    <span class="value">
                        {{pacient.DSNAM ? pacient.DSNAM : pacient.DSPONAM}}
                    </span>
                    </div>
                </div>

                <div class="row pd5">
                    <div class="col-md-4">
                        <strong class="name"> СМО: </strong>
                    </div>
                    <div class="col-md-8">
                    <span class="value">
                        <div ng-switch on="pacient.SMO">
                            <div ng-switch-when="26005">
                                ООО «СК «Ингосстрах - М»
                            </div>
                            <div ng-switch-when="26002">
                              ООО ВТБ МС
                            </div>
                            <div ng-switch-when="26004">
                               ООО ВТБ МС
                            </div>

                            <div ng-switch-default></div>
                        </div>
                    </span>
                    </div>
                </div>
            </div>

            <div class="col-md-6">

                <div class="row pd5">
                    <div class="col-md-12">
                        <strong> {{pacient.NAMMO_clinic}} </strong>
                    </div>

                    <div class="col-md-12">
                        <strong class="name"> {{pacient.NAMPMO_clinic}} </strong>
                    </div>

                    <div class="col-md-12">
                        <em>Направление от: </em>
                        <strong class="name"> {{pacient.medrab_fam}} &nbsp; {{pacient.medrab_im}} &nbsp;
                            {{pacient.medrab_ot}} </strong>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2 text-center">
                        <i class="fa fa-arrow-down" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="row pd5">
                    <div class="col-md-12">
                        <strong> {{pacient.NAMPK}} </strong> <br>
                        <span class="small">Отделение: ({{pacient.NAMPO}})</span>
                    </div>
                    <div class="col-md-12">
                        <strong> {{pacient.NAMMO_hospital}} </strong>
                    </div>
                    <div class="col-md-12">
                        <strong class="name"> {{pacient.NAMPMO_hospital}} </strong>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <hr>

                <div ng-switch on="setting.params">
                    <div ng-switch-when="DANUL">
                        <?= $this->render('_annul'); ?>
                    </div>
                    <div ng-switch-when="DPGOSP">
                        <?= $this->render('_gosp'); ?>
                    </div>
                    <div ng-switch-when="DPOGOSP">
                        <?= $this->render('_dogosp'); ?>
                    </div>

                    <div ng-switch-default></div>

                </div>

            </div>
        </div>

    </fieldset>
</div>