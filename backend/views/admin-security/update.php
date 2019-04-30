<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Login */
/* @var $sip backend\models\SipAccount  */

$this->title = 'Редактирование: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Logins', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="login-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
        'sip' => $sip,
    ]) ?>

</div>
