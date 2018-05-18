<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;

?>
<h3><?= Yii::t('user', 'Persons to transfer') ?></h3>
<?php
$dataProvider = new ActiveDataProvider([
    'query' => $persons,
    'pagination' => false,
]);
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'fullname',
        'shortname',
    ],
]);
?>
