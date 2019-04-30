<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */
/* @var $browser */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use kartik\widgets\ActiveForm;

$this->title = 'Контакт - центр';
?>
<?php
// $this is the view object currently being used
echo Breadcrumbs::widget([
    'itemTemplate' => '<li><i class="fa fa-home" aria-hidden="true"></i></li>', // template for all links
    'links' => ['Авторизация'],
]);
?>

<div class="site-login">
    <div class="row">
        <div class="col-md-offset-4 col-md-4">
            <h2 class="text-center">Авторизация</h2>

            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?php if($browser == 'Mozila Firefox'): ?>

                <?= $form->field($model, 'username', [
                    'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]
                ])
                    ->textInput(['autofocus' => true, 'placeholder' => 'Логин'])
                    ->label(false); ?>

                <?= $form->field($model, 'password', [
                    'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-lock"></i>']]
                ])
                    ->passwordInput(['placeholder' => 'Пароль'])
                    ->label(false); ?>

                <div class="form-group">

                        <?= Html::submitButton('Авторизация', [
                            'class' => 'btn btn-tfoms',
                            'name' => 'login-button',
                            'style' => 'width:100%;'
                        ]) ?>
                </div>
                    <?php else: ?>

                    <?= $form->field($model, 'username', [
                        'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-user"></i>']]
                    ])
                        ->textInput(['placeholder' => 'Логин', 'readonly' => true])
                        ->label(false); ?>

                    <?= $form->field($model, 'password', [
                        'addon' => ['prepend' => ['content'=>'<i class="glyphicon glyphicon-lock"></i>']],
                    ])
                        ->passwordInput(['placeholder' => 'Пароль', 'readonly' => true])
                        ->label(false); ?>

                    <div class="form-group">
                        <?= Html::button('Авторизация', [
                            'class' => 'btn btn-tfoms',
                            'disabled' => 'disabled',
                            'name' => 'login-button',
                            'style' => 'width:100%;'
                        ]) ?>
                    </div>

                    <?php endif;?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4" style="padding-top: 180px;">
            <blockquote class="blockquote" style="width: 600px; font-style: italic; font-size: 20px;">
                <p class="mb-0">Заявления об управлении доступом к ПК «КИСЕРО»</p>
                <footer class="blockquote-footer"><a class="link-save-file" href="https://tfomssk.ru/files/kisero/zayavlenie_KISERO.doc"> Заявление о получении идентификационных данных </a></footer>
                <footer class="blockquote-footer"><a class="link-save-file" href="https://tfomssk.ru/files/kisero/Zayavlenie_na_blokirovanie_KISERO.doc"> Заявление о блокировании идентификационных данных </a></footer>
                <p class="mb-0" style="margin-top: 10px;">
                    <a style="color: #666666; text-decoration: none;" href="/img/Свидетельство o государственной регистрации.jpg">Свидетельство o государственной регистрации</a>
                </p>
            </blockquote>
        </div>
        <div class="col-md-offset-2 col-md-4">
            <?= Html::img('@web/img/КОНТАКТ-ЦЕНТР3.jpg', [
                'alt'=> "Контакт-центр",
                'width' => '570px',
                'style' => 'padding-top:30px;'
            ])?>
        </div>
    </div>
    <?php if($browser != 'Mozila Firefox'):?>
    <div class="row">
        <div class="col-md-12 text-center">
            <hr>
            <p style="font-size: 16px">
                Для работы с контакт-центром необходимо пользоваться браузером  «Firefox»
                <?= Html::img('@web/img/firefox-logo.png', [
                    'width' => '40px',
                    'height' => '40px'
                ])?><br>
                Скачайте и установите браузер <?= Html::a('Mozila Firefox', 'https://www.mozilla.org/ru/firefox/new/')?><br>
                Сейчас вы пользуетесь браузером: <span style="border-bottom: dashed 1px"><?= $browser; ?></span></p>
            </p>
        </div>
    </div>
    <?php endif;?>
</div>
