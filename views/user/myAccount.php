<?php

$this->title = Yii::t('user', 'My Data');
$this->params['breadcrumbs'][] = $this->title;
?>
<?=

$this->render('_form', [
    'user' => $user,
    'return' => $return,
])
?>