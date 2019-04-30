<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Statement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="statement-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'channel_id')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'send_user')->textInput() ?>

    <?= $form->field($model, 'statement')->textInput() ?>

    <?= $form->field($model, 'statement_date')->textInput() ?>

    <?= $form->field($model, 'tip_statement')->textInput() ?>

    <?= $form->field($model, 'theme_statement')->textInput() ?>

    <?= $form->field($model, 'theme_statement_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'erz_uid')->textInput() ?>

    <?= $form->field($model, 'f_name')->textInput() ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'l_name')->textInput() ?>

    <?= $form->field($model, 'dt')->textInput() ?>

    <?= $form->field($model, 'def_answer')->textInput() ?>

    <?= $form->field($model, 'contact_phone')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-tfoms-green' : 'btn btn-tfoms-blue']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
