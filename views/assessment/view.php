<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use app\models\Assessment;
use app\models\Wheel;
use app\models\WheelQuestion;
use yii\bootstrap\Modal;
use kartik\select2\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$individualQuestionCount = WheelQuestion::getQuestionCount(Wheel::TYPE_INDIVIDUAL);
$groupQuestionCount = WheelQuestion::getQuestionCount(Wheel::TYPE_GROUP);
$organizationalQuestionCount = WheelQuestion::getQuestionCount(Wheel::TYPE_ORGANIZATIONAL);

$this->title = $assessment->fullname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'Teams'), 'url' => ['/team']];
$this->params['breadcrumbs'][] = ['label' => $assessment->team->fullname, 'url' => ['/team/view', 'id' => $assessment->team->id]];
$this->params['breadcrumbs'][] = $this->title;

$buttonId = 0;

$wheels_completed = true;
foreach ($assessment->individualWheels as $wheel)
    if ($wheel->answerStatus != '100%') {
        $wheels_completed = false;
        break;
    }
if ($wheels_completed)
    foreach ($assessment->groupWheels as $wheel)
        if ($wheel->answerStatus != '100%') {
            $wheels_completed = false;
            break;
        }
if ($wheels_completed)
    foreach ($assessment->organizationalWheels as $wheel)
        if ($wheel->answerStatus != '100%') {
            $wheels_completed = false;
            break;
        }

$mail_icon = '<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>';
$file_icon = '<span class="glyphicon glyphicon-file" aria-hidden="true"></span>';

$coachesProvider = new ArrayDataProvider([
    'allModels' => $assessment->assessmentCoaches,
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
        <?= Yii::t('user', 'Coach') ?>: <?= Html::label($assessment->team->coach->fullname) ?><br />
        <?= Yii::t('team', 'Company') ?>: <?= Html::label($assessment->team->company->name) ?><br />
        <?= Yii::t('team', 'Team') ?>: <?= Html::label($assessment->team->name) ?><br />
        <?= Yii::t('team', 'Sponsor') ?>: <?= Html::label($assessment->team->sponsor->fullname) ?>
    </div>
    <div class="clearfix"></div>
    <div class="row col-md-6">
        <h2><?= $assessment->getAttributeLabel('coaches') ?></h2>
        <?=
        GridView::widget([
            'dataProvider' => $coachesProvider,
            'columns' => [
                'coach.fullname',
                ['class' => 'app\components\grid\ActionColumn',
                    'template' => '{delete}',
                    'options' => ['width' => '60px'],
                    'buttons' => [
                        'delete' => function ($url, $model, $key) {
                            if ($model['coach_id'] == Yii::$app->user->identity->id) {
                                return '';
                            } else {

                                return Html::a(app\components\Icons::REMOVE, Url::to(['remove-coach', 'id' => $model['id']]), [
                                            'title' => Yii::t('assessment', 'Remove access'),
                                            'class' => 'btn btn-danger',
                                ]);
                            }
                        },
                    ]
                ]
            ],
        ]);
        ?>
        <?php $form = ActiveForm::begin(['id' => 'addcoach-form', 'action' => ['grant-coach', 'id' => $assessment->id], 'options' => ['class' => 'form-inline']]);
        ?>
        <?=
        Select2::widget([
            'name' => 'coach_id',
            'options' => [
                'placeholder' => Yii::t('team', 'Select coach ...'),
            ],
            'pluginOptions' => $pluginOptions,
        ])
        ?>
        <?= Html::submitButton(\Yii::t('assessment', 'Grant access'), ['class' => 'btn btn-primary', 'name' => 'save-button', 'style' => 'margin-bottom: 10px;']) ?>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-md-6">
        <h2>
            <?= Yii::t('assessment', 'Individual wheels') ?>
            <button id="cell_individual" type="button" class="btn btn-default btn-xs" onclick="showTokens('individual_modal');" >
                <span class="glyphicon glyphicon-phone" aria-hidden="true"></span>
            </button>
        </h2>
        <table class="table table-bordered table-hover">
            <?php foreach ($assessment->team->members as $observerMember) { ?>
                <tr>
                    <th style="text-align: right;">
                        <?= $observerMember->member->fullname ?>
                        <?= Html::a($mail_icon, Url::to(['assessment/send-wheel', 'id' => $assessment->id, 'memberId' => $observerMember->person_id, 'type' => Wheel::TYPE_INDIVIDUAL]), ['class' => 'btn btn-default btn-xs', 'title' => Yii::t('wheel', 'Send by email')]) ?>

                        <?php
                        foreach ($assessment->individualWheels as $wheel)
                            if ($wheel->observer_id == $observerMember->person_id) {
                                ?>
                                <button id="cell_<?= $buttonId ?>" type="button" class="btn btn-default btn-xs" onclick="showEmail('<?= $observerMember->member->fullname ?>', '<?= $observerMember->member->email ?>', '<?= Url::toRoute(['wheel/run', 'token' => $wheel->token], true) ?>');" title="<?= Yii::t('wheel', 'Manual email') ?>">
                                    <?= $mail_icon ?>!
                                </button>
                                <?php
                                $buttonId++;
                                break;
                            }
                        ?>
                    </th>
                    <td>
                        <?php
                        foreach ($assessment->individualWheels as $wheel)
                            if ($wheel->observer_id == $observerMember->person_id) {
                                echo $wheel->answerStatus . '&nbsp;';
                                echo Html::a($file_icon, Url::to(['wheel/manual-form', 'id' => $wheel->id]), ['class' => 'btn btn-default btn-xs']);
                            }
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div class="clearfix"></div>
    <div class="row col-md-12">
        <h2>
            <?= Yii::t('assessment', 'Group wheels') ?>
            <button id="cell_individual" type="button" class="btn btn-default btn-xs" onclick="showTokens('group_modal');" >
                <span class="glyphicon glyphicon-phone" aria-hidden="true"></span>
            </button>
        </h2>
        <table width="100%" class="table table-bordered table-hover">
            <tr>
                <th style="text-align: right;">
                    <?= Yii::t('wheel', "Observer \\ Observed") ?>
                </th>
                <?php foreach ($assessment->team->members as $teamMember): ?>
                    <th>
                        <?= $teamMember->member->fullname ?>
                    </th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($assessment->team->members as $observerMember) { ?>
                <tr>
                    <th style="text-align: right;">
                        <?= $observerMember->member->fullname ?>
                        <?= Html::a($mail_icon, Url::to(['assessment/send-wheel', 'id' => $assessment->id, 'memberId' => $observerMember->person_id, 'type' => Wheel::TYPE_GROUP]), ['class' => 'btn btn-default btn-xs', 'title' => Yii::t('wheel', 'Send by email')]) ?>
                        <?php
                        foreach ($assessment->groupWheels as $wheel)
                            if ($wheel->observer_id == $observerMember->person_id) {
                                ?>
                                <button id="cell_<?= $buttonId ?>" type="button" class="btn btn-default btn-xs" onclick="showEmail('<?= $observerMember->member->fullname ?>', '<?= $observerMember->member->email ?>', '<?= Url::toRoute(['wheel/run', 'token' => $wheel->token], true) ?>');" title="<?= Yii::t('wheel', 'Manual email') ?>">
                                    <?= $mail_icon ?>!
                                </button>
                                <?php
                                $buttonId++;
                                break;
                            }
                        ?>
                    </th>
                    <?php foreach ($assessment->team->members as $observedMember) { ?>
                        <td>
                            <?php
                            foreach ($assessment->groupWheels as $wheel)
                                if ($wheel->observer_id == $observerMember->person_id && $wheel->observed_id == $observedMember->person_id) {
                                    echo $wheel->answerStatus . '&nbsp;';
                                    echo Html::a($file_icon, Url::to(['wheel/manual-form', 'id' => $wheel->id]), ['class' => 'btn btn-default btn-xs']);
                                }
                            ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div class="clearfix"></div>
    <div class="row col-md-12">
        <h2>
            <?= Yii::t('assessment', 'Organizational wheels') ?>
            <button id="cell_individual" type="button" class="btn btn-default btn-xs" onclick="showTokens('organizational_modal');" >
                <span class="glyphicon glyphicon-phone" aria-hidden="true"></span>
            </button>
        </h2>
        <table width="100%" class="table table-bordered table-hover">
            <tr>
                <th style="text-align: right;">
                    <?= Yii::t('wheel', "Observer \\ Observed") ?>
                </th>
                <?php foreach ($assessment->team->members as $teamMember): ?>
                    <th>
                        <?= $teamMember->member->fullname ?>
                    </th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($assessment->team->members as $observerMember) { ?>
                <tr>
                    <th style="text-align: right;">
                        <?= $observerMember->member->fullname ?>
                        <?= Html::a($mail_icon, Url::to(['assessment/send-wheel', 'id' => $assessment->id, 'memberId' => $observerMember->person_id, 'type' => Wheel::TYPE_ORGANIZATIONAL]), ['class' => 'btn btn-default btn-xs', 'title' => Yii::t('wheel', 'Send by email')]) ?>
                        <?php
                        foreach ($assessment->organizationalWheels as $wheel)
                            if ($wheel->observer_id == $observerMember->person_id) {
                                ?>
                                <button id="cell_<?= $buttonId ?>" type="button" class="btn btn-default btn-xs" onclick="showEmail('<?= $observerMember->member->fullname ?>', '<?= $observerMember->member->email ?>', '<?= Url::toRoute(['wheel/run', 'token' => $wheel->token], true) ?>');"  title="<?= Yii::t('wheel', 'Manual email') ?>">
                                    <?= $mail_icon ?>!
                                </button>
                                <?php
                                $buttonId++;
                                break;
                            }
                        ?>
                    </th>
                    <?php foreach ($assessment->team->members as $observedMember) { ?>
                        <td>
                            <?php
                            foreach ($assessment->organizationalWheels as $wheel)
                                if ($wheel->observer_id == $observerMember->person_id && $wheel->observed_id == $observedMember->person_id) {
                                    echo $wheel->answerStatus . '&nbsp;';
                                    echo Html::a($file_icon, Url::to(['wheel/manual-form', 'id' => $wheel->id]), ['class' => 'btn btn-default btn-xs']);
                                }
                            ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </table>
        <?= Html::a(\Yii::t('app', 'Refresh'), Url::to(['assessment/view', 'id' => $assessment->id,]), ['class' => 'btn btn-default']) ?>
        <?=
        Html::a(\Yii::t('assessment', 'Go to dashboard...'), Url::to(['assessment/go-to-dashboard', 'id' => $assessment->id,]), [
            'class' => ($wheels_completed ? 'btn btn-success' : 'btn btn-default')
        ])
        ?>
        <?=
        Html::a(\Yii::t('assessment', 'Go to report...'), Url::to(['report/view', 'id' => $assessment->id,]), [
            'class' => ($wheels_completed ? 'btn btn-success' : 'btn btn-default')
        ])
        ?>
    </div>
    <?php
    Modal::begin([
        'id' => 'email_modal',
        'header' => '<h4>' . Yii::t('assessment', 'Email to send') . '</h4>',
        'size' => Modal::SIZE_LARGE,
    ]);
    ?>
    <div>
        <h3><?= Yii::t('assessment', 'Please send this email') ?></h3>
        <h2><?= Yii::t('app', 'To:') ?> <span id="member_email"></span></h2>
        <h3><?= Yii::t('app', 'Body:') ?></h3>
        <h3 style="margin-left: 50px;">
            <p>
                <?= Yii::t('wheel', "Dear ") ?> <span id="member"></span>
            </p>
            <p>
                <?= Yii::t('wheel', "Please, use next link in your browser") ?>
            </p>
            <p>
                <span id="url"></span>
            </p>
            <p>
                <?= Yii::t('wheel', 'Thank you very much!') ?>
            </p>
            <p>
                <?= Yii::t('app', 'Empowerment Foundation') ?>
            </p>
        </h3>        
    </div>
    <?php Modal::end(); ?>
    <?php
    Modal::begin([
        'id' => 'individual_modal',
        'header' => '<h4>' . Yii::t('assessment', 'Run on smartphones') . '</h4>',
        'size' => Modal::SIZE_LARGE,
    ]);
    ?>
    <div class="text-center">
        <h3><?= Yii::t('assessment', 'Please ask the audience to enter this site and token in his/her phone browser') ?></h3>
        <h2><?= Url::to('@web/', true); ?></h2>
        <table align="center" class="table table-bordered table-hover" style="width: 50%; font-size: 18px;">
            <?php foreach ($assessment->team->members as $observerMember) { ?>
                <tr>
                    <td style="text-align: right">
                        <?= $observerMember->member->fullname ?>:
                    </td>
                    <td>
                        <?php
                        foreach ($assessment->individualWheels as $wheel)
                            if ($wheel->observer_id == $observerMember->person_id) {
                                echo $wheel->token;
                                break;
                            }
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php Modal::end(); ?>
    <?php
    Modal::begin([
        'id' => 'group_modal',
        'header' => '<h4>' . Yii::t('assessment', 'Run on smartphones') . '</h4>',
        'size' => Modal::SIZE_LARGE,
    ]);
    ?>
    <div class="text-center">
        <h3><?= Yii::t('assessment', 'Please ask the audience to enter this site and token in his/her phone browser') ?></h3>
        <h2><?= Url::to('@web/', true); ?></h2>
        <table align="center" class="table table-bordered table-hover" style="width: 50%; font-size: 18px;">
            <?php foreach ($assessment->team->members as $observerMember) { ?>
                <tr>
                    <td style="text-align: right">
                        <?= $observerMember->member->fullname ?>:
                    </td>
                    <td>
                        <?php
                        foreach ($assessment->groupWheels as $wheel)
                            if ($wheel->observer_id == $observerMember->person_id) {
                                echo $wheel->token;
                                break;
                            }
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php Modal::end(); ?>
    <?php
    Modal::begin([
        'id' => 'organizational_modal',
        'header' => '<h4>' . Yii::t('assessment', 'Run on smartphones') . '</h4>',
        'size' => Modal::SIZE_LARGE,
    ]);
    ?>
    <div class="text-center">
        <h3><?= Yii::t('assessment', 'Please ask the audience to enter this site and token in his/her phone browser') ?></h3>
        <h2><?= Url::to('@web/', true); ?></h2>
        <table align="center" class="table table-bordered table-hover" style="width: 50%; font-size: 18px;">
            <?php foreach ($assessment->team->members as $observerMember) { ?>
                <tr>
                    <td style="text-align: right">
                        <?= $observerMember->member->fullname ?>:
                    </td>
                    <td>
                        <?php
                        foreach ($assessment->organizationalWheels as $wheel)
                            if ($wheel->observer_id == $observerMember->person_id) {
                                echo $wheel->token;
                                break;
                            }
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php Modal::end(); ?>
</div>
<script type="text/javascript">
    function showEmail(member, email, url, token)
    {
        $('#email_modal').modal('show');
        $('#member').html(member);
        $('#member_email').html(email);
        $('#url').html(url);
    }

    function showTokens(modal)
    {
        $('#' + modal).modal('show');
    }
</script>
