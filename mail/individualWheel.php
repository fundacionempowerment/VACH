<?php

use app\components\Downloader;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;

$type_text = Wheel::getWheelTypes()[$wheel->type];

$wheel_url = Url::toRoute(['wheel/run', 'token' => $wheel->token], true);
$reception_url = Url::toRoute(['wheel/received', 'token' => $wheel->token], true);


?>
<style>
    body {
        color: #333;
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
        font-size: 14px;
        line-height: 1.42857;
    }
</style>
<p>
    <?php
    switch ($wheel->observer->gender) {
        case app\models\Person::GENDER_MALE:
            echo 'Estimado ' . $wheel->observed->name . ',';
            break;
        case app\models\Person::GENDER_FEMALE:
            echo 'Estimada ' . $wheel->observed->name . ',';
            break;
        default :
            echo 'Estimado/a ' . $wheel->observed->name . ',';
            break;
    }
    ?>
</p>
<p>
    <?=
    Yii::t('wheel', "Le enviamos su rueda individual.")
    ?>
</p>
<img src="<?= $message->embed($radarPath); ?>">
<?= $this->render('_footer') ?>