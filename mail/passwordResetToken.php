<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */


$resetLinks = [];
foreach ($users as $user) {
    $resetLinks[$user->username] = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
}
?>
<div class="password-reset">
    <p><?= \Yii::t('app', 'Hello from VACH') ?>,</p>

    <p><?= \Yii::t('app', 'Follow the links below to reset your passwords:') ?></p>

    <ul>
        <?php foreach ($resetLinks as $username => $link) { ?>
            <li><?= $username ?>: <?= Html::a(Html::encode($link), $link) ?></li>
        <?php } ?>
    </ul>
</div>
<?= $this->render('_footer') ?>