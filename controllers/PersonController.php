<?php

namespace app\controllers;

use app\models\search\PersonSearch;
use Yii;
use yii\web\Controller;
use app\models\CoachModel;
use app\models\Person;

class PersonController extends BaseController
{

    public $layout = 'inner';

    public function actionIndex()
    {
        $searchModel = new PersonSearch();
        $persons = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'persons' => $persons,
            'searchModel' => $searchModel,
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

        if ($person->load(Yii::$app->request->post())) {
            $this->upload($person, 'photo');

            if ($person->save()) {
                SiteController::addFlash('success', Yii::t('app', '{name} has been successfully created.', ['name' => $person->fullname]));
                return $this->redirect(['/person']);
            } else {
                SiteController::FlashErrors($person);
            }
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

        if ($person->load(Yii::$app->request->post())) {
            self::upload($person, 'photo');

            if ($person->save()) {
                SiteController::addFlash('success', Yii::t('app', '{name} has been successfully edited.', ['name' => $person->fullname]));
                return $this->redirect(['/person']);
            } else {
                SiteController::FlashErrors($person);
            }
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

    public static function upload($model, $attr)
    {
        $file = \yii\web\UploadedFile::getInstance($model, $attr);

        if ($file && $model->validate()) {
            $fileName = uniqid($attr) . '.' . $file->extension;
            $filePath = Yii::getAlias('@webroot/photos/' . $fileName);

            $file->saveAs($filePath);
            $model->$attr = $fileName;

            return true;
        } else {
            return false;
        }
    }

}
