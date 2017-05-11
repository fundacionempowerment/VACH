<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Company;

class CompanyController extends BaseController
{

    public $layout = 'inner';

    public function actionIndex()
    {
        $companies = Company::browse();
        return $this->render('index', [
                    'companies' => $companies,
        ]);
    }

    public function actionView($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site']);
        }

        $company = Company::findOne(['id' => $id]);
        return $this->render('view', [
                    'company' => $company,
        ]);
    }

    public function actionNew()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site']);
        }

        $company = new Company();

        if ($company->load(Yii::$app->request->post()) && $company->save()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully created.', ['name' => $company->name]));
            return $this->redirect(['/company']);
        } else {
            SiteController::FlashErrors($company);
        }

        return $this->render('form', [
                    'company' => $company,
        ]);
    }

    public function actionEdit($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site']);
        }

        $company = Company::findOne(['id' => $id]);

        if (!$company || $company->coach_id != Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }

        if ($company->load(Yii::$app->request->post()) && $company->save()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully edited.', ['name' => $company->name]));
            return $this->redirect(['/company']);
        } else {
            SiteController::FlashErrors($company);
        }

        return $this->render('form', [
                    'company' => $company,
        ]);
    }

    public function actionDelete($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site']);
        }

        $company = Company::findOne(['id' => $id]);

        if (!$company || $company->coach_id != Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }

        if ($company->delete()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully deleted.', ['name' => $company->name]));
        } else {
            SiteController::FlashErrors($company);
        }

        return $this->redirect(['/company']);
    }

}
