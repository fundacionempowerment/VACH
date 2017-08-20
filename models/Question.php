<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Question extends ActiveRecord
{

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['text'], 'required'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'text' => Yii::t('app', 'Text'),
        ];
    }

    static public function getId($questionText)
    {
        $question = Question:: findOne(['text' => $questionText]);
        if (!$question) {
            $question = new Question();
            $question->text = $questionText;
            $question->save();
        }

        return $question->id;
    }

    public function wheelText($wheel)
    {
        $result = static::getWheelText($this->text, $wheel);
        return $result;
    }

    public static function getWheelText($text, $wheel)
    {
        $replaces = [
            '[observed]' => $wheel->observed->fullname,
            '[team]' => $wheel->team->name,
            '[company]' => $wheel->team->company->name,
        ];

        $result = $text;

        foreach ($replaces as $key => $value) {
            $result = str_replace($key, $value, $result);
        }

        return $result;
    }

    public static function getEmergentText($text, $member, $team, $company)
    {
        $result = $text;
        $result = str_replace('[observed]', ($member ? $member->fullname : Yii::t('app', 'All')), $result);
        $result = str_replace('[team]', ($team ? $team->name : Yii::t('app', 'All')), $result);
        $result = str_replace('[company]', ($company ? $company->name : Yii::t('app', 'All')), $result);
        return $result;
    }

}
