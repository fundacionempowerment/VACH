<?php

use yii\helpers\Html;
use yii\helpers\Url;
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
        <link rel="icon" type="image/x-icon" href="<?= Url::to('@web/favicon.png') ?>" />
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
            <?= $this->render('_footer') ?>            
        </footer>

        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
