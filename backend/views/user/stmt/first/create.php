<?php
use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */

?>

<ol class="breadcrumb">
    <li><a href="#/">Главная</a></li>
    <li class="active">Новое обращение</li>
</ol>


<div class="stmt-create">

    <?= $this->render('_form', [
        'widget' => $widget,
    ]) ?>

</div>