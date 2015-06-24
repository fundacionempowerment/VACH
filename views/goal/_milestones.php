<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use kartik\widgets\DatePicker;
use app\models\GoalMilestone;
?>
<div class="new-goal">
    <?php foreach ($goal->milestones as $milestone): ?>
        <div class="row col-md-12">
            <?php
            switch ($milestone->type) {
                case GoalMilestone::TYPE_LOW: $tag = 'h4';
                    break;
                case GoalMilestone::TYPE_MEDIUM: $tag = 'h4';
                    break;
                case GoalMilestone::TYPE_HIGH: $tag = 'h3';
                    break;
            }
            echo Html::tag($tag, $milestone->description . '     ' . Html::a(Yii::t('app', 'Edit'), Url::to(['/goal/edit-milestone', 'id' => $milestone->id]), ['class' => 'btn btn-default']))
            ;
            ?>
            <p>
                <label><?= Yii::t('app', 'Date') ?>:</label> <?= $milestone->date ?>
            </p>
            <p>
                <label><?= Yii::t('goal', 'Evidence') ?>:</label> <?= $milestone->evidence ?>
            </p>

            <p>
                <label><?= Yii::t('goal', 'Celebration') ?>:</label> <?= $milestone->celebration ?>
            </p>
        </div>
    <?php endforeach; ?>
</div>