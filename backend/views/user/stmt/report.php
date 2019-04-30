<?php

use backend\models\Login;
use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */

?>

<ol class="breadcrumb">
    <li><a href="#/">Главная</a></li>
    <li class="active">Отчёты</li>
</ol>


<div class="stmt-create">
    <div class="row">
        <legend>Формирование отчёта</legend>
        <div class="col-md-12">

            <span us-spinner spinner-key="spinner"></span>

            <div class="row">
                <div class="col-md-5 form-group">
                    <label class="col-xs-4"> Период:</label>

                    <div class="col-xs-8">
                        <input date-range-picker
                               id="daterange"
                               name="daterange"
                               class="form-control date-picker"
                               type="text"
                               ng-model="datePicker"
                               options="opts"
                               required
                        />
                    </div>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-default" ng-click="getReport()">Сформировать отчет</button>
                </div>

                <?php if(Login::companyID(Yii::$app->user->id) == 2):?>
                    <div class="col-md-3">
                        <a href="report-intraservice?range={{datePicker}}" class="btn btn-default">Отчет Интрасервис</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="row" ng-show="dataReport">
                <hr>
                <div class="col-md-12">
                    <h4>Отчёт</h4>
                    <p>
                        1. ОБРАЩЕНИЯ ЗАСТРАХОВАННЫХ ЛИЦ - Таблица 1.1 — <a  href="save-report?range={{datePicker}}" style="cursor: pointer;" >Сохранить <i class="fa fa-download" aria-hidden="true"></i></a>
                    </p>
                    <p>
                        2. ЖАЛОБЫ И ИХ ПРИЧИНЫ - Таблица 1.2 — <a  href="report-plaints?range={{datePicker}}" style="cursor: pointer;" >Сохранить <i class="fa fa-download" aria-hidden="true"></i></a>
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>