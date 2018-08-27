<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;

?>
<h3><?= Yii::t('user', 'Companies to transfer') ?></h3>
<?php
$dataProvider = new ActiveDataProvider([
    'query' => $companies,
    'pagination' => false,
]);
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'name',
    ],
]);
?>
