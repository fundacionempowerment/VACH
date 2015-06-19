<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;

$type_text = Wheel::getWheelTypes()[$type];

$url = Url::toRoute(['wheel/run', 'token' => $token], true);
?>
<p>
    <?= Yii::t('wheel', "Please, click next link to access to your $type_text: ") ?>
</p>
<p>
    <?= Html::a($url, $url) ?>
</p>
<p>
    <?= Yii::t('app', 'Thanks!') ?>
</p>