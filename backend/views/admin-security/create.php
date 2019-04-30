<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Login */
/* @var $sip backend\models\SipAccount  */

$this->title = 'Create Login';
$this->params['breadcrumbs'][] = ['label' => 'Logins', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'items' => $items,
        'sip' => $sip,
    ]) ?>

</div>
