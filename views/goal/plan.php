<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use kartik\widgets\DatePicker;
use app\models\GoalMilestone;

$this->title = Yii::t('goal', 'Action plan');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Persons'), 'url' => ['/person']];
$this->params['breadcrumbs'][] = ['label' => $person->fullname, 'url' => ['/person/view', 'id' => $person->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="new-goal">
    <h1><?= Html::encode($this->title) ?>    <?= Yii::$app->request->get('printable') == null ? Html::a(Yii::t('app', 'Printable'), Url::to(['/goal/plan', 'person_id' => $person->id, 'printable' => 1]), ['class' => 'btn btn-default']) : '' ?></h1>
    
    <p>
        <?= Yii::t('user', 'Coach') ?>: <?= Html::label($person->coach->fullname) ?><br />
        <?= Yii::t('user', 'Person') ?>: <?= Html::label($person->fullname) ?><br />
    </p>
    <?php foreach ($milestones as $milestone): ?>
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
            echo Html::tag($tag, $milestone['description']);
            ?>
            <p>
                <label><?= Yii::t('app', 'Date') ?>:</label> <?= $milestone['date'] ?>
            </p>
            <p>
                <label><?= Yii::t('goal', 'Evidence') ?>:</label> <?= $milestone['evidence'] ?>
            </p>

            <p>
                <label><?= Yii::t('goal', 'Celebration') ?>:</label> <?= $milestone['celebration'] ?>
            </p>
        </div>
    <?php endforeach; ?>
</div>