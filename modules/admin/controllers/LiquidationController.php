<?php

namespace app\modules\admin\controllers;

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
use app\models\Liquidation;
use app\controllers\SiteController;

class LiquidationController extends \app\controllers\BaseController
{

    public $layout = '//admin';

    public function actionIndex()
    {
        if (!Yii::$app->user->identity->is_administrator) {
            return $this->goHome();
        }

        $models = Liquidation::adminBrowse();

        return $this->render('index', [
                    'models' => $models,
        ]);
    }

    public function actionView($id)
    {
        if (!Yii::$app->user->identity->is_administrator) {
            return $this->goHome();
        }

        $model = Liquidation::findOne(['id' => $id]);

        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    public function actionNew()
    {
        $model = new Liquidation();

        if (Yii::$app->request->isPost) {
            $ids = Yii::$app->request->post('selection');

            $payments = \app\models\Payment::find()
                    ->where(['in', 'id', $ids])
                    ->all();

            $raw_amount = 0;
            $commisions = 0;
            $part1 = 0;
            $part2 = 0;

            foreach ($payments as $payment) {
                $raw_amount += $payment->localAmount;
                $commisions += $payment->commision;
                $part1 += $payment->netAmount * $payment->part_distribution / 100;
                $part2 += $payment->netAmount * (100 - $payment->part_distribution) / 100;
            }

            $net_amount = $raw_amount - $commisions;

            $model->raw_amount = $raw_amount;
            $model->commision = $commisions;
            $model->net_amount = $net_amount;
            $model->part1_amount = $part1;
            $model->part2_amount = $part2;

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                foreach ($payments as $payment) {
                    $payment->liquidation_id = $model->id;
                    $payment->save();
                }

                SiteController::addFlash('success', Yii::t('app', '{name} has been successfully edited.', ['name' => $model->name]));
                return $this->redirect(['liquidation/view', 'id' => $model->id]);
            } else {
                SiteController::FlashErrors($model);
            }
        }

        return $this->render('form', [
                    'model' => $model,
        ]);
    }

    public function actionCalculateTotals()
    {
        $ids = Yii::$app->request->post('ids');

        $payments = \app\models\Payment::find()
                ->where(['in', 'id', $ids])
                ->all();

        $raw_amount = 0;
        $commisions = 0;
        $part1 = 0;
        $part2 = 0;

        foreach ($payments as $payment) {
            $raw_amount += $payment->localAmount;
            $commisions += $payment->commision;
            $part1 += $payment->netAmount * $payment->part_distribution / 100;
            $part2 += $payment->netAmount * (100 - $payment->part_distribution) / 100;
        }

        $net_amount = $raw_amount - $commisions;

        Yii::$app->response->format = 'json';
        return [
            'raw_amount' => 'ARS ' . Yii::$app->formatter->asDecimal($raw_amount),
            'commision' => 'ARS ' . Yii::$app->formatter->asDecimal($commisions),
            'net_amount' => 'ARS ' . Yii::$app->formatter->asDecimal($net_amount),
            'part1_amount' => 'ARS ' . Yii::$app->formatter->asDecimal($part1, 2),
            'part2_amount' => 'ARS ' . Yii::$app->formatter->asDecimal($part2, 2),
        ];
    }

}
