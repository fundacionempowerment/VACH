<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * LoginForm is the model behind the login form.
 */
class Client extends ActiveRecord {

    public $fullname;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%user}}';
    }

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            // username and password are both required
            [['name', 'surname', 'email', 'username', 'password_hash', 'coach_id'], 'required'],
            ['email', 'email'],
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
            'name' => Yii::t('user', 'Name'),
            'surname' => Yii::t('user', 'Surname'),
            'email' => Yii::t('user', 'Email'),
            'fullname' => Yii::t('user', 'Name'),
        ];
    }

    public function afterFind() {
        $this->fullname = $this->name . ' ' . $this->surname;
    }

    public function beforeValidate() {
        if (!isset($this->username))
            $this->username = strtolower($this->name) . '.' . strtolower($this->surname);

        if (!isset($this->password_hash)) {
            $encryptedPassword = Yii::$app->getSecurity()->generatePasswordHash('123456');
            $this->password_hash = $encryptedPassword;
        }

        if (!isset($this->coach_id))
            $this->coach_id = Yii::$app->user->id;

        return parent::beforeValidate();
    }

}
