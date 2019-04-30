<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Statement */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Statements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="statement-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-tfoms-blue']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-tfoms-red',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'channel_id',
            'user_id',
            'send_user',
            'statement',
            'statement_date',
            'tip_statement',
            'theme_statement',
            'theme_statement_description:ntext',
            'erz_uid',
            'f_name',
            'name',
            'l_name',
            'dt',
            'def_answer',
            'contact_phone',
            'status',
        ],
    ]) ?>

</div>
