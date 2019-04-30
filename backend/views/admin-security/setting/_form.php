<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SipSetting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sip-setting-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sip_websocket_proxy_url')->textInput() ?>

    <?= $form->field($model, 'sip_outbound_proxy_url')->textInput() ?>

    <?= $form->field($model, 'sip_ice_servers')->textInput() ?>

    <?= $form->field($model, 'sip_disable_video')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-tfoms-green' : 'btn btn-tfoms-blue']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>