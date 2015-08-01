<?php

$this->title = Yii::t('user', 'My Account');
$this->params['breadcrumbs'][] = $this->title;
?>
<?=

$this->render('_form', [
    'user' => $user,
    'return' => $return,
])
?>