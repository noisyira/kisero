<?php
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сценарии ответа';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="answer-script-index">

    <h4><?= Html::encode($this->title) ?></h4>

    <p>
        <?= Html::a('Добавить новый сценарий', ['answer-create'], ['class' => 'btn btn-tfoms-green']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'options'=>['class'=>''],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'attribute' => 'stmt.theme_statement',
                'label' => 'Тема обращения',
            ],
            [
                'attribute' => 'recomend_users',
                'value' => function($model)
                {
                    return implode(",", json_decode($model->recomend_users));
                }
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}{info}',
                'buttons'=>[
                    'view'=>function ($url, $model) {
                        $customurl=  \yii\helpers\Url::to(['answer-update','id'=>$model['id']]);
                        return \yii\helpers\Html::a( 'Редактирование', $customurl,
                            ['title' => Yii::t('yii', 'View'), 'data-pjax' => '0']);
                    }
                ],
            ],
        ],
    ]); ?>
</div>