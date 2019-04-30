
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\AnswerScript */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Сценарии ответа', 'url' => ['answer-script']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="answer-script-view">

    <h4><?= Html::encode($this->title) ?></h4>

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
            'name',
            'key_statement',
            'answer:ntext',
            'recomend_users',
        ],
    ]) ?>

</div>