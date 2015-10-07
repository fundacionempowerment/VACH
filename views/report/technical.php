<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\WheelAnswer;
use yii\bootstrap\Button;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */

$this->title = Yii::t('report', 'Technical Report');
?>
<script>
    function toggle(id)
    {
        element = document.getElementById('view-' + id);
        if (element.style.display === 'none')
            element.style.display = 'inline';
        else
            element.style.display = 'none';

        element = document.getElementById('edit-' + id);
        if (element.style.display === 'none')
            element.style.display = 'inline';
        else
            element.style.display = 'none';
    }
</script>
<div class="report-technical">
    <div class="col-lg-12 text-center">
        <?= Html::img('@web/images/logo.png', ['class' => 'image-responsive']) ?>
        <h1>
            <?= Yii::t('report', 'Technical Report') ?>
        </h1>
    </div>
    <div class="col-lg-6">
        <h2>
            <?= Yii::t('team', 'Company') ?>: <?= Html::label($assessment->team->company->name) ?>
        </h2>
        <h2>
            <?= Yii::t('team', 'Team') ?>: <?= Html::label($assessment->team->name) ?>
        </h2>
        <h2>
            <?= Yii::t('user', 'Coach') ?>: <?= Html::label($assessment->team->coach->fullname) ?>
        </h2>
        <h2>
            <?= Yii::t('team', 'Sponsor') ?>: <?= Html::label($assessment->team->sponsor->fullname) ?>
        </h2>
    </div>
    <div class="col-lg-6">
        <h2>
            <?= Yii::t('team', 'Members') ?>:
        </h2>
        <?php foreach ($assessment->team->members as $teamMember) { ?>
            <h3>
                <?= Html::label($teamMember->member->fullname) ?>
            </h3>
        <?php } ?>
    </div>
    <div class="col-lg-12">
        <h2>
            <?= Yii::t('report', 'Index') ?>
        </h2>
        <ol>
            <li><?= Yii::t('report', 'Introduction') ?></li>
            <li><?= Yii::t('report', 'Method fundaments') ?></li>
            <li><?= Yii::t('report', 'Group and organizational performance') ?></li>
            <li><?= Yii::t('report', 'Individual perferformance') ?></li>
            <li><?= Yii::t('report', 'Assessment summary') ?></li>
            <li><?= Yii::t('report', 'Action plan') ?></li>
        </ol>
    </div>
    <div class="col-lg-12">
        <h2>
            <?= Yii::t('report', 'Introduction') ?>
        </h2>
        <div id="view-introduction">
            <?= $assessment->report->introduction ?>
            <br />
            <?=
            Button::widget(['label' => Yii::t('app', 'Edit'),
                'options' => [
                    'class' => 'btn btn-default',
                    'onclick' => 'toggle(\'introduction\')']])
            ?>
        </div>        
        <div id="edit-introduction" style="display: none;">
            <?php $form = ActiveForm::begin([ 'id' => 'introduction-form',]); ?>
            <?= Html::textarea('introduction', $assessment->report->introduction, ['class' => 'col-lg-12']) ?>
            <?= Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-12">
        <?= $this->render('_fundaments', []) ?>
    </div>
    <div class="clearfix"></div>
    <div class="col-lg-12">
        <?= Html::a(Yii::t('app', 'Home'), ['/site'], ['class' => 'btn btn-default']) ?>
    </div>
</div>