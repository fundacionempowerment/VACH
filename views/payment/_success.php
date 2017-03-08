<?php

use yii\helpers\Html;
?>

<h3 class="text-success"><?= Yii::t('payment', 'Payment successfull!') ?></h3>
<br>
<br>
<?= Html::a(Yii::t('stock', 'Go to My Licences'), ['/stock'], ['class' => 'btn btn-success']) ?>

