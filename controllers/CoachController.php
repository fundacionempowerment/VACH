<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\CoachModel;
use app\models\Client;

class CoachController extends Controller {

    public $layout = 'inner';

    public function actionIndex() {
        $model = new CoachModel();
        $model->id = Yii::$app->user->id;

        return $this->redirect(['clients']);
    }

    public function actionIntro() {
        return $this->render('intro', [
        ]);
    }

    public function actionNewclient() {
        $client = new Client();

        if ($client->load(Yii::$app->request->post()) && $client->save()) {
            \Yii::$app->session->addFlash('success', \Yii::t('user', 'Client has been succesfully created.'));
            return $this->redirect(['/coach/clients']);
        } else {
            foreach ($client->getErrors() as $attribute => $errors)
                foreach ($errors as $error)
                    \Yii::$app->session->addFlash('error', \Yii::t('user', 'Client not saved: ') . $error);
        }

        return $this->render('client', [
                    'client' => $client,
        ]);
    }

    public function actionClient($id) {
        $client = Client::findOne(['id' => $id]);

        if (isset($_GET['delete']) && $_GET['delete'] == 1)
            if ($client->delete()) {
                \Yii::$app->session->addFlash('success', \Yii::t('user', 'Client has been succesfully deleted.'));
                return $this->redirect(['/coach/clients']);
            } else {
                foreach ($client->getErrors() as $attribute => $errors)
                    foreach ($errors as $error)
                        \Yii::$app->session->addFlash('error', \Yii::t('user', 'Client not deleted: ') . $error);
            }

        if ($client->load(Yii::$app->request->post()) && $client->save()) {
            \Yii::$app->session->addFlash('success', \Yii::t('user', 'Client has been succesfully created.'));
            return $this->redirect(['/coach/clients']);
        } else {
            foreach ($client->getErrors() as $attribute => $errors)
                foreach ($errors as $error)
                    \Yii::$app->session->addFlash('error', \Yii::t('user', 'Client not saved: ') . $error);
        }

        return $this->render('client', [
                    'client' => $client,
        ]);
    }

    public function actionClients() {
        $clients = Client::find()->where(['coach_id' => Yii::$app->user->id]);
        return $this->render('clients', [
                    'clients' => $clients,
        ]);
    }

}
