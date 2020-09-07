<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "team_type".
 *
 * @property integer $id
 * @property string $name
 * @property integer $product_id
 * @property string $level_0_name
 * @property string $level_1_name
 * @property string $level_2_name
 * @property boolean $level_0_enabled
 * @property boolean $level_1_enabled
 * @property boolean $level_2_enabled
 *
 * @property Team[] $teams
 * @property Product $product
 * @property WheelQuestion[] $wheelQuestions
 * @property WheelQuestion[] $rawWheelQuestions
 * @property TeamTypeDimension[] $dimensions
 * @property string[] $dimensionList
 */
class TeamType extends \yii\db\ActiveRecord {

    public $deletable;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'team_type';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'product_id'], 'required'],
            [['level_0_name', 'level_0_enabled', 'level_1_name', 'level_1_enabled', 'level_2_name', 'level_2_enabled'], 'required'],
            [['product_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => Yii::t('app', 'Name'),
            'product_id' => Yii::t('team', 'Licence Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function browse() {
        return TeamType::find()->orderBy('name');
    }

    public function afterFind() {
        $this->deletable = $this->getTeams()->count() == 0;

        return parent::afterFind();
    }

    public function beforeDelete() {
        WheelQuestion::deleteAll(['team_type_id' => $this->id]);

        return parent::beforeDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeams() {
        return $this->hasMany(Team::class, ['team_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct() {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDimensions() {
        return $this->hasMany(TeamTypeDimension::class, ['team_type_id' => 'id']);
    }

    public function getWheelQuestions() {
        return WheelQuestion::find()
            ->innerJoin('team_type', 'team_type.id = wheel_question.team_type_id')
            ->where(['team_type_id' => $this->id])
            ->andWhere('
            (team_type.level_0_enabled = 1 and wheel_question.type = 0) OR
            (team_type.level_1_enabled = 1 and wheel_question.type = 1) OR
            (team_type.level_2_enabled = 1 and wheel_question.type = 2)
            ')
            ->orderBy('type, dimension, order')
            ->all();
    }

    public function getRawWheelQuestions() {
        return WheelQuestion::find()
            ->innerJoin('team_type', 'team_type.id = wheel_question.team_type_id')
            ->where(['team_type_id' => $this->id])
            ->orderBy('type, dimension, order')
            ->all();
    }

    public static function getList() {
        return \yii\helpers\ArrayHelper::map(static::browse()->all(), 'id', 'name');
    }

    public function isLevelEnabled($type) {
        if ($type == Wheel::TYPE_INDIVIDUAL) {
            return $this->level_0_enabled;
        } else if ($type == Wheel::TYPE_GROUP) {
            return $this->level_1_enabled;
        } else {
            return $this->level_2_enabled;
        }
    }

    public function levelName($level) {
        if ($level == Wheel::TYPE_INDIVIDUAL) {
            return $this->level_0_name;
        } else if ($level == Wheel::TYPE_GROUP) {
            return $this->level_1_name;
        } else {
            return $this->level_2_name;
        }
    }

    /**
     * @return array
     */
    public function getWheelTypeList() {
        $result = [];

        if ($this->level_0_enabled) {
            $result[0] = $this->level_0_name;
        }
        if ($this->level_1_enabled) {
            $result[1] = $this->level_1_name;
        }
        if ($this->level_2_enabled) {
            $result[2] = $this->level_2_name;
        }

        return $result;
    }

    public function getDimensionNames($level, $short = false) {
        $dimensions = [];
        foreach ($this->getDimensions()->where(['level' =>$level])->all() as $d ) {
            $dimensions[] = $d->name;
        };

        if ($short) {
            for ($i = 0; $i < count($dimensions); $i++) {
                $dimensions[$i] = str_replace('Orientación', 'O.', $dimensions[$i]);
                $dimensions[$i] = str_replace('Orientation', 'O.', $dimensions[$i]);
                $dimensions[$i] = str_replace('Resolución', 'Res.', $dimensions[$i]);
                $dimensions[$i] = str_replace('Dimensión', 'D.', $dimensions[$i]);
                $dimensions[$i] = str_replace('Gestión', 'G.', $dimensions[$i]);
                $dimensions[$i] = str_replace('Visión', 'V.', $dimensions[$i]);
                $dimensions[$i] = str_replace('estratégica', 'estrat.', $dimensions[$i]);
            }
        }

        return $dimensions;
    }
}
