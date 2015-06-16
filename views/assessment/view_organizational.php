<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use app\models\Assessment;
use app\models\Wheel;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = $assessment->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'My Teams'), 'url' => ['/team']];
$this->params['breadcrumbs'][] = ['label' => $assessment->team->name, 'url' => ['/team/view', 'id' => $assessment->team->id]];
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
        <h2><?= Yii::t('assessment', 'Organizational wheels') ?></h2>
        <table width="100%">
            <tr>
                <th>
                    <?= Yii::t('wheel', 'Observer \ Observed') ?>
                </th>
                <?php foreach ($assessment->team->members as $teamMember): ?>
                    <th>
                        <?= $teamMember->member->fullname ?>
                    </th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($assessment->team->members as $observerMember) { ?>
                <tr>
                    <th>
                        <?= $observerMember->member->fullname ?>
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
    </div>
</div>