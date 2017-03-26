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

}
