<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Log extends ActiveRecord {

    public function __construct() {
        $this->datetime = date('Y-m-d H:i:s');
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%log}}';
    }

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['text', 'datetime'], 'required'],
        ];
    }

    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels() {
        return [
            'datetime' => Yii::t('log', 'Date Time'),
            'coach' => Yii::t('log', 'Coach'),
            'text' => Yii::t('log', 'Text'),
        ];
    }

    public static function browse() {
        return Log::find()
                        ->where(['coach_id' => Yii::$app->user->id])
                        ->orderBy('id desc');
    }

    public function getCoach() {
        return $this->hasOne(User::className(), ['id' => 'coach_id']);
    }

}
