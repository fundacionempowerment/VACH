<?php

namespace app\modules\admin\controllers;

use Yii;

class TestController extends \app\controllers\BaseController
{

    public $layout = '//admin';

    public function actionEmail()
    {
        if (!Yii::$app->user->identity->is_administrator) {
            return $this->goHome();
        }

        $email = Yii::$app->request->post('email');
        if ($email) {

            $message = Yii::$app->mailer->compose()
                ->setSubject('Test email')
                ->setFrom(Yii::$app->params['senderEmail'])
                ->setTo($email)
                ->setTextBody("Test email\n(" . date('H:i d/m/Y') . ")");

            if ($message->send()) {
                Yii::$app->session->setFlash('success', 'Email sent');
            } else {
                Yii::$app->session->setFlash('error', 'Email not sent :(');
            }
        }
        return $this->render('email', [
            'email' => $email,
        ]);
    }


}
