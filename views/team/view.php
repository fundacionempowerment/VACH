<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = $team->fullname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/team']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-register">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="row col-md-4">
        <h3><?= Yii::t('team', 'Team data') ?> </h3>
        <p>
            <?= Yii::t('user', 'Coach') ?>: <?= Html::label($team->coach->fullname) ?><br />
            <?= Yii::t('team', 'Company') ?>: <?= Html::label($team->company->name) ?><br />
            <?= Yii::t('team', 'Sponsor') ?>: <?= Html::label($team->sponsor->fullname) ?>
        </p>
        <p>
            <?=
            Html::a(Yii::t('app', 'Edit'), ['team/edit', 'id' => $team->id], [
                'title' => Yii::t('yii', 'Edit'),
                'class' => 'btn btn-primary',
            ])
            ?>
        </p>
    </div>
    <div class="col-md-4 thumbnail" style="padding: 10px; margin-right: 20px;" >
        <h3 style="margin-top: 12px;"><?= Yii::t('team', 'Members') ?></h3>
        <?php
        $membersDataProvider = new ArrayDataProvider([
            'allModels' => $team->members,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $columns = [
            [
                'attribute' => 'member.fullname',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::a($data->member->fullname, Url::to(['team/edit-member', 'id' => $data['id']]));
                },
                    ]
                ];
                if (!$team->blocked) {
                    $columns [] = [
                        'format' => 'html',
                        'options' => ['width' => '60px'],
                        'value' => function( $data ) {
                    return
                            Html::a('<span class="glyphicon glyphicon-trash"></span>', ['team/delete-member', 'id' => $data['id']], [
                                'title' => Yii::t('yii', 'Delete'),
                                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                                'class' => 'btn btn-danger',
                    ]);
                }
                    ];
                }
                echo GridView::widget([
                    'dataProvider' => $membersDataProvider,
                    'summary' => '',
                    'columns' => $columns,
                ]);
                ?>
                <?php if (!$team->blocked) { ?>
                    <?php $form = ActiveForm::begin([ 'id' => 'addmember-form', 'options' => [ 'class' => 'form-inline']]);
                    ?>
                    <?= Html::a(Yii::t('team', 'New member'), Url::to(['team/new-member', 'id' => $team->id]), ['class' => 'btn btn-success', 'style' => 'margin-bottom: 10px;']) ?>                    
                    <?=
                    Select2::widget([
                        'name' => 'new_member',
                        'data' => $persons,
                        'options' => [
                            'placeholder' => Yii::t('team', 'Select new member ...'),
                        ],
                    ])
                    ?>
                    <?= Html::submitButton(\Yii::t('app', 'Add'), ['class' => 'btn btn-primary', 'name' => 'save-button', 'style' => 'margin-bottom: 10px;']) ?>
                    <br>
                    <?=
                    Html::a(Yii::t('team', 'Team fullfilled'), Url::to(['team/fullfilled', 'id' => $team->id]), [
                        'class' => 'btn btn-warning',
                        'style' => 'margin-bottom: 10px;',
                        'data-confirm' => Yii::t('app', 'Are you sure?'),
                        'data-method' => 'post',])
                    ?>
                    <?php ActiveForm::end(); ?>
        <?php } ?>
            </div>
            <div class="col-md-4 thumbnail" style="padding: 10px;">
                <h3 style="margin-top: 12px;"><?= Yii::t('team', 'Assessments') ?></h3>
                <?php
                $assessmentsDataProvider = new ArrayDataProvider([
                    'allModels' => $team->assessments,
                    'pagination' => [
                        'pageSize' => 20,
                    ],
                ]);
                echo GridView::widget([
                    'dataProvider' => $assessmentsDataProvider,
                    'summary' => '',
                    'columns' => [
                        [
                            'attribute' => 'name',
                            'format' => 'html',
                            'value' => function ($data) {
                                return Html::a($data->name, Url::to(['assessment/view', 'id' => $data['id']]));
                            },
                                ],
                                [
                                    'format' => 'html',
                                    'options' => ['width' => '60px'],
                                    'value' => function ($data) {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::to(['team/delete-assessment', 'id' => $data['id']]), [
                                            'title' => Yii::t('yii', 'Delete'),
                                            'class' => 'btn btn-danger',
                                ]);
                            },
                                ],
                            ],
                        ]);
                        ?>
                <?= ( $team->blocked ? Html::a(Yii::t('team', 'New assessment'), Url::to(['assessment/new', 'teamId' => $team->id]), ['class' => 'btn btn-success']) : Html::a(Yii::t('team', 'New assessment (requires team fullfilled)'), '#', ['class' => 'btn btn-default disabled'])) ?>
    </div>
</div>