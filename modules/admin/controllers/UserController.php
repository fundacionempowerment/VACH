<?php

namespace app\modules\admin\controllers;

use app\controllers\SiteController;
use app\models\User;
use app\models\search\UserSearch;
use Yii;
use yii\filters\AccessControl;

/**
 * User controller
 */
class UserController extends AdminBaseController
{

    public $layout = '//admin';

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
            return $this->redirect(['/site']);
        else if ($action->id != 'my-account' && $action->id != 'find-by-name' && !Yii::$app->user->identity->is_administrator) {
            \Yii::$app->session->addFlash('error', \Yii::t('app', 'Access denied'));
            return $this->redirect(['/site']);
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $filter = new UserSearch();
        $filter->load(Yii::$app->request->queryParams);
        $users = $filter->adminBrowse();

        return $this->render('index', [
            'filter' => $filter,
            'users' => $users,
        ]);
    }

    public function actionNew($personId = null)
    {
        $user = new User();
        $user->scenario = User::PASSWORD;

        if ($user->load(Yii::$app->request->post())) {
            $postUser = Yii::$app->request->post('User');
            $password = $postUser['password'];
            if (isset($password)) {
                $user->setPassword($password);
            }

            if ($user->save()) {
                SiteController::addFlash('success', Yii::t('app', '{name} has been successfully created.', ['name' => $user->fullname]));
                return $this->redirect(['index']);
            } else {
                SiteController::FlashErrors($user);
            }
        }

        return $this->render('form', [
            'user' => $user,
            'return' => '/user',
        ]);
    }

    public function actionEdit($id)
    {
        $user = User::findOne(['id' => $id]);
        $user->scenario = User::PASSWORD;

        if ($user->load(Yii::$app->request->post())) {
            $postUser = Yii::$app->request->post('User');
            $password = $postUser['password'];
            if ($password) {
                $encryptedPassword = Yii::$app->getSecurity()->generatePasswordHash($password);
                $user->password_hash = $encryptedPassword;
            }

            if ($user->save()) {
                SiteController::addFlash('success', Yii::t('app', '{name} has been successfully edited.', ['name' => $user->fullname]));
                return $this->redirect(['index']);
            } else {
                SiteController::FlashErrors($user);
            }
        }

        return $this->render('form', [
            'user' => $user,
            'return' => '/user',
        ]);
    }

    public function actionDelete($id)
    {
        $user = User::findOne(['id' => $id]);
        if ($user->delete()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully deleted.', ['name' => $user->fullname]));
        } else {
            SiteController::FlashErrors($user);
        }

        return $this->redirect(['/user']);
    }

}
