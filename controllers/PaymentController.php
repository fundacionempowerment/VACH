<?php

namespace app\controllers;

use app\models\Account;
use app\models\BuyModel;
use app\models\ClientModel;
use app\models\LoginModel;
use app\models\Payment;
use app\models\RegisterModel;
use app\models\Stock;
use app\models\Transaction;
use app\models\User;
use Yii;

class PaymentController extends BaseController {
    public $layout = 'inner';
    public $enableCsrfValidation = false;

    public function beforeAction($action) {
        if ($action->id == 'confirmation' || $action->id == 'response' || $action->id == 'init') {
            return true;
        }
        return parent::beforeAction($action);
    }

    public function actionIndex() {
        $models = Payment::browse();

        return $this->render('index', [
            'models' => $models,
        ]);
    }

    public function actionView($id) {
        $model = Payment::findOne(['id' => $id]);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionSent() {
        return $this->render('sent');
    }

    public function actionSelect($referenceCode) {
        $payment = Payment::findOne(['uuid' => $referenceCode]);

        $model = BuyModel::fromPayment($payment);

        $action = Yii::$app->request->post('pay-button');
        if ($model->load(Yii::$app->request->post())) {
            if ($action == 'send' && !$model->payerEmail) {
                $model->addError('payerEmail', \Yii::t('stock', 'Email required to send link'));
            } else {
                $paymentLink = Yii::$app->urlManager->createAbsoluteUrl(['payment/init', 'referenceCode' => $model->referenceCode]);

                if ($action == 'send') {
                    Yii::$app->mailer->compose('payment_send', [
                        'paymentLink' => $paymentLink,
                    ])
                        ->setSubject(\Yii::t('stock', 'VACH licences payment link'))
                        ->setFrom(Yii::$app->params['senderEmail'])
                        ->setTo($model->payerEmail)
                        ->setCc($payment->coach->email)
                        ->send();

                    return $this->redirect(['/payment/sent']);
                } else {
                    return $this->redirect($paymentLink);
                }
            }
        }

        return $this->render('select', [
            'model' => $model,
        ]);
    }

    public function actionInit($referenceCode) {
        $this->layout = Yii::$app->user->isGuest ? 'printable' : 'inner';

        $payment = Payment::findOne(['uuid' => $referenceCode]);

        if (!$payment) {
            return $this->goHome();
        }

        if ($payment->status != Payment::STATUS_PENDING) {
            switch ($payment->status) {
                case Payment::STATUS_PAID:
                    return $this->render('response_success');
                default:
                    return $this->render('response_declined');
            }
        }

        $transaction = $payment->newTransaction();

        $buyModel = BuyModel::fromTransaction($transaction);

        return $this->render('/payment/redirect', [
            'model' => $buyModel,
        ]);
    }

    public function actionResponse() {
        $this->layout = Yii::$app->user->isGuest ? 'printable' : 'inner';

        $referenceCode = Yii::$app->request->get('referenceCode');
        $lapTransactionState = Yii::$app->request->get('lapTransactionState');

        $model = Transaction::findOne(['uuid' => $referenceCode]);

        $responses = [
            'APPROVED' => [
                Payment:: STATUS_PENDING => 'wait',
                Payment:: STATUS_PAID => 'success',
                Payment:: STATUS_REJECTED => 'wait',
                Payment:: STATUS_ERROR => 'wait',
            ],
            'DECLINED' => [
                Payment:: STATUS_PENDING => 'declined',
                Payment:: STATUS_PAID => 'error',
                Payment:: STATUS_REJECTED => 'error',
                Payment:: STATUS_ERROR => 'declined',
            ],
            'EXPIRED' => [
                Payment:: STATUS_PENDING => 'declined',
                Payment:: STATUS_PAID => 'error',
                Payment:: STATUS_REJECTED => 'error',
                Payment:: STATUS_ERROR => 'declined',
            ],
            'PENDING' => [
                Payment:: STATUS_PENDING => 'pending',
                Payment:: STATUS_PAID => 'error',
                Payment:: STATUS_REJECTED => 'pending',
                Payment:: STATUS_ERROR => 'pending',
            ],
            'ERROR' => [
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
            default:
                $this->notifyError($referenceCode);
                return $this->render('response_error');
        }
    }

    public function actionConfirmation() {
        try {
            $referenceCode = Yii::$app->request->post('reference_sale');

            $transaction = Transaction::findOne(['uuid' => $referenceCode]);
            $payment = $transaction->payment;
            $stocks = Stock::find()->where(['payment_id' => $payment->id])->all();

            $transaction->external_id = Yii::$app->request->post('reference_pol');
            $transaction->external_data = serialize($_POST);

            $state_pol = Yii::$app->request->post('state_pol');

            switch ($state_pol) {
                case 4:
                    $transaction->status = Payment::STATUS_PAID;
                    $transaction->rate = Yii::$app->request->post('exchange_rate');
                    $transaction->commision = Yii::$app->request->post('commision_pol');
                    $transaction->commision_currency = Yii::$app->request->post('commision_pol_currency');
                    $transaction->save();

                    $payment->status = Payment::STATUS_PAID;
                    $payment->rate = Yii::$app->request->post('exchange_rate');
                    $payment->commision = Yii::$app->request->post('commision_pol');
                    $payment->commision_currency = Yii::$app->request->post('commision_pol_currency');
                    $payment->save();

                    foreach ($stocks as $stock) {
                        $stock->status = Stock::STATUS_VALID;
                        $stock->save();
                    }

                    $this->notifyPayed($referenceCode);
                    break;
                case 7:
                    $transaction->status = Payment::STATUS_PENDING;
                    $transaction->save();
                    break;
                case 5:
                case 6:
                    $transaction->status = Payment::STATUS_REJECTED;
                    $transaction->save();

                    foreach ($stocks as $stock) {
                        $stock->status = Stock::STATUS_INVALID;
                        $stock->save();
                    }
                    break;
                default:
                    if ($payment->status != Payment::STATUS_PAID) {
                        $transaction->status = Payment::STATUS_ERROR;
                        $transaction->save();
                    }

                    if ($payment->status != Payment::STATUS_PAID) {
                        $payment->status = Payment::STATUS_ERROR;
                        $payment->save();
                        foreach ($stocks as $stock) {
                            $stock->status = Stock::STATUS_ERROR;
                            $stock->save();
                        }
                    }
                    break;
            }
        } catch (Exception $e) {

        }

        return 'OK';
    }

    private function notifyError($referenceCode) {
        Yii::$app->mailer->compose('payment_error', [
            'referenceCode' => $referenceCode,
        ])
            ->setSubject('Payment with issues')
            ->setFrom(Yii::$app->params['senderEmail'])
            ->setTo(User::getAdminEmails())
            ->send();
    }

    private function notifyPayed($referenceCode) {
        $transaction = Transaction::findOne(['uuid' => $referenceCode]);
        $payment = $transaction->payment;

        Yii::$app->mailer->compose('payment_success', [
            'model' => $payment,
        ])
            ->setSubject('Payment successful')
            ->setFrom(Yii::$app->params['senderEmail'])
            ->setTo($payment->coach->email)
            ->setBcc(User::getAdminEmails())
            ->send();
    }
}
