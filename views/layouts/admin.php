<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\widgets\Alert;
use app\components\Icons;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);

$isAdministrator = false;
if (Yii::$app->user->identity) {
    $isAdministrator = Yii::$app->user->identity->is_administrator;
}

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <link rel="icon" type="image/x-icon" href="<?= Url::to('@web/favicon.png') ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <div class="wrap">
            <?php
            NavBar::begin([
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            $logo = Html::img('@web/images/logo.png', ['alt' => 'logo',
                        'class' => 'image-responsive', 'height' => '35px', 'style' => 'margin-top: 6px',]);
            echo Html::a($logo, ['/site/index']);

            echo $this->render('_menu');
            NavBar::end();
            ?>

            <div class="container">
                <?=
                Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ])
                ?>
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>
        <footer class="footer">
            <?= $this->render('_footer') ?>
        </footer>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
