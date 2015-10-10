<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use app\models\Assessment;
use app\models\Wheel;
use app\models\WheelQuestion;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = Yii::t('report', 'Report');
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/team']];
$this->params['breadcrumbs'][] = ['label' => $assessment->team->fullname, 'url' => ['/team/view', 'id' => $assessment->team->id]];
$this->params['breadcrumbs'][] = ['label' => $assessment->fullname, 'url' => ['/assessment/view', 'id' => $assessment->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row col-md-6">
        <?= Yii::t('user', 'Coach') ?>: <?= Html::label($assessment->team->coach->fullname) ?><br />
        <?= Yii::t('team', 'Company') ?>: <?= Html::label($assessment->team->company->name) ?><br />
        <?= Yii::t('team', 'Team') ?>: <?= Html::label($assessment->team->name) ?><br />
        <?= Yii::t('team', 'Sponsor') ?>: <?= Html::label($assessment->team->sponsor->fullname) ?><br />
        <?= Yii::t('assessment', 'Assessment') ?>: <?= Html::label($assessment->fullname) ?>
    </div>
    <div class="col-md-6 text-right">
        <?= Html::a(\Yii::t('app', 'Printable version'), Url::to(['report/download', 'id' => $assessment->id]), ['class' => 'btn btn-default']) ?>
    </div>
    <div class="clearfix"></div>
    <h3>
        <?= Yii::t('report', 'Introduction'); ?>
        <?= Html::a(\Yii::t('app', 'Edit'), Url::to(['report/introduction', 'id' => $assessment->id]), ['class' => 'btn btn-default']) ?>
    </h3>
    <p>
        <?= $assessment->report->introduction ?>
    </p>
    <h3>
        <?= Yii::t('report', 'Efectiveness Matrix'); ?>
        <?= Html::a(\Yii::t('app', 'Edit'), Url::to(['report/effectiveness', 'id' => $assessment->id]), ['class' => 'btn btn-default']) ?>
    </h3>
    <p>
        <?= $assessment->report->effectiveness ?>
    </p>
    <h3>
        <?= Yii::t('report', 'Performance Matrix'); ?>
        <?= Html::a(\Yii::t('app', 'Edit'), Url::to(['report/performance', 'id' => $assessment->id]), ['class' => 'btn btn-default']) ?>
    </h3>
    <p>
        <?= $assessment->report->performance ?>
    </p>
    <h3>
        <?= Yii::t('report', 'Competences Matrix'); ?>
        <?= Html::a(\Yii::t('app', 'Edit'), Url::to(['report/competences', 'id' => $assessment->id]), ['class' => 'btn btn-default']) ?>
    </h3>
    <p>
        <?= $assessment->report->competences ?>
    </p>
    <h3>
        <?= Yii::t('report', 'Emergents Matrix'); ?>
        <?= Html::a(\Yii::t('app', 'Edit'), Url::to(['report/emergents', 'id' => $assessment->id]), ['class' => 'btn btn-default']) ?>
    </h3>
    <p> 
        <?= $assessment->report->emergents ?>
    </p>
    <?php foreach ($assessment->report->individualReports as $individualReport) { ?>
        <div class="clearfix"></div>
        <h1>
            <?= $individualReport->member->fullname ?>
        </h1>
        <div class="col-md-push-1 col-md-11">
            <h3>
                <?= Yii::t('report', 'Perception Matrix'); ?>
                <?= Html::a(\Yii::t('app', 'Edit'), Url::to(['report/perception', 'id' => $individualReport->id]), ['class' => 'btn btn-default']) ?>
            </h3>
            <p>
                <?= $individualReport->perception ?>
            </p>
            <h3>
                <?= Yii::t('report', 'Relations Matrix'); ?>
                <?= Html::a(\Yii::t('app', 'Edit'), Url::to(['report/relations', 'id' => $individualReport->id]), ['class' => 'btn btn-default']) ?>
            </h3>
            <p>
                <?= $individualReport->relations ?>
            </p>
            <h3>
                <?= Yii::t('report', 'Competence Matrix'); ?>
                <?= Html::a(\Yii::t('app', 'Edit'), Url::to(['report/individual-competences', 'id' => $individualReport->id]), ['class' => 'btn btn-default']) ?>
            </h3>
            <p>
                <?= $individualReport->competences ?>
            </p>
            <h3>
                <?= Yii::t('report', 'Emergent Matrix'); ?>
                <?= Html::a(\Yii::t('app', 'Edit'), Url::to(['report/individual-emergents', 'id' => $individualReport->id]), ['class' => 'btn btn-default']) ?>
            </h3>
            <p>
                <?= $individualReport->emergents ?>
            </p>
        </div>
    <?php } ?>
    <div class="col-md-12">
        <h3>
            <?= Yii::t('report', 'Summary'); ?>
            <?= Html::a(\Yii::t('app', 'Edit'), Url::to(['report/summary', 'id' => $assessment->id]), ['class' => 'btn btn-default']) ?>
        </h3>
        <p>
            <?= $assessment->report->summary ?>
        </p>
        <h3>
            <?= Yii::t('report', 'Action Plan'); ?>
            <?= Html::a(\Yii::t('app', 'Edit'), Url::to(['report/action-plan', 'id' => $assessment->id]), ['class' => 'btn btn-default']) ?>
        </h3>
        <p>
            <?= $assessment->report->action_plan ?>
        </p>
    </div>
</div>
