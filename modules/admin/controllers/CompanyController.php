<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\Company;

class CompanyController extends AdminBaseController {


    public function actionIndex() {
        $companies = Company::browse();
        return $this->render('index', [
                    'companies' => $companies,
        ]);
    }

    public function actionView($id) {
        $company = Company::findOne(['id' => $id]);
        return $this->render('view', [
                    'company' => $company,
        ]);
    }

    public function actionNew() {
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

    public function actionEdit($id) {
        $company = Company::findOne(['id' => $id]);

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

    public function actionDelete($id) {
        $company = Company::findOne(['id' => $id]);
        if ($company->delete()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully deleted.', ['name' => $company->name]));
        } else {
            SiteController::FlashErrors($company);
        }

        return $this->redirect(['/company']);
    }

}
