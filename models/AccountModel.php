<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\Query;

/**
 * LoginForm is the model behind the login form.
 */
class AccountModel extends Model {

    public $id;
    public $name;
    public $surname;
    public $email;
    public $oldPassword;
    public $password;
    public $confirm;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['name', 'surname', 'email'], 'required'],
            ['email', 'email'],
            ['confirm', 'compare',
                'compareAttribute' => 'password',
                'message' => Yii::t('user', Yii::t('user', 'Passwords don\t match.'))
            ],
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
            'oldPassword' => Yii::t('user', 'Old password'),
            'password' => Yii::t('user', 'New password'),
            'confirm' => Yii::t('user', 'Re-enter password'),
        ];
    }

    public function read() {
        $this->attributes = (new yii\db\Query())->select('name, surname, email')
                ->from('user')
                ->where(['Id' => $this->id])
                ->one();
    }

    public function validate($attributeNames = null, $clearErrors = true) {
        $count = (new yii\db\Query())->select('count(*)')
                ->from('user')
                ->where(['email' => $this->email])
                ->andWhere('id != ' . $this->id)
                ->scalar();

        if ($count > 0)
            $this->addError('email', Yii::t('user', 'Email already used.'));

        $oldPassword_hash = (new yii\db\Query())->select('password_hash')
                ->from('user')
                ->where(['id' => $this->id])
                ->scalar();

        if ($this->oldPassword)
            if (!Yii::$app->getSecurity()->validatePassword($this->oldPassword, $oldPassword_hash))
                $this->addError('oldPassword', Yii::t('user', 'Wrong old password.'));


        if ($this->password || $this->confirm)
            if ($this->password != $this->confirm)
                $this->addError('oldPassword', Yii::t('user', 'Passwords don\'t match.'));

        return !$this->hasErrors();
    }

    public function save() {
        $command = yii::$app->db->createCommand();

        if ($this->password) {
            $encryptedPassword = Yii::$app->getSecurity()->generatePasswordHash($this->password);

            return $command->update('user', [
                                'name' => $this->name,
                                'surname' => $this->surname,
                                'email' => $this->email,
                                'password_hash' => $encryptedPassword,
                                    ], ['Id' => $this->id])
                            ->execute();
        } else {
            return $command->update('user', [
                                'name' => $this->name,
                                'surname' => $this->surname,
                                'email' => $this->email,
                                    ], ['Id' => $this->id])
                            ->execute();
        }
    }

}
