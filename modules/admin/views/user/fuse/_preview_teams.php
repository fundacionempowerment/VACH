<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<h3><?= Yii::t('user', 'Teams to transfer') ?></h3>
<?php
$dataProvider = new ActiveDataProvider([
    'query' => $teams,
    'pagination' => false,
]);
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'name',
        [
            'attribute' => 'team_type_id',
            'format' => 'html',
            'value' => function ($data) {
                return $data->teamType->name;
            },
        ],
        [
            'attribute' => 'IndividualWheelStatus',
        ],
        [
            'attribute' => 'GroupWheelStatus',
        ],
        [
            'attribute' => 'OrganizationalWheelStatus',
        ],
    ],
]);
?>

<h3><?= Yii::t('user', 'Team invitations to transfer') ?></h3>
<?php
$dataProvider = new ActiveDataProvider([
    'query' => $teamInvitations,
    'pagination' => false,
]);
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'team.name',
    ],
]);
?>
