<?php
use dee\angular\NgView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget NgView */

?>

<ol class="breadcrumb">
    <li><a href="#/">Главная</a></li>
    <li class="active">Просмотр обращения</li>
</ol>

<div class="stmt-view">

    <?= $this->render('_view', [
        'widget' => $widget,
    ]) ?>

</div>