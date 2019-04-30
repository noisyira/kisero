<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
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

<div class="wrap">
<div>
    <?php
    NavBar::begin([
        'brandLabel' => 'Контакт - центр',
        'brandUrl' => Yii::$app->homeUrl,
        'innerContainerOptions' => ['class'=>'container-fluid'],
        'options' => [
            'class' => 'navbar navbar-default',
        ],
    ]);
    $menuItems = [
    ];

    $menuAction = [
        ['label' => 'Главная', 'url' => ['/moderator/index']],
     //   ['label' => 'Опрос', 'url' => ['/moderator/poll']],
        [
            'label' => 'Опросы',
            'items' => [
//                ['label' => '<span class="glyphicon glyphicon-phone-alt"></span>  Диспансеризация', 'url' => 'clinical-examination'],
//                '<li class="divider"></li>',
                ['label' => '<span class="glyphicon glyphicon-earphone"></span>  Опрос застрахованных', 'url' => 'poll'],
            ],
        ],

        [
            'label' => 'ССТУ',
            'items' => [
                ['label' => '<span class="glyphicon glyphicon-apple"></span>  Архив ССТУ', 'url' => '/moderator/sstu'],
                ['label' => '<span class="glyphicon glyphicon-list-alt"></span>  Отчет ССТУ', 'url' => '/moderator/sstu-report'],
            ],
        ],

    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/moderator/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                '<i class="fa fa-power-off"></i> Завершить',
                ['class' => 'btn btn-link']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'encodeLabels' => false,
        'items' => $menuAction,
    ]);
    NavBar::end();
    ?>
</div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
            <div class="col-md-2">
                <?= $this->render('//layouts/page/right_sidebar') ?>
            </div>
        </div>

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
