<?php

use app\models\User;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('user', 'Fuse Users');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title;

$isAdministrator = Yii::$app->user->identity->is_administrator;
?>
    <div class="fusion-form">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php
        $form = ActiveForm::begin([
            'id' => 'fusion-form',
        ]);
        ?>
        <div class="row">
            <div class="col-lg-push-2 col-lg-3 text-right">
                <?= $form->field($model, 'originUserId')->widget(Select2::classname(), [
                    'data' => User::getUserList(),
                ]) ?>
            </div>
            <div class="col-lg-push-2 col-lg-2 text-center">
                <i class="glyphicon glyphicon-chevron-right"></i>
                <i class="glyphicon glyphicon-chevron-right"></i>
                <i class="glyphicon glyphicon-chevron-right"></i>
                <i class="glyphicon glyphicon-chevron-right"></i>
                <i class="glyphicon glyphicon-chevron-right"></i>
            </div>
            <div class="col-lg-push-2 col-lg-3">
                <?= $form->field($model, 'destinationUserId')->widget(Select2::classname(), [
                    'data' => User::getUserList(),
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                <?= Html::button(Yii::t('app', 'Preview'), ['class' => 'btn btn-success', 'id' => 'preview-button']) ?>
                <?= Html::submitButton(Yii::t('app', 'Fuse'), ['class' => 'btn btn-danger', 'id' => 'fuse-button',
                    'data-confirm' => Yii::t('user', 'Are you sure you want to fuse these users?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <div id="preview" class="col-lg-push-1 col-lg-10">

        </div>
    </div>
    <script>
        var FusionForm = new function () {
            this.init = function () {
                $('#fuse-button').addClass('disabled');
                $('#fuse-button').prop('disabled', true)
                $(document).off('click', '#preview-button')
                    .on('click', '#preview-button', function () {
                        FusionForm.preview();
                    });
            }

            this.preview = function () {
                $('#fuse-button').addClass('disabled');
                var originUserId = $('[name="UserFusionForm[originUserId]"]').val()
                $.ajax({
                    url: '<?= Url::to(['fusion-preview']) ?>',
                    type: 'post',
                    data: {originUserId: originUserId},
                    dataType: 'json',
                }).done(function (response) {
                    if (response.status == 'success') {
                        var $previewDiv = $("#preview");
                        $previewDiv.html(response.preview)
                        $('#fuse-button').removeClass('disabled');
                        $('#fuse-button').prop('disabled', false);
                    }
                });
            }
        }
    </script>
<?php $this->registerJs('FusionForm.init()') ?>