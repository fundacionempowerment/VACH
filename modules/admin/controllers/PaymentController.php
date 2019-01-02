<?php

namespace app\modules\admin\controllers;

use app\models\Transaction;
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
use app\controllers\SiteController;

class PaymentController extends AdminBaseController
{

    public $layout = '//admin';

    public function actionIndex()
    {
        if (!Yii::$app->user->identity->is_administrator) {
            return $this->goHome();
        }

        $models = Payment::adminBrowse();

        return $this->render('index', [
                    'models' => $models,
        ]);
    }

    public function actionView($id)
    {
        if (!Yii::$app->user->identity->is_administrator) {
            return $this->goHome();
        }

        $model = Payment::findOne(['id' => $id]);

        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    public function actionEdit($id)
    {
        $model = Payment::findOne(['id' => $id]);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                SiteController::addFlash('success', Yii::t('app', '{name} has been successfully edited.', ['name' => $model->name]));
                return $this->redirect(['payment/view', 'id' => $model->id]);
            } else {
                SiteController::FlashErrors($model);
            }
        }

        return $this->render('form', [
                    'model' => $model,
        ]);
    }

    public function actionPayPendings()
    {
        $models = Payment::adminBrowsePendings();

        if (Yii::$app->request->isPost) {
            $ids = Yii::$app->request->post('selection');

            foreach ($ids as $id) {
                $payment = Payment::findOne($id);

                $payment->status = 'paid';
                $payment->save();

                $transaction = $payment->newTransaction();
                $transaction->status = Payment::STATUS_PAID;
                $transaction->save();
            }

            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully edited.', ['name' => Yii::t('payment', 'Payments')]));
            return $this->redirect(['index']);
        }

        return $this->render('pay_pendings', [
                    'models' => $models,
        ]);
    }

    public function actionCalculateTotals()
    {
        Yii::$app->response->format = 'json';

        $ids = Yii::$app->request->post('ids');

        $payments = \app\models\Payment::find()
                ->where(['in', 'id', $ids])
                ->all();

        $total = 0;
        foreach ($payments as $payment) {
            $total += $payment->localAmount;
        }

        return [
            'total' => 'ARS ' . Yii::$app->formatter->asDecimal($total, 2),
        ];
    }

}
