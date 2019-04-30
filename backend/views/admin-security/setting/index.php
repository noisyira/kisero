<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SipSettingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sip Settings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sip-setting-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Sip Setting', ['create'], ['class' => 'btn btn-tfoms-green']) ?>
    </p>

</div>