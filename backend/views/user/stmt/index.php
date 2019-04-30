<?php
use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */

?>

<style>
    .collapse.am-collapse {
        animation-duration: .3s;
        animation-timing-function: ease;
        animation-fill-mode: backwards;
        overflow: hidden;
    .in-remove {
         animation-name: collapse;
         display: block;
     }
    .in-add {
         animation-name: expand;
     }
    }
</style>
<div class="stmt-index">
    <legend style="margin-bottom: 5px;">Электронный журнал</legend>

    <div class="callout callout-info">
        <h4>Редактирование обращений</h4>
        Для завершения обращения заполните поле <code>«Принятые меры»</code> и измените его статус на
        <span class="label label-default">Закрыто</span> <br>
        Обращение будет отправленно администратору контакт-центра на проверку.
        </p>
    </div>
    
    <div class="grid-view">
        <div class="row">
            <!-- Ответственный оператор -->
            <div class="form-group col-md-4" ng-init="listUser()">
                <label>Ответственный оператор</label>
                <select class="form-control" ng-model="filterList.user_o">
                    <option ng-repeat="item in userlists" value="{{item.id}}">
                        <span>{{item.fam}}</span>&nbsp;<span>{{item.im}}</span>&nbsp;<span>{{item.ot}}</span>
                    </option>
                </select>
            </div>

            <!-- Форма обращения -->
            <div class="form-group col-md-3">
                <label>Форма обращения</label>
                <div class="btn-group" ng-model="filterList.form_statement" bs-radio-group>
                    <label ng-click="initForm()" class="btn btn-default"><input type="radio" class="btn btn-default" ng-disabled="$filterRow.disabled"> Все</label>
                    <label ng-click="initForm(0)" class="btn btn-default"><input type="radio" class="btn btn-default" value="0"> Устные</label>
                    <label ng-click="initForm(1)" class="btn btn-default"><input type="radio" class="btn btn-default" value="1"> Письменные</label>
                </div>
            </div>

            <!-- Тема обращения -->
            <div class="form-group col-md-4">
                <label>Тема обращения</label>
                <select class="form-control" ng-init="listStmt()"
                        ng-model="filterList.theme_statement"
                        ng-options="value.key_statement as value.theme_statement group by value.group.name for value in stmtlists | orderBy:['key_statement']"
                >
                </select>
            </div>
        </div>

        <div class="row">
            <!-- Номер обращения -->
            <div class="col-md-2 form-group">
                <label class="control-label">Номер</label>
                <input type="text" class="form-control input-sm" placeholder="Номер" ng-model="filterList.id" value="">
            </div>

            <!-- ФИО -->
            <div class="col-md-2 form-group">
                <label class="control-label">ФИО</label>
                <input type="text" class="form-control input-sm" placeholder="Фамилия" ng-model="filterList.fio" value="">
            </div>

            <!-- Дата -->
<!--            <div class="col-md-2 form-group">-->
<!--                <label class="control-label">Дата</label>-->
<!--                <input type="text" class="form-control input-sm" placeholder="Дата" ng-model="search.statement_date" value="">-->
<!--            </div>-->

            <!-- Тип обращения -->
            <div class="col-md-2 form-group">
                <label class="control-label">Тип</label>
                <select class="form-control input-sm"
                        ng-model="filterList.tip_statement"
                        ng-options="option.id as option.title for option in listTipStmt">
                </select>
            </div>

            <!-- Поиск -->
            <div class="col-md-3 form-group">
                <label class="control-label"></label><br>
                <button class="btn btn-primary" ng-click="search()">Поиск</button>
                <button class="btn btn-default" ng-click="clearSearch ()">Очистить фильтр </button>
            </div>

        </div>

        <div cg-busy="{promise:myPromise,message:'Загрузка ',templateUrl:'/partials/busy.html'}"></div>
        <div tasty-table
             bind-resource-callback="getResource"
             bind-init="init"
             bind-filters="filterBy"
        >
            <table class="table table-striped table-condensed">
                <thead tasty-thead></thead>
                <tbody>
                <tr ng-repeat="row in rows">
                    <td>{{ row.id }}</td>
                    <td><span ng-bind-html="fioStmt(row.deffered)"></span></td>
                    <td>{{row.statement_date}}</td>
                    <td>{{ row.group.name }} <span ng-if="row.call.channel_id"><i class="fa fa-volume-up" aria-hidden="true"></i></span> </td>
                    <td ng-switch="row.status">
                        <span class="label label-default" ng-switch-when="2">{{row.stmt_status.name}}</span>
                        <span class="label label-default" ng-switch-when="4">{{row.stmt_status.name}}</span>
                        <span class="label label-default" ng-switch-when="3">{{row.stmt_status.name}}</span>
                        <span class="label label-info" ng-switch-when="5">{{row.stmt_status.name}}</span>
                        <span class="label label-danger" ng-switch-when="6">{{row.stmt_status.name}}</span>
                        <span class="label label-tfoms-orange" ng-switch-when="1">{{row.stmt_status.name}}</span>
                    </td>
                    <td ng-switch="row.status">
                             <span ng-switch-when="1">
                                 <a style="border-bottom: 1px dashed" ng-href="#/{{row.id}}/edit"> редактирование </a> <br>
                                 <a style="border-bottom: 1px dashed" ng-href="#/{{row.id}}/close"> закрытие </a>
                             </span>
                        <span ng-switch-when="5"> <a style="border-bottom: 1px dashed" ng-href="#/{{row.id}}/edit" >редактирование </a></span>
                        <span ng-switch-default > <a style="border-bottom: 1px dashed" ng-href="#/{{row.id}}"> просмотр </a></span>
                    </td>
                </tr>
                </tbody>
            </table>
            <div tasty-pagination
                 bind-items-per-page="itemsPerPage"
                 bind-list-items-per-page="listItemsPerPage"
            ></div>
        </div>

        </div>
    </div>
</div>
