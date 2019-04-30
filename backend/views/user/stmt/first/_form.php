<?php
use dee\angular\NgView;

/* @var $this yii\web\View */
/* @var $widget NgView */

$widget->renderJs('js/form.js');
$id = Yii::$app->user->id;
?>

<style>
    .myTabs>ul>li {width: 25%; text-align: center;}
    .myTabs>ul>li a {cursor: pointer}

    .animate-show.ng-hide-add.ng-hide-add-active,
    .animate-show.ng-hide-remove.ng-hide-remove-active {
        -webkit-transition: .5s linear all;
        transition: .5s linear all;
        overflow: hidden;
    }

    .animate-show.ng-hide {
        line-height: 0;
        opacity: 0;
        padding: 0 10px;
    }
    .dropList .select-dropdown { max-height: none; }
    .dropList_250 .select-dropdown { max-height: 250px; }
</style>

<div class="stmt-form">

    <a  class="btn btn-default btn-sm"
        ng-click="discard()"
        style="margin-bottom: 10px;"
    >
        <i class="fa fa-arrow-left" aria-hidden="true"></i> Вернуться назад
    </a>

    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Регистрационная карта</legend>

    <form name="Form" >
        <div ng-if="errorStatus">
            <p>
            <ul>
                <li ng-repeat="(field,msg) in errors">{{msg}}</li>
            </ul>
            </p>
        </div>

    <!-- Открытие РКК  -->
    <div class="row">
        <div class="col-md-5">
            <!-- Тип обращения -->
            <div class="form-group col-md-12" style="padding: 0; margin: 10px 0;">
                <label for="task_tip_statement">Тип обращения</label>
                <oi-select
                    class="dropList"
                    oi-options="item.id as item.title for (key, item) in listTipStmt"
                    ng-model="model.tip_statement"
                    placeholder="Тип обращения"
                    ng-change="refreshStmt(model.tip_statement); clear();"
                ></oi-select>
            </div>
        </div>

        <div class="col-md-7">
            <!-- Тема обращения -->
            <div class="form-group col-md-12" ng-init="refreshStmt(model.tip_statement)" style="padding: 0; margin: 10px 0;">
                <label for="task_theme_statement_desc">Тема обращения:</label>
                <oi-select
                    class="dropList_250"
                    oi-options="(item.k +': '+item.theme_statement) group by item.gp for item in themeLists"
                    ng-model="theme"
                    ng-disabled="!model.tip_statement"
                    ng-change="model.theme_statement = theme.key_statement; answer(theme.key_statement);"
                    placeholder="Тематика обращения"
                ></oi-select>

                <input type="hidden" ng-model="model.theme_statement">
            </div>
        </div>
    </div>

    <hr>

    <!-- Сохранение обращения -->
    <div class="col-md-8">
        <p ng-show="stmtID">
            Обращение сохранено. <br>
            Для продолжения заполнения регистрационной карты нажмите «Продолжить».
        </p>
    </div>
    <div class="col-md-4 text-right">
        <!-- Далее -->
        <div class="form-group">
            <div class="col-sm-12 text-right">
                <a class="btn btn-default"
                   ng-click="save()"
                   ng-disabled="stmtID"
                >
                    <i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить
                </a>
                &nbsp;&nbsp;
                <a class="btn btn-default"
                   ng-click="continue()"
                >
                    Продолжить <i class="fa fa-arrow-circle-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>

    </form>
    </fieldset>
    
    <div class="row">
        <div class="col-md-12">

            <div ng-show="answerList" class="row" style="margin: 15px;">
                <div class="col-md-12">
                    <h4>Сценарии ответа на вопрос: <em>{{theme.theme_statement}}</em></h4>

                    <div class="panel-group" ng-model="panels.activePanel" role="tablist" aria-multiselectable="true" bs-collapse>
                        <div class="panel panel-default" ng-repeat="panel in answerList.current track by $index">
                            <div class="panel-heading" role="tab">
                                <h4 class="panel-title">
                                    <a bs-collapse-toggle>
                                        {{ panel.name }}
                                    </a>
                                </h4>
                            </div>
                            <div class="panel-collapse" role="tabpanel" bs-collapse-target>
                                <div class="panel-body">
                                    <p ng-bind-html="panel.answer"></p>
                                    <hr>
                                    <p> Рекомендованные операторы:</p>
                            <span ng-repeat="user in panel.login">
                                <button class="btn btn-default btn-sm" ng-click="renderTab(user.sip_private_identity)">
                                    <abbr title={{user.username.company}}>{{user.username.fam}}</abbr>
                                    <span class="label label-default">{{user.sip_private_identity}}</span><br>
                                </button>
                            </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="">
                    <div class="col-md-12">
                        <div class="col-md-8">
                            <h5>Другие сценарии ответа:</h5>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="padding: 0; margin: 10px 0;">
                                <input class="form-control"
                                       id="query"
                                       ng-model="query"
                                       placeholder="Введите название"
                                       size="30" type="text" />
                            </div>
                        </div>
                    </div>

                    <div class="">
                        <div class="col-md-12">
                            <div class="panel-group" ng-model="items.activePanel" role="tablist" aria-multiselectable="true" bs-collapse>
                                <div class="panel panel-default" ng-repeat="item in answerList.all | filter:query | limitTo:10">
                                    <div class="panel-heading" role="tab">
                                        <h4 class="panel-title">
                                            <a bs-collapse-toggle>
                                                {{ item.name }}
                                            </a>
                                        </h4>
                                    </div>
                                    <div class="panel-collapse" role="tabpanel" bs-collapse-target>
                                        <div class="panel-body">
                                            <p ng-bind-html="item.answer"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
