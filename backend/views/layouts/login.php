<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap" style=" min-height: 100%; height: auto; margin: 0 auto -100px; padding: 0 0 100px;">

    <div class="container" style="padding: 10px 0 0 0;">
        <div class="col-md-3">
            <?= Html::img('@web/img/tfoms_logo_120.gif', [
            'alt'=>Yii::$app->name,
            'height' => '100px' ])?>
        </div>
        <div class="col-md-9" style="text-align: center;">
            <?= Html::img('@web/img/КИСЕРО1.jpg', [
                'alt'=> "Контакт-центр",
                //'width' => '550px',
                'style' => 'padding-top:25px;'
                 ])?>
        </div>
    </div>

    <div class="container" style="padding: 15px 0">
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
    
</div>

<footer class="footer-s">
        <div class="col-md-2"></div>
        <div class="col-md-8 text-center" style="border-top:1px solid darkgray; padding-top: 20px;">
            &copy; <span>ТФОМС СК, ПК «КИСЕРО»,  2016-<?= date('Y'); ?></span>
            <br/>
            Телефон: 8(8652)94-20-60
            <br/>
            Электронная почта: <?= Html::mailto('support@tfomssk.ru', 'support@tfomssk.ru')?>
        </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
