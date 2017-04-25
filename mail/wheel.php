<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;

$type_text = Wheel::getWheelTypes()[$wheel->type];

$wheel_url = Url::toRoute(['wheel/run', 'token' => $wheel->token], true);
$reception_url = Url::toRoute(['wheel/received', 'token' => $wheel->token], true);
?>
<style>
    *::before, *::after {
        box-sizing: border-box;
    }
    *::before, *::after {
        box-sizing: border-box;
    }
    .btn-success {
        background-color: #5cb85c;
        border-color: #4cae4c;
        color: #fff;
    }
    .btn-primary {
        background-color: #337ab7;
        border-color: #2e6da4;
        color: #fff;
    }
    .btn {
        -moz-user-select: none;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
        cursor: pointer;
        display: inline-block;
        font-size: 14px;
        font-weight: normal;
        line-height: 1.42857;
        margin-bottom: 0;
        padding: 6px 12px;
        text-align: center;
        touch-action: manipulation;
        vertical-align: middle;
        white-space: nowrap;
    }
    a {
        color: #337ab7;
        text-decoration: none;
        background-color: transparent;
    }
    * {
        box-sizing: border-box;
    }
    body {
        color: #333;
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
        font-size: 14px;
        line-height: 1.42857;
    }
</style>
<p>
    <?=
    Yii::t('wheel', "{gender,select,0{Dear} 1{Dear} other{Dear}} {name},", [
        'name' => $wheel->observer->name,
        'gender' => $wheel->observer->gender,
    ])
    ?>
</p>
<p>
    <?=
    Yii::t('wheel', "Please, click next button to confirm reception of this email:")
    ?>
</p>
<p style="margin: 30px">
    <?= Html::a(Yii::t('wheel', 'Confirm reception'), $reception_url, ['class' => 'btn btn-success']) ?>
</p>
<p>
    <?=
    Yii::t('wheel', "Please, click next button to run the {wheel} of assessment {assessment}:", [
        'wheel' => $type_text,
        'assessment' => $wheel->assessment->fullname,
    ])
    ?>
</p>
<p style="margin: 30px">
    <?php
    switch ($wheel->type) {
        case Wheel::TYPE_INDIVIDUAL:
            $button_text = Yii::t('wheel', 'Run individual wheel');
            break;
        case Wheel::TYPE_GROUP:
            $button_text = Yii::t('wheel', 'Run group wheel');
            break;
        case Wheel::TYPE_ORGANIZATIONAL:
            $button_text = Yii::t('wheel', 'Run organizational wheel');
            break;
    }
    echo Html::a($button_text, $wheel_url, ['class' => 'btn btn-primary']);
    ?>
</p>
<p>
    <?= Yii::t('wheel', 'Thank you very much!') ?>
</p>
<p>
    <b>VACH</b><br/>
    Team Integration Tool<br/><br/>
    <b><?= Yii::t('app', 'Empowerment Foundation') ?></b>
</p>