<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
?>

<div class="col-md-6 thumbnail" style="padding: 10px; margin-left: 20px;" >
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
            'value' => function ($data) use ($team) {
                if ($team->coach_id == Yii::$app->user->identity->id) {
                    return Html::a($data->member->fullname, Url::to(['team/edit-member', 'id' => $data['id']]));
                } else {
                    return $data->member->fullname;
                }
            },
        ],
        [
            'class' => 'yii\grid\DataColumn',
            'attribute' => 'active',
            'format' => 'html',
            'content' => function ($data) {
                return kartik\checkbox\CheckboxX::widget([
                            'name' => 'c' . $data['id'],
                            'value' => $data['active'],
                            'pluginOptions' => [
                                'threeState' => false
                            ],
                            'pluginEvents' => [
                                "change" => 'function(e) { 
                                        var element = $(e.target);
                                        var isActive = element.val()
                                        activate(' . $data['id'] . ', isActive); }',
                            ]
                ]);
            },
        ],
    ];
    if (count($team->wheels) == 0) {
        $columns [] = [
            'format' => 'html',
            'options' => ['width' => '60px'],
            'value' => function( $data ) use ($team) {
                if ($team->coach_id == Yii::$app->user->identity->id) {
                    return
                            Html::a('<span class="glyphicon glyphicon-trash"></span>', ['team/delete-member', 'id' => $data['id']], [
                                'title' => Yii::t('yii', 'Delete'),
                                'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                'data-method' => 'post',
                                'data-pjax' => '0',
                                'class' => 'btn btn-danger',
                    ]);
                } else {
                    return '';
                }
            }
        ];
    }
    echo GridView::widget([
        'dataProvider' => $membersDataProvider,
        'summary' => '',
        'columns' => $columns,
    ]);
    ?>
    <?php $form = ActiveForm::begin(['id' => 'addmember-form', 'options' => ['class' => 'form-inline']]);
    ?>
    <table>
        <tr>
            <td style="width: 100%;">
                <?=
                Select2::widget([
                    'name' => 'new_member',
                    'data' => $persons,
                    'options' => [
                        'placeholder' => Yii::t('team', 'Select new member...'),
                    ],
                ])
                ?>            
            </td>
            <td>
                <?= Html::submitButton(\Yii::t('app', 'Add'), ['class' => 'btn btn-primary', 'name' => 'save-button']) ?>            
            </td>
        </tr>
    </table>        
    <br/>
    <?php
    if (count($team->wheels) == 0) {
        echo Html::a(Yii::t('team', 'Team fullfilled'), Url::to(['team/fullfilled', 'id' => $team->id]), [
            'class' => 'btn btn-warning',
            'style' => 'margin-bottom: 10px;',
        ]);
    } else if (count($team->individualWheels) != count($team->members)) {
        echo Html::a(Yii::t('team', 'Update Team'), Url::to(['team/fullfilled', 'id' => $team->id]), [
            'class' => 'btn btn-info',
            'style' => 'margin-bottom: 10px;',
        ]);
    }
    ?>
    <?php ActiveForm::end(); ?>
</div>
<script>
    function activate(id, isActive) {
        $.ajax({
            url: 'index.php?r=team/activate-member',
            data: {
                id: id,
                isActive: isActive,
            },
        });
    }
</script>
