<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\RegisterModel;
use app\models\User;

class SiteController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex() {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirectIfCoach();
        }

        $model = new LoginForm();
        return $this->render('index', [
                    'model' => $model,
        ]);
    }

    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirectIfCoach();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirectIfCoach();
        } else {
            return $this->render('index', [
                        'model' => $model,
            ]);
        }
    }

    private function redirectIfCoach() {
        $isCoach = Yii::$app->user->identity->is_coach;
        Yii::$app->session->set('is_coach', $isCoach);

        if ($isCoach)
            return $this->redirect(['/coachee']);
        else {
            Yii::$app->session->set('coachee_id', Yii::$app->user->id);
            return $this->redirect(['/client/view', ['id' => Yii::$app->user->id]]);
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRegister() {
        $model = new RegisterModel();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->register()) {
                $loginModel = new LoginForm();
                $loginModel->username = $model->username;
                $loginModel->password = $model->password;

                if ($loginModel->login())
                    return $this->goHome();
            }
        }
        return $this->render('register', [
                    'model' => $model,
        ]);
    }

    public function actionCoach() {
        return $this->render('coachIntro', [
        ]);
    }

    public function actionCoachee() {
        return $this->render('coacheeIntro', [
        ]);
    }

    public function actionEs() {
        Yii::$app->session->set('language', 'es');
        return $this->goHome();
    }

    public function actionEn() {
        Yii::$app->session->set('language', 'en');
        return $this->goHome();
    }

    public function actionMigrateUp() {
        // https://github.com/yiisoft/yii2/issues/1764#issuecomment-42436905
        defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
        defined('STDOUT') or define('STDOUT', fopen('php://stdout', 'w'));

        $oldApp = \Yii::$app;
        \Yii::$app = new \yii\console\Application([
            'id' => 'Command runner',
            'basePath' => '@app',
            'components' => [
                'db' => $oldApp->db,
            ],
        ]);
        \Yii::$app->runAction('migrate/up', ['migrationPath' => '@app/migrations/', 'interactive' => false]);
        \Yii::$app = $oldApp;
    }

}
