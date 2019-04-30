<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\MnStatement;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\MnStatement */
/* @var $form ActiveForm */
?>
<div class="admin-security-statement-index">
    <h2>Записи разговоров</h2>

    <?= GridView::widget([
        'dataProvider' => $data,
        'options'=>['class'=>''],
        'columns' => [
            'calldate',
            'src',
            'uniqueid',
            'dcontext',
            [
                'attribute' => 'dst',
                'label' => 'Организация',
                'format' => 'raw',
                'value' => function($model){
                    switch ($model->dst) {
                        case "948113":
                            return 'ИНГОССТРАХ-М';
                            break;
                        default:
                            return 'ТФОМС';
                    }
                }
            ],
            [
                'attribute' => 'recordingfile',
                'label' => 'Запись разговора',
                'format' => 'raw',
                'value' => function($model){

                    switch ($model->dst) {
                        case "948113":
                            $i = 3;
                            break;
                        default:
                            $i = 1;
                    }

                    $path = Yii::getAlias('@backend').DIRECTORY_SEPARATOR.'web'.DIRECTORY_SEPARATOR.'record'.DIRECTORY_SEPARATOR.$i;

                    if(file_exists($path.DIRECTORY_SEPARATOR.$model->recordingfile.'.mp3'))
                    {
                        $file = $model->recordingfile.'.mp3';
                    } else {
                        $file = $model->recordingfile.'.wav';
                    }

                    if ($model->recordingfile && file_exists($path.DIRECTORY_SEPARATOR.$file)){

                        return \hosanna\audiojs\AudioJs::widget([
                            'uploads' => '/record/'.$i,
                            'files'=>$file, //Full URL to Mp3 file here
                        ]);
                    }
                    else { return 'Нет записи'; }

//                    return Yii::$app->formatter->asDate($model->statement_date, 'dd-MM-yyyy');
                },
                'options' => ['width' => '200'],
                'headerOptions' => ['style'=>'text-align:center; vertical-align: middle;'],
            ],
        ],
    ]); ?>
</div><!-- admin-security-statement-index -->