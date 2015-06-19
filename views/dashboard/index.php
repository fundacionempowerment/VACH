<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\bootstrap\ActiveForm;
use app\models\Wheel;

/* @var $this yii\web\View */
$this->title = Yii::t('dashboard', 'Dashboard');

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dashboard">
    <h1><?= Html::encode($this->title) ?></h1>
    <div>
        <?php $form = ActiveForm::begin(['id' => 'dashboard-form']); ?>
        <div>
            <label><?= Yii::t('company', 'Companies') ?></label>
            <?= Html::dropDownList('companyId', $companyId, $companies, ['onchange' => 'this.form.submit()']) ?>
            <label><?= Yii::t('team', 'Teams') ?></label>
            <?= Html::dropDownList('teamId', $teamId, $teams, ['onchange' => 'this.form.submit()']) ?>
            <label><?= Yii::t('assessment', 'Assessments') ?></label>
            <?= Html::dropDownList('assessmentId', $assessmentId, $assessments, ['onchange' => 'this.form.submit()']) ?>
            <label><?= Yii::t('team', 'Members') ?></label>
            <?= Html::dropDownList('memberId', $memberId, $members, ['onchange' => 'this.form.submit()']) ?>
            <label><?= Yii::t('wheel', 'Wheels') ?></label>
            <?= Html::dropDownList('wheelType', $wheelType, Wheel::getWheelTypes(), ['onchange' => 'this.form.submit()']) ?>
        </div>
        <?php ActiveForm::end(); ?>
        <?=
        $wheelType == Wheel::TYPE_INDIVIDUAL && $assessmentId > 0 && $memberId > 0 ?
                $this->render('_individual', [
                    'projectedIndividualWheel' => $projectedIndividualWheel,
                    'projectedGroupWheel' => $projectedGroupWheel,
                    'projectedOrganizationalWheel' => $projectedOrganizationalWheel,
                    'reflectedGroupWheel' => $reflectedGroupWheel,
                    'reflectedOrganizationalWheel' => $reflectedOrganizationalWheel,
                ]) : ''
        ?>
    </div>
</div>
