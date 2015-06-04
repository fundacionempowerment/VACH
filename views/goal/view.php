<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use kartik\widgets\DatePicker;
use app\models\GoalMilestone;

$this->title = Yii::t('goal', 'Goal - ') . $goal->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Coachees'), 'url' => ['/coachee']];
$this->params['breadcrumbs'][] = ['label' => $goal->coachee->fullname, 'url' => ['/coachee/view', 'id' => $goal->coachee->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="new-goal">
    <h1><?= Html::encode($this->title) ?></h1>
     <p>
        <?= Yii::t('user', 'Coach') ?>: <?= Html::label($goal->coachee->coach->fullname) ?><br />
        <?= Yii::t('user', 'Coachee') ?>: <?= Html::label($goal->coachee->fullname) ?><br />
    </p>
    <h2><?= Yii::t('goal', 'Resources') ?></h2>
    <?=
    $this->render('_resources', [
        'goal' => $goal,
    ])
    ?>
    <?= Html::a(Yii::t('goal', 'Edit resources'), Url::to(['/goal/resources', 'id' => $goal->id]), ['class' => 'btn btn-primary']) ?>
    <h2><?= Yii::t('goal', 'Milestones') ?></h2>
    <?=
    $this->render('_milestones', [
        'goal' => $goal,
    ])
    ?>
    <br />
    <?= Html::a(Yii::t('goal', 'New milestone'), Url::to(['/goal/new-milestone', 'id' => $goal->id]), ['class' => 'btn btn-primary']) ?>
</div>