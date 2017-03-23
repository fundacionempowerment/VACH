<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;
use app\models\WheelQuestion;
use yii\bootstrap\Progress;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

if (!empty($assessment) && $assessment->version == 2) {
    $version = 2;
} else {
    $version = 1;
}

$this->title = Yii::t('dashboard', 'Dashboard');

$this->params['breadcrumbs'][] = $this->title;
?>
<script>
    var relations = new Array();
    var forwardRelationsData = new Array();
    var backwardRelationsData = new Array();
</script>
<div class="dashboard">
    <script src="<?= Url::to('@web/js/relations.js') ?>"></script>
    <h1><?= Html::encode($this->title) ?></h1>
    <?php
    echo $this->render('_filter', [
        'filter' => $filter,
        'companies' => $companies,
        'teams' => $teams,
        'assessments' => $assessments,
        'members' => $members,
    ]);

    if ($filter->wheelType == Wheel::TYPE_INDIVIDUAL) {
        echo $this->render('_radar', [
            'assessmentId' => $filter->assessmentId,
            'memberId' => $filter->memberId,
            'wheelType' => Wheel::TYPE_INDIVIDUAL,
        ]);
    }

    if ($filter->wheelType == Wheel::TYPE_INDIVIDUAL) {
        echo $this->render('_radar', [
            'assessmentId' => $filter->assessmentId,
            'memberId' => $filter->memberId,
            'wheelType' => Wheel::TYPE_GROUP,
        ]);
    }

    if (count($projectedGroupWheel) > 0 && count($reflectedGroupWheel) > 0) {
        echo $this->render('_lineal', [
            'assessmentId' => $filter->assessmentId,
            'memberId' => $filter->memberId,
            'wheelType' => Wheel::TYPE_GROUP,
        ]);
    }

    if ($filter->wheelType == Wheel::TYPE_INDIVIDUAL) {
        echo $this->render('_radar', [
            'assessmentId' => $filter->assessmentId,
            'memberId' => $filter->memberId,
            'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
        ]);
    }

    if (count($projectedOrganizationalWheel) > 0 && count($reflectedOrganizationalWheel) > 0) {
        echo $this->render('_lineal', [
            'assessmentId' => $filter->assessmentId,
            'memberId' => $filter->memberId,
            'wheelType' => Wheel::TYPE_ORGANIZATIONAL,
        ]);
    }

    if (count($gauges) > 0)
        echo $this->render('_gauges', [
            'gauges' => $gauges,
            'type' => $filter->wheelType,
            'member' => $member,
        ]);


    if (count($relationsMatrix) > 0) {
        echo $this->render('_number_matrix', [
            'data' => $relationsMatrix,
            'members' => $members,
            'type' => $filter->wheelType,
            'memberId' => $filter->memberId,
            'member' => $member,
            'assessment' => $assessment,
        ]);
    }

    if (count($performanceMatrix) > 0) {
        echo $this->render('_matrix', [
            'assessmentId' => $filter->assessmentId,
            'memberId' => $filter->memberId,
            'wheelType' => $filter->wheelType,
        ]);
    }

    if (count($relationsMatrix) > 0) {
        echo $this->render('_relation', [
            'assessmentId' => $filter->assessmentId,
            'memberId' => $filter->memberId,
            'wheelType' => $filter->wheelType,
        ]);
        echo $this->render('_relation_table', [
            'data' => $relationsMatrix,
            'members' => $members,
            'type' => $filter->wheelType,
            'memberId' => $filter->memberId,
            'member' => $member,
        ]);
    }

    if (count($emergents))
        echo $this->render('_emergents', [
            'data' => $relationsMatrix,
            'members' => $members,
            'memberId' => $filter->memberId,
            'emergents' => $emergents,
            'type' => $filter->wheelType,
            'member' => $member,
        ]);
    echo $this->render('_detailed_emergents', [
        'data' => $relationsMatrix,
        'members' => $members,
        'memberId' => $filter->memberId,
        'emergents' => $emergents,
        'type' => $filter->wheelType,
        'member' => $member,
    ]);
    ?>
</div>
<script>
    window.onload = function () {
        for (var i in relations) {
            doForwardRelations(document.getElementById("canvas" + relations[i] + 'f').getContext("2d"), forwardRelationsData[i]);
            doBackwardRelations(document.getElementById("canvas" + relations[i] + 'b').getContext("2d"), backwardRelationsData[i]);
        }
    }
</script>
<script src="<?= Url::to('@web/js/html2canvas/html2canvas.js') ?>"></script>
<script src="<?= Url::to('@web/js/FileSaver.js') ?>"></script>
<script>
    function printDiv(div)
    {
        html2canvas(document.querySelector("#" + div)).then(function (canvas) {
            canvas.toBlob(function (blob) {
                saveAs(blob, "image.png");
            }, "image/png");
        });

    }
</script>
