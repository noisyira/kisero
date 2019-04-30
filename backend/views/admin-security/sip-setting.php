<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $sip backend\models\SipSetting */

$this->title = 'Настройка Sip';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-12">
    <div class="col-md-3">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($sip, 'sip_websocket_proxy_url')->textInput() ?>

        <?= $form->field($sip, 'sip_outbound_proxy_url')->textInput() ?>

        <?= $form->field($sip, 'sip_ice_servers')->textInput() ?>

        <?= $form->field($sip, 'sip_disable_video')->textInput() ?>

        <div class="form-group">
            <?= Html::submitButton($sip->isNewRecord ? 'Сохранить' : 'Обновить',
                ['class' => $sip->isNewRecord ? 'btn btn-tfoms-green' : 'btn btn-tfoms-blue']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
