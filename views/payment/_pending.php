<?php
if ($model->status == Payment::STATUS_INIT) {
    $this->registerJs("setTimeout(function(){ window.location.reload(1); }, 5000);", View::POS_END, 'refresh-page');
}
?>

<h3><?= Yii::t('payment', 'Please, wait in this page.') ?></h3>
<h3><?= Yii::t('payment', "We are waiting payment broker confirmation.") ?></h3>
<br>
<br>
<img src="images/red-loading.gif" />

