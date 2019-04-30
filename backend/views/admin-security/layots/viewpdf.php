<?php 
    use yii\helpers\Html;
?>
<div class="row">
    <div class="col-md-3">
        Пользователь: <span style="font-weight: bold;"><?= $user->username; ?></span> <br>
        Пароль:       <span style="font-weight: bold;"><?= $user->secret; ?></span>   <br>
        Фамилия:      <span style="font-weight: bold;"><?= $user->fam; ?></span>  <br>
        Имя:          <span style="font-weight: bold;"><?= $user->im; ?></span> <br>
        Отчество:     <span style="font-weight: bold;"><?= $user->ot; ?></span>  <br>
    </div>
</div>

<hr>

<?php if($user->user->sip_outer != 0):?>
<div class="row">
    <div class="col-md-12">
        <p>Доступк к контакт-центру</p>
        <p>
          1.  Откройте «ViPNet Client» и найдите координатор «СкТФОМС».
        </p>

        <p style="text-align: center">
            <?= Html::img('@web/img/coordinator.png')?>
        </p>

        <p>
            2.  Перейдите на вкладку «Тунель» и установитье флажек «Использовать виртуальные IP-адреса»
        </p>

        <p style="text-align: center">
            <?= Html::img('@web/img/setting-tunel.png')?>
        </p>

        <p>
            3.  Если адресу 192.168.1.47 не присвоен виртуальный адрес, тогда добавьте его вручную «Добавить...»
        </p>

        <p>
            4.  Доступ к контакт-центру будет доступен по виртуальному адресу. Введите его в адресной строке вашего браузера.
        </p>

    </div>
</div>
<?php else: ?>
<div class="row">
    <div class="col-md-12">
        <p>Доступк к контакт-центру</p>
        <p>В адресной строке браузера «Firefox» введите — <strong>192.168.1.47</strong></p>
    </div>
</div>
<?php endif;?>
