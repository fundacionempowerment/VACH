<?php

use yii\widgets\DetailView;
?>
<p>
    Dear administrator, a new team has been fulfilled:
</p>
<?=
DetailView::widget([
    'model' => $team,
    'attributes' => [
        [
            'attribute' => 'name',
            'value' => function ($data) {
                return $data->fullname;
            },
        ],
        [
            'attribute' => 'members',
            'value' => function ($data) {
                return count($data->members);
            },
        ],
        [
            'attribute' => 'team_type_id',
            'format' => 'html',
            'value' => function ($data) {
                return $data->teamType->name;
            },
        ],
    ],
])
?>
<p>
    Thanks,<br>
    VACH
</p>