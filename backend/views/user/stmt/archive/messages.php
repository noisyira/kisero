<?php
use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */

?>

<ol class="breadcrumb">
    <li><a href="#/">Главная</a></li>
    <li class="active">Журнал сообщений</li>
</ol>

<div class="stmt-view">
    <!-- ngModel is optional -->
    <div class="panel-group" ng-model="panels.activePanel" role="tablist" aria-multiselectable="true" bs-collapse>
        <div class="panel panel-default" ng-repeat="(key, value) in model"">
            <div class="panel-heading" role="tab">
                <h4 class="panel-title">
                    <a bs-collapse-toggle>
                        Дата записи: {{key}}
                    </a>
                </h4>
            </div>
            <div class="panel-collapse" role="tabpanel" bs-collapse-target>
                <div class="panel-body" ng-repeat="(k, v) in value">
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
</div>