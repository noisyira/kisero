<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\UserSipAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

UserSipAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" ng-app="dAdmin">
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
            <div class="col-md-6">
                <!--Отчёты-->
                <?= Nav::widget([
                    'items' => [
                        [
                            'label' => '<span style="font-size: 12px" class="glyphicon glyphicon-home"></span> Главная',
                            'url' => '#/',
                            'linkOptions' =>  ['style' => 'color: #777;'],
                        ],
                        [
                            'label' => '<span style="font-size: 12px" class="glyphicon glyphicon-tasks"></span> Отчёты',
                            'url' => '#/report',
                            'linkOptions' =>  ['style' => 'color: #777;'],
                            'visible' => \backend\models\Login::getTypeUser(Yii::$app->user->id) == 2 ? true : false
                        ],
                        [
                            'label' => '<span style="font-size: 12px" class="glyphicon glyphicon-share"></span> Мониторинг',
                            'url' => '#/monitoring',
                            'linkOptions' =>  ['style' => 'color: #777;'],
                            'visible' => \backend\models\Login::getTypeUser(Yii::$app->user->id) == 2 ? true : false
                        ],
                        [
                            'label' => '<span style="font-size: 12px" class="glyphicon glyphicon-phone"></span> Сообщения',
                            'url' => '#/archiveMessages',
                            'linkOptions' =>  ['style' => 'color: #777;'],
                           // 'visible' => \backend\models\Login::getTypeUser(Yii::$app->user->id) == 2 ? true : false
                        ],
//                        [
//                            'label' => '<span style="font-size: 12px" class="glyphicon glyphicon-list-alt"></span> Журнал опроса',
//                            'linkOptions' =>  ['style' => 'color: #777;'],
//                            'url' => '#',
//                            'items' => [
//                                [
//                                    'label' => 'Диспансеризация',
//                                    'url' => '#/dial',
//                                ],
//                                [
//                                    'label' => 'Опрос застрахованных',
//                                    'url' => '#/poll',
//                                ],
//                            ],
//                        ],
                    ],
                    'encodeLabels' => false,
                    'options' => ['class' =>'navbar-nav'], // set this to nav-tab to get tab-styled navigation
                ]);
                ?>
                <ul class="nav navbar-nav">
                    <li class="dropdown" dropdown on-toggle="toggled(open)">
                        <a href class="dropdown-toggle" dropdown-toggle>
                            <span style="font-size: 12px" class="glyphicon glyphicon-list"></span> Опросы
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#/dial"> Диспансеризация </a></li>
                            <li><a href="#/dsp"> Диспансерный учет </a></li>
                            <?php if( \backend\models\Login::getTypeUser(Yii::$app->user->id) == 2 ): ?>
                                <li><a href="#/interaction"> Загрузка файлов </a></li>
                                <li><a href="#/dial-report"> Статистика </a></li>
                            <?php endif; ?>
                            <li role="separator" class="divider"></li>
                            <li><a href="#/poll"> Опрос застрахованных </a></li>
                        </ul>
                    </li>
                    <?php if( \backend\models\Login::companyID(Yii::$app->user->id) != 1 ): ?>
                        <li class="dropdown" dropdown on-toggle="toggled(open)">
                            <a href class="dropdown-toggle" dropdown-toggle>
                                <span style="font-size: 12px" class="glyphicon glyphicon-plus"></span> ЕИР263
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#/eir263">Информирование</a></li>
                                <?php if( \backend\models\Login::getTypeUser(Yii::$app->user->id) == 2 ): ?>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#/eir263/report">Отчёт</a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="col-md-4 col-sm-6 text-right" style="padding: 5px;">
                <p>
                    <i class="fa fa-fw fa-user"></i>
                    <?= Yii::$app->user->identity->fam
                    .' '. Yii::$app->user->identity->im
                    .' '. Yii::$app->user->identity->ot;?>
                    </br>
                    <span class="label label-tfoms-green">
                        <?= key(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id));?>
                    </span>
                    <span class="label label-tfoms-orange">
                        <?= \backend\models\SipAccount::getNumber(Yii::$app->user->id)->sip_private_identity?>
                    </span>
                </p>
           </div>
            <div class="col-md-2 col-sm-4 text-right">
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
        &copy; <span>ТФОМС СК, ПК «КИСЕРО»,  2016-<?= date('Y'); ?></span>
        <br/>
        Телефон: 8(8652)94-20-60
        <br/>
        E-mail: <?= Html::mailto('support@tfomssk.ru', 'support@tfomssk.ru')?>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>