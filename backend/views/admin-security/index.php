<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\LoginSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Список пользователей';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12">
    <div class="col-md-3">
        <?= Html::a('настройки SIP', ['admin-security/sip-setting'], ['class' => 'bnt bnt-default'])?>
    </div>
    <div class="col-md-3">
        <?= Html::a('Темы обращений', ['admin-security/theme-statement'], ['class' => 'bnt bnt-default'])?>
    </div>
    <div class="col-md-3">
        <?= Html::a('Список разговоров', ['admin-security/all-view-records'], ['class' => 'bnt bnt-default'])?>
    </div>
</div>
<div class="login-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Добавить пользователя', ['create'], ['class' => 'btn btn-tfoms-green']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function($model){
            if($model->block == 1)
            { return ['class' => 'danger']; }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'username',
            'fam',
            'im',
            ['class' =>
                'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete} {block} {unblock}',
                'buttons' => [
                    'block' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-ban-circle"></span>', ['block', 'id' => $model->id]);
                    },
                    'unblock' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-ok-sign"></span>', ['unblock', 'id' => $model->id]);
                    },
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Сохранить',
                'template' => '{pdf}',
                'buttons' => [
                    'pdf' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-save"></span> pdf', ['report', 'id' => $model->id]);
                    },
                ],
            ]
        ],
    ]); ?>
</div>