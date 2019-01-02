<?php

namespace app\controllers;

use app\models\search\CompanySearch;
use Yii;
use yii\web\Controller;
use app\models\Company;

class CompanyController extends BaseController
{
    public $layout = 'inner';

    public function actionIndex()
    {
        $searchModel = new CompanySearch();
        $companies = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'companies' => $companies,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id)
    {
        $company = Company::findOne(['id' => $id]);

        if ($company->userNotAllowed()) {
            throw new \yii\web\ForbiddenHttpException();
        }

        return $this->render('view', [
                    'company' => $company,
        ]);
    }

    public function actionNew()
    {
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
