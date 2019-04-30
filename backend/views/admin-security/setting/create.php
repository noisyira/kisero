<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SipSetting */

$this->title = 'Create Sip Setting';
$this->params['breadcrumbs'][] = ['label' => 'Sip Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sip-setting-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>