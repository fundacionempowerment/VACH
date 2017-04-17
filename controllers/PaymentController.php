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
use app\models\Account;
use app\models\Stock;
use app\models\BuyModel;
use app\models\Payment;

class PaymentController extends BaseController
{

    public $layout = 'inner';
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $models = Payment::browse();

        return $this->render('index', [
                    'models' => $models,
        ]);
    }

    public function actionView($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = Payment::findOne(['id' => $id]);

        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    public function actionResponse()
    {
        $referenceCode = Yii::$app->request->get('referenceCode');

        $model = Payment::findOne(['uuid' => $referenceCode]);

        if (!$model) {
            $model = new Payment();
            $model->status = Payment::STATUS_ERROR;
        } else{
            $a = new \DateTime($model->stamp);
        }

        if ($model->status == Payment::STATUS_ERROR) {
            $this->notifyAdmin($referenceCode);
        }

        return $this->render('response', [
                    'model' => $model,
        ]);
    }

    public function actionConfirmation()
    {
        $referenceCode = Yii::$app->request->post('reference_sale');

        $payment = Payment::findOne(['uuid' => $referenceCode]);
        $stock = $payment->stock;

        $payment->external_id = Yii::$app->request->post('reference_pol');
        $payment->external_data = serialize($_POST);

        $state_pol = Yii::$app->request->post('state_pol');

        switch ($state_pol) {
            case 4:
                $payment->status = Payment::STATUS_PAID;
                $stock->status = Stock::STATUS_VALID;
                break;
            default :
                $payment->status = Payment::STATUS_ERROR;
                $stock->status = Stock::STATUS_ERROR;
                break;
        }

        if (!$payment->save()) {
            SiteController::FlashErrors($payment);
        }
        if (!$stock->save()) {
            SiteController::FlashErrors($stock);
        }
    }

    private function notifyAdmin($referenceCode)
    {
        Yii::$app->mailer->compose('payment', [
                    'referenceCode' => $referenceCode,
                ])
                ->setSubject('Payment with issues')
                ->setTo(Yii::$app->params['adminEmail'])
                ->send();
    }

}
