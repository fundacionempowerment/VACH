<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginModel;
use app\models\RegisterModel;
use app\models\User;
use app\models\CoachModel;
use app\models\ClientModel;
use app\models\AccountModel;

class AccountController extends Controller {

    public $layout = 'inner';

    public function actionIndex() {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new AccountModel();
        $model->id = Yii::$app->user->id;

        if (Yii::$app->request->isPost) {
            $data = \Yii::$app->request->post('AccountModel' , []);
            $model->attributes = $data;
            $model->oldPassword = isset($data['oldPassword']) ? $data['oldPassword'] : null;
            $model->password = isset($data['password']) ? $data['password'] : null;
            $model->confirm = isset($data['confirm']) ? $data['confirm'] : null;
            if ($model->validate()) {
                $model->save();
                return $this->goHome();
            }
        }
        else
            $model->read();

        return $this->render('index', [
                    'model' => $model,
        ]);
    }

}

