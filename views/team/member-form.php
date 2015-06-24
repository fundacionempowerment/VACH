<?php

$this->title = $member->id == 0 ? Yii::t('team', 'New member') : $member->fullname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('team', 'My Teams'), 'url' => ['/team']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=

$this->render('../coachee/_form', [
    'coachee' => $member,
])
?>