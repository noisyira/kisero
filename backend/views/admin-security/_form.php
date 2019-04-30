<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use backend\models\MnCompany;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;

/* @var $this yii\web\View */
/* @var $model backend\models\Login */
/* @var $sip backend\models\SipAccount */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="login-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="col-md-12">
        <div class="col-md-6">
            <legend>Перс. данные</legend>
            <?= $form->field($model, 'type')->dropDownList($items,[
                'prompt' => 'Тип пользователя'
            ]);?>

            <?php
            // Все организации
            $companys = MnCompany::find()->all();
            // формируем массив, с ключем равным полю 'id' и значением равным полю 'name'
            $items = ArrayHelper::map($companys,'id','name');

            $params = [
                'id' => 'company',
                'prompt' => 'Выберите организацию...'
            ];
            echo $form->field($model, 'company')->dropDownList($items, $params);
            ?>

            <?php echo Html::hiddenInput('model_id1', $model->id, ['id'=>'model_id1' ]); ?>

            <?= $form->field($model, 'sub_company')->widget(DepDrop::className(), [
                'options'=>['id'=>'name', 'class'=>'input-large form-control'],
                'data'=> ArrayHelper::map(\backend\models\MnCompanySub::getSubCompany($model->company),'id','name'),
                'pluginOptions'=>[
                    'depends'=>['company'],
                    'placeholder' => 'Филиал ...',
                    'loadingText' => 'Поиск ...',
                    'url'=>\yii\helpers\Url::to(['theme'])
                ]
            ]);
            ?>

            <?= $form->field($model, 'subdivision')->textInput() ?>

            <?= $form->field($model, 'username')->textInput() ?>

            <?= $form->field($model, 'secret', [
                'addon' => [
                    'append' => [
                        'content' => Html::button('сгенерировать', [
                            'class'=>'btn btn-default',
                            'onclick'=> "$('#login-secret').val(str_rand());"
                        ]),
                        'asButton' => true
                    ]
                ]
            ]);?>

            <?= $form->field($model, 'fam')->textInput() ?>

            <?= $form->field($model, 'im')->textInput() ?>

            <?= $form->field($model, 'ot')->textInput() ?>
        </div>

        <?php if($sip):?>
            <div class="col-md-6">
                <legend>Настройки SIP</legend>

                <?= $form->field($sip, 'createSIP')->checkbox(); ?>

                <div id="hiddenDiv" class="col-md-12">
                    <?= $form->field($sip, 'sip_type')->dropDownList(['SIP' => 'SIP', 'PJSIP' => 'PJSIP'],[
                        'prompt' => 'Тип аккаунта...'
                    ]);?>

                    <?= $form->field($sip, 'sip_dispaly_name')->textInput(['placeholder' => 'Contact center']) ?>

                    <?= $form->field($sip, 'sip_private_identity')->textInput(['placeholder' => '1001']) ?>

                    <?= $form->field($sip, 'sip_password')->textInput(['placeholder' => 'пароль']) ?>

                    <?= $form->field($sip, 'sip_outer')->checkbox(); ?>
                </div>

            </div>
        <?php endif;?>

    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-tfoms-green' : 'btn btn-tfoms-blue']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    function str_rand() {
        var result       = '';
        var words        = '0123456789qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        var max_position = words.length - 1;
        for( i = 0; i < 8; ++i ) {
            position = Math.floor ( Math.random() * max_position );
            result = result + words.substring(position, position + 1);
        }
        return result;
    }

    $('#sipaccount-createsip').change(function(){
        $('#hiddenDiv').toggle(); // or do $('#MODELNAME_nilaiblksk').parent().toggle();
    });
</script>