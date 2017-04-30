<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use yii\filters\AccessControl;
use app\controllers\SiteController;

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
        if (!isset(Yii::$app->user->identity))
            $this->redirect(['/site']);
        else if ($action->id != 'my-account' && $action->id != 'find-by-name' && !Yii::$app->user->identity->is_administrator) {
            \Yii::$app->session->addFlash('error', \Yii::t('app', 'Access denied'));
            $this->redirect(['/site']);
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
        if (Yii::$app->user->isGuest)
            $this->goHome();

        $user = User::findOne(['id' => Yii::$app->user->id]);

        if ($user->load(Yii::$app->request->post())) {
            $postUser = Yii::$app->request->post('User');
            $password = $postUser['password'];
            if (isset($password)) {
                $encryptedPassword = Yii::$app->getSecurity()->generatePasswordHash($password);
                $user->password_hash = $encryptedPassword;
            }

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

}
