<?php

namespace app\models;

use app\models\User;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model {

    public $password;
    public $password_confirm;

    /**
     * @var \app\models\User
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param  string                          $token
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = []) {
        $this->_user = User::findByPasswordResetToken($token);
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['password', 'password_confirm'], 'required'],
            ['username', 'safe'],
            ['password', 'string', 'min' => 6],
            ['password_confirm', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    public function attributeLabels() {
        return [
            'password' => \Yii::t('user', 'New password'),
            'password_confirm' => Yii::t('user', 'Password confirmation'),
        ];
    }

    /**
     * Resets password.
     *
     * @return boolean if password was reset.
     */
    public function resetPassword() {
        $user = $this->_user;
        $user->setPassword($this->password);
        $user->removePasswordResetToken();

        return $user->save(false);
    }

    public function getUser() {
        return $this->_user;
    }

    public function getUsername() {
        return $this->_user->username;
    }

}
