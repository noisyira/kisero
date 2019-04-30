<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\StatementSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="statement-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'channel_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'send_user') ?>

    <?= $form->field($model, 'statement') ?>

    <?php // echo $form->field($model, 'statement_date') ?>

    <?php // echo $form->field($model, 'tip_statement') ?>

    <?php // echo $form->field($model, 'theme_statement') ?>

    <?php // echo $form->field($model, 'theme_statement_description') ?>

    <?php // echo $form->field($model, 'erz_uid') ?>

    <?php // echo $form->field($model, 'f_name') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'l_name') ?>

    <?php // echo $form->field($model, 'dt') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-tfoms-blue']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
