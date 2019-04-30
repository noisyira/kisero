<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\web\JsExpression;
use kartik\field\FieldRange;
use kartik\widgets\DepDrop;
use kartik\widgets\RangeInput;

/* @var $this yii\web\View */
/* @var $model backend\models\PollList */
/* @var $searchModel backend\models\PeopleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Создание нового опроса';
$this->params['breadcrumbs'][] = $this->title;

if($dataProvider)
    $insurance = ArrayHelper::map($dataProvider->getModels(), 'ENP', 'reestr.orgId', 'reestr.orgId');
?>
<div class="statement-index">

    <?php $form = \kartik\form\ActiveForm::begin([
        'id' => 'poll-form',
        'type'=>\kartik\form\ActiveForm::TYPE_VERTICAL,
        'options' => ['enctype'=>'multipart/form-data'],
        'formConfig'=>['deviceSize'=>\kartik\form\ActiveForm::SIZE_SMALL]
     ]);
    ?>

    <div class="row row-flex row-flex-wrap">
        <div class="col-md-2 text-center" style="padding-top: 2em">
            <h1><span class="grey">1</span></h1>
        </div>

        <div class="col-md-10">
            <div class="row">
                <div class="col-md-4">
                    <label>Типовой сценарий опроса</label>
                    <?php echo $form->field($model, 'poll_key')->widget(\kartik\widgets\Select2::classname(), [
                        'data' => \app\models\Poll::getOptions(),
                        'options' => ['placeholder' => 'Выберите сценарий опроса...'],
                        'hideSearch' => true,
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->label(false);
                    ?>
                </div>
                <div class="col-md-3">
                    <label>Период проведения опроса</label>
                    <?= \kartik\date\DatePicker::widget([
                        'model' => $model,
                        'attribute' => 'poll_start',
                        'attribute2' => 'poll_end',
                        'options' => ['placeholder' => 'Начало'],
                        'options2' => ['placeholder' => 'Завершение'],
                        'separator' => 'по',
                        'type' => \kartik\date\DatePicker::TYPE_RANGE,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'dd-mm-yyyy'
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-md-5">
                    <label>Описание</label>
                    <?= $form->field($model, 'description')->textarea(['rows' => '1','class'=>'form-control'])->label(false);?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <?= $form->field($model, 'tfoms')->checkbox()->label(false)?>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row row-flex row-flex-wrap">
        <div class="col-md-2 text-center" style="padding-top: 2em">
            <h1><span class="grey">2</span></h1>
        </div>

        <div class="col-md-10">
            <div class="row">
                <div class="col-md-12">
                    <label>Параметры поиска:</label>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <?= $form->field($searchModel, 'total')->textInput();?>
                </div>

                <div class="col-md-4">
                    <?php
                    $items = [
                        '1' => 'ООО СК "ИНГОССТРАХ-М"',
                        '2' => 'ЗАО ВТБ Медицинское страхование',
                    ];
                    ?>

                    <?php echo $form->field($searchModel, 'smo')->widget(\kartik\widgets\Select2::classname(), [
                        'data' => $items,
                        'options' => ['placeholder' => 'Выберите СМО...'],
                        'hideSearch' => true,
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
                    ?>
                </div>

                <div class="col-md-3">
                    <?= FieldRange::widget([
                        'form' => $form,
                        'model' => $searchModel,
                        'label' => 'Возраст',
                        'attribute1' => 'from_age',
                        'attribute2' => 'to_age',
                        'options1' => ['placeholder' => 'От'],
                        'options2' => ['placeholder' => 'До'],
                        'separator' => '—',
                    ]);
                    ?>
                </div>

                <div class="col-md-3">
                    <?= '<label class="control-label">Соотношение к СМО: (%)</label>';?>

                    <?=  RangeInput::widget([
                        'model' => $searchModel,
                        'attribute' => 'range',
                        'width' => '60%',
                        'options' => ['placeholder' => ''],
                        'html5Options' => ['min'=>0, 'max'=>100, 'step'=>10],
                        'addon' => [
                            'prepend' => ['content'=>'<span class="text-danger">0%</span>'],
                            'append' => ['content'=>'%']
                        ]
                    ]);
                    ?>
                    <div class="help-block">«Ингосстрах» — «ВТБ»</div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($searchModel, 'okato_mj')->widget(\kartik\widgets\Select2::className(), [
                        'options' => ['placeholder' => 'Место обращения ...'],
                        'id' => 'okato',
                        'data' => ArrayHelper::map(\backend\models\Kladr::getOkato($searchModel->okato_mj), 'OKATO', function($data){
                            return $data->SOCR .': '. $data->NAME;
                        }),
                        'pluginOptions' => [
                            'allowClear' => true,
                            'minimumInputLength' => 3,
                            'language' => [
                                'errorLoading' => new JsExpression("function () { return 'Поиск ...'; }"),
                            ],
                            'ajax' => [
                                'url' => \yii\helpers\Url::to(['okato']),
                                'dataType' => 'json',
                                'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                            ],
                            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                            'templateResult' => new JsExpression('function(city) { return city.SOCR +". "+ city.text; }'),
                            'templateSelection' => new JsExpression('function (city) {
                                if(city.SOCR) { return city.SOCR +": "+ city.text; } else { return city.text; }
                         }'),
                        ],
                    ]); ?>
                </div>

                <div class="col-md-4">
                    <?= $form->field($searchModel, 'mo')->widget(DepDrop::className(), [
                        'type' => DepDrop::TYPE_SELECT2,
                        'data' => \backend\models\MO::getMO($searchModel->okato_mj),
                        'options'=>['KODMO'=>'NAMMO', 'class'=>'input-large form-control', 'multiple' => false],
                        'select2Options'=>[
                            'pluginOptions'=>[
                                'allowClear'=>true,
                                'tags' => false,
                                'escapeMarkup' => new JsExpression("function(m) { return m; }"),
                                'templateSelection' => new JsExpression("function(data) { return data.text.split('<br>')[0]; }"),
                                'templateResult' => new JsExpression('function(data) { return data.text; }'),
                            ]
                        ],
                        'pluginOptions'=>[
                            'depends'=>['peoplesearch-okato_mj'],
                            'placeholder' => 'МО ...',
                            'loadingText' => 'МО ...',
                            'url'=>\yii\helpers\Url::to(['mo']),
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="cil-md-12 form-group text-right">
            <?= Html::a('Очистить поиск', [], ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton('Поиск', ['class' => 'btn btn-tfoms-blue', 'name' => 'search', 'value' => 1]) ?>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-tfoms-green', 'name' => 'save', 'value' => 1 ]) ?>
        </div>
    </div>
    <hr>

    <?php if($dataProvider): ?>

        <div class="row">

        </div>

        <div class="row">
            <!-- Параметр опроса -->
            <div class="col-md-4">

                <div class="row list-group">
                    <div class="col-md-4">
                        <strong><?= $model->getAttributeLabel('poll_key')?></strong>
                    </div>
                    <div class="col-md-8">
                        <?= \app\models\Poll::getNamePoll($model->getAttribute('poll_key'))->name ?>
                    </div>
                </div>

                <div class="row list-group">
                    <div class="col-md-4">
                        <strong><?= $model->getAttributeLabel('description')?></strong>
                    </div>
                    <div class="col-md-8">
                        <?= $model->getAttribute('description')?>
                    </div>
                </div>

                <div class="row list-group">
                    <div class="col-md-4">
                        <strong> <?= $model->getAttributeLabel('poll_start')?></strong>
                    </div>
                    <div class="col-md-8">
                        <?= $model->getAttribute('poll_start')?>
                    </div>
                </div>

                <div class="row list-group">
                    <div class="col-md-4">
                        <strong><?= $model->getAttributeLabel('poll_end')?></strong>
                    </div>
                    <div class="col-md-8">
                        <?= $model->getAttribute('poll_end')?>
                    </div>
                </div>
            </div>

            <!-- Параметры поиска -->
            <div class="col-md-4">

                <div class="row list-group">
                    <div class="col-md-4">
                        <strong><?= $searchModel->getAttributeLabel('smo')?></strong>
                    </div>
                    <div class="col-md-8">
                        <?php
                            switch ($searchModel->smo)
                            {
                                case 1:
                                    echo 'ООО "СК "ИНГОССТРАХ-М"';
                                    break;
                                case 2:
                                    echo 'ЗАО ВТБ Медицинское страхование';
                                    break;
                                default:
                                    echo '<code>(не указано)</code>';
                                    break;
                            }
                        ?>
                    </div>
                </div>

                <div class="row list-group">
                    <div class="col-md-4">
                        <strong><?= $searchModel->getAttributeLabel('mo')?></strong>
                    </div>
                    <div class="col-md-8">
                        <?php $mo = \backend\models\MO::getMO($searchModel->okato_mj); ?>
                        <?= empty($searchModel->mo)? '<code>(не указано)</code>' : $mo[$searchModel->mo] ?>
                    </div>
                </div>

                <div class="row list-group">
                    <div class="col-md-4">
                        <strong><?= $searchModel->getAttributeLabel('from_age')?></strong>
                    </div>
                    <div class="col-md-8">
                        <?= empty($searchModel->from_age)? '<code>(не указано)</code>' : $searchModel->from_age ?>
                    </div>
                </div>

                <div class="row list-group">
                    <div class="col-md-4">
                        <strong><?= $searchModel->getAttributeLabel('to_age')?></strong>
                    </div>
                    <div class="col-md-8">
                        <?= empty($searchModel->to_age)? '<code>(не указано)</code>' : $searchModel->to_age ?>
                    </div>
                </div>

                <div class="row list-group">
                    <div class="col-md-4">
                        <strong><?= $searchModel->getAttributeLabel('okato_mj')?></strong>
                    </div>
                    <div class="col-md-8">
                        <?php
                            $okato = ArrayHelper::map(\backend\models\Kladr::getOkato($searchModel->okato_mj), 'OKATO', function($data){
                                return $data->SOCR .': '. $data->NAME;
                            }) ;
                        ?>
                        <?= empty($searchModel->getAttribute('okato_mj'))? '<code>(не указано)</code>' : $okato[$searchModel->okato_mj] ?>
                    </div>
                </div>

            </div>

            <!-- Параметры -->
            <div class="col-md-4">
                <div class="row list-group">
                    <div class="col-md-4">
                        <strong>Количество</strong>
                    </div>
                    <div class="col-md-8">
                        <?= $dataProvider->getTotalCount(); ?>
                    </div>
                </div>

                <div class="row list-group">
                    <div class="col-md-4">
                        <strong>Ингосстрах-М</strong>
                    </div>
                    <div class="col-md-8">
                            <?= count($insurance[1])?>
                            <strong>
                                <?php if($dataProvider->getTotalCount() > 0): ?>
                                    (<?= Yii::$app->formatter->asPercent(count($insurance[1])/ $dataProvider->getTotalCount(), 2); ?>)
                                <?php else: ?>
                                    (0 %)
                                <?php endif; ?>
                            </strong>
                    </div>
                </div>

                <div class="row list-group">
                    <div class="col-md-4">
                        <strong>ВТБ</strong>
                    </div>
                    <div class="col-md-8">
                            <?= count($insurance[2])?>
                            <strong>
                                <?php if($dataProvider->getTotalCount() > 0): ?>
                                    (<?= Yii::$app->formatter->asPercent(count($insurance[2])/ $dataProvider->getTotalCount(), 2); ?>)
                                <?php else: ?>
                                    (0 %)
                                <?php endif; ?>
                            </strong>
                    </div>
                </div>

            </div>
        </div>

    <div class="row">
        <div class="col-md-12">
            <?= Html::hiddenInput('list', json_encode($dataProvider->getKeys()))?>
        </div>
        <div class="col-md-12">


        </div>
    </div>
    <?php endif; ?>
<?php \kartik\form\ActiveForm::end(); ?>
</div>
