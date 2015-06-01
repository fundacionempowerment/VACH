<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\CoachModel;
use app\models\Coachee;

class CoacheeController extends Controller {

    public $layout = 'inner';

    public function actionIndex() {
        $coachees = Coachee::find()->where(['coach_id' => Yii::$app->user->id]);
        return $this->render('index', [
                    'coachees' => $coachees,
        ]);
    }

    public function actionView($id) {
        $coachee = Coachee::findOne(['id' => $id]);
        return $this->render('view', [
                    'coachee' => $coachee,
        ]);
    }

    public function actionNew() {
        $coachee = new Coachee();

        if ($coachee->load(Yii::$app->request->post()) && $coachee->save()) {
            \Yii::$app->session->addFlash('success', \Yii::t('user', 'Coachee has been succesfully created.'));
            return $this->redirect(['/coachee']);
        } else {
            foreach ($coachee->getErrors() as $attribute => $errors)
                foreach ($errors as $error)
                    \Yii::$app->session->addFlash('error', \Yii::t('user', 'Coachee not saved: ') . $error);
        }

        return $this->render('form', [
                    'coachee' => $coachee,
        ]);
    }

    public function actionEdit($id) {
        $coachee = Coachee::findOne(['id' => $id]);

        if ($coachee->load(Yii::$app->request->post()) && $coachee->save()) {
            \Yii::$app->session->addFlash('success', \Yii::t('user', 'Coachee has been succesfully created.'));
            return $this->redirect(['/coachee']);
        } else {
            foreach ($coachee->getErrors() as $attribute => $errors)
                foreach ($errors as $error)
                    \Yii::$app->session->addFlash('error', \Yii::t('user', 'Coachee not saved: ') . $error);
        }

        return $this->render('form', [
                    'coachee' => $coachee,
        ]);
    }

    public function actionDelete($id) {
        $coachee = Coachee::findOne(['id' => $id]);
        if ($coachee->delete()) {
            \Yii::$app->session->addFlash('success', \Yii::t('user', 'Coachee has been succesfully deleted.'));
            return $this->redirect(['/coachee']);
        } else {
            foreach ($coachee->getErrors() as $attribute => $errors)
                foreach ($errors as $error)
                    \Yii::$app->session->addFlash('error', \Yii::t('user', 'Coachee not deleted: ') . $error);
        }
    }

}
