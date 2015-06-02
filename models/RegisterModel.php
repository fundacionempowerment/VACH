<?php

namespace app\models;

use yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class RegisterModel extends Model {

    public $name;
    public $surname;
    public $email;
    public $username;
    public $password;
    public $confirm;
    public $isCoach;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            // name, email, subject and body are required
            [['name', 'surname', 'email', 'username', 'password', 'confirm', 'isCoach'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            ['isCoach', 'boolean'],
            ['confirm', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('user', 'Passwords don\'t match.')],
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
            'username' => Yii::t('user', 'username'),
            'password' => Yii::t('user', 'Password'),
            'confirm' => Yii::t('user', 'Re-enter password'),
            'isCoach' => Yii::t('user', 'I\'m coach'),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param  string  $email the target email address
     * @return boolean whether the model passes validation
     */
    public function register() {
        $command = yii::$app->db->createCommand();

        $encryptedPassword = Yii::$app->getSecurity()->generatePasswordHash($this->password);

        $command->insert('user', [
            'name' => $this->name,
            'surname' => $this->surname,
            'email' => $this->email,
            'username' => $this->username,
            'password_hash' => $encryptedPassword,
            'is_coach' => $this->isCoach,
        ])->execute();

        return true;
    }

}
