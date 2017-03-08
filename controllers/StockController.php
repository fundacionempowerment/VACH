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
use app\models\Payment;
use app\models\Stock;
use app\models\Product;
use app\models\BuyModel;

class StockController extends BaseController
{

    public $layout = 'inner';

    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $models = Stock::browse();

        return $this->render('index', [
                    'models' => $models,
        ]);
    }

    public function actionView($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = Stock::findOne(['id' => $id]);

        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    public function actionNew($product_id = null, $quantity = null)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        if (!$product_id) {
            $product_id = Yii::$app->params['default_product_id'];
        }
        if (!$quantity) {
            $quantity = Yii::$app->params['default_quantity'];
        }

        $product = Product::findOne(['id' => $product_id]);

        $model = new BuyModel([
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $product->price,
        ]);

        if ($model->load(Yii::$app->request->post())) {
            // Register new stock
            $stock = new Stock();
            $stock->coach_id = Yii::$app->user->id;
            $stock->product_id = $model->product_id;
            $stock->quantity = $model->quantity;
            $stock->total = $model->quantity * $product->price;
            $stock->price = $product->price;
            $stock->status = Stock::STATUS_PENDING;
            if (!$stock->save()) {
                \app\controllers\SiteController::FlashErrors($stock);
            }

            $payment = new Payment();
            $payment->coach_id = Yii::$app->user->id;
            $payment->stock_id = $stock->id;
            $payment->concept = $model->quantity . ' ' . $product->name;
            $payment->amount = $stock->total;
            $payment->status = Payment::STATUS_INIT;
            if (!$payment->save()) {
                \app\controllers\SiteController::FlashErrors($payment);
            }

            $model->description = 'VACH ' . $payment->concept;
            $model->amount = $payment->amount;
            $model->referenceCode = $payment->uuid;

            return $this->render('/payment/redirect', [
                        'model' => $model,
            ]);
        }

        return $this->render('new', [
                    'model' => $model,
        ]);
    }

}
