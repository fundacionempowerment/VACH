<?php

use app\models\Wheel;
use yii\db\Migration;

/**
 * Class m181201_132648_populate_team_type_dimension
 */
class m181201_132648_populate_team_type_dimension extends Migration {
    /**
     * {@inheritdoc}
     * @throws \yii\db\Exception
     */
    public function safeUp() {
        $teamTypes = $this->db->createCommand("SELECT * FROM `team_type`")->queryAll();

        foreach ($teamTypes as $teamType) {
            $this->insertDimension($teamType['id']);
        }
    }

    private function insertDimension($team_type_id) {
        $data = $this->getData();
        foreach ($data as $level => $level_data) {
            foreach ($level_data as $order => $dimension) {
                $this->insert('team_type_dimension', [
                    'team_type_id' => $team_type_id,
                    'order' => $order,
                    'level' => $level,
                    'name' => $dimension,
                ]);
            }
        }
    }

    private function getData() {
        return [
            Wheel::TYPE_INDIVIDUAL => [
                Yii::t('wheel', 'Free time'),
                Yii::t('wheel', 'Work'),
                Yii::t('wheel', 'Family'),
                Yii::t('wheel', 'Physical Dimension'),
                Yii::t('wheel', 'Emotional Dimension'),
                Yii::t('wheel', 'Mental Dimension'),
                Yii::t('wheel', 'Existential Dimension'),
                Yii::t('wheel', 'Spiritual Dimension'),
            ],
            Wheel::TYPE_GROUP => [
                Yii::t('wheel', 'Initiative'),
                Yii::t('wheel', 'Appropriateness'),
                Yii::t('wheel', 'Belonging'),
                Yii::t('wheel', 'Team work'),
                Yii::t('wheel', 'Flexibility'),
                Yii::t('wheel', 'Communication'),
                Yii::t('wheel', 'Leadership'),
                Yii::t('wheel', 'Legitimation'),
            ],
            Wheel::TYPE_ORGANIZATIONAL => [
                Yii::t('wheel', 'Creativity'),
                Yii::t('wheel', 'Results guidance'),
                Yii::t('wheel', 'Client guidance'),
                Yii::t('wheel', 'Quality guidance'),
                Yii::t('wheel', 'Conflict resolution'),
                Yii::t('wheel', 'Change Management'),
                Yii::t('wheel', 'Strategic vision'),
                Yii::t('wheel', 'Identity'),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->delete('team_type_dimension');
    }

}
