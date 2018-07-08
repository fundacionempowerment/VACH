<?php

namespace app\controllers;

use app\models\Account;
use app\models\BuyModel;
use app\models\ClientModel;
use app\models\LoginModel;
use app\models\Product;
use app\models\RegisterModel;
use app\models\Stock;
use app\models\User;
use Yii;

class StockController extends BaseController
{
    public $layout = 'inner';

    public function actionIndex()
    {
        $availableModels = Stock::browseAvailable();
        $othersModels = Stock::browseOthers();

        return $this->render('index', [
                    'availableModels' => $availableModels,
                    'othersModels' => $othersModels,
        ]);
    }

    public function actionView($id)
    {
        $model = Stock::findOne(['id' => $id]);

        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    public function actionNew($product_id = null, $quantity = null)
    {
        if (!$product_id) {
            $product_id = Yii::$app->params['default_product_id'];
        }
        if (!$quantity) {
            $quantity = Yii::$app->params['default_quantity'];
        }

        $product = Product::findOne(['id' => $product_id]);
        $user = User::findOne(['id' => Yii::$app->user->id]);

        $model = new BuyModel([
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $product->price,
            'buyerEmail' => $user->email,
        ]);

        $action = Yii::$app->request->post('pay-button');
        if ($model->load(Yii::$app->request->post())) {
            if ($action == 'send' && !$model->payerEmail) {
                $model->addError('payerEmail', \Yii::t('stock', 'Email required to send link'));
            } else {
                Stock::saveBuyModel($model);

                $paymentLink = Yii::$app->urlManager->createAbsoluteUrl(['payment/init', 'referenceCode' => $model->referenceCode]);

                if ($action == 'send') {
                    Yii::$app->mailer->compose('payment_send', [
                        'paymentLink' => $paymentLink,
                    ])
                        ->setSubject(\Yii::t('stock', 'VACH licences payment link'))
                        ->setFrom(Yii::$app->params['senderEmail'])
                        ->setTo($model->payerEmail)
                        ->send();

                    return $this->redirect(['/payment/sent']);
                } else {
                    return $this->redirect($paymentLink);
                }
            }
        }

        return $this->render('new', [
                    'model' => $model,
        ]);
    }
}
