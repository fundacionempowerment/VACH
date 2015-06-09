<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = $coachee->id == 0 ? Yii::t('user', 'New coachee') : $coachee->fullname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'My Coachees'), 'url' => ['/coachee']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('user', 'Please fill out the following fields with coachee data:') ?></p>

    <?php
    $form = ActiveForm::begin([
                'id' => 'newcoachee-form',
    ]);
    ?>

    <?= $form->field($coachee, 'name') ?>
    <?= $form->field($coachee, 'surname') ?>
    <?= $form->field($coachee, 'email') ?>
    <div class="form-group">
        <?= Html::submitButton(\Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>
        <?=
        $coachee->id == 0 ?
                Html::a(\Yii::t('app', 'Cancel'), ['/coachee'], ['class' => 'btn', 'name' => 'cancel-button']) :
                Html::a(\Yii::t('app', 'Cancel'), ['/coachee/view', 'id' => $coachee->id], ['class' => 'btn', 'name' => 'cancel-button'])
        ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
