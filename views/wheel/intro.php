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

$instructions_shown = Yii::$app->session->get('instructions_shown');
$show_instructions = $instructions_shown == true ? false : true;
?>
<div class="site-wheel">
    <h1><?= Html::encode($this->title) ?></h1>
    <h2>
        <?= Yii::t('user', 'Coach') ?>: <?= Html::label($wheel->coach->fullname) ?><br />
        <?= Yii::t('wheel', 'Observer') ?>: <?= Html::label($wheel->observer->fullname) ?><br />
        <?= Yii::t('wheel', 'Observed') ?>: <?= Html::label($wheel->observed->fullname) ?><br />
    </h2>
    <div id="collapsedDiv" class="panel-collapse <?= $show_instructions == true ? '' : 'collapse' ?> row col-md-12" aria-expanded="<?= $show_instructions == true ? 'true' : 'false' ?>">
        <h4>
            <ol>
                <li>
                    Tome <b>conciencia y responsabilidad</b> de la tarea que está a punto de ejecutar.
                    <br/><br/>
                </li>
                <li>
                    Antes de responder a cada pregunta haga el ejercicio de visualizar  a su compañero:
                    asóciese con él y dese el permiso de que su Dación (Comunicación) sea en el
                    marco de los valores esenciales que vimos durante el taller.
                    <br/><br/>
                </li>
                <li>
                    Toda percepción no es la Realidad, pero sí nos abre la Comunicación para
                    acercarnos a Ella.
                    <br/><br/>
                </li>
                <li>
                    En cada respuesta, los valores usados son<br/>
                    0 = nunca, 1 = casi nunca, 2 = regularmente, 3 = casi siempre, 4 = siempre<br/>
                    0 = totalmente en desacuerdo, 1 = algo de acuerdo, 2 = de acuerdo, 3 = muy de acuerdo, 4 = totalmente de acuerdo.
                    <br/><br/>
                </li>
            </ol>
        </h4>
    </div>
    <?php $form = ActiveForm::begin(['id' => 'wheel-form']); ?>
    <input type="hidden" name="id" value="<?= $wheel->id ?>"/>
    <input type="hidden" name="current_dimension" value="<?= $current_dimension ?>"/>
    <?= Html::submitButton(Yii::t('app', 'Begin'), ['class' => 'btn btn-primary btn-lg']); ?>
    <a class="collapsed btn btn-default btn-lg" aria-controls="collapsedDiv" aria-expanded="false" href="#collapsedDiv" data-toggle="collapse" role="button">
        <?= Yii::t('wheel', 'Show instructions') ?>
    </a>
    <br/><br/>
    <?php
    if (isset(Yii::$app->user))
        if (isset(Yii::$app->user->identity))
            if (Yii::$app->user->identity->is_coach) {
                echo Html::a(Yii::t('wheel', 'Back to assessment board'), ['assessment/view', 'id' => $wheel->assessment->id], ['class' => 'btn btn-default']);
            }
    ?>
    <?php ActiveForm::end(); ?>
</div>
<?php
Modal::begin([
    'id' => 'dummy_modal',
    'size' => Modal::SIZE_SMALL,
]);
?>
<?php Modal::end(); ?>
