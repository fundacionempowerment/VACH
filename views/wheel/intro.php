<?php

use app\models\Wheel;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $wheel app\models\Wheel */

$this->title = Yii::t('wheel', 'Running wheel') . ' ' . $wheel->levelName;

$instructions_shown = Yii::$app->session->get('instructions_shown');
$show_instructions = $instructions_shown == true ? false : true;
?>
<div class="site-wheel col-md-push-2 col-md-8">
    <div>
        <h1><?= Html::encode($this->title) ?></h1>
        <h2>
            <?= Yii::t('wheel', 'Observer') ?>: <?= Html::label($wheel->observer->fullname) ?><br/>
            <?= Yii::t('wheel', 'Observed') ?>: <?= Html::label($wheel->observed->fullname) ?><br/>
        </h2>
        <h3>
            <?= Yii::t('company', 'Company') ?>: <?= Html::label($wheel->team->company->name) ?><br/>
            <?= Yii::t('team', 'Team') ?>: <?= Html::label($wheel->team->name) ?><br/>
            <?= Yii::t('user', 'Coach') ?>: <?= Html::label($wheel->coach->fullname) ?><br/>
            <?= Yii::t('app', 'Progress') ?>: <?= Html::label($wheel->team->getMemberProgress($wheel->observer_id, $wheel->type)) ?><br/>
        </h3>
    </div>
    <?php if ($wheel->observed->photo) { ?>
        <div class="col-md-push-1 col-md-10">
            <img src="<?= $wheel->observed->photoUrl ?>" class="img-responsive  text-center"
                 style="box-shadow: 0 2px 4px 0 rgba(0,0,0,0.16),0 2px 10px 0 rgba(0,0,0,0.12) !important"/>
        </div>
    <?php } ?>
    <div class="clearfix"></div>
    <div id="collapsedDiv" class="panel-collapse <?= $show_instructions == true ? '' : 'collapse' ?> row col-md-12"
         aria-expanded="<?= $show_instructions == true ? 'true' : 'false' ?>">
        <h4>
            <ol>
                <li>
                    Tome <b>conciencia y responsabilidad</b> de la tarea que está a punto de ejecutar.
                    <br/><br/>
                </li>
                <li>
                    Antes de responder a cada pregunta haga el ejercicio de visualizar
                    <?= $wheel->team->teamType->level_0_enabled ? "a su compañero" : "al área que está observando" ?>:
                    asóciese con <?= $wheel->team->teamType->level_0_enabled ? "él/ella" : "ella" ?>  y dese el permiso de que su Dación (Comunicación) sea en el
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
                    0 = nunca, 1 = rara vez, 2 = de vez en cuando, 3 = a menudo, 4 = siempre<br/>
                    0 = totalmente en desacuerdo, 1 = algo de acuerdo, 2 = de acuerdo, 3 = muy de acuerdo, 4 =
                    totalmente de acuerdo.
                    <br/><br/>
                </li>
            </ol>
        </h4>
    </div>
    <?php $form = ActiveForm::begin(['id' => 'wheel-form']); ?>
    <input type="hidden" name="id" value="<?= $wheel->id ?>"/>
    <input type="hidden" name="current_dimension" value="<?= $current_dimension ?>"/>
    <?= \app\components\SpinnerSubmitButton::widget([
        'caption' => Yii::t('app', 'Begin'),
        'options' => ['class' => 'btn btn-primary btn-lg']
    ]) ?>
    <a class="collapsed btn btn-default btn-lg" aria-controls="collapsedDiv" aria-expanded="false" href="#collapsedDiv"
       data-toggle="collapse" role="button">
        <?= Yii::t('wheel', 'Show instructions') ?>
    </a>
    <br/><br/>
    <?php
    if (isset(Yii::$app->user))
        if (isset(Yii::$app->user->identity)) {
            echo \app\components\SpinnerAnchor::widget([
                'caption' => Yii::t('wheel', 'Back to team board'),
                'url' => Url::to(['team/view', 'id' => $wheel->team->id]),
                'options' => ['class' => 'btn btn-default'],
            ]);
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
