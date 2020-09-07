<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "team_type_dimension".
 *
 * @property int $id
 * @property int $team_type_id
 * @property string $name
 * @property string $description
 * @property int $order
 * @property int $level
 *
 * @property TeamType $teamType
 */
class TeamTypeDimension extends \yii\db\ActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'team_type_dimension';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['team_type_id', 'name', 'order', 'level'], 'required'],
            [['team_type_id', 'order', 'level'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['team_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => TeamType::class, 'targetAttribute' => ['team_type_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'team_type_id' => Yii::t('team', 'Team Type'),
            'name' => Yii::t('app', 'Name'),
            'order' => Yii::t('app', 'Order'),
            'level' => Yii::t('app', 'Level'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeamType() {
        return $this->hasOne(TeamType::class, ['id' => 'team_type_id']);
    }
}
