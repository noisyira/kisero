<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\StatementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Опрос';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    thead {
        font-family: "Tahoma", "Geneva", sans-serif;
        text-transform: uppercase;
    }
</style>

<div class="statement-index">

    <div class="row">
        <div class="col-md-12">
            <div class="bs-callout bs-callout-info" style="margin-top: 0;">
                <h4>
                    Создание нового опроса
                    <?= Html::a('Создать', ['poll-create'], ['class' => 'btn btn-default btn-sm'])?>
                </h4>
                Для создания нового опроса застрахованных лиц нажмите по кнопке «Создать».<br>
                Выберите «Типовой сценарий опроса» и список застарахованных лиц, подлежащих опросу.
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h4>
                Текущие опросы
            </h4>

            <?php if($dataProvider->count == 0): ?>
        <p>
            Нет текущих опросов.
        </p>
            <?php else: ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'options'=>['class'=>''],
                'columns' => [
                    ['class' => \kartik\grid\SerialColumn::className()],
                    [
                        'attribute' => 'poll_key',
                        'label' => 'Наименование',
                        'format' => 'html',
                        'vAlign'=>'middle',
                        'hAlign'=>'left',
                        'value' => function($model){
                            $title = $model->name->name;
                            $desc = $model->description;
                            $res = '<h4>'.Html::a($title, ['detail', 'id' =>$model->id]).'</h4><p>'.$desc.'</p>';

                            return $res;
                        },
                    ],
                    [
                        'attribute' => 'poll_start',
                        'label' => 'Дата начало',
                        'vAlign'=>'middle',
                        'hAlign'=>'center',
                    ],
                    [
                        'attribute' => 'poll_end',
                        'label' => 'Дата завершения',
                        'vAlign'=>'middle',
                        'hAlign'=>'center',
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'Текущий статус',
                        'format' => 'html',
                        'vAlign'=>'middle',
                        'hAlign'=>'center',
                        'value' => function($model){
                            switch ($model->status) {
                                case 0:
                                    return '<h4><span class="label label-default">Новый</span></h4>';
                                    break;
                                case 1:
                                    return '<span class="label label-tfoms-green">Идет опрос</span>';
                                    break;
                                case 2:
                                    return '<span class="label label-danger">Завершен</span>';
                                    break;
                                default:
                                    return '<span class="label label-default">Новый</span>';
                            }
                        },
                    ],
                    [
                        'attribute' => 'result',
                        'label' => 'Результат',
                        'vAlign'=>'middle',
                        'hAlign'=>'center',
                        'value' => function($model){

                            $total = \backend\models\PollPeople::find()->where(['poll_id' => $model->id])->count();
                            $res = \backend\models\PollPeople::find()
                                ->where(['poll_id' => $model->id])
                                ->andWhere(['NOT IN', 'status', [0, 12] ])
                                ->count();
                           return $res." / ".$total;
                        }
                    ],
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'vAlign'=>'middle',
                        'hAlign'=>'center',
                        'template' => '<p>{detail}</p> <p>{start} {close}</p>',
                        'buttons' => [
//                            'detail' => function ($url) {
//                                return Html::a('Подробно', $url, ['class' => 'btn btn-default btn-sm']);
//                            },
                            'start' => function ($url) {
                                return Html::a('<i class="fa fa-play" aria-hidden="true"></i>', $url, ['class' => 'btn btn-tfoms-green btn-sm']);
                            },
                            'close' => function ($url) {
                                return Html::a('<i class="fa fa-stop" aria-hidden="true"></i>', $url, ['class' => 'btn btn-tfoms-red btn-sm']);
                            },
                        ],
                    ]
                ],
            ]); ?>
            <?php endif; ?>
        </div>
    </div>
</div>
