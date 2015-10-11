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
    public $phone;
    public $username;
    public $password;
    public $confirm;
    public $isCoach;
    public $selected_assisstance;

    const ASSISTANCE_NONE = 0;
    const ASSISTANCE_BASIC = 1;
    const ASSISTANCE_PREMIUM = 2;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            // name, email, subject and body are required
            [['name', 'surname', 'email', 'username', 'password', 'confirm', 'isCoach'], 'required'],
            [['phone'], 'safe'],
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
        return [ 'name' => Yii ::t('app', 'Name'),
            'surname' => Yii::t('user', 'Surname'),
            'email' => Yii::t('app', 'Email'),
            'phone' => Yii::t('app', 'Phone'),
            'username' => Yii::t('user', 'Username'),
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
        $previous = User::find()
                ->where(['username' => $this->username])
                ->one();

        if (isset($previous))
            return false;

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

    public static function getAssisstanceTypes() {
        return [
            [
                'id' => self::ASSISTANCE_NONE,
                'label' => Yii::t('register', 'No assisstance'),
                'description' => Yii::t('register', 'VACH can be used with no technical assistance.'),
                'price' => 'free',
            ],
            [
                'id' => self::ASSISTANCE_BASIC,
                'label' => Yii::t('register', 'Basic assisstance'),
                'description' => Yii::t('register', 'Technical assistance via email, chat, teleconference, remote desktop, in labor days, in tipical commercial schedule, 9am to 6pm (Arg)'),
                'price' => Yii::t('register', 'ar$ 200 / month'),
            ],
            [
                'id' => self::ASSISTANCE_PREMIUM,
                'label' => Yii::t('register', 'Premium assisstance'),
                'description' => Yii::t('register', 'Technical assistance via any required communication device, all days, whole year.'),
                'price' => Yii::t('register', 'ar$ 2000 / month'),
            ],
        ];
    }

}
