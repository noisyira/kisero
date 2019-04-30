<?php
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $model backend\models\Statement */
/* @var $deffered backend\models\StmtDeffered */
/* @var $attch backend\models\StmtAttachment */
/* @var $erz backend\models\People */
/* @var $def */
/* @var $data */
/* @var $form yii\widgets\ActiveForm */
/* @var $forms yii\widgets\ActiveForm */

$this->title = 'Новое обращение';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="statement-form">

    <div class="row">
        <div class="col-md-12">


    <?php
        $items = [
            [
                'label'=>'<i class="glyphicon glyphicon-plus-sign"></i> Создание нового обращения',
                'content'=>$this->render('_tab_index', [
                    'model' => $model,
                    'deffered' => $deffered,
                    'attch' => $attch,
                    'erz' => $erz
                ]),
                'options' => ['id' => 'create'],
                'active'=>true
            ],
            [
                'label'=>'<i class="glyphicon glyphicon-search"></i> Поиск по реестру застрахованных',
                'content'=>$this->render('_tab_search', [ 'erz' => $erz, 'data' => $data ]),
                'options' => ['id' => 'search'],
            ],
        ];

        echo TabsX::widget([
            'items'=>$items,
            'position'=>TabsX::POS_ABOVE,
            'encodeLabels'=>false
        ]);
    ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        $("#stmtdeffered-active").click(function () {
            if($(this).is(":checked")){
                $("#def-answer").show();
            } else {
                $("#def-answer").hide();
            }
        });
    });
</script>