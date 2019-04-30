<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use dosamigos\ckeditor\CKEditor;
use backend\models\MnGroupStatement;
use backend\models\SipAccount;

/* @var $this yii\web\View */
/* @var $model backend\models\AnswerScript */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="answer-script-form">
<div class="row">
    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL, 'formConfig'=>['labelSpan'=>2]]); ?>
    <div class="col-md-12">
        <?= $form->field($model, 'name')->textInput() ?>

        <?php if(!$model->id): ?>

        <?= $form->field($model, 'group')->dropDownList(MnGroupStatement::getOptions(),
            [   'id' => 'tip_statement',
                'class'=>'input-large form-control',
                'prompt' => 'Выберите тип обращения...'
            ]);
        ?>

        <?= $form->field($model, 'key_statement')->widget(DepDrop::className(), [
            'options'=>['key_statement'=>'theme_statement', 'class'=>'input-large form-control'],
            'pluginOptions'=>[
                'depends'=>['tip_statement'],
                'placeholder'=>'Тема обращения...',
                'url'=>\yii\helpers\Url::to(['theme'])
            ]
        ]);
        ?>

        <?php endif;?>

        <?= $form->field($model, 'answer')->widget(CKEditor::className(), [ 'preset' => 'full' ]) ?>

        <?php
        $format = <<< SCRIPT
function format(state) {
    return state.text;
}
function selection(state) {
    return 'Вн. номер: ' + state.id;
}
SCRIPT;

        $escape = new \yii\web\JsExpression("function(m) { return m; }");
        $this->registerJs($format, \yii\web\View::POS_HEAD);
        $model->recomend_users = isset($model->recomend_users)?json_decode($model->recomend_users):'';
        echo $form->field($model, 'recomend_users')->widget(Select2::className(), [
            'data' => SipAccount::recomendUsers(),
            'options' => ['placeholder' => 'Оператор ...', 'multiple' => true],
            'pluginOptions' => [
                'templateResult' => new \yii\web\JsExpression('format'),
                'templateSelection' => new \yii\web\JsExpression('selection'),
                'escapeMarkup' => $escape,
                'tags' => true,
                'maximumInputLength' => 10,
                'allowClear' => true,
            ],
        ]);
        ?>
    </div>
    <div class="col-md-12 text-right" style="margin: 20px 0;">
        <?= Html::submitButton($model->isNewRecord ? 'Сохранить' : 'Обновить',
            ['class' => $model->isNewRecord ? 'btn btn-tfoms-green' : 'btn btn-tfoms-blue']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</div>