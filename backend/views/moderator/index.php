<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\StatementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Обращения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="statement-index">

    <div class="row">
        <div class="col-md-12">
      
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'options'=>['class'=>''],
                'columns' => [
                    [
                        'attribute' => 'id',
                        'label' => '№',
                        'width'=>'20px',
                        'filter' => false,
                        'headerOptions' => ['style'=>'text-align:center; vertical-align: middle;'],
                    ],
                    [
                        'attribute' => 'org',
                        'label' => 'Огранизация',
                        'headerOptions' => ['style'=>'text-align:center; vertical-align: middle;'],
                        'width'=>'180px',
                        'format' => 'html',
                        'filterType'=>GridView::FILTER_SELECT2,
                        'filter'=> ArrayHelper::map(\backend\models\MnCompany::find()->orderBy('name')->asArray()->all(), 'name', 'name'),
                        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear'=>true],
                        ],
                        'filterInputOptions'=>['placeholder'=>'Огранизация'],
                        'value' => function($model){
                            $company = $model->org->name;
                            $sub = isset($model->operator->sub->name)? $model->operator->sub->name : null;
                            return $company . '<br>'.'<small>'. $sub .'</small>';
                        }
                    ],
                    [
                        'attribute' => 'user_o',
                        'label' => 'Оператор',
                        'format' => 'raw',
                        'value' => function($model){
                            return \backend\models\SipAccount::getFIO($model->user_o);
                        },
                        'options' => ['width' => '200'],
                        'headerOptions' => ['style'=>'text-align:center; vertical-align: middle;'],
                    ],
                    [
                        'class' => '\kartik\grid\DataColumn',
                        'attribute' => 'statement_date',
                        'label' => 'Дата обращения',
                        'format' => ['date', 'php:Y-m-d'],
                        'filterType'=>GridView::FILTER_DATE_RANGE,
                        'options' => [
                            'width' => '200',
                        ],
                        'filterWidgetOptions' =>([
                            'presetDropdown' => true,
                            'pluginOptions' => [
                                'locale'=>['format' => 'DD-MM-YYYY'],
                            ],
                        ]),
                        'headerOptions' => ['style'=>'text-align:center; vertical-align: middle;'],
                    ],
                    [
                        'attribute' => 'fio',
                        'label' => 'ФИО',
                        'headerOptions' => ['style'=>'text-align:center; vertical-align: middle;'],
                        'value' => function($model){
                            if(isset($model->deffered->fam) || isset($model->deffered->im) || isset($model->deffered->ot))
                            {
                                $fio = $model->deffered->fam .' '. $model->deffered->im .' '. $model->deffered->ot;
                            }
                            return isset($fio)? $fio : null;
                        },
                    ],
                    [
                        'attribute' => 'group',
                        'label' => 'Тип обращения',
                        'value' => 'group.name',
                        'headerOptions' => ['style'=>'text-align:center; vertical-align: middle;'],
                        'width'=>'180px',
                        'filterType'=>GridView::FILTER_SELECT2,
                        'filter'=>ArrayHelper::map(\backend\models\MnGroupStatement::find()->orderBy('name')->asArray()->all(), 'name', 'name'),
                        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear'=>true],
                        ],
                        'filterInputOptions'=>['placeholder'=>'Тип обращения'],
                    ],
                    [
                        'label' => 'Статус',
                        'attribute' => 'status',
                        'format'=>'raw',
                        'headerOptions' => ['style'=>'text-align:center; vertical-align: middle;'],
                        'contentOptions' =>['style'=>'text-align: center; vertical-align: middle; font-size: 16px;'],
                        'value' => function($data)
                        {
                            switch ($data->status) {
                                case "1":
                                    return '<span class="label label-tfoms-orange">'.$data->stmt_status->name.'</span>';
                                    break;
                                case "2":
                                    return '<span class="label label-default">'.$data->stmt_status->name.'</span>';
                                    break;
                                case "3":
                                    return '<span class="label label-default">'.$data->stmt_status->name.'</span>';
                                    break;
                                default:
                                    return '';
                            }
                        },
                        'width'=>'180px',
                        'filterType'=>GridView::FILTER_SELECT2,
                        'filter'=>ArrayHelper::map(\backend\models\MnStatementStatus::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear'=>true],
                        ],
                        'filterInputOptions'=>['placeholder'=>'Статус'],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Действие',
                        'headerOptions' => ['style'=>'text-align:center; vertical-align: middle;'],
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function ($url) {
                                return Html::a('Редактирование', $url, ['class' => 'btn btn-sm btn-default']);
                            }
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
