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
use app\models\AddModel;
use app\models\RemoveModel;

class StockController extends BaseController
{

    public $layout = 'inner';

    public function actionIndex()
    {
        $models = Stock::browse();

        return $this->render('index', [
                    'models' => $models,
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

        $model = new BuyModel([
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $product->price,
        ]);

        if ($model->load(Yii::$app->request->post())) {

            Stock::saveBuyModel($model);

            return $this->render('/payment/redirect', [
                        'model' => $model,
            ]);
        }

        return $this->render('new', [
                    'model' => $model,
        ]);
    }

}
