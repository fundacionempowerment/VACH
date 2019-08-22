<?php

use app\components\SpinnerAnchor;
use app\models\Wheel;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

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
                                'data-confirm' => Yii::t('wheel', 'Are you sure you want to send all wheels?'),
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
                                'data-confirm' => Yii::t('wheel', 'Are you sure you want to send all wheels?'),
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
                                'data-confirm' => Yii::t('wheel', 'Are you sure you want to send all wheels?'),
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
                    <?= SpinnerAnchor::widget(['caption' => $mail_icon . $team->notifyIcon(Wheel::TYPE_INDIVIDUAL, $observerMember->member->id),
                        'url' => Url::to(['team/send-wheel', 'id' => $team->id, 'memberId' => $observerMember->person_id, 'type' => Wheel::TYPE_INDIVIDUAL]),
                        'options' => ['class' => "btn btn-default btn-xs ",
                            'title' => Yii::t('wheel', 'Send by email')],]) ?>
                    <?php
                    foreach ($team->individualWheels as $wheel)
                        if ($wheel->observer_id == $observerMember->person_id) {
                            ?>
                            <button type="button" class="btn btn-default btn-xs"

                                    onclick="showEmail('<?= $observerMember->member->fullname ?>', '<?= $observerMember->member->email ?>', '<?= Url::toRoute(['wheel/run', 'token' => $wheel->token], true) ?>', '<?= $wheel->token ?>');"
                                    title="<?= Yii::t('wheel', 'Manual email') ?>">
                                <?= $mail_icon2 ?>
                            </button>
                        <?php } ?>
                </th>
                <?php if ($team->teamType->level_0_enabled) { ?>
                    <td id="cell_<?= $buttonId++ ?>" class="text-center" data-token="<?= $team->getWheelToken($observerMember, Wheel::TYPE_INDIVIDUAL) ?>">
                        <?= $team->getMemberProgress($observerMember, Wheel::TYPE_INDIVIDUAL) ?>
                    </td class="text-center">
                <?php } ?>
                <?php if ($team->teamType->level_1_enabled) { ?>
                    <td id="cell_<?= $buttonId++ ?>" class="text-center" data-token="<?= $team->getWheelToken($observerMember, Wheel::TYPE_GROUP) ?>">
                        <?= $team->getMemberProgress($observerMember, Wheel::TYPE_GROUP) ?>
                    </td>
                <?php } ?>
                <?php if ($team->teamType->level_2_enabled) { ?>
                    <td id="cell_<?= $buttonId++ ?>" class="text-center" data-token="<?= $team->getWheelToken($observerMember, Wheel::TYPE_ORGANIZATIONAL) ?>" >
                        <?= $team->getMemberProgress($observerMember, Wheel::TYPE_ORGANIZATIONAL) ?>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
    </table>
</div>
<div class="clearfix"></div>
<div>
    <?= Html::a(\Yii::t('app', 'Refresh'), Url::to(['team/view', 'id' => $team->id,]), ['class' => 'btn btn-default']) ?>
    <?= Html::a(\Yii::t('team', 'Go to dashboard...'),
        Url::to(['team/go-to-dashboard', 'id' => $team->id,]), ['class' => ($team->wheelsCompleted ? 'btn btn-success' : 'btn btn-default')])
    ?>
    <?= Html::a(\Yii::t('team', 'Go to report...'),
        Url::to(['report/view', 'id' => $team->id,]), ['class' => ($team->wheelsCompleted ? 'btn btn-success' : 'btn btn-default')])
    ?>
</div>
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