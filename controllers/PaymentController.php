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

    public function beforeAction($action)
    {
        if ($action->id == 'confirmation') {
            return true;
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $models = Payment::browse();

        return $this->render('index', [
                    'models' => $models,
        ]);
    }

    public function actionView($id)
    {
        $model = Payment::findOne(['id' => $id]);

        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    public function actionResponse()
    {
        $referenceCode = Yii::$app->request->get('referenceCode');
        $lapTransactionState = Yii::$app->request->get('lapTransactionState');

        $model = Payment::findOne(['uuid' => $referenceCode]);

        $responses = [
            'APPROVED' => [
                Payment:: STATUS_INIT => 'wait',
                Payment:: STATUS_PENDING => 'wait',
                Payment:: STATUS_PAID => 'success',
                Payment:: STATUS_REJECTED => 'wait',
                Payment:: STATUS_ERROR => 'wait',
            ],
            'DECLINED' => [
                Payment:: STATUS_INIT => 'declined',
                Payment:: STATUS_PENDING => 'declined',
                Payment:: STATUS_PAID => 'error',
                Payment:: STATUS_REJECTED => 'error',
                Payment:: STATUS_ERROR => 'declined',
            ],
            'EXPIRED' => [
                Payment:: STATUS_INIT => 'declined',
                Payment:: STATUS_PENDING => 'declined',
                Payment:: STATUS_PAID => 'error',
                Payment:: STATUS_REJECTED => 'error',
                Payment:: STATUS_ERROR => 'declined',
            ],
            'PENDING' => [
                Payment:: STATUS_INIT => 'pending',
                Payment:: STATUS_PENDING => 'pending',
                Payment:: STATUS_PAID => 'error',
                Payment:: STATUS_REJECTED => 'pending',
                Payment:: STATUS_ERROR => 'pending',
            ],
            'ERROR' => [
                Payment:: STATUS_INIT => 'error',
                Payment:: STATUS_PENDING => 'error',
                Payment:: STATUS_PAID => 'error',
                Payment:: STATUS_REJECTED => 'error',
                Payment:: STATUS_ERROR => 'error',
            ]
        ];

        switch ($responses[$lapTransactionState][$model->status]) {
            case 'success':
                return $this->render('response_success');
            case 'wait':
                return $this->render('response_wait');
            case 'pending':
                return $this->render('response_pending');
            case 'declined':
                return $this->render('response_declined');
            default :
                $this->notifyAdmin($referenceCode);
                return $this->render('response_error');
        }
    }

    public function actionConfirmation()
    {
        try {
            $referenceCode = Yii::$app->request->post('reference_sale');

            $payment = Payment::findOne(['uuid' => $referenceCode]);
            $stock = $payment->stock;

            $payment->external_id = Yii::$app->request->post('reference_pol');
            $payment->external_data = serialize($_POST);

            $state_pol = Yii::$app->request->post('state_pol');

            switch ($state_pol) {
                case 4:
                    $payment->status = Payment::STATUS_PAID;
                    $payment->rate = Yii::$app->request->post('exchange_rate');
                    $payment->commision = Yii::$app->request->post('commision_pol');
                    $payment->commision_currency = Yii::$app->request->post('commision_pol_currency');
                    $payment->save();
                    $stock->status = Stock::STATUS_VALID;
                    $stock->save();

                    $this->notifyPayed($referenceCode);
                    break;
                case 7:
                    $payment->status = Payment::STATUS_PENDING;
                    $payment->save();
                    break;
                case 5:
                case 6:
                    $payment->status = Payment::STATUS_REJECTED;
                    $payment->save();
                    $stock->status = Stock::STATUS_INVALID;
                    $stock->save();
                    break;
                default :
                    if ($payment->status != Payment::STATUS_PAID) {
                        $payment->status = Payment::STATUS_ERROR;
                        $stock->status = Stock::STATUS_ERROR;
                        $payment->save();
                        $stock->save();
                    }
                    break;
            }
        } finally {
            return 'OK';
        }
    }

    private function notifyAdmin($referenceCode)
    {
        Yii::$app->mailer->compose('payment_error', [
                    'referenceCode' => $referenceCode,
                ])
                ->setSubject('Payment with issues')
                ->setFrom(Yii::$app->params['senderEmail'])
                ->setTo(Yii::$app->params['adminEmail'])
                ->send();
    }

    private function notifyPayed($referenceCode)
    {
        $model = Payment::findOne(['uuid' => $referenceCode]);

        Yii::$app->mailer->compose('payment_success', [
                    'model' => $model,
                ])
                ->setSubject('Payment successful')
                ->setFrom(Yii::$app->params['senderEmail'])
                ->setTo($model->coach->email)
                ->setBcc(Yii::$app->params['adminEmail'])
                ->send();
    }

}
