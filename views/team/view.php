<?php

use kartik\widgets\Select2;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = $team->fullname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/team']];
$this->params['breadcrumbs'][] = $this->title;

$coachesProvider = new ArrayDataProvider([
    'allModels' => $team->teamCoaches,
]);

$pluginOptions = [
    'allowClear' => true,
    'minimumInputLength' => 3,
    'ajax' => [
        'url' => Url::to(['/user/find-by-name']),
        'dataType' => 'json',
        'data' => new JsExpression('function(params) { return {name:params.term, id:$(this).val()}; }')
    ],
    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
    'templateResult' => new JsExpression('function(user) { return user.userFullname; }'),
    'templateSelection' => new JsExpression('function (user) { return user.userFullname; }'),
    'cache' => true
];
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row col-md-6">
        <h3><?= Yii::t('team', 'Team data') ?> </h3>
        <p>
            <?= Yii::t('app', 'Type') ?>: <?= Html::label($team->teamType->name) ?><br/>
            <?= Yii::t('user', 'Coach') ?>: <?= Html::label($team->coach->fullname) ?><br/>
            <?= Yii::t('team', 'Company') ?>: <?= Html::label($team->company->name) ?><br/>
            <?= Yii::t('team', 'Sponsor') ?>: <?= Html::label($team->sponsor->fullname) ?><br/>
            <?= Yii::t('app', 'Notes') ?>: <?= Html::label($team->notes) ?>
        </p>
        <p>
            <?=
            $team->coach_id == Yii::$app->user->identity->id ?
                \app\components\SpinnerAnchor::widget([
                    'caption' => Yii::t('app', 'Edit'),
                    'url' => Url::to(['team/edit', 'id' => $team->id]),
                    'options' => ['class' => 'btn btn-primary'],
                ]) : ''
            ?>
            <?= Html::a(\Yii::t('app', 'Download PPT'), Url::to(['report/presentation', 'id' => $team->id]), ['class' => 'btn btn-success']) ?>
            <?= Html::a(\Yii::t('app', 'Download DOC'), Url::to(['report/word', 'id' => $team->id]), ['class' => 'btn btn-primary']) ?>
        </p>
        <h3><?= $team->getAttributeLabel('coaches') ?></h3>
        <?=
        GridView::widget([
            'dataProvider' => $coachesProvider,
            'columns' => [
                'coach.fullname',
                ['class' => 'app\components\grid\ActionColumn',
                    'template' => '{delete}',
                    'options' => ['width' => '60px'],
                    'buttons' => [
                        'delete' => function ($url, $model, $key) use ($team) {
                            if ($model['coach_id'] == Yii::$app->user->identity->id || $team->coach_id != Yii::$app->user->identity->id) {
                                return '';
                            } else {
                                return Html::a(app\components\Icons::REMOVE, Url::to(['remove-coach', 'id' => $model['id']]), [
                                    'title' => Yii::t('team', 'Remove access'),
                                    'class' => 'btn btn-danger',
                                ]);
                            }
                        },
                    ]
                ]
            ],
        ]);
        ?>
        <?php
        if ($team->coach_id == Yii::$app->user->identity->id) {
            $form = ActiveForm::begin(['id' => 'addcoach-form', 'action' => ['grant-coach', 'id' => $team->id], 'options' => ['class' => 'form-inline']]);
            ?>
            <table>
                <tr>
                    <td style="width: 100%;">
                        <?=
                        Select2::widget([
                            'name' => 'coach_id',
                            'options' => [
                                'placeholder' => Yii::t('team', 'Select coach...'),
                            ],
                            'pluginOptions' => $pluginOptions,
                        ])
                        ?>
                    </td>
                    <td>
                        <?= \app\components\SpinnerSubmitButton::widget([
                            'caption' => \Yii::t('team', 'Grant access'),
                            'options' => ['class' => 'btn btn-primary']
                        ]) ?>
                    </td>
                </tr>
            </table>
            <?php
            ActiveForm::end();
        }
        ?>
    </div>
    <?= $this->render('_members', ['team' => $team, 'persons' => $persons]) ?>
    <div class="clearfix"></div>
    <?= $this->render('_wheels', ['team' => $team, 'persons' => $persons]) ?>
</div>

