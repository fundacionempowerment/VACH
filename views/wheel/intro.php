<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\Wheel;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\ContactForm */

if ($wheel->type == Wheel::TYPE_INDIVIDUAL) {
    $this->title = Yii::t('wheel', 'Running individual wheel');
} else if ($wheel->type == Wheel::TYPE_GROUP) {
    $this->title = Yii::t('wheel', 'Running group wheel');
} else {
    $this->title = Yii::t('wheel', 'Running organizational wheel');
}
?>
<div class="site-wheel">
    <h1><?= Html::encode($this->title) ?></h1>
    <h2>
        <?= Yii::t('user', 'Coach') ?>: <?= Html::label($wheel->coach->fullname) ?><br />
        <?= Yii::t('wheel', 'Observer') ?>: <?= Html::label($wheel->observer->fullname) ?><br />
        <?= Yii::t('wheel', 'Observed') ?>: <?= Html::label($wheel->observed->fullname) ?><br />
    </h2>
    <?php $form = ActiveForm::begin(['id' => 'wheel-form']); ?>
    <input type="hidden" name="id" value="<?= $wheel->id ?>"/>
    <input type="hidden" name="current_dimension" value="<?= $current_dimension ?>"/>
    <?= Html::submitButton(Yii::t('app', 'Begin'), ['class' => 'btn btn-primary btn-lg']); ?>
    <a class="collapsed btn btn-default btn-lg" aria-controls="collapsedDiv" aria-expanded="false" href="#collapsedDiv" data-toggle="collapse" role="button">
        <?= Yii::t('wheel', 'Instructions') ?>
    </a>
    <br/><br/>
    <div id="collapsedDiv" class="panel-collapse collapse row col-md-12" aria-expanded="false">
        <ol>
            <li>
                Tome <b>conciencia y responsabilidad</b> de la tarea que está a punto de ejecutar.
            </li>
            <li>
                Antes de responder a cada pregunta haga el ejercicio de mirar a su compañero:
                asóciese con él y dese el permiso de que su Dación (Comunicación) sea en el
                marco de los valores esenciales que vimos durante el taller.
            </li>
            <li>
                Toda percepción no es la Realidad, pero sí nos abre la Comunicación para
                acercarnos a Ella.
            </li>
        </ol>
    </div>
    <?php
    if (isset(Yii::$app->user))
        if (isset(Yii::$app->user->identity))
            if (Yii::$app->user->identity->is_coach) {
                echo Html::a(Yii::t('wheel', 'Back to assessment board'), ['assessment/view', 'id' => $wheel->assessment->id], ['class' => 'btn btn-default']);
            }
    ?>
</div>
<?php
Modal::begin([
    'id' => 'dummy_modal',
    'size' => Modal::SIZE_SMALL,
]);
?>
<?php Modal::end(); ?>
