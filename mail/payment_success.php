<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Wheel;
?>
<p>
    <?= 'Estimado/a ' . $model->coach->name . ',' ?>
</p>
<p>
    Su pago ha sido aceptado con éxito.
</p>
<p>
    El número de transacción es <?= $model->uuid ?>.<br />
    Concepto <?= $model->concept ?>.<br />
    Monto <?= Yii::$app->formatter->asCurrency($model->amount) ?>.
</p>
<?= $this->render('_footer') ?>
