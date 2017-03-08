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

        return $this->render('response', [
                    'model' => $model,
        ]);
    }

    public function actionConfirmation()
    {
        $referenceCode = Yii::$app->request->post('reference_sale');

        $model = Payment::findOne(['uuid' => $referenceCode]);
        $stock = $model->stock;

        $model->external_id = Yii::$app->request->post('reference_pol');
        $model->external_data = print_r(Yii::$app->request->post());

        $state_pol = Yii::$app->request->post('state_pol');

        switch ($state_pol) {
            case 4:
                $model->status = Payment::STATUS_PAID;
                $stock->status = Stock::STATUS_PAID;
                break;
            default :
                $model->status = Payment::STATUS_ERROR;
                $stock->status = Stock::STATUS_ERROR;
        }

        $model->save();
        $stock->save();
    }

}
