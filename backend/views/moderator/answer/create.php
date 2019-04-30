<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\AnswerScript */

$this->title = 'Добавить сценарий ответа';
$this->params['breadcrumbs'][] = ['label' => 'Сценарии ответа', 'url' => ['answer-script']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="answer-script-create">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>