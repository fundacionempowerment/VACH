<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\CoachModel;
use app\models\Person;

class PersonController extends BaseController
{

    public $layout = 'inner';

    public function actionIndex()
    {
        $persons = Person::browse();
        return $this->render('index', [
                    'persons' => $persons,
        ]);
    }

    public function actionView($id)
    {
        $person = Person::findOne(['id' => $id]);

        if (!$person || $person->coach_id != Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }
        Yii::$app->session->set('person_id', $id);
        return $this->render('view', [
                    'person' => $person,
        ]);
    }

    public function actionNew()
    {
        $person = new Person();

        if ($person->load(Yii::$app->request->post()) && $person->save()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully created.', ['name' => $person->fullname]));
            return $this->redirect(['/person']);
        } else {
            SiteController::FlashErrors($person);
        }

        return $this->render('form', [
                    'person' => $person,
        ]);
    }

    public function actionEdit($id)
    {
        $person = Person::findOne(['id' => $id]);

        if (!$person || $person->coach_id != Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }

        if ($person->load(Yii::$app->request->post()) && $person->save()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully edited.', ['name' => $person->fullname]));
            return $this->redirect(['/person']);
        } else {
            SiteController::FlashErrors($person);
        }

        return $this->render('form', [
                    'person' => $person,
        ]);
    }

    public function actionDelete($id)
    {
        $person = Person::findOne(['id' => $id]);

        if (!$person || $person->coach_id != Yii::$app->user->id) {
            throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'Your not allowed to access this page.'));
        }

        if ($person->delete()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully deleted.', ['name' => $person->fullname]));
            return $this->redirect(['/person']);
        } else {
            SiteController::FlashErrors($person);
        }
    }

}
