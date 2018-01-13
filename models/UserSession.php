<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\httpclient\Client;
use app\models\Log;
use app\controllers\LogController;
use app\models\UserSession;

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

}
