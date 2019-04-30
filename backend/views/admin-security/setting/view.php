<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\SipSetting */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Sip Settings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sip-setting-view">

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
            'sip_websocket_proxy_url:url',
            'sip_outbound_proxy_url:url',
            'sip_ice_servers',
            'sip_disable_video',
        ],
    ]) ?>

</div>