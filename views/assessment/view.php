<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use app\models\Assessment;
use app\models\Wheel;
use app\models\WheelQuestion;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$individualQuestionCount = count(WheelQuestion::getQuestions(Wheel::TYPE_INDIVIDUAL));
$groupQuestionCount = count(WheelQuestion::getQuestions(Wheel::TYPE_GROUP));
$organizationalQuestionCount = count(WheelQuestion::getQuestions(Wheel::TYPE_ORGANIZATIONAL));

$this->title = $assessment->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/team']];
$this->params['breadcrumbs'][] = ['label' => $assessment->team->fullname, 'url' => ['/team/view', 'id' => $assessment->team->id]];
$this->params['breadcrumbs'][] = $this->title;
$wheel_count = 0;

$mail_icon = '<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>';
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row col-md-6">
        <?= Yii::t('user', 'Coach') ?>: <?= Html::label($assessment->team->coach->fullname) ?>
    </div>
    <div class="clearfix"></div>
    <div class="row col-md-5">
        <h2><?= Yii::t('assessment', 'Individual wheels') ?></h2>
        <table class="table table-bordered table-striped">
            <?php foreach ($assessment->team->members as $observerMember) { ?>
                <tr>
                    <th style="text-align: right;">
                        <?= $observerMember->member->fullname ?>
                        <?= Html::a($mail_icon, Url::to(['assessment/send-wheel', 'id' => $assessment->id, 'memberId' => $observerMember->user_id, 'type' => Wheel::TYPE_INDIVIDUAL]), ['class' => 'btn btn-default btn-xs']) ?>
                    </th>
                    <td>
                        <?php
                        foreach ($assessment->individualWheels as $wheel)
                            if ($wheel->observer_id == $observerMember->user_id) {
                                echo $wheel->answerStatus;
                            }
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div class="clearfix"></div>
    <div class="row col-md-12">
        <h2><?= Yii::t('assessment', 'Group wheels') ?></h2>
        <table width="100%" class="table table-bordered">
            <tr>
                <th style="text-align: right;">
                    <?= Yii::t('wheel', "Observer \\ Observed") ?>
                </th>
                <?php foreach ($assessment->team->members as $teamMember): ?>
                    <th>
                        <?= $teamMember->member->fullname ?>
                    </th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($assessment->team->members as $observerMember) { ?>
                <tr>
                    <th style="text-align: right;">
                        <?= $observerMember->member->fullname ?>
                        <?= Html::a($mail_icon, Url::to(['assessment/send-wheel', 'id' => $assessment->id, 'memberId' => $observerMember->user_id, 'type' => Wheel::TYPE_GROUP]), ['class' => 'btn btn-default btn-xs']) ?>
                    </th>
                    <?php foreach ($assessment->team->members as $observedMember) { ?>
                        <td>
                            <?php
                            foreach ($assessment->groupWheels as $wheel)
                                if ($wheel->observer_id == $observerMember->user_id && $wheel->observed_id == $observedMember->user_id) {
                                    echo $wheel->answerStatus;
                                }
                            ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div class="clearfix"></div>
    <div class="row col-md-12">
        <h2><?= Yii::t('assessment', 'Organizational wheels') ?></h2>
        <table width="100%" class="table table-bordered">
            <tr>
                <th style="text-align: right;">
                    <?= Yii::t('wheel', "Observer \\ Observed") ?>
                </th>
                <?php foreach ($assessment->team->members as $teamMember): ?>
                    <th>
                        <?= $teamMember->member->fullname ?>
                    </th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($assessment->team->members as $observerMember) { ?>
                <tr>
                    <th style="text-align: right;">
                        <?= $observerMember->member->fullname ?>
                        <?= Html::a($mail_icon, Url::to(['assessment/send-wheel', 'id' => $assessment->id, 'memberId' => $observerMember->user_id, 'type' => Wheel::TYPE_ORGANIZATIONAL]), ['class' => 'btn btn-default btn-xs']) ?>
                    </th>
                    <?php foreach ($assessment->team->members as $observedMember) { ?>
                        <td>
                            <?php
                            foreach ($assessment->organizationalWheels as $wheel)
                                if ($wheel->observer_id == $observerMember->user_id && $wheel->observed_id == $observedMember->user_id) {
                                    echo $wheel->answerStatus;
                                }
                            ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
        <?= Html::a(\Yii::t('app', 'Refresh'), Url::to(['assessment/view', 'id' => $assessment->id,]), ['class' => 'btn btn-default']) ?>
        <?=
        Html::a(\Yii::t('assessment', 'Go to dashboard...'), Url::to(['assessment/go-to-dashboard', 'id' => $assessment->id,]), [
            'class' => ($wheel_count == count($assessment->team->members) * 3 ? 'btn btn-success' : 'btn btn-default')
        ])
        ?>
    </div>
</div>
