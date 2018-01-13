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
use app\models\Question;
use app\models\Wheel;
use app\models\WheelAnswer;
use app\models\WheelQuestion;
use app\models\Product;
use app\controllers\SiteController;

class ProductController extends AdminBaseController
{

    public $layout = '//admin';

    public function actionIndex()
    {
        $products = Product::browse();

        return $this->render('index', [
                    'products' => $products,
        ]);
    }

    public function actionNew()
    {
        $product = new Product();

        if ($product->load(Yii::$app->request->post()) && $product->save()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully created.', ['name' => $product->name]));
            return $this->redirect(['index']);
        } else {
            SiteController::FlashErrors($product);
        }

        return $this->render('form', [
                    'product' => $product,
        ]);
    }

    public function actionView($id)
    {
        $product = Product::findOne($id);

        if (!isset($product)) {
            return $this->redirect(['index']);
        }

        return $this->render('view', [
                    'product' => $product,
        ]);
    }

    public function actionEdit($id)
    {
        $product = Product::findOne(['id' => $id]);

        if (!isset($product)) {
            return $this->redirect(['index']);
        }

        if ($product->load(Yii::$app->request->post()) && $product->save()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully edited.', ['name' => $product->name]));
            return $this->redirect(['view', 'id' => $product->id]);
        } else {
            SiteController::FlashErrors($product);
        }

        return $this->render('form', [
                    'product' => $product,
        ]);
    }

    public function actionDelete($id)
    {
        $product = Product::findOne($id);

        if (!isset($product)) {
            return $this->redirect(['index']);
        }

        if ($product->delete()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully deleted.', ['name' => $product->name]));
        } else {
            SiteController::FlashErrors($product);
        }
        return $this->redirect(['index']);
    }

}
