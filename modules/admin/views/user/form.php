<?php

$this->title = $user->id == 0 ? Yii::t('user', 'New user') : $user->fullname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['user/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=

$this->render('_form', [
    'user' => $user,
])
?>