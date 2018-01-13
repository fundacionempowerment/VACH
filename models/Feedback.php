<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;
use \yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Feedback extends ActiveRecord
{

    public function __construct()
    {
        $this->ip = Yii::$app->request->userIP;
        $this->datetime = date("Y-m-d H:i:s");
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['ip',
            'effectiveness',
            'efficience',
            'satisfaction',
            'comment',
            'datetime'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'ip' => Yii::t('feedback', 'IP'),
            'effectiveness' => Yii::t('feedback', 'Effectiveness'),
            'efficience' => Yii::t('feedback', 'Efficience'),
            'satisfaction' => Yii::t('feedback', 'Satisfaction'),
            'comment' => Yii::t('feedback', 'Comment'),
            'datetime' => Yii::t('app', 'Date and Time'),
        ];
    }

    public static function getPrevious()
    {
        if (!Yii::$app->user->isGuest) {
            return [];
        }

        return Feedback::find()->where("user_id is null and ip = '" . Yii::$app->request->userIP . "' and datetime >= '" . date('Y-m-d', strtotime("-30 days")) . '\'')->all();
    }

}
