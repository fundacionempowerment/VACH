<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;
use yii\bootstrap\Modal;
use app\components\Icons;

if (count($team->wheels) == 0) {
    return '';
}

$buttonId = 0;

$mail_icon = '<span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>';
$file_icon = '<span class="glyphicon glyphicon-file" aria-hidden="true"></span>';

$wheels_completed = true;
foreach ($team->individualWheels as $wheel)
    if ($wheel->answerStatus != '100%') {
        $wheels_completed = false;
        break;
    }
if ($wheels_completed)
    foreach ($team->groupWheels as $wheel)
        if ($wheel->answerStatus != '100%') {
            $wheels_completed = false;
            break;
        }
if ($wheels_completed)
    foreach ($team->organizationalWheels as $wheel)
        if ($wheel->answerStatus != '100%') {
            $wheels_completed = false;
            break;
        }
?>
<div class="row col-md-6">
    <h2>
        <?= Yii::t('team', 'Individual wheels') ?>
        <button id="cell_individual" type="button" class="btn btn-default btn-xs" onclick="showTokens('individual_modal');" >
            <span class="glyphicon glyphicon-phone" aria-hidden="true"></span>
        </button>
    </h2>
    <table class="table table-bordered table-hover">
        <?php foreach ($team->members as $observerMember) { ?>
            <tr>
                <th style="text-align: right;">
                    <?= $observerMember->member->fullname ?>
                    <?= Html::a($mail_icon . $team->notifyIcon(Wheel::TYPE_INDIVIDUAL, $observerMember->member->id), Url::to(['team/send-wheel', 'id' => $team->id, 'memberId' => $observerMember->person_id, 'type' => Wheel::TYPE_INDIVIDUAL]), ['class' => "btn btn-default btn-xs ", 'title' => Yii::t('wheel', 'Send by email')]) ?>

                    <?php
                    foreach ($team->individualWheels as $wheel)
                        if ($wheel->observer_id == $observerMember->person_id) {
                            ?>
                            <button id="cell_<?= $buttonId ?>" type="button" class="btn btn-default btn-xs" onclick="showEmail('<?= $observerMember->member->fullname ?>', '<?= $observerMember->member->email ?>', '<?= Url::toRoute(['wheel/run', 'token' => $wheel->token], true) ?>', '<?= $wheel->token ?>');" title="<?= Yii::t('wheel', 'Manual email') ?>">
                                <?= $mail_icon ?>!
                            </button>
                            <?= Html::a(Icons::PLAY, Url::toRoute(['wheel/run', 'token' => $wheel->token]), ['class' => 'btn btn-default btn-xs', 'target' => '_blank']); ?>
                            <?php
                            $buttonId++;
                            break;
                        }
                    ?>
                </th>
                <td>
                    <?php
                    foreach ($team->individualWheels as $wheel)
                        if ($wheel->observer_id == $observerMember->person_id) {
                            echo $wheel->answerStatus . '&nbsp;';
                            echo Html::a($file_icon, Url::to(['wheel/manual-form', 'id' => $wheel->id]), ['class' => 'btn btn-default btn-xs']) . '&nbsp;';
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
        <?= Yii::t('team', 'Group wheels') ?>
        <button id="cell_individual" type="button" class="btn btn-default btn-xs" onclick="showTokens('group_modal');" >
            <span class="glyphicon glyphicon-phone" aria-hidden="true"></span>
        </button>
    </h2>
    <table width="100%" class="table table-bordered table-hover">
        <tr>
            <th style="text-align: right;">
                <?= Yii::t('wheel', "Observer \\ Observed") ?>
            </th>
            <?php foreach ($team->members as $teamMember): ?>
                <th>
                    <?= $teamMember->member->fullname ?>
                </th>
            <?php endforeach; ?>
        </tr>
        <?php foreach ($team->members as $observerMember) { ?>
            <tr>
                <th style="text-align: right;">
                    <?= $observerMember->member->fullname ?>
                    <?= Html::a($mail_icon . $team->notifyIcon(Wheel::TYPE_GROUP, $observerMember->member->id), Url::to(['team/send-wheel', 'id' => $team->id, 'memberId' => $observerMember->person_id, 'type' => Wheel::TYPE_GROUP]), ['class' => 'btn btn-default btn-xs', 'title' => Yii::t('wheel', 'Send by email')]) ?>
                    <button id="cell_<?= $buttonId ?>" type="button" class="btn btn-default btn-xs" onclick="showEmail('<?= $observerMember->member->fullname ?>', '<?= $observerMember->member->email ?>', '<?= Url::toRoute(['wheel/run', 'token' => $wheel->token], true) ?>', '<?= $wheel->token ?>');" title="<?= Yii::t('wheel', 'Manual email') ?>">
                        <?php
                        foreach ($team->groupWheels as $wheel)
                            if ($wheel->observer_id == $observerMember->person_id) {
                                ?>
                                <?= $mail_icon ?>!
                            </button>
                            <?= Html::a(Icons::PLAY, Url::toRoute(['wheel/run', 'token' => $wheel->token]), ['class' => 'btn btn-default btn-xs', 'target' => '_blank']); ?>
                            <?php
                            $buttonId++;
                            break;
                        }
                    ?>
                </th>
                <?php foreach ($team->members as $observedMember) { ?>
                    <td>
                        <?php
                        foreach ($team->groupWheels as $wheel)
                            if ($wheel->observer_id == $observerMember->person_id && $wheel->observed_id == $observedMember->person_id) {
                                echo $wheel->answerStatus . '&nbsp;';
                                echo Html::a($file_icon, Url::to(['wheel/manual-form', 'id' => $wheel->id]), ['class' => 'btn btn-default btn-xs']) . '&nbsp;';
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
        <?= Yii::t('team', 'Organizational wheels') ?>
        <button id="cell_individual" type="button" class="btn btn-default btn-xs" onclick="showTokens('organizational_modal');" >
            <span class="glyphicon glyphicon-phone" aria-hidden="true"></span>
        </button>
    </h2>
    <table width="100%" class="table table-bordered table-hover">
        <tr>
            <th style="text-align: right;">
                <?= Yii::t('wheel', "Observer \\ Observed") ?>
            </th>
            <?php foreach ($team->members as $teamMember): ?>
                <th>
                    <?= $teamMember->member->fullname ?>
                </th>
            <?php endforeach; ?>
        </tr>
        <?php foreach ($team->members as $observerMember) { ?>
            <tr>
                <th style="text-align: right;">
                    <?= $observerMember->member->fullname ?>
                    <?= Html::a($mail_icon . $team->notifyIcon(Wheel::TYPE_ORGANIZATIONAL, $observerMember->member->id), Url::to(['team/send-wheel', 'id' => $team->id, 'memberId' => $observerMember->person_id, 'type' => Wheel::TYPE_ORGANIZATIONAL]), ['class' => 'btn btn-default btn-xs', 'title' => Yii::t('wheel', 'Send by email')]) ?>
                    <?php
                    foreach ($team->organizationalWheels as $wheel)
                        if ($wheel->observer_id == $observerMember->person_id) {
                            ?>
                            <button id="cell_<?= $buttonId ?>" type="button" class="btn btn-default btn-xs" onclick="showEmail('<?= $observerMember->member->fullname ?>', '<?= $observerMember->member->email ?>', '<?= Url::toRoute(['wheel/run', 'token' => $wheel->token], true) ?>', '<?= $wheel->token ?>');"  title="<?= Yii::t('wheel', 'Manual email') ?>">
                                <?= $mail_icon ?>!
                            </button>
                            <?= Html::a(Icons::PLAY, Url::toRoute(['wheel/run', 'token' => $wheel->token]), ['class' => 'btn btn-default btn-xs', 'target' => '_blank']); ?>
                            <?php
                            $buttonId++;
                            break;
                        }
                    ?>
                </th>
                <?php foreach ($team->members as $observedMember) { ?>
                    <td>
                        <?php
                        foreach ($team->organizationalWheels as $wheel)
                            if ($wheel->observer_id == $observerMember->person_id && $wheel->observed_id == $observedMember->person_id) {
                                echo $wheel->answerStatus . '&nbsp;';
                                echo Html::a($file_icon, Url::to(['wheel/manual-form', 'id' => $wheel->id]), ['class' => 'btn btn-default btn-xs']) . '&nbsp;';
                            }
                        ?>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
    </table>
    <?= Html::a(\Yii::t('app', 'Refresh'), Url::to(['team/view', 'id' => $team->id,]), ['class' => 'btn btn-default']) ?>
    <?=
    Html::a(\Yii::t('team', 'Go to dashboard...'), Url::to(['team/go-to-dashboard', 'id' => $team->id,]), [
        'class' => ($wheels_completed ? 'btn btn-success' : 'btn btn-default')
    ])
    ?>
    <?=
    Html::a(\Yii::t('team', 'Go to report...'), Url::to(['report/view', 'id' => $team->id,]), [
        'class' => ($wheels_completed ? 'btn btn-success' : 'btn btn-default')
    ])
    ?>
</div>
<?php
Modal::begin([
    'id' => 'email_modal',
    'header' => '<h4>' . Yii::t('team', 'Email to send') . '</h4>',
    'size' => Modal::SIZE_LARGE,
]);
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
        <?php if (YII_ENV_TEST) { ?>
            <span id="token"></span>
        <?php } ?>
    </h3>
</div>
<?php Modal::end(); ?>
<?php
Modal::begin([
    'id' => 'individual_modal',
    'header' => '<h4>' . Yii::t('team', 'Run on smartphones') . '</h4>',
    'size' => Modal::SIZE_LARGE,
]);
?>
<div class="text-center">
    <h3><?= Yii::t('team', 'Please ask the audience to enter this site and token in his/her phone browser') ?></h3>
    <h2><?= Url::to('@web/', true); ?></h2>
    <table align="center" class="table table-bordered table-hover" style="width: 50%; font-size: 18px;">
        <?php foreach ($team->members as $observerMember) { ?>
            <tr>
                <td style="text-align: right">
                    <?= $observerMember->member->fullname ?>:
                </td>
                <td>
                    <?php
                    foreach ($team->individualWheels as $wheel)
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
    'header' => '<h4>' . Yii::t('team', 'Run on smartphones') . '</h4>',
    'size' => Modal::SIZE_LARGE,
]);
?>
<div class="text-center">
    <h3><?= Yii::t('team', 'Please ask the audience to enter this site and token in his/her phone browser') ?></h3>
    <h2><?= Url::to('@web/', true); ?></h2>
    <table align="center" class="table table-bordered table-hover" style="width: 50%; font-size: 18px;">
        <?php foreach ($team->members as $observerMember) { ?>
            <tr>
                <td style="text-align: right">
                    <?= $observerMember->member->fullname ?>:
                </td>
                <td>
                    <?php
                    foreach ($team->groupWheels as $wheel)
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
    'header' => '<h4>' . Yii::t('team', 'Run on smartphones') . '</h4>',
    'size' => Modal::SIZE_LARGE,
]);
?>
<div class="text-center">
    <h3><?= Yii::t('team', 'Please ask the audience to enter this site and token in his/her phone browser') ?></h3>
    <h2><?= Url::to('@web/', true); ?></h2>
    <table align="center" class="table table-bordered table-hover" style="width: 50%; font-size: 18px;">
        <?php foreach ($team->members as $observerMember) { ?>
            <tr>
                <td style="text-align: right">
                    <?= $observerMember->member->fullname ?>:
                </td>
                <td>
                    <?php
                    foreach ($team->organizationalWheels as $wheel)
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
<script type="text/javascript">
    function showEmail(member, email, url, token)
    {
        $('#email_modal').modal('show');
        $('#member').html(member);
        $('#member_email').html(email);
        $('#url').html(url);
<?php if (YII_ENV_TEST) { ?>
            $('#token').html(token);
<?php } ?>
    }

    function showTokens(modal)
    {
        $('#' + modal).modal('show');
    }
</script>