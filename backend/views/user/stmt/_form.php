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

    #userList .select-dropdown
    {
       min-height: 500px;
    }

    #mo .select-dropdown
    {
       min-height: 50px;
       max-height: 200px;
    }
</style>

<!-- Поиск застрахованных по ЕРЗ -->
<div class="stmt-form">

    <a  class="btn btn-default btn-sm"
        ng-click="discard()"
        style="margin-bottom: 10px;"
    >
        <i class="fa fa-arrow-left" aria-hidden="true"></i> Вернуться назад
    </a>
    
    <div ng-init="clearERZ()">
        <label class="task-erz" for="erz">Поиск по реестру застрахованных:</label>
        <input type="checkbox" id="erz" name="erz" ng-model="findERZ" style="display: none;" />

        <div class="form-group check-element animate-show" ng-show="findERZ">

            <fieldset class="scheduler-border">
                <legend class="scheduler-border">Поиск застрахованного</legend>
                <form name="saveFind_ERZ">

                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="erz_f_name">Фамилия</label>
                            <input class="form-control"
                                   id="erz_f_name"
                                   placeholder="Фамилия"
                                   ng-model="erz.sName"
                                   size="30" type="text" />
                        </div>
                        <div class="col-sm-3">
                            <label for="erz_name">Имя</label>
                            <input class="form-control"
                                   id="erz_name"
                                   placeholder="Имя"
                                   ng-model="erz.Name"
                                   size="30" type="text" />
                        </div>
                        <div class="col-sm-3">
                            <label for="erz_l_name">Отчество</label>
                            <input class="form-control"
                                   id="erz_l_name"
                                   placeholder="Отчество"
                                   ng-model="erz.pName"
                                   size="30" type="text" />
                        </div>
                        <div class="col-sm-3">
                            <!--<label for="erz_date">Дата рождения</label>-->
                            <div class="form-group" ng-class="{'has-error': datepickerForm.date.$invalid}">
                                <label class="control-label" for="erz_date">Дата рождения</label>
                                <input type="text"
                                       id="erz_date"
                                       class="form-control"
                                       ng-model="erz.dateMan"
                                       timezone="UTC"
                                       data-date-format="dd-MM-yyyy"
                                       placeholder="ДД-ММ-ГГГГ"
                                       name="date"
                                       autoclose="true"
                                       bs-datepicker>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-12 text-right">
                            <!--<input class="btn btn-default" style="margin: 10px 0;"  type="button" ng-click="findERZ=!findERZ" value="Скрыть" >-->
                            <!--<input class="btn btn-default" style="margin: 10px 0;"  type="button" ng-click="clearERZ()" value="Очистить" >-->
                            <a class="btn btn-default" ng-click="findERZ=!findERZ"><i class="fa fa-minus-square-o"></i>&nbsp;Скрыть</a>
                            <a class="btn btn-default" ng-click="clearERZ()"><i class="fa fa-pencil-square-o"></i>&nbsp;Очистить</a>
                            <a class="btn btn-default" ng-click="submitForm()"><i class='fa fa-search'></i>&nbsp;Поиск</a>

                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-md-12">
                        <hr style="margin-bottom: 0;">
                        <span  ng-show="loading" us-spinner="{right:0, top:10, radius:7, lines: 13, length: 4, width:2, }"></span>
                        <p ng-show="!erzData">
                            Для поиска застрахованного лица введите его данные в форму поиска.<br>
                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Все поля заполнять необязательно.
                        </p>

                        <!-- Список застрахованных -->
                        <table ng-show="erzData" class="table table-hover table-condensed table-bordered">
                            <thead>
                            <tr>
                                <td>Фамилия</td>
                                <td>Имя</td>
                                <td>Отчество</td>
                                <td>Дата рождения</td>
                                <td>Страховая</td>
                                <td>Прикрепление к МО</td>
                                <td>Действие</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="row in erzData">
                                <td>{{row.sName}}</td>
                                <td>{{row.Name}}</td>
                                <td>{{row.pName}}</td>
                                <td> {{formatDate(row.dateMan) | date:'dd-MM-yyyy'}}</td>
                                <td ng-switch="row.reestr.orgId">
                                    <span ng-switch-when="1">ООО СК "ИНГОССТРАХ-М" </span>
                                    <span ng-switch-when="4">ООО ВТБ МС</span>
                                    <span ng-switch-default> не указана </span>
                                </td>
                                <td>
                                    <span style="font-size: 12px;">{{row.stik.mo.NAMMO}}</span>
                                    <br>
                                    <span style="font-size: 10px;">{{row.stik.mo.ADRESMO}}</span>
                                </td>
                                <td>
                                    <a class="exit" style="font-size: 14px; cursor: pointer;" ng-click="setERZ(row)">Применить</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>

    <fieldset class="scheduler-border">
        <legend class="scheduler-border">Регистрационная карта</legend>

    <form name="Form" >
        <div ng-if="errorStatus">
            <ul>
                <li ng-repeat="(field,msg) in errors">{{msg}}</li>
            </ul>
        </div>

    <div cg-busy="myPromise">
    <!-- Открытие РКК  -->
    <div class="row">
        <div class="col-md-6">

            <!-- Форма обращения -->
            <div class="form-group col-md-12" style="padding: 0; margin: 10px 0;">
                <div class="row">
                    <div class="col-md-4">
                        <label>Форма обращения</label><br>
                    </div>
                    <div class="col-md-8">
                        <div class="btn-group" ng-model="model.form_statement" bs-radio-group>
                            <label class="btn btn-default">
                                <input type="radio" class="btn btn-default" value="0"> Устная</label>
                            <label class="btn btn-default">
                                <input type="radio" class="btn btn-default" value="1"> Письменная</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Вид обращения -->
            <div class="form-group col-md-12" ng-init="refreshModeStmt()" style="padding: 0; margin: 10px 0;">
                <label for="statement">Вид обращения</label>
                <oi-select
                    id="statement"
                    class="dropList"
                    oi-options="item.id as item.name for (key, item) in listMode"
                    ng-model="model.statement"
                    placeholder="Вид обращения"
                ></oi-select>
            </div>

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

            <!-- Тема обращения -->
            <div class="form-group col-md-12" ng-init="refreshStmt(model.tip_statement)" style="padding: 0; margin: 10px 0;">
                <label for="task_theme_statement_desc">Тема обращения:</label>
                <oi-select
                    class="dropList_250"
                    oi-options="(item.k +': '+item.theme_statement) group by item.gp for item in themeLists"
                    ng-model="theme"
                    ng-disabled="!model.tip_statement"
                    ng-change="model.theme_statement = theme.key_statement"
                    placeholder="Тематика обращения"
                ></oi-select>

                <input type="hidden" ng-model="model.theme_statement">
            </div>

            <!-- Мед. организация -->
            <div class="form-group col-md-12" ng-show="model.tip_statement == 1" ng-init="moList()" style="padding: 0; margin: 10px 0;">
                <label for="mo">Мед. организация</label>
                <oi-select
                        id="mo"
                        class="dropList"
                        oi-options="item.KODMO as item for (key, item) in listMo"
                        ng-model="model.mo"
                        placeholder="Мед. организация"
                        oi-select-options="{
                            dropdownFilter: 'filterMoList',
                            searchFilter: 'searchFilterMoList',
                            listFilter: 'listFilterMoList'
                        }"
                ></oi-select>
            </div>

            <!-- Комменттарий -->
            <div class="form-group" ng-class="{error:!!errors.theme_statement_description}">
                <label for="stmt-theme_statement_description" class="control-label">Краткое описание</label>
                <textarea id="stmt-theme_statement_description"
                    name="theme_statement_description"
                    class="form-control"
                    ng-model="model.theme_statement_description"
                ></textarea>
                <div class="help-block" ng-bind"errors.theme_statement_description"></div>
            </div>

            <!-- Исполнитель -->
            <div class="form-group col-md-12" ng-init="transferList(<?php echo $id ?>)" style="padding: 0; margin: 10px 0;">
                <label for="task_theme_statement_desc">Оператор:</label>

                <oi-select
                    id="userList"
                    oi-options="item for (key, item) in userTransferLists"
                    ng-model="user_o"
                    ng-change="model.user_o = user_o.user_id"
                    placeholder="Выберите исполнителя"
                    oi-select-options="{
                        dropdownFilter: 'myDropdownFilter',
                        searchFilter: 'mySearchFilter',
                        listFilter: 'myListFilter'
                    }"
                ></oi-select>

                <input type="hidden" ng-model="model.user_o">
            </div>

        </div>

        <!-- ФИО обратившегося -->
        <div class="col-md-6">
            <!-- Фамилия -->
            <div class="form-group">
                <label for="stmt-deffered-fam" class="control-label">Фамилия</label>
                <input type="text" id="stmt-deffered-fam" class="form-control" ng-model="model.deffered.fam" >
            </div>

            <!-- Имя -->
            <div class="form-group">
                <label for="stmt-deffered-im" class="control-label">Имя</label>
                <input type="text" id="stmt-deffered-im" class="form-control" ng-model="model.deffered.im" >
            </div>

            <!-- Отчество -->
            <div class="form-group">
                <label for="stmt-deffered-ot" class="control-label">Отчество</label>
                <input type="text" id="stmt-deffered-ot" class="form-control" ng-model="model.deffered.ot" >
            </div>
            
            <!-- Дата рождения -->
            <div class="form-group">
                <label for="stmt-deffered-dt" class="control-label">Дата рождения</label>
                <input type="text"
                       id="stmt-deffered-dt"
                       class="form-control"
                       ng-model="model.deffered.dt"
                       timezone="UTC"
                       data-date-format="dd-MM-yyyy"
                       placeholder="ДД-ММ-ГГГГ"
                       name="date"
                       autoclose="true"
                       bs-datepicker>
            </div>

            <!-- Телефон -->
            <div class="form-group">
                <label for="stmt-deffered-phone" class="control-label">Контактный телефон</label>
                <input type="text" id="stmt-deffered-phone" class="form-control" ng-model="model.deffered.phone" >
            </div>

            <!-- добавить представителя -->
            <div class="form-group">
                <label for="add_proxy" class="control-label">Добавить представителя</label>
                <input type="checkbox" id="add_proxy" ng-model="proxy" >
            </div>

            <div ng-show="proxy">
                <!-- ФИО представителя -->
                <div class="form-group">
                    <label for="stmt-deffered-add-fio" class="control-label">ФИО представителя</label>
                    <input type="text" id="stmt-deffered-add-fio" class="form-control" ng-model="model.deffered.add_fio" >
                </div>

                <!-- Телефон представителя -->
                <div class="form-group">
                    <label for="stmt-deffered-add-phone" class="control-label">Телефон представителя</label>
                    <input type="text" id="stmt-deffered-add-phone" class="form-control" ng-model="model.deffered.add_phone" >
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Добавление файлов к обращению -->
    <div class="" ng-show="model.form_statement == 1">
        <div class="col-md-12" style="padding: 0">
            <div class="mod-header">
                <ul class="ops"></ul>
                <h2 class="detail-title">Прикрепить файлы к обращению</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <label class="right inline">Список прикрепленных файлов</label>

                <ol>
                    <div class="row">
                        <li ng-repeat="item in model.attachment track by $index" style="padding: 5px 0">
                            <div>
                                {{item.file_name}}

                                <span style="float: right;">
                                     <button type="button" class="btn btn-default btn-xs" ng-click="deleteAtt(item, $index)">
                                        <i class="fa fa-close" aria-hidden="true"></i>
                                    </button>
                                </span>
                            </div>
                       </li>
                    </div>
                </ol>

            </div>

            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group"
                             style="padding: 0; margin: 10px 0;">
                            <label for="attch_num" class="right inline">Номер документа</label>
                            <input class="form-control"
                                   id="attch_num"
                                   placeholder="Номер"
                                   ng-model="model.file.num"
                                   size="30"
                                   type="text"
                            />
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="form-group"
                             style="padding: 0; margin: 10px 0;">
                            <label for="attch_num" class="right inline">Дата регистрации документа</label>
                            <input type="text"
                                   id="erz_date"
                                   class="form-control"
                                   ng-model="model.file.date"
                                   timezone="UTC"
                                   data-date-format="dd-MM-yyyy"
                                   placeholder="ДД-ММ-ГГГГ"
                                   name="date"
                                   autoclose="true"
                                   bs-datepicker>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <input type="file" nv-file-select="" uploader="uploader" multiple  /><br/>
                    </div>

                    <?= \yii\helpers\Html::beginForm()?>
                    <div class="col-md-8" style="margin-bottom: 40px">
                        <p>Количетсво файлов: {{ uploader.queue.length }}</p>

                        <table class="table" ng-show="uploader.queue.length > 0">
                            <thead>
                            <tr>
                                <th width="50%">Название</th>
                                <th ng-show="uploader.isHTML5">Размер</th>
                                <th>Действие</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="item in uploader.queue">
                                <td><strong>{{ item.file.name }}</strong></td>
                                <td ng-show="uploader.isHTML5" nowrap>{{ item.file.size/1024/1024|number:2 }} MB</td>
                                <td nowrap>
                                    <button type="button" class="btn btn-warning btn-xs" ng-click="item.remove()">
                                        <span class="glyphicon glyphicon-ban-circle"></span> Убрать
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <?= \yii\helpers\Html::endForm(); ?>
                </div>
            </div>
        </div>

    </div>

    <!-- Сохранение РКК -->
    <div class="row">
        <div class="col-md-12 text-right">
            <div class="form-group" ng-show="btnSave">
                <button class="btn btn-default" ng-click="save()">Сохранить</button>
            </div>

            <div class="form-group" ng-show="btnUpdate">
                <button class="btn btn-default" ng-click="update()">Обновить</button>
            </div>
        </div>
    </div>

    </form>
    </fieldset>
</div>
