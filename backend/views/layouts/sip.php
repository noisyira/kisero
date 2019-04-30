<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\SipAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

SipAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" ng-app="operatorFirstLevel">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <div class="navbar navbar-default" role="navigation" style="min-height: 60px;">
        <div class="container-fluid" style="display: flex; align-items: center;">
            <div class="col-md-2 col-sm-3 text-left">
                <div>
                    <a class="navbar-brand" href="#/" style="padding: 0;">
                        <?= Html::img('@web/img/Logo_Horizontal_color.gif', [
                            'alt'=>Yii::$app->name,
                           ])?>
                    </a>
                </div>

            </div>
            <div class="col-md-9 col-sm-6 text-right" style="padding: 5px;">
                <p>
                    <i class="fa fa-fw fa-user"></i>
                    <?= Yii::$app->user->identity->fam
                    .' '. Yii::$app->user->identity->im
                    .' '. Yii::$app->user->identity->ot;?>
                    </br>
                    <span class="label label-tfoms-green">
                        <?= key(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id));?>
                    </span>
                    &nbsp;
                    <span class="label label-tfoms-orange">
                        <?= \backend\models\SipAccount::getNumber(Yii::$app->user->id)->sip_private_identity?>
                    </span>
                </p>
           </div>
            <div class="col-md-1 col-sm-4 text-right">
                <?= Html::a('<i class="fa fa-power-off"></i> Выход', ['/site/logout'], ['data-method' => 'post', 'class' => 'exit'])?>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer-s" style="margin: 0 30px;">
    <div class="col-md-12 text-center" style="border-top:1px solid darkgray; padding: 20px; 0">
        <span>ТФОМС СК «Контакт-центр»  <?= date('Y'); ?></span>
        <br/>
        Телефон: 8(8652)94-20-60
        <br/>
        Электропочта: <?= Html::mailto('support@tfomssk.ru', 'support@tfomssk.ru')?>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>