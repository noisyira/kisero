<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use \kartik\form\ActiveForm;
use kartik\daterange\DateRangePicker;
use dosamigos\ckeditor\CKEditor;
use dosamigos\ckeditor\CKEditorInline;
use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model app\models\Monitoring */

$this->title = 'Мониторинг';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="post-search">
    <?php $form = ActiveForm::begin([
        'method' => 'post',
    ]); ?>
    <div class="row">
        <div class="col-md-6">
            <?php echo $form->field($model, 'range', [
                'addon' => [
                    'prepend' => ['content'=>'<i class="glyphicon glyphicon-calendar"></i>'],
                    'append' => ['content'=> Html::submitButton('Показать', ['class' => 'btn btn-default']), 'asButton'=>true]
                ],
                 'options'=>['class'=>'drp-container form-group']
                ])->widget(DateRangePicker::classname(), [
                'useWithAddon'=>true,
                'pluginOptions' => [
                    'locale' => ['format' => 'DD-MM-YYYY'],
                    'ranges' => [
                        "Январь" => ["moment().startOf('year')", "moment().startOf('year').add(0, 'month').endOf('month')"],
                        "Февраль" => ["moment().startOf('year')", "moment().startOf('year').add(1, 'month').endOf('month')"],
                        "Март" => ["moment().startOf('year')", "moment().startOf('year').add(2, 'month').endOf('month')"],
                        "Апрель" => ["moment().startOf('year')", "moment().startOf('year').add(3, 'month').endOf('month')"],
                        "Май" => ["moment().startOf('year')", "moment().startOf('year').add(4, 'month').endOf('month')"],
                        "Июнь" => ["moment().startOf('year')", "moment().startOf('year').add(5, 'month').endOf('month')"],
                        "Июль" => ["moment().startOf('year')", "moment().startOf('year').add(6, 'month').endOf('month')"],
                        "Август" => ["moment().startOf('year')", "moment().startOf('year').add(7, 'month').endOf('month')"],
                        "Сентябрь" => ["moment().startOf('year')", "moment().startOf('year').add(8, 'month').endOf('month')"],
                        "Октябрь" => ["moment().startOf('year')", "moment().startOf('year').add(9, 'month').endOf('month')"],
                        "Ноябрь" => ["moment().startOf('year')", "moment().startOf('year').add(10, 'month').endOf('month')"],
                        "Декабрь" => ["moment().startOf('year')", "moment().startOf('year').add(11, 'month').endOf('month')"],
                        "I - квартал" =>   ["moment().startOf('year')", "moment().startOf('year').add(2, 'month').endOf('month')"],
                        "II - квартал" =>  ["moment().startOf('year')", "moment().startOf('year').add(5, 'month').endOf('month')"],
                        "III - квартал" => ["moment().startOf('year')", "moment().startOf('year').add(8, 'month').endOf('month')"],
                        "IV - квартал" =>  ["moment().startOf('year')", "moment().startOf('year').add(11, 'month').endOf('month')"],
                    ]
                ],
                ]);
            ?>
        </div>

        <div class="col-md-6 text-right">
            <p>
                <br>
               <?= Html::a('Список всех файлов', 'history')?>
            </p>

        </div>
    </div>

    <?php if($model->text): ?>
    <div class="row">
        <div class="col-md-12 text-right">
            <?= Html::submitButton('<i class="fa fa-floppy-o" aria-hidden="true"></i> Сохранить', ['class' => 'btn btn-link', 'name' => 'save', 'value' => 1]) ?>
        </div>

        <div class="col-md-12">
            <?= $form->field($model, 'text')->widget(TinyMce::className(),
                [
                    'options' => ['rows' => 30],
                    'language' => 'ru',
                    'clientOptions' =>
                        [
                            'plugins' => [ "advlist autolink lists link charmap print preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table contextmenu paste" ],
                            'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image" ]
                ]);?>
        </div>
    </div>
    <?php endif; ?>
    <?php ActiveForm::end(); ?>
</div>


