<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <link rel="icon" type="image/x-icon" href="../favicon.png" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>

        <?php $this->beginBody() ?>
        <div class="wrap">
            <div class="container">
                <?= $content ?>
            </div>
        </div>

        <footer class="footer">
            <div class="container">
                <p class="pull-left">
                    <?= Html::a('Fundación Empowerment', 'http://www.fundacionempowerment.org/') ?>
                    &nbsp;
                    <?= Html::a('Español', ['site/es']) ?>
                    &nbsp;
                    <?= Html::a('English', ['site/en']) ?>
                </p>
                <p class="pull-right">
                    <?= Yii::t('app', 'Powered by') ?>
                    <?= Html::a('Yii Framework', 'http://www.yiiframework.com/', ['rel' => 'external', 'target' => '_blank']) ?>
                </p>
            </div>
        </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
