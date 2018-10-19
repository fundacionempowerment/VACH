<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $password string */
?>
    <div class="password-reset">
        <p><?= \Yii::t('app', 'Welcome to VACH') ?>,</p>
        <p>
            <?= Yii::t('user', 'Your username is') ?>: <strong><?= $user->username ?></strong>
        </p>
        <p>
            <?= \Yii::t('app', 'Your password is') ?>: <strong><?= $password ?></strong>
        </p>
    </div>
<?= $this->render('_footer') ?>