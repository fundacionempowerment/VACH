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
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row col-md-12">
        <p>
            <?= Yii::t('user', 'Coach') ?>: <?= Html::label($assessment->team->coach->fullname) ?><br />
        </p>
    </div>
    <div class="row col-md-12">
        <h2><?= Yii::t('assessment', 'Individual wheels') ?></h2>
        <p>
            <?php
            if ($assessment->individual_status == Assessment::STATUS_PENDING) {
                echo Html::a(\Yii::t('assessment', 'Send individual wheels'), Url::to(['assessment/send-individual', 'id' => $assessment->id]), ['class' => 'btn btn-primary']);
            } else {
                echo Html::a(\Yii::t('assessment', 'Individual wheels sent'), '#', ['class' => 'btn btn-default', 'disabled' => 'disabled']);
            }
            ?>
        <ul>
            <?php foreach ($assessment->wheelStatus(Wheel::TYPE_INDIVIDUAL) as $individualWheel):
                ?>
                <li>
                    <?= $individualWheel['name'] . ' ' . $individualWheel['surname'] ?>:&nbsp;
                    <?= ($individualWheel['count'] / $individualQuestionCount * 100) . '%' ?>
                    <?= Yii::t('app', 'done') ?>
                    <?= Html::a($individualWheel['token'], ['wheel/run', 'token' => $individualWheel['token']]) ?>
                </li>
            <?php endforeach; ?>
        </ul>
        </p>
    </div>
    <div class="row col-md-12">
        <h2><?= Yii::t('assessment', 'Group wheels') ?></h2>
        <p>       
            <?php
            if ($assessment->group_status == Assessment::STATUS_PENDING) {
                echo Html::a(\Yii::t('assessment', 'Send group wheels'), Url::to(['assessment/send-group', 'id' => $assessment->id]), ['class' => 'btn btn-primary']);
            } else {
                echo Html::a(\Yii::t('assessment', 'Group wheels sent'), '#', ['class' => 'btn btn-default', 'disabled' => 'disabled']);
                echo '&nbsp;' . Html::a(\Yii::t('assessment', 'View detailed status'), Url::to(['assessment/detail-view', 'id' => $assessment->id, 'type' => Wheel::TYPE_GROUP]), ['class' => 'btn btn-primary']);
            }
            ?>
        <ul>
            <?php foreach ($assessment->wheelStatus(Wheel::TYPE_GROUP) as $wheel):
                ?>
                <li>
                    <?= $wheel['name'] . ' ' . $wheel['surname'] ?>:&nbsp;
                    <?= round($wheel['count'] * 100 / count($assessment->team->members) / $groupQuestionCount, 1) . '%' ?>
                    <?= Yii::t('app', 'done') ?>
                    <?= Html::a($wheel['token'], ['wheel/run', 'token' => $wheel['token']]) ?>
                </li>
            <?php endforeach; ?>
        </ul>
        </p>
    </div>
    <div class="row col-md-12">
        <h2><?= Yii::t('assessment', 'Organizational wheels') ?></h2>
        <p>
            <?php
            if ($assessment->organizational_status == Assessment::STATUS_PENDING) {
                echo Html::a(\Yii::t('assessment', 'Send organizational wheels'), Url::to(['assessment/send-organizational', 'id' => $assessment->id]), ['class' => 'btn btn-primary']);
            } else {
                echo Html::a(\Yii::t('assessment', 'Organizational wheels sent'), '#', ['class' => 'btn btn-default', 'disabled' => 'disabled']);
                echo '&nbsp;' . Html::a(\Yii::t('assessment', 'View detailed status'), Url::to(['assessment/detail-view', 'id' => $assessment->id, 'type' => Wheel::TYPE_ORGANIZATIONAL]), ['class' => 'btn btn-primary']);
            }
            ?>
        <ul>
            <?php foreach ($assessment->wheelStatus(Wheel::TYPE_ORGANIZATIONAL) as $wheel):
                ?>
                <li>
                    <?= $wheel['name'] . ' ' . $wheel['surname'] ?>:&nbsp;
                    <?= round($wheel['count'] * 100 / count($assessment->team->members) / $organizationalQuestionCount, 1) . '%' ?>
                    <?= Yii::t('app', 'done') ?>
                    <?= Html::a($wheel['token'], ['wheel/run', 'token' => $wheel['token']]) ?>
                </li>
            <?php endforeach; ?>
        </ul>
        </p>
        <?= Html::a(\Yii::t('app', 'Refresh'), Url::to(['assessment/view', 'id' => $assessment->id,]), ['class' => 'btn btn-default']) ?>

    </div>
</div>
