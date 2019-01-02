<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * User controller
 */
class UserController extends Controller
{
    public $layout = '//inner';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
        ];
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'roles' => ['@'],],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!isset(Yii::$app->user->identity)) {
            $this->redirect(['/site'])->send();
            return false;
        } elseif ($action->id != 'my-account' && $action->id != 'my-password' && $action->id != 'find-by-name' && !Yii::$app->user->identity->is_administrator) {
            \Yii::$app->session->addFlash('error', \Yii::t('app', 'Access denied'));
            $this->redirect(['/site'])->send();
            return false;
        }
        return parent::beforeAction($action);
    }

    public function actionFindByName($name = null)
    {
        Yii::$app->response->format = 'json';

        $data['results'] = [];
        if ($name) {
            $users = User::findByName($name)->all();
            foreach ($users as $user) {
                $newElement['id'] = $user->id;
                $newElement['name'] = $user->name;
                $newElement['surname'] = $user->surname;
                $newElement['fullname'] = $user->fullname;
                $newElement['userFullname'] = $user->userFullname;
                $newElement['email'] = $user->email;
                $data['results'][] = $newElement;
            }
        }

        return $data;
    }

    public function actionMyAccount()
    {
        $user = User::findOne(['id' => Yii::$app->user->id]);

        if ($user->load(Yii::$app->request->post())) {
            if ($user->save()) {
                SiteController::addFlash('success', Yii::t('user', 'Your personal data has been successfully saved.'));
                return $this->redirect(['/site']);
            } else {
                SiteController::FlashErrors($user);
            }
        }

        return $this->render('myAccount', [
            'user' => $user,
            'return' => '/site',
        ]);
    }


    public function actionMyPassword()
    {
        $user = User::findOne(['id' => Yii::$app->user->id]);
        $user->scenario = User::PASSWORD;

        if ($user->load(Yii::$app->request->post())) {
            $postUser = Yii::$app->request->post('User');
            $password = $postUser['password'];
            $encryptedPassword = Yii::$app->getSecurity()->generatePasswordHash($password);
            $user->password_hash = $encryptedPassword;

            if ($user->save()) {
                SiteController::addFlash('success', Yii::t('user', 'Your password data has been successfully saved.'));
                return $this->redirect(['/site']);
            } else {
                SiteController::FlashErrors($user);
            }
        }

        return $this->render('myPassword', [
            'user' => $user,
            'return' => '/site',
        ]);
    }
}
