<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;

?>
<h3><?= Yii::t('user', 'Payments to transfer') ?></h3>
<?php
$dataProvider = new ActiveDataProvider([
    'query' => $payments,
    'pagination' => false,
]);
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'uuid',
        'stamp:datetime',
        'concept',
        [
            'attribute' => 'amount',
            'class' => 'yii\grid\DataColumn', // can be omitted, as it is the default
            'value' => function ($data) {
                return $data['currency'] . ' $' . $data['amount'];
            },
        ],
        'statusName',
    ],
]);
?>
