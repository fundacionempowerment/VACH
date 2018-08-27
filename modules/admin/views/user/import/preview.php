<?php

use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = Yii::t('user', 'Import Users') . ' - ' . Yii::t('app', 'Preview');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="fusion-form">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    $form = ActiveForm::begin([
        'id' => 'import-form',
        'options' => [
            'enctype' => 'multipart/form-data'
        ],
    ]);
    ?>
    <div class="row col-lg-12">
        <?php
        $dataProvider = new ArrayDataProvider([
            'allModels' => $users,
            'pagination' => false,
        ]);
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'name',
                    'format' => 'html',
                    'value' => function ($data) {
                        return $data->fullname;
                    },
                ],
                'username',
                'email',
                'phone',
                [
                    'header' => 'errors',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return \app\controllers\SiteController::Errors($data);
                    },
                ],
            ],
        ]);
        ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success', 'name' => 'save-button']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>