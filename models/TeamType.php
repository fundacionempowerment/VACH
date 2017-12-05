<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "team_type".
 *
 * @property integer $id
 * @property string $name
 * @property integer $product_id
 *
 * @property Team[] $teams
 * @property Product $product
 * @property WheelQuestion[] $wheelQuestions
 */
class TeamType extends \yii\db\ActiveRecord
{

    public $deletable;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'team_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'product_id'], 'required'],
            [['product_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => Yii::t('app', 'Name'),
            'product_id' => Yii::t('team', 'Licence Type'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function browse()
    {
        return TeamType::find()->orderBy('name');
    }

    public function afterFind()
    {
        $this->deletable = $this->getTeams()->count() == 0;

        return parent::afterFind();
    }

    public function beforeDelete()
    {
        WheelQuestion::deleteAll(['team_type_id' => $this->id]);

        return parent::beforeDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeams()
    {
        return $this->hasMany(Team::className(), ['team_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWheelQuestions()
    {
        return $this->hasMany(WheelQuestion::className(), ['team_type_id' => 'id']);
    }

    public static function getList()
    {
        return \yii\helpers\ArrayHelper::map(static::browse()->all(), 'id', 'name');
    }

}
