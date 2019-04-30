<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\MnStatement;

/* @var $this yii\web\View */
/* @var $model backend\models\MnStatement */
/* @var $form ActiveForm */
?>
<div class="admin-security-statement-index">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-md-6">
        <?= $form->field($model, 'group_statement')->dropDownList(MnStatement::getGroupStatement());?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'key_statement') ?>
    </div>

    <div class="col-md-12">
        <?= $form->field($model, 'theme_statement') ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-tfoms-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- admin-security-statement-index -->