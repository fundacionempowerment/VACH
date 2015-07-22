<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\CoachModel;
use app\models\Person;

class PersonController extends Controller {

    public $layout = 'inner';

    public function actionIndex() {
        $persons = Person::browse();
        return $this->render('index', [
                    'persons' => $persons,
        ]);
    }

    public function actionView($id) {
        $person = Person::findOne(['id' => $id]);
        Yii::$app->session->set('person_id', $id);
        return $this->render('view', [
                    'person' => $person,
        ]);
    }

    public function actionNew() {
        $person = new Person();

        if ($person->load(Yii::$app->request->post()) && $person->save()) {
            \Yii::$app->session->addFlash('success', \Yii::t('user', 'Person has been succesfully created.'));
            return $this->redirect(['/person']);
        } else {
            foreach ($person->getErrors() as $attribute => $errors)
                foreach ($errors as $error)
                    \Yii::$app->session->addFlash('error', \Yii::t('user', 'Person not saved: ') . $error);
        }

        return $this->render('form', [
                    'person' => $person,
        ]);
    }

    public function actionEdit($id) {
        $person = Person::findOne(['id' => $id]);

        if ($person->load(Yii::$app->request->post()) && $person->save()) {
            \Yii::$app->session->addFlash('success', \Yii::t('user', 'Person has been succesfully created.'));
            return $this->redirect(['/person']);
        } else {
            foreach ($person->getErrors() as $attribute => $errors)
                foreach ($errors as $error)
                    \Yii::$app->session->addFlash('error', \Yii::t('user', 'Person not saved: ') . $error);
        }

        return $this->render('form', [
                    'person' => $person,
        ]);
    }

    public function actionDelete($id) {
        $person = Person::findOne(['id' => $id]);
        if ($person->delete()) {
            \Yii::$app->session->addFlash('success', \Yii::t('user', 'Person has been succesfully deleted.'));
            return $this->redirect(['/person']);
        } else {
            foreach ($person->getErrors() as $attribute => $errors)
                foreach ($errors as $error)
                    \Yii::$app->session->addFlash('error', \Yii::t('user', 'Person not deleted: ') . $error);
        }
    }

}
