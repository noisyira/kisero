<?php
use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'История создания';
$this->params['breadcrumbs'][] = ['label' => 'Мониторинг', 'url' => ['monitoring']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="post-search">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'range',
            'dt',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{history}',
                'buttons' => [
                    'history' => function ($url, $model) {
                        return Html::a(
                            'просмотр',
                            [
                                'monitoring',
                            ],
                            [
                                'data'=>[
                                    'method' => 'post',
                                    'params'=>[ 'range' => $model->range ],
                                ]
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>

</div>
