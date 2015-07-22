<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;

$type_text = Wheel::getWheelTypes()[$wheel->type];

$url = Url::toRoute(['wheel/run', 'token' => $wheel->token], true);
?>
<p>
    <?=
    Yii::t('wheel', "Dear {name},", [
        'name' => $wheel->observer->name
    ])
    ?>
</p>
<p>
    <?=
    Yii::t('wheel', "Please, click next link to run the {wheel} of assessment {assessment}", [
        'wheel' => $type_text,
        'assessment' => $wheel->assessment->name,
    ])
    ?>
</p>
<p>
    <?= Html::a($url, $url) ?>
</p>
<p>
    <?= Yii::t('wheel', 'Thank you very much!') ?>
</p>
<p>
    <b>
        <?= Yii::t('app', 'Empowerment Foundation') ?>
    </b>
</p>