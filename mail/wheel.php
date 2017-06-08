<?php

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
            echo 'Estimado ' . $wheel->observer->name . ',';
            break;
        case app\models\Person::GENDER_FEMALE:
            echo 'Estimada ' . $wheel->observer->name . ',';
            break;
        default :
            echo 'Estimado/a ' . $wheel->observer->name . ',';
            break;
    }
    ?>
</p>
<p>
    <?=
    Yii::t('wheel', "Please, click next button to confirm reception of this email:")
    ?>
</p>
<p style="margin: 30px">
    <?= Html::a(Yii::t('wheel', 'Confirm reception'), $reception_url, ['style' => '
        background-color: #5cb85c;
        border-color: #4cae4c;
        color: #fff;
        text-decoration: none;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
        display: inline-block;
        font-size: 14px;
        font-weight: normal;
        line-height: 1.42857;
        margin-bottom: 0;
        padding: 6px 12px;
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
        box-sizing: border-box;']) ?>
</p>
<p>
    <?=
    Yii::t('wheel', "Please, click next button to run the {wheel} of team {team}:", [
        'wheel' => $type_text,
        'team' => $wheel->team->fullname,
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
    echo Html::a($button_text, $wheel_url, ['style' => '
        background-color: #337ab7;
        border-color: #2e6da4;
        color: #fff;
        text-decoration: none;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
        display: inline-block;
        font-size: 14px;
        font-weight: normal;
        line-height: 1.42857;
        margin-bottom: 0;
        padding: 6px 12px;
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
        box-sizing: border-box;']);
    ?>
</p>
<?= $this->render('_footer') ?>