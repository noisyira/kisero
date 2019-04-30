<?php

use dee\angular\NgView;
use yii\helpers\Html;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $route \backend\controllers\user\StmtController */
/* @var $roszdravnadzor \backend\models\StmtRoszdravnadzor */

$this->title = 'Кабинет оператора';
?>
<style>
    .borderless td, .borderless th {
        border: none;
    }
</style>
    <div class="col-md-8">
        <?=
        NgView::widget([
            'name' => 'dAdmin',
            'requires' => [
                'oi.select', 'ui.select', 'ngSanitize', 'ngAnimate', 'ngResource', 'ui.bootstrap', 'dee.ui', 'mgcrea.ngStrap',
                'angularFileUpload', 'ngCookies', 'ui-notification', 'ngTasty', 'daterangepicker', 'cgBusy',
                'com.2fdevs.videogular', 'com.2fdevs.videogular.plugins.controls'
            ],
            'routes' => $route,
            'js' => 'main.js',
            'resources' => [
                'Stmt' => [
                    'url' => '/user/rest-stmt/:id',
                    'actions' =>[
                        'update' => [
                            'method' => 'PUT',
                        ],
                    ]
                ]
            ],
        ]);?>
    </div>
    <!-- форма телефона -->
    <div class="col-md-4" ng-controller="phoneCtrl" >
        <legend>Соединение</legend>

        <!--Connection-->
        <div class="col-md-12" style="padding: 0;">

            <div class="row" ng-show="!micro">
                <div class="col-md-12">
                    <div class="callout callout-info">
                        <h4> <i class="fa fa-warning" aria-hidden"true"=""></i>
                            Проблема с подключением гарнитуры
                        </h4>
                        <p>
                            Проверьте подключение гарнитуры к компьютеру.<br>
                            <code>Без подключенной гарнитуры невозможно принимать и совершать звонки.</code>
                        </p>
                    </div>
                </div>
            </div>

            <div class="row" style="margin-bottom: 20px">
                <div class="col-md-12" style="text-align: right">

                    <input id="btnRegister"
                           class="btn btn-default"
                           style="border-bottom: 3px solid #008C77"
                           type="button"
                           ng-click="sipRegister()"
                           value="Начало работы" disabled />


                    <input id="btnPause"
                           class="btn btn-default"
                           style="border-bottom: 3px solid #FF6633"
                           type="button"
                           ng-click="sipPause()"
                           value="Перерыв" disabled />

                    <input id="btnUnRegister"
                           class="btn btn-default"
                           style="border-bottom: 3px solid #ca6464"
                           type="button"
                           ng-click="sipUnRegister()"
                           value="Завершить" disabled />
                </div>
            </div>

            <div class="col-md-12 well">

                <div class="col-md-6 text-center">
                    <label>Статус:</label><br><hr style="margin: 10px 0">
                    <span><label id="txtRegStatus">Не активен</label></span>
                </div>
                <div class="col-md-6 text-center" ng-cloak="startTime">
                    <label>Начало работы:</label><br><hr style="margin: 10px 0">
                    <span>{{startDate | date:'H:mm:ss'}}</span>
                </div>
            </div>
        </div>
        
        <!--phonebook-->
        <div class="col-md-12 text-right" style="margin-bottom: 5px;">
            <a type="button"
               class="phone_book"
               data-template-url="/partials/first/tpl/phonebook.html"
               data-placement="right"
               data-animation="am-slide-right"
               bs-aside="aside"
               data-container="body">
                <i class="fa fa-book"></i>&nbsp;Телефонный справочник
            </a>
        </div>
        <?php
        $ringtone = Yii::getAlias('@web').'/audio/ringtone.wav';
        $ringbacktone = Yii::getAlias('@web').'/audio/ringbacktone.wav';
        // $dtmf = Yii::getAlias('@web').'/audio/dtmf.wav';
        ?>

        <!-- Audios -->
        <audio id="audio_remote" autoplay="autoplay"></audio>
        <audio id="ringtone" loop src="<?= $ringtone ?>"></audio>
        <audio id="ringbacktone" loop src="<?= $ringbacktone?>"></audio>

        <!--Web phone-->
        <div id="divCallCtrl" class="col-md-12 well" style='vertical-align:middle'>
            <div class="col-md-12 text-right">
                <!-- You can use a custom html template with the `data-template` attr -->
            </div>

            <div class="col-md-6">
                <label style="width: 100%;" align="center" id="txtCallStatus"></label>
            </div>
            <div class="col-md-6">
                <label style="width: 100%;" align="center" id="Caller">

                </label>
            </div>
            <br />
            <div class="col-md-12" >
                <div class="input-group">
                    <input type="text" id="txtPhoneNumber" ng-model="Phone.number" class="form-control" placeholder="Введите номер">
                    <span class="input-group-btn">
                         <div id="divBtnCallGroup" class="btn-group">
                             <button id="btnCall" disabled class="btn btn-tfoms-green" ng-click="dialA()" data-content="call">Вызов</button>
                         </div>
                         <div class="btn-group">
                             <input type="button" id="btnHangUp" style="margin: 0; vertical-align:middle; height: 100%;" class="btn btn-tfoms-red" value="Сброс" onclick='sipHangUp();' disabled />
                         </div>
                    </span>
                </div>
            </div>

            <div class="clearfix"></div>

            <div class="col-md-12" style="padding-top: 10px">
                <div id='divCallOptions' class='call-options' style='opacity: 1; margin-top: 0px'>
                    <!--                  <div class="col-md-4 text-left" style="padding: 0">
                        <?php /*Html::a('Добавить', [Yii::$app->controller->id.'/index#/newTask'], ['class' => 'btn btn-warning']);*/?>
                    </div>-->
                    <div class="col-md-4 text-left" style="padding: 0">
                        <?= Html::a('Добавить', '#/create', ['class' => 'btn btn-tfoms-blue']);?>
                    </div>
                    <div class="col-md-8 text-right" style="padding: 0">
                        <input type="button" class="btn btn-default" id="btnMute" value="Откл. звук" onclick='sipToggleMute();' /> &nbsp;
                        <!-- <input type="button" class="btn btn-default" id="btnHoldResume" value="Пауза" onclick='sipToggleHoldResume();' />&nbsp;-->
                        <input type="button" class="btn btn-default" style="" id="btnTransfer" value="Перевести" onclick='sipTransfer();' />

                        <?php if(in_array(Yii::$app->getUser()->id, array(2, 8))) : ?>

                        <?php
                        \yii\bootstrap\Modal::begin([
                            'header' => '<h3>Обращение в Росздравнадзор СК</h3>',
                            'id' => 'modalR',
                            'toggleButton' => [
                                'label' => 'Росздравнадзор',
                                'tag' => 'button',
                                'class' => 'btn btn-default',
                            ],
                            'options' => [
                                'style' => ['text-align' => 'left']
                            ],
                            'footer' => '',
                        ]);?>



                        <?php $form = \yii\widgets\ActiveForm::begin(['id' => 'roszdravnadzor-form']); ?>

                        <?= $form->field($roszdravnadzor, 'theme')->widget(Select2::className(),[
                            'data' => \yii\helpers\ArrayHelper::map( \backend\models\MnThemeRoszdravnadzor::find()->asArray()->all(), 'id', 'theme'),
                            'options' => [
                                'placeholder' => 'Выберите',
                                'ng-model' => 'roszdravTheme',
                            ],
                        ]); ?>

                        <div class="form-group text-right">
                            <?= Html::button('Перевести обращение', ['class' => 'btn tfoms-blue', 'name' => 'contact-button', 'ng-click' => 'sendData()', 'ng-disabled' => '!roszdravTheme']) ?>
                        </div>

                        <?php \yii\widgets\ActiveForm::end(); ?>

                        <?php \yii\bootstrap\Modal::end(); ?>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Call button options -->
    <ul id="ulCallOptions" class="dropdown-menu" style="visibility:hidden">
        <li><a href="#" onclick='sipCall("call-audio");'>Audio</a></li>
        <li><a href="#" onclick='sipCall("call-audiovideo");'>Video</a></li>
        <li id='liScreenShare'><a href="#" onclick='sipShareScreen();'>Screen Share</a></li>
        <li class="divider"></li>
        <li><a href="#" onclick='uiDisableCallOptions();'><b>Disable these options</b></a></li>
    </ul>

<?php $this->registerJsFile('@web/js/SIP-client.js');?>