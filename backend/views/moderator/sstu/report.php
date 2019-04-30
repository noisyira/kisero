<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\StatementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ССТУ отчет';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="statement-index">
    <div class="row">
        <div class="col-md-12">

         <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'Year',
                    'label' => 'Год',
                    'filter' => false,
                    'headerOptions' => ['style'=>'text-align:center; vertical-align: middle;'],
                ],
                [
                    'attribute' => 'MonthNum',
                    'label' => 'Месяц',
                    'filter' => false,
                    'headerOptions' => ['style'=>'text-align:center; vertical-align: middle;'],
                    'value' => function($model){
                        return \backend\components\Helpers::MonthList()[$model['MonthNum']];
                    }
                ],
                [
                    'attribute' => 'Total',
                    'label' => 'Количество',
                    'width'=>'40px',
                    'filter' => false,
                    'headerOptions' => ['style'=>'text-align:center; vertical-align: middle;'],
                ]
            ]
        ]);
        ?>
        </div>
    </div>
</div>