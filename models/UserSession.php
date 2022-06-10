<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property integer $user_id
 * @property string $token
 * @property integer $stamp
 */
class UserSession extends ActiveRecord
{

    public function __construct()
    {
        $this->stamp = date('Y-m-d H:i:s');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_session}}';
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['user_id', 'token', 'stamp'], 'required'],
        ];
    }

    public static function getLastSession(int $coach_id) {
        return UserSession::find()
            ->where(['user_id' => $coach_id])
            ->orderBy(['stamp'=> SORT_DESC])
            ->one();
    }
}
