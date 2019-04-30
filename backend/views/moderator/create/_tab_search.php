<?php
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\widgets\DatePicker;
use kartik\widgets\ActiveForm;

/* @var $erz backend\models\People */
/* @var $data */
?>
<div class="row">
    <div class="col-md-12">
        <p>
            Введите данные для поиска застрахованных в Ставропольском крае
        </p>
        <?php Pjax::begin();?>
        <?php $form = ActiveForm::begin([
            'id' => 'login-form-inline',
            'class' => 'form-inline',
            'type' => ActiveForm::TYPE_INLINE,
            'formConfig' => ['showErrors' => true],
            'options' => ['data-pjax' => true]
        ]); ?>

        <?= $form->field($erz, 'sName')->textInput(['placeholder' => 'Фамилия'])?>
        <?= $form->field($erz, 'Name')->textInput(['placeholder' => 'Имя'])?>
        <?= $form->field($erz, 'pName')->textInput(['placeholder' => 'Отчество'])?>

        <?= $form->field($erz, 'dateMan')->widget(DatePicker::className(), [
            'type' => DatePicker::TYPE_COMPONENT_PREPEND,
            'options' => ['placeholder' => 'Дата рождения ...'],
            'pluginOptions' => [
                'format' => 'dd-mm-yyyy',
                'todayHighlight' => true,
                'autoclose'=>true
            ]
        ]);?>

        <?= Html::submitButton('Найти', ['class' => 'btn btn-tfoms-blue']) ?>
        <?= Html::resetButton('Очистить', [
            'class' => 'btn btn-default',
            'onclick' => new \yii\web\JsExpression("clearZL()"),
        ]) ?>


    <div class="row">
        <div class="col-md-8">

        <?php if($data): ?>
            <p>
                Список застрахованных
            </p>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <td>№</td>
                        <td>Фамилия</td>
                        <td>Имя</td>
                        <td>Отчество</td>
                        <td>Дата рождения</td>
                        <td>Действие</td>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $key=>$item):?>
                    <?php
                    $data = json_encode($item->attributes);
                    $dt = json_encode(Yii::$app->formatter->asDate($item->dateMan, 'dd-MM-yyyy'));
                    ?>
                    <tr>
                        <td><?= $key + 1; ?></td>
                        <td><?= $item->sName; ?></td>
                        <td><?= $item->Name; ?></td>
                        <td><?= $item->pName; ?></td>
                        <td><?= Yii::$app->formatter->asDate($item->dateMan, 'dd-MM-yyyy'); ?></td>
                        <td><?= Html::a('Применить', '#create' , [
                                'class' => 'exit',
                                'style' => 'font-size: 14px;',
                                'onclick' => new \yii\web\JsExpression("SetZL($data, $dt)"),
                                'data-toggle' => 'tab',
                                'role' => 'tab',
                            ])?></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        <?php endif; ?>

        </div>
    </div>
        <?php ActiveForm::end(); ?>
        <?php Pjax::end(); ?>
    </div>
</div>

<script>
    function clearZL() {
        $('#people-sname').attr('value', "");
        $('#people-name').attr('value', "");
        $('#people-pname').attr('value', "");
        $('#people-dateman').attr('value', "");
    }

    function SetZL(data, dt) {
        $('#stmtdeffered-id_erz').val(data.ENP);
        $('#stmtdeffered-fam').val(data.sName);
        $('#stmtdeffered-im').val(data.Name);
        $('#stmtdeffered-ot').val(data.pName);
        $('#stmtdeffered-name_okato').val(data.pbMan);
        $('#stmtdeffered-req_okato').append('<option value="'+data.okato_reg+'">'+data.pbMan+'</option>');
        $('#stmtdeffered-req_okato option[value='+data.okato_reg+']').attr('selected', 'selected');
        $('#select2-stmtdeffered-req_okato-container').text(data.pbMan);
        $('#stmtdeffered-dt').val(dt);
        OpenTab();
    }

    function OpenTab() {
        $('#w1-container li.active').removeClass('active');
        $('#w1-container li:first').toggleClass('active');
    }
</script>
