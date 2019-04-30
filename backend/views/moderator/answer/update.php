<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\AnswerScript */

$this->title = 'Редактирование: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Сценарии ответа', 'url' => ['answer-script']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="answer-script-update">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
