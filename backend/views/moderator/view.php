<?php

use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\SipAccount;
use kartik\widgets\Select2;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use kartik\widgets\ActiveForm;
use kartik\widgets\FileInput;
use hosanna\audiojs\AudioJs;
use backend\controllers\ModeratorController;
use backend\models\StmtAttachment;
use backend\models\Login;

/* @var $this yii\web\View */
/* @var $model backend\models\Statement */
/* @var $cdr backend\models\Cdr */
/* @var $cel backend\models\Cel */

$this->title = 'Обращение '.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Обращение', 'url' => ['index']];
$this->params['breadcrumbs'][] = '# '.$model->id;
?>
<div class="statement-view">
    <div class="row">
        <div class="col-md-4">
            <div id="details-module_heading" class="mod-header">
                <ul class="ops"></ul>
                <h2 class="detail-title">Обращение</h2>
            </div>

            <div class="row pd5">
                <div class="col-md-4">
                    <strong class="name">Организация:</strong>
                </div>
                <div class="col-md-8">
                    <span id="type-val" class="value">
                         <?= ModeratorController::emptyVal($model->org->name) ?>
                    </span>
                </div>
            </div>

            <div class="row pd5">
                <div class="col-md-4">
                    <strong class="name">Вид обращения:</strong>
                </div>
                <div class="col-md-8">
                    <span id="type-val" class="value">
                        <?php
                            switch ($model->form_statement) {
                                case 0:
                                   $form = 'Устное';
                                    break;
                                case 1:
                                    $form = 'Письменное';
                                    break;

                                default:
                                    $form = 'Устное';
                            }
                        ?>
                        <?= ModeratorController::emptyVal($form) ?>
                    </span>
                </div>
            </div>

            <div class="row pd5">
                <div class="col-md-4">
                    <strong class="name">Тип обращения:</strong>
                </div>
                <div class="col-md-8">
                    <span id="type-val" class="value">
                        <?= ModeratorController::emptyVal($model->group->name) ?>
                    </span>
                </div>
            </div>

            <div class="row pd5">
                <div class="col-md-4">
                    <strong class="name">Тема обращения:</strong>
                </div>
                <div class="col-md-8">
                    <span id="type-val" class="value">
                        <?= ModeratorController::emptyVal($model->theme->theme_statement) ?>
                    </span>
                </div>
            </div>

            <div class="row pd5">
                <div class="col-md-4">
                    <strong class="name">Код обращения:</strong>
                </div>
                <div class="col-md-8">
                    <span id="type-val" class="value">
                        <?= ModeratorController::emptyVal($model->theme_statement) ?>
                    </span>
                </div>
            </div>

            <div class="row pd5">
                <div class="col-md-4">
                    <strong class="name">Комментарий:</strong>
                </div>
                <div class="col-md-8">
                    <span id="type-val" class="value">
                        <?= ModeratorController::emptyVal($model->theme_statement_description) ?>
                    </span>
                </div>
            </div>

            <div class="row pd5">
                <div class="col-md-4">
                    <strong class="name">Дата создания:</strong>
                </div>
                <div class="col-md-8">
                    <span id="type-val" class="value">
                        <?= ModeratorController::emptyDate($model->statement_date) ?>
                    </span>
                </div>
            </div>

            <div class="row pd5">
                <div class="col-md-4">
                    <strong class="name">Ответственный оператор:</strong>
                </div>
                <div class="col-md-8">
                    <span id="type-val" class="value">
                        <?= SipAccount::getFIO($model->user_o) ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div id="details-module_heading" class="mod-header">
                <ul class="ops"></ul>
                <h2 class="detail-title">Информация об обратившемся</h2>
            </div>

            <div class="row pd5">
                <div class="col-md-6">
                    <strong class="name">Фамилия:</strong>
                </div>
                <div class="col-md-6">
                <span id="type-val" class="value">
                    <?= ModeratorController::emptyVal($model->deffered->fam) ?>
                </span>
                </div>
            </div>

            <div class="row pd5">
                <div class="col-md-6">
                    <strong class="name">Имя:</strong>
                </div>
                <div class="col-md-6">
                <span id="type-val" class="value">
                    <?= ModeratorController::emptyVal($model->deffered->im) ?>
                </span>
                </div>
            </div>

            <div class="row pd5">
                <div class="col-md-6">
                    <strong class="name">Отчество:</strong>
                </div>
                <div class="col-md-6">
                <span id="type-val" class="value">
                     <?= ModeratorController::emptyVal($model->deffered->ot) ?>
                </span>
                </div>
            </div>

            <div class="row pd5">
                <div class="col-md-6">
                    <strong class="name">Дата рождения:</strong>
                </div>
                <div class="col-md-6">
                <span id="type-val" class="value">
                    <?= ModeratorController::emptyDate($model->deffered->dt) ?>
                </span>
                </div>
            </div>

            <div class="row pd5">
                <div class="col-md-6">
                    <strong class="name">Контактный телефон:</strong>
                </div>
                <div class="col-md-6">
                <span id="type-val" class="value">
                    <?= ModeratorController::emptyVal($model->deffered->phone) ?>
                </span>
                </div>
            </div>

            <div class="row pd5">
                <div class="col-md-6">
                    <strong class="name">E-mail адрес:</strong>
                </div>
                <div class="col-md-6">
                <span id="type-val" class="value">
                    <?= ModeratorController::emptyVal($model->deffered->email) ?>
                </span>
                </div>
            </div>

            <div class="row pd5">
                <div class="col-md-6">
                    <strong class="name">Отсроченный ответ:</strong>
                </div>
                <div class="col-md-6">
                <span id="type-val" class="value">
                    <?= ($model->deffered->active == 1)?'Да':'Нет' ?>
                </span>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="row" style="padding: 10px 0;">
                <div class="col-md-9">
                    <div id="details-module_heading" class="mod-header">
                        <h2 class="detail-title">
                            Прикрепленные файлы
                        </h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <?php
                    Modal::begin([
                        'header'=>'Добавить файл к обращению',
                        'toggleButton' => [
                            'tag' => 'span',
                            'label'=>'Добавить новый файл', 'class'=>'label label-primary',
                            'style' => 'float:right; cursor:pointer;'
                        ],
                    ]);

                    $form = ActiveForm::begin([
                        'options'=>['enctype'=>'multipart/form-data'] // important
                    ]);

                    echo FileInput::widget([
                        'attribute' => 'file_name[]',
                        'model' => new StmtAttachment(),
                        'options'=>[
                            'multiple' => true,
                        ],
                        'pluginOptions' => [
                            'showRemove' => true,
                            'browseClass' => 'btn btn-tfoms-green',
                            'uploadClass' => 'btn btn-info',
                            'removeClass' => 'btn btn-tfoms-red',
                            'removeIcon' => '<i class="glyphicon glyphicon-trash"></i> '
                        ]
                    ]);
                    ActiveForm::end();
                    Modal::end();
                    ?>
                </div>
            </div>


            <?php if(!$model->attachment):?>
                <div class="row pd5">
                    <div class="col-md-12">
                        Нет прикрепленных файлов к обращению.
                    </div>
                </div>
            <?php endif; ?>

            <?php foreach ($model->attachment as $key => $attch) :?>
                <div class="row pd5">
                    <div class="col-md-1">
                        <strong class="name"><?= $key + 1 ?>&#8228;</strong>
                    </div>
                    <div class="col-md-5">
                        <strong class="name"><?= $attch->file_name; ?></strong>
                    </div>
                    <div class="col-md-4">
                        <span id="type-val" class="value">
                            <?= $attch->dt;?>
                        </span>
                    </div>
                    <div class="col-md-2">
                       <span id="type-val" class="value">
                            <?= Html::a('просмотр', ['save-file', 'id' => $attch->id])?>
                        </span>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div id="details-module_heading" class="mod-header">
                <ul class="ops"></ul>
                <h4>Действия оператора</h4>
            </div>

            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <td>№</td>
                    <td>Дата</td>
                    <td>Оператор</td>
                    <td>Организация</td>
                    <td>Действие</td>
                    <td>Комментарий</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($model->allactions as $key=>$log):?>
                    <tr>
                        <td><?= $key +1 ?></td>
                        <td><?= Yii::$app->formatter->asDate($log->action_date, 'dd-MM-yyyy HH:i:s'); ?></td>
                        <td><?= SipAccount::getFIO($log->user_id); ?></td>
                        <td><?= \backend\models\Login::getCompanyUser($log->user_id)->companyname->name; ?></td>
                        <td><?= \backend\models\MnStatementAction::getNameAction($log->action) ?></td>
                        <td><?= $log->description; ?></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>

        <!-- Детализация обращения: -->
        <?php if($cdr):?>
        <div class="col-md-12">
        <h4>Детализация обращения: #<?= $model->id?></h4>
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <td>Дата</td>
                    <td>Оператор</td>
                    <td>Номер</td>
                    <td>Статус</td>
<!--                    <td>Длительность</td>-->
                    <td style="width: 500px;">Прослушать</td>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($cdr as $cdr_item):?>
                <?php if(!SipAccount::answerUser($cdr_item->dstchannel)) { continue; }?>
                <tr>
                    <td><?= $cdr_item->calldate; ?></td>
                    <td><?= SipAccount::answerUser($cdr_item->dstchannel); ?></td>
                    <td><?= $cdr_item->src; ?></td>
                    <td> Ответил </td>
<!--                    <td>-->
<!--                        --><?php
////                        date("H:i:s", mktime(0, 0, $cdr_item->billsec));
//                        ?>
<!--                    </td>-->
                    <td>
                        <?php if ($cdr_item->recordingfile){

                            switch ($cdr_item->dst) {
                                case "948113":
                                    $i = 3;
                                    break;
                                default:
                                    $i = 1;
                            }

                            echo AudioJs::widget([
                                'uploads' => '/record/'.$i,
                                'files'=>$cdr_item->recordingfile.'.mp3', //Full URL to Mp3 file here
                            ]); }
                        else { echo 'Нет записи'; }
                        ?>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
    </div>

    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title lead">
                        Результат обращения
                    </h3>
                </div>

                <div class="panel-body">
                    <?php
                    $form = ActiveForm::begin([
                        'id' => 'login-form-inline',
                    ]);
                    ?>

                    <dl class="dl-horizontal">
                        <dt>Текущий статус</dt>
                        <dd><?= $model->stmt_status->name ?></dd>
                    </dl>

                    <dl class="dl-horizontal">
                        <dt>Закрыть обращение</dt>
                        <dd>
                            <div class="col-sm-9">
                                <?php
                                    echo Html::radioList('statusStmt', 3,
                                        [
                                            3 => 'Закрыть обращение',
                                            2 => 'Продлить сроки рассмотрения',
                                            1 => 'Вернуть статус «В работе»'
                                        ],
                                        ['separator'=>"<br>",'encode'=>false]);
                                ?>
                            </div>
                        </dd>
                    </dl>

                    <?php if ($model->company == 1):?>
                    <dl id="sstu" class="dl-horizontal">
                        <dt>ССТУ.РФ</dt>
                        <dd>
                            <div class="col-sm-9">
                                <?= Html::checkbox('sstu', false, ['id' => 'sstu_id', 'value' => $model->id])?> <label for="sstu_id">Отправить обращение на ссту.рф</label>
                            </div>
                        </dd>
                    </dl>
                    <dl id="sstu" class="dl-horizontal">
                        <dt>Дата ответа ЗЛ</dt>
                        <dd>
                            <div class="col-sm-9">

                                <?= $form->field($model, 'date_send')->widget(DatePicker::className(), [
                                    'options' => ['placeholder' => 'Дата ответа ЗЛ'],
                                    'pluginOptions' => [
                                        'format' => 'yyyy-mm-dd',
                                        'todayHighlight' => true,
                                        'autoclose' => true,
                                    ]])->label(false);
                                ?>
                            </div>
                        </dd>
                    </dl>
                    <?php endif; ?>

                    <dl id="expired_date" class="dl-horizontal" style="display: none;">
                        <dt><?php  echo '<label class="control-label">Продлить до</label>'; ?></dt>
                        <dd>
                            <?php
                                // Usage with model and Active Form (with no default initial value)
                                echo $form->field($model, 'expired')->widget(\kartik\widgets\DatePicker::className(), [
                                    'options' => ['placeholder' => 'Enter birth date ...'],
                                    'pluginOptions' => [
                                        'autoclose'=>true,
                                        'startDate' => $model->expired,
                                        'format' => 'yyyy-mm-dd'
                                    ],
                                ])->label(false);

                            ?>
                        </dd>
                    </dl>

                    <dl class="dl-horizontal">
                        <dt><?php  echo '<label class="control-label">Оператор</label>'; ?></dt>
                        <dd>
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
                ])->label(false);
                            ?>

                        </dd>
                    </dl>

                    <dl class="dl-horizontal">
                        <dt></dt>
                        <dd>
                            <?= Html::submitButton('Закрыть обращение',
                                ['class' => 'btn btn-tfoms-blue',
                                    'name' => 'closeStmt',
                                    'value'=>'close',]) ?>
                        </dd>
                    </dl>
                    <?php ActiveForm::end(); ?>
                </div> <!-- /panel body -->
            </div>
        </div>
    </div>
</div>

<script>
    $('input[name="statusStmt"]').on('change', function(e) {
        var value = $('input[name="statusStmt"]:checked' ).val();

        if(value == 2)
        {
            $("#expired_date").show();
        } else {
            $("#expired_date").hide();
        }

        if(value == 1)
        {
            $("#user_o").show();
        } else {
            $("#user_o").hide();
        }

        if(value == 3)
        {
            $("#sstu").show();
        } else {
            $("#sstu").hide();
        }
    });
</script>