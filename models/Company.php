<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Company extends ActiveRecord {

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
            [['name', 'email', 'username', 'password_hash', 'coach_id'], 'required'],
            [['phone'], 'safe'],
            [['name', 'surname', 'email', 'phone'], 'filter', 'filter' => 'trim'],
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
            'name' => Yii::t('app', 'Name'),
            'email' => Yii::t('app', 'Email'),
            'fullname' => Yii::t('app', 'Name'),
        ];
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

        if (!isset($this->is_company))
            $this->is_company = true;

        return parent::beforeValidate();
    }

    public static function browse() {
        return Company::find()->where(['coach_id' => Yii::$app->user->id, 'is_company' => true]);
    }

    public function getCoach() {
        return $this->hasOne(User::className(), ['id' => 'coach_id']);
    }

}
