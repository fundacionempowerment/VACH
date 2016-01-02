<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\RegisterModel;
use app\models\Wheel;
use app\models\PasswordResetRequestForm;
use app\models\ResetPasswordForm;

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

        $nowheel = Yii::$app->request->get('nowheel');
        if (isset($nowheel))
            $wheel->addError('token', Yii::t('wheel', 'Wheel not found.'));

        return $this->render('index', [
                    'model' => $model,
                    'wheel' => $wheel,
        ]);
    }

    public function actionToken() {
        if (!Yii::$app->request->isPost)
            return $this->goHome();

        $wheel = Yii::$app->request->post('Wheel');
        if (!isset($wheel)) {
            return $this->redirect(['/site', 'nowheel' => 1]);
        }
        $token = $wheel['token'];
        if (!isset($token)) {
            return $this->redirect(['/site', 'nowheel' => 1]);
        }
        return $this->redirect(['wheel/run', 'token' => $token]);
    }

    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirectIfCoach();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            LogController::log(Yii::t('app', 'Logged in as {username}.', ['username' => Yii::$app->user->identity->username]));
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

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Check your email for further instructions.'));
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, we are unable to reset password for email provided.'));
            }
        }
        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'New password was saved.'));
            return $this->goHome();
        }
        return $this->render('resetPassword', [
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

    public static function addFlash($key, $value) {
        \Yii::$app->session->addFlash('success', $value);
        LogController::log($value);
    }

    public static function FlashErrors($record) {
        if (!isset($record))
            return;

        foreach ($record->getErrors() as $attribute => $messages)
            foreach ($messages as $message)
                self::addFlash('error', \Yii::t('app', 'Problem: ') . $message);
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
