<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\DepDrop;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use kartik\widgets\FileInput;
use kartik\date\DatePicker;
use backend\models\MnGroupStatement;
use backend\models\Login;

/* @var $this yii\web\View */
/* @var $model backend\models\Statement */
/* @var $deffered backend\models\StmtDeffered */
/* @var $attch backend\models\StmtAttachment */
/* @var $erz backend\models\People */
/* @var $def */
/* @var $model_atch */
/* @var $data */
/* @var $form yii\widgets\ActiveForm */
/* @var $forms yii\widgets\ActiveForm */
?>

<?php
$form = ActiveForm::begin([
    'id' => 'stmt-form',
    'type'=>ActiveForm::TYPE_HORIZONTAL,
    'options' => ['enctype'=>'multipart/form-data'],
    'formConfig'=>['labelSpan'=>3, 'deviceSize'=>ActiveForm::SIZE_SMALL],
]);
?>

<div class="row">
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Обращение</h3>
            </div>
            <div class="panel-body">
                <?php
                $items = [
                    '1' => 'Через законного представителя',
                    '2' => 'Через web-приложение',
                    '3' => 'Лично',
                    '4' => 'Почтой',
                    '5' => 'По телефону',
                    '6' => 'По e-mail',
                    '7' => 'Курьером',
                    '8' => 'Автоинформатором',
                ];
                $params = [
                    'prompt' => 'Выберите вид обращения...'
                ];

                echo $form->field($model, 'statement')->dropDownList($items,$params);
                ?>

                <?= $form->field($model, 'tip_statement')->dropDownList(MnGroupStatement::getOptions(),
                    [   'id' => 'tip_statement',
                        'class'=>'input-large form-control',
                        'prompt' => 'Выберите тип обращения...'
                    ]);
                ?>

                <?= $form->field($model, 'theme_statement')->widget(DepDrop::className(), [
                    'options'=>['key_statement'=>'theme_statement', 'class'=>'input-large form-control'],
                    'pluginOptions'=>[
                        'depends'=>['tip_statement'],
                        'placeholder' => 'Тема обращения ...',
                        'loadingText' => 'Тема обращения ...',
                        'url'=>\yii\helpers\Url::to(['theme'])
                    ]
                ]);
                ?>

                <?= $form->field($model, 'theme_statement_description')->textarea()?>


<?php
        $format = <<< SCRIPT
function format(state) {
    return state.text;
}
function selection(state) {
    return 'Исполнитель: ' + state.text.split('<br>')[0];
}
SCRIPT;

            $escape = new JsExpression("function(m) { return m; }");
            $this->registerJs($format, \yii\web\View::POS_HEAD);
            $model->user_o = Yii::$app->user->id;
                echo $form->field($model, 'user_o')->widget(Select2::className(), [
                        'data' => Login::responsibleUser(),
                        'options' => ['placeholder' => ' ', 'multiple' => false],
                        'class'=>'input-large form-control',
                        'pluginOptions' => [
                            'templateResult' => new JsExpression('format'),
                            'templateSelection' => new JsExpression('selection'),
                            'escapeMarkup' => $escape,
                            'tags' => false,
                            'maximumInputLength' => 10,
                        ],
                    ]); ?>

            </div> <!-- /panel body -->
        </div> <!-- /panel panel-default -->

    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Информация обратившегося</h3>
            </div>
            <div class="panel-body">
                <?= $form->field($deffered, 'id_erz')->hiddenInput()->label(false);?>

                <?= $form->field($deffered, 'fam')->textInput(['placeholder' => 'Фамилия'])?>

                <?= $form->field($deffered, 'im')->textInput(['placeholder' => 'Имя'])?>

                <?= $form->field($deffered, 'ot')->textInput(['placeholder' => 'Отчество'])?>

                <?= $form->field($deffered, 'req_okato')->widget(Select2::className(), [
                    'options' => ['placeholder' => 'Место обращения ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 3,
                        'language' => [
                            'errorLoading' => new JsExpression("function () { return 'Поиск ...'; }"),
                        ],
                        'ajax' => [
                            'url' => \yii\helpers\Url::to(['city']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                        'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                        'templateResult' => new JsExpression('function(city) { return city.SOCR +". "+ city.text; }'),
                        'templateSelection' => new JsExpression('function (city) { return city.text; }'),
                    ],
                ]); ?>

                <?= $form->field($deffered, 'name_okato')->hiddenInput()->label(false);?>

                <?= $form->field($deffered, 'dt')->widget(DatePicker::className(), [
                    'options' => ['placeholder' => 'Дата рождения ...'],
                    'pluginOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true,
                        'autoclose'=>true
                    ]]);
                ?>

                <?= $form->field($deffered, 'active')->checkbox()?>

                <div id="def-answer" style="display: none">

                    <?= $form->field($deffered, 'phone')->textInput(['placeholder' => 'Контактный телефон'])?>

                    <?= $form->field($deffered, 'email')->textInput(['placeholder' => 'E-mail'])?>

                    <?= $form->field($deffered, 'description')->textarea(['placeholder' => 'Комментарий'])?>

                </div>

            </div> <!-- /panel body -->
        </div> <!-- /panel panel-default -->
    </div>

    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Прикрепить файл</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-5">
                        <?php
                        echo '<label class="control-label">Номер документа</label>';
                        echo $form->field($attch, 'n_attach')->textInput(['placeholder' => 'номер'])->label(false);
                        ?>
                    </div>
                    <div class="col-md-7">
                        <?php
                        echo '<label class="control-label">Дата регистрации</label>';
                        echo $form->field($attch, 'date_attach')->label(false)->widget(DatePicker::className(), [
                            'options' => [
                                'style' => 'width:170px',
                                'placeholder' => 'Дата регистрации ...'
                            ],
                            'pluginOptions' => [
                                'format' => 'dd-mm-yyyy',
                                'todayHighlight' => true,
                                'autoclose'=>true
                            ]]);
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?php
                            // Usage without a model
                            echo '<label class="control-label">Прикрепить документ</label>';
                            echo FileInput::widget([
                                'attribute' => 'file_name[]',
                                'model' => new \backend\models\StmtAttachment(),
                                'options'=>[
                                    'multiple' => true,
                                ],
                                'pluginOptions' => [
                                    'showRemove' => true,
                                    'showUpload' => false,
                                    'browseClass' => 'btn btn-tfoms-green',
                                    'uploadClass' => 'btn btn-info',
                                    'removeClass' => 'btn btn-tfoms-red',
                                    'removeIcon' => '<i class="glyphicon glyphicon-trash"></i> '
                                ]
                            ]);
                        ?>
                    </div>
                </div>

            </div> <!-- /panel body -->
        </div> <!-- /panel panel-default -->
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-tfoms-blue', 'name' => 'stmt',]) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>