<?php

use app\components\SpinnerAnchor;
use app\models\Team;
use app\models\Wheel;
use app\models\ManualWheelModel;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\SpinnerSubmitButton;

/* @var Team $team */
/* @var ManualWheelModel $manualWheel */

$manualWheel = new ManualWheelModel(['team_id' => $team->id]);

function wheelStatus(int $status) {
    switch ($status) {
        case Wheel::STATUS_CREATED:
            return Html::tag('span', Html::tag("strong", Yii::t('app', 'to send')), ['class' => 'text-danger']);
        case Wheel::STATUS_SENT:
            return Html::tag('span', Yii::t('app', 'sent'), ['class' => 'text-info']);
        case Wheel::STATUS_RECEIVED:
            return Html::tag('span', Yii::t('app', 'received'), ['class' => 'text-success']);
        case Wheel::STATUS_IN_PROGRESS:
            return Html::tag('span', Yii::t('app', 'in progress'));
        case Wheel::STATUS_DONE:
            return Html::tag('span', Yii::t('app', 'done'));
        default:
            return Html::tag('span', Yii::t('app', 'error'));
    }
}

if (count($team->wheels) == 0) {
    return '';
}

$buttonId = 0;

$mail_icon = '<i class="fas fa-paper-plane"></i>';
$mail_icon2 = '<i class="far fa-paper-plane"></i>';
$mail_all_icon = '<i class="fas fa-paper-plane"></i><i class="fas fa-paper-plane"></i>';
$file_icon = '<span class="glyphicon glyphicon-file" aria-hidden="true"></span>';

?>

<div class="row col-md-12">
    <h2>
        <?= Yii::t('team', 'Wheels') ?>
    </h2>
    <table class="table table-striped table-hover">
        <tr>
            <td></td>
            <?php if ($team->teamType->level_0_enabled) { ?>
                <td class="text-center">
                    <h3>
                        <?= $team->teamType->level_0_name ?>
                        <?= SpinnerAnchor::widget([
                            'caption' => $mail_all_icon,
                            'url' => Url::to(['team/send-all-wheel', 'id' => $team->id, 'type' => Wheel::TYPE_INDIVIDUAL]),
                            'options' => [
                                'class' => "btn btn-default",
                                'title' => Yii::t('wheel', 'Send all by email'),
                            ],
                        ]) ?>
                    </h3>
                </td>
            <?php } ?>
            <?php if ($team->teamType->level_1_enabled) { ?>
                <td class="text-center">
                    <h3>
                        <?= $team->teamType->level_1_name ?>
                        <?= SpinnerAnchor::widget([
                            'caption' => $mail_all_icon,
                            'url' => Url::to(['team/send-all-wheel', 'id' => $team->id, 'type' => Wheel::TYPE_GROUP]),
                            'options' => [
                                'class' => "btn btn-default",
                                'title' => Yii::t('wheel', 'Send all by email'),
                            ],
                        ]) ?>
                    </h3>
                </td>
            <?php } ?>
            <?php if ($team->teamType->level_2_enabled) { ?>
                <td class="text-center">
                    <h3>
                        <?= $team->teamType->level_2_name ?>
                        <?= SpinnerAnchor::widget([
                            'caption' => $mail_all_icon,
                            'url' => Url::to(['team/send-all-wheel', 'id' => $team->id, 'type' => Wheel::TYPE_ORGANIZATIONAL]),
                            'options' => [
                                'class' => "btn btn-default",
                                'title' => Yii::t('wheel', 'Send all by email'),
                            ],
                        ]) ?>
                    </h3>
                </td>
            <?php } ?>
        </tr>
        <?php foreach ($team->members as $observerMember) { ?>
            <tr>
                <th style="text-align: right;">
                    <?= $observerMember->member->fullname ?>
                </th>
                <?php if ($team->teamType->level_0_enabled) {
                    $status = $team->getMemberStatus(Wheel::TYPE_INDIVIDUAL, $observerMember->member->id);
                    $progress = $team->getMemberProgress($observerMember->person_id, Wheel::TYPE_INDIVIDUAL);
                    $token = $team->getWheelToken($observerMember, Wheel::TYPE_INDIVIDUAL);
                    if ($token) {
                        ?>
                        <td id="cell_<?= $buttonId++ ?>" class="text-center"
                            data-token="<?= $token ?>">
                            <?php if ($progress != "100%") { ?>
                                <?= SpinnerAnchor::widget(['caption' => $mail_icon,
                                    'url' => Url::to(['team/send-wheel', 'id' => $team->id, 'memberId' => $observerMember->person_id, 'type' => Wheel::TYPE_INDIVIDUAL]),
                                    'options' => ['class' => "btn btn-default btn-xs ",
                                        'title' => Yii::t('wheel', 'Send by email')],]) ?>
                                <button type="button" class="btn btn-default btn-xs"
                                        onclick="showEmail('<?= $observerMember->member->fullname ?>', '<?= $observerMember->member->email ?>', '<?= Url::toRoute(['wheel/run', 'token' => $token], true) ?>', '<?= $token ?>');"
                                        title="<?= Yii::t('wheel', 'Manual email') ?>">
                                    <?= $mail_icon2 ?>
                                </button>
                                <?= $progress = "0%" && $status != Wheel::STATUS_IN_PROGRESS ? wheelStatus($status) : $progress ?>
                            <?php } else echo $progress ?>
                        </td class="text-center">
                    <?php }
                } ?>
                <?php if ($team->teamType->level_1_enabled) {
                    $status = $team->getMemberStatus(Wheel::TYPE_GROUP, $observerMember->member->id);
                    $progress = $team->getMemberProgress($observerMember->person_id, Wheel::TYPE_GROUP);
                    $token = $team->getWheelToken($observerMember, Wheel::TYPE_GROUP);
                    if ($token) {
                        ?>
                        <td id="cell_<?= $buttonId++ ?>" class="text-center"
                            data-token="<?= $token ?>">
                            <?php if ($progress != "100%") { ?>
                                <?= SpinnerAnchor::widget(['caption' => $mail_icon,
                                    'url' => Url::to(['team/send-wheel', 'id' => $team->id, 'memberId' => $observerMember->person_id, 'type' => Wheel::TYPE_GROUP]),
                                    'options' => ['class' => "btn btn-default btn-xs ",
                                        'title' => Yii::t('wheel', 'Send by email')],]) ?>
                                <button type="button" class="btn btn-default btn-xs"
                                        onclick="showEmail('<?= $observerMember->member->fullname ?>', '<?= $observerMember->member->email ?>', '<?= Url::toRoute(['wheel/run', 'token' => $token], true) ?>', '<?= $token ?>');"
                                        title="<?= Yii::t('wheel', 'Manual email') ?>">
                                    <?= $mail_icon2 ?>
                                </button>
                                <?= $progress = "0%" && $status != Wheel::STATUS_IN_PROGRESS ? wheelStatus($status) : $progress ?>
                            <?php } else echo $progress ?>
                        </td>
                    <?php }
                } ?>
                <?php if ($team->teamType->level_2_enabled) {
                    $status = $team->getMemberStatus(Wheel::TYPE_ORGANIZATIONAL, $observerMember->member->id);
                    $progress = $team->getMemberProgress($observerMember->person_id, Wheel::TYPE_ORGANIZATIONAL);
                    $token = $team->getWheelToken($observerMember, Wheel::TYPE_ORGANIZATIONAL);
                    if ($token) {
                        ?>
                        <td id="cell_<?= $buttonId++ ?>" class="text-center"
                            data-token="<?= $token ?>">
                            <?php if ($progress != "100%") { ?>
                                <?= SpinnerAnchor::widget(['caption' => $mail_icon,
                                    'url' => Url::to(['team/send-wheel', 'id' => $team->id, 'memberId' => $observerMember->person_id, 'type' => Wheel::TYPE_ORGANIZATIONAL]),
                                    'options' => ['class' => "btn btn-default btn-xs ",
                                        'title' => Yii::t('wheel', 'Send by email')],]) ?>
                                <button type="button" class="btn btn-default btn-xs"
                                        onclick="showEmail('<?= $observerMember->member->fullname ?>', '<?= $observerMember->member->email ?>', '<?= Url::toRoute(['wheel/run', 'token' => $token], true) ?>', '<?= $token ?>');"
                                        title="<?= Yii::t('wheel', 'Manual email') ?>">
                                    <?= $mail_icon2 ?>
                                </button>
                                <?= $progress = "0%" && $status != Wheel::STATUS_IN_PROGRESS ? wheelStatus($status) : $progress ?>
                            <?php } else echo $progress ?>
                        </td>
                    <?php }
                } ?>
            </tr>
        <?php } ?>
    </table>
</div>
<div class="clearfix"></div>
<div>
    <?= Html::a(\Yii::t('app', 'Refresh'), Url::to(['team/view', 'id' => $team->id,]), ['class' => 'btn btn-default']) ?>
    <?= Html::a(\Yii::t('team', 'Go to dashboard...'),
        Url::to(['team/go-to-dashboard', 'id' => $team->id,]), ['class' => 'btn btn-success'])
    ?>
    <?= Html::a(\Yii::t('team', 'Go to report...'),
        Url::to(['report/view', 'id' => $team->id,]), ['class' => 'btn btn-primary'])
    ?>
    <?= Html::a(\Yii::t('app', 'Download PPT'), Url::to(['report/presentation', 'id' => $team->id]), ['class' => 'btn btn-success']) ?>
    <?= Html::a(\Yii::t('app', 'Download DOC'), Url::to(['report/word', 'id' => $team->id]), ['class' => 'btn btn-primary']) ?>
</div>
<div class="clearfix"></div>
<div class="row  col-md-12">
    <h3><?= Yii::t('wheel', 'Wheel answers') ?></h3>
    <?php
    $form = ActiveForm::begin([
        'id' => 'manual-wheel-form',
        'action' => ['manual-wheel', 'teamId' => $team->id],
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-6\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ]);
    ?>
    <?= $form->field($manualWheel, 'observer_id')->widget(Select2::classname(), [
        'data' => $team->getMemberList(),
    ]) ?>
    <?= $form->field($manualWheel, 'observed_id')->widget(Select2::classname(), [
        'data' => $team->getMemberList(),
    ]) ?>
    <?= $form->field($manualWheel, 'wheel_type')->widget(Select2::classname(), [
        'data' => Wheel::getWheelTypes(),
    ]) ?>
    <div class="form-group">
        <div class="col-lg-push-2 col-lg-6">
            <?= SpinnerSubmitButton::widget([
                'caption' => \Yii::t('app', 'Edit'),
                'options' => [
                    'name' => 'edit',
                    'value'  =>'1',
                    'class' => 'btn btn-primary'
                ]
            ]) ?>
            <?= SpinnerSubmitButton::widget([
                'caption' => \Yii::t('app', 'Redo'),
                'options' => [
                    'name' => 'redo',
                    'value'  =>'1',
                    'class' => 'btn btn-danger'
                ]
            ]) ?>
        </div>
    </div>
    <?= $form->field($manualWheel, 'team_id')->hiddenInput()->label("") ?>
    <?php ActiveForm::end(); ?>
</div>
<div class="clearfix"></div>
<?php
Modal::begin(['id' => 'email_modal',
    'header' => '<h4>' . Yii::t('team', 'Email to send') . '</h4>',
    'size' => Modal::SIZE_LARGE,]);
?>
<div>
    <h3><?= Yii::t('team', 'Please send this email') ?></h3>
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
<script type="text/javascript">
    function showEmail(member, email, url, token) {
        $('#email_modal').modal('show');
        $('#member').html(member);
        $('#member_email').html(email);
        $('#url').html(url);
    }
</script>
