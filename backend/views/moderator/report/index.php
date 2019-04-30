<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\StatementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Архив обращений';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="post-search">
    <?php $form = \kartik\form\ActiveForm::begin([
        'method' => 'post',
    ]); ?>
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="form-group">
                Обращение застрахованных лиц —
                <?= Html::submitButton('Таблица 1.1 <i class="fa fa-download" aria-hidden="true"></i>', ['class' => 'btn btn-default btn-sm', 'name' => 'download', 'value' => '1']) ?>
                <?= Html::submitButton('Таблица 1.2 <i class="fa fa-download" aria-hidden="true"></i>', ['class' => 'btn btn-default btn-sm', 'name' => 'plaint', 'value' => '1']) ?>
            </div>
        </div>
    </div>
    <?php \kartik\form\ActiveForm::end(); ?>
</div>

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
                        'headerOptions' => ['style'=>'text-align:center; vertical-align: middle;'],
                        'width'=>'100px',
                    ],
                    [
                        'attribute' => 'org',
                        'label' => 'Огранизация',
                        'headerOptions' => ['style'=>'text-align:center; vertical-align: middle;'],
                        'width'=>'200px',
                        'format' => 'html',
                        'filterType'=>GridView::FILTER_SELECT2,
                        'filter'=>ArrayHelper::map(\backend\models\MnCompany::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear'=>true],
                        ],
                        'filterInputOptions'=>['placeholder'=>'Организация'],
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
                            //'presetDropdown' => true,
                            'pluginOptions' => [
                                'locale' => ['format' => 'DD-MM-YYYY'],
                                'ranges' => [
                                    "Текущий месяц" => ["moment().startOf('month')", "moment().endOf('month')"],
                                    "Прошлый месяц" => ["moment().subtract(1, 'month').startOf('month')", "moment().subtract(1, 'month').endOf('month')"],
                                    "I - квартал" =>   ["moment().startOf('year')", "moment().startOf('year').add(2, 'month').endOf('month')"],
                                    "II - квартал" =>  ["moment().startOf('year')", "moment().startOf('year').add(5, 'month').endOf('month')"],
                                    "III - квартал" => ["moment().startOf('year')", "moment().startOf('year').add(8, 'month').endOf('month')"],
                                    "IV - квартал" =>  ["moment().startOf('year')", "moment().startOf('year').add(11, 'month').endOf('month')"],
                                ]
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
                        'filter'=>ArrayHelper::map(\backend\models\MnGroupStatement::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear'=>true],
                        ],
                        'filterInputOptions'=>['placeholder'=>'Тип обращения'],
                    ],
                    [
                        'attribute' => 'theme',
                        'label' => 'Тема обращения',
                        'value' => 'theme.theme_statement',
                        'headerOptions' => ['style'=>'text-align:center; vertical-align: middle;'],
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => \backend\models\MnStatement::getSearchTheme(),
                        'filterWidgetOptions'=>[
                            'pluginOptions'=>['allowClear'=>true],
                        ],
                        'filterInputOptions'=>['placeholder'=>'Тематика'],
                        'width'=>'300px',

                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>
