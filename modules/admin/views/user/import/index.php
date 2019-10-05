<?php

use kartik\widgets\FileInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('user', 'Import Users');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title;

$isAdministrator = Yii::$app->user->identity->is_administrator;
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
        <?=
        $form->field($model, 'file')->widget(FileInput::classname(), [
            'pluginOptions' => [
                'showPreview' => false,
                'showCaption' => true,
                'showRemove' => false,
                'showUpload' => false,
                'overwriteInitial' => true,
            ]]);
        ?>
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Preview'), ['class' => 'btn btn-info', 'name' => 'save-button']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>