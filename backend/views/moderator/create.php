<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Statement */

$this->title = 'Create Statement';
$this->params['breadcrumbs'][] = ['label' => 'Statements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="statement-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
