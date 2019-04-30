<?php
use dee\angular\NgView;

/* @var $this yii\web\View */
/* @var $widget NgView */
?>

<fieldset class="scheduler-border">
    <legend class="scheduler-border">Принятые меры  <span class="label label-tfoms-orange"></span></legend>
    <form name="Form" >
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="task_result">Результат обращения:</label>
                    <oi-select
                        id="task_result"
                        oi-options="item.id as item.name for (key, item) in closeList"
                        ng-model="res.action"
                        placeholder="Результат обращения"
                    ></oi-select>
                </div>
            </div>

            <div class="row form-group" ng-show="model.tip_statement == 1">
                <div class="col-md-4">
                    <label>Тип жалобы:</label>
                </div>
                <div class="col-md-8">
                    <div class="btn-group" ng-model="res.plaint" bs-radio-group>
                        <label class="btn btn-default"><input type="radio" class="btn btn-default" value="0"> Обоснованная</label>
                        <label class="btn btn-default"><input type="radio" class="btn btn-default" value="1"> Необоснованная</label>
                    </div>
                </div>
            </div>

            <!-- Новая дата expired -->
            <div class="row" ng-show="res.action == 12">
                <div class="col-md-12 form-group">
                    <label for="expired_1">Дата завершения</label>
                    <input type="text"
                           id="expired_1"
                           class="form-control"
                           ng-model="res.data_expired"
                           timezone="UTC"
                           data-date-format="dd-MM-yyyy"
                           placeholder="ДД-ММ-ГГГГ"
                           name="date"
                           autoclose="true"
                           bs-datepicker>
                </div>
            </div>

            <!-- Переадресация обратившегося -->
            <div class="row" ng-show="res.action == 8">
                <div class="col-md-12 form-group" ng-init="transferList()">
                    <label for="task_theme_statement_desc">Оператор:</label>
                    <oi-select
                        id="userList"
                        oi-options="item for (key, item) in userTransferLists"
                        ng-model="res.user"
                        placeholder="Выберите исполнителя"
                        oi-select-options="{
                        dropdownFilter: 'myDropdownFilter',
                        searchFilter: 'mySearchFilter',
                        listFilter: 'myListFilter'
                    }"
                    ></oi-select>
                </div>
            </div>

            <!-- Жалоба: Взимание денеж. ср-в -->
            <div class="row form-group" ng-show="res.action == 5 && ['15', '15.1', '15.2'].indexOf(model.theme.k) > -1">
                <div class="col-md-4">
                    <label for="cash">Сумма возмещения:</label>
                </div>
                <div class="col-md-8">
                    <input type="text" id="stmt-collection-cash" class="form-control" ng-model="res.cash_back" placeholder="Сумма возмещения">
                </div>
            </div>

        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="taken_measures">Описание:</label>
                        <textarea id="taken_measures"
                                  class="form-control"
                                  style="width: 100%;"
                                  rows="4"
                                  placeholder="Краткое описание принятых мер..."
                                  ng-model="res.msg">
                        </textarea>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 text-right">
            <input class="btn btn-default"
                   style="margin: 10px 0;"
                   type="button"
                   ng-click="closeStmt()"
                   value="Сохранить">
        </div>
    </div>
    </form>
</fieldset>