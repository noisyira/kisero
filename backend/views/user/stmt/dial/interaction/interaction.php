<?php
use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */

?>

<ol class="breadcrumb">
    <li><a href="#/">Главная</a></li>
    <li><a href="#/dial">Диспансеризация</a></li>
    <li class="active">Информационное сопровождение</li>
</ol>

<div class="stmt-view" cg-busy="{promise:myPromise,message:'Загрузка ',templateUrl:'/partials/busy.html'}">

    <div class="row" style="height: 30vh;">

        <div class="col-md-3">
            <h3>Загрузите файл</h3>
            <input type="file" nv-file-select="" uploader="uploader" />
        </div>

        <div class="col-md-9" style="margin-bottom: 40px">
            <h3>Список файлов</h3>
            <p>Количество: {{ uploader.queue.length }}</p>

            <table class="table">
                <thead>
                <tr>
                    <th width="50%">Название</th>
                    <th ng-show="uploader.isHTML5">Размер</th>
                    <th ng-show="uploader.isHTML5">Процесс</th>
                    <th>Статус</th>
                    <th>Действие</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="item in uploader.queue">
                    <td><strong>{{ item.file.name }}</strong></td>
                    <td ng-show="uploader.isHTML5" nowrap>{{ item.file.size/1024/1024|number:2 }} MB</td>
                    <td ng-show="uploader.isHTML5">
                        <div class="progress" style="margin-bottom: 0;">
                            <div class="progress-bar" role="progressbar" ng-style="{ 'width': item.progress + '%' }"></div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span ng-show="item.isSuccess"><i class="glyphicon glyphicon-ok"></i></span>
                        <span ng-show="item.isCancel"><i class="glyphicon glyphicon-ban-circle"></i></span>
                        <span ng-show="item.isError"><i class="glyphicon glyphicon-remove"></i></span>
                    </td>
                    <td nowrap>
                        <button type="button" class="btn btn-tfoms-green btn-xs" ng-click="item.upload()" ng-disabled="item.isReady || item.isUploading || item.isSuccess">
                            <span class="glyphicon glyphicon-upload"></span> Отправить
                        </button>
                        <button type="button" class="btn btn-tfoms-red btn-xs" ng-click="item.remove()">
                            <span class="glyphicon glyphicon-trash"></span> Удалить
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

            <div cg-busy="{promise:promisesFiles,message:'Загрузка ',templateUrl:'/partials/busy.html'}"></div>
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
                        <td>{{ row.file_name }}</td>
                        <td>{{ row.dt }}</td>
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

</div>