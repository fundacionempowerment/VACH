<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\RegisterModel;
use app\models\Wheel;

class SiteController extends BaseController {

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
        $wheel = new Wheel();
        return $this->render('index', [
                    'model' => $model,
                    'wheel' => $wheel,
        ]);
    }

    public function actionToken() {
        if (!Yii::$app->request->isPost)
            return $this->goHome();

        $token = Yii::$app->request->post('token1');
        $token .= '-' . Yii::$app->request->post('token2');
        $token .= '-' . Yii::$app->request->post('token3');
        if (!isset($token) || strlen($token) < 11)
            return $this->goHome();

        return $this->redirect(['wheel/run', 'token' => $token]);
    }

    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirectIfCoach();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirectIfCoach();
        } else {
            $wheel = new Wheel();
            return $this->render('index', [
                        'model' => $model,
                        'wheel' => $wheel,
            ]);
        }
    }

    private function redirectIfCoach() {
        $isCoach = Yii::$app->user->identity->is_coach;
        Yii::$app->session->set('is_coach', $isCoach);

        if ($isCoach)
            return $this->redirect(['/assessment']);
        else {
            Yii::$app->user->logout();
            return $this->redirect(['/site']);
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRegister() {
        $model = new RegisterModel();
        $model->isCoach = true;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->register()) {
                $loginModel = new LoginForm();
                $loginModel->username = $model->username;
                $loginModel->password = $model->password;

                if ($loginModel->login()) {
                    \Yii::$app->session->addFlash('success', \Yii::t('register', 'Sign up successfull. Welcome to VACH!'));
                    return $this->goHome();
                }
            }
            else
                \Yii::$app->session->addFlash('error', \Yii::t('register', 'Username already used.'));
        }
        else
            SiteController::FlashErrors($model);
        return $this->render('register', [
                    'model' => $model,
        ]);
    }

    public function actionCoach() {
        return $this->render('coachIntro', [
        ]);
    }

    public function actionPerson() {
        return $this->render('personIntro', [
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

    public static function FlashErrors($record) {
        if (!isset($record))
            return;

        foreach ($record->getErrors() as $attribute => $messages)
            foreach ($messages as $message)
                \Yii::$app->session->addFlash('error', \Yii::t('app', 'Problem while saving: ') . $message);
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
