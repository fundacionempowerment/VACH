<?php

use app\models\WheelQuestion;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\TeamType */

$this->title = ($teamType->id == 0 ? Yii::t('team', 'New team type') : Yii::t('app', 'Edit') . ' ' . $teamType->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Team Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $teamType->name, 'url' => ['view', 'id' => $teamType->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-wheel">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['id' => 'questions-form']); ?>
    <h3><?= $teamType->getAttributeLabel('wheelQuestions') ?></h3>
    <p>
        <label>Textos especiales</label><br>
        <b>[observed]</b> miembro al que se está "evaluando".<br>
        <b>[team]</b> nombre del equipo.<br>
        <b>[company]</b> nombre de la empresa.<br>
    </p>
    <div class="row col-xs-12">
        <?php
        $current_dimension = -1;
        $div_open = false;

        foreach ($teamType->wheelQuestions as $question) {
            if (!$teamType->isLevelEnabled($question->type)) {
                continue;
            }

            if ($current_dimension != $question->dimension) {
                $current_dimension = $question->dimension;

                if ($div_open == true) {
                    echo '</div>';
                    $div_open = false;
                }

                echo '<div class="box"><h3>' . $question->teamTypeDimension->name . '</h3>';
                $div_open = true;
            }
            ?>
            <p>
                <?= Html::input('text', "q-$question->type-$question->dimension-$question->order", $question->question->text, ['class' => 'col-xs-12']) ?>
            </p>
        <?php } ?>
        <?= '</div>' ?>
    </div>
    <p>
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']); ?>
    </p>
    <?php ActiveForm::end(); ?>
</div>
