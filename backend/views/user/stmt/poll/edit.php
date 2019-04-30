<?php
use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */

?>

<ol class="breadcrumb">
    <li><a href="#/">Главная</a></li>
    <li><a href="#/dial">Журнал опроса</a></li>
    <li class="active">Просмотр</li>
</ol>

<div class="stmt-view">

    <?= $this->render('_edit', [
        'widget' => $widget,
    ]) ?>

</div>