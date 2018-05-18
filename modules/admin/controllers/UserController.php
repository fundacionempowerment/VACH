<?php

namespace app\modules\admin\controllers;

use app\controllers\LogController;
use app\controllers\SiteController;
use app\models\Company;
use app\models\Payment;
use app\models\Person;
use app\models\search\UserSearch;
use app\models\Stock;
use app\models\Team;
use app\models\TeamCoach;
use app\models\User;
use app\modules\admin\models\UserFusionForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

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

    public function actionFuse()
    {
        $model = new UserFusionForm();
        if ($model->load(Yii::$app->request->post())) {
            LogController::log(Yii::t('user', 'User {origin} has been fused to {destination}.', [
                'origin' => $model->originUser->username,
                'destination' => $model->destinationUser->username,
            ]));

            if ($model->fuse()) {
                SiteController::addFlash('success', Yii::t('user', 'Users has been successfully fused.'));
                return $this->redirect(['index']);
            }

            SiteController::addFlash('error', Yii::t('user', 'Users has not been fused.'));
        }

        return $this->render('fusion-form', [
            'model' => $model,
        ]);
    }

    public function actionFusionPreview()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!Yii::$app->request->isAjax && !Yii::$app->request->isPost) {
            $response = [
                'status' => 'error',
                'preview' => '',
            ];
            return $response;
        }

        $originUserId = Yii::$app->request->post('originUserId');

        $persons = Person::find()->where(['coach_id' => $originUserId]);
        $companies = Company::find()->where(['coach_id' => $originUserId]);
        $teams = Team::find()->where(['coach_id' => $originUserId]);
        $teamInvitations = TeamCoach::find()->where(['coach_id' => $originUserId]);
        $stocks = Stock::adminBrowse($originUserId);
        $payments = Payment::adminBrowse($originUserId);

        $previewResult = $this->renderAjax('_preview', [
            'persons' => $persons,
            'companies' => $companies,
            'teams' => $teams,
            'teamInvitations' => $teamInvitations,
            'stocks' => $stocks,
            'payments' => $payments,
        ]);

        $response = [
            'status' => 'success',
            'preview' => $previewResult,
        ];

        return $response;
    }

}
