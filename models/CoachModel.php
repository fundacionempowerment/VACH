<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class CoachModel extends Model {

    public $id;
    public $name;
    public $email;
    public $password;
    public $isCoach = true;
    public $coachId;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            // username and password are both required
            [['name', 'email'], 'required'],
            ['isCoach', 'boolean'],
            ['email', 'email'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels() {
        return [
            'name' => 'Nombre completo',
            'email' => 'Correo electrónico',
        ];
    }
}
