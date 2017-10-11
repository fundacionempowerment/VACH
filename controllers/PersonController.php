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

    public function actionPhoto($id)
    {
        $tour = Tour::findOne($id);
        if (!$tour) {
            throw new NotFoundHttpException(Yii::t('app', 'Page not found'));
        }
        $picture = new TourPicture(['scenario' => 'upload']);
        $picture->tour_id = $id;
        $picture->image = UploadedFile::getInstance($picture, 'image');
        if ($picture->image !== null && $picture->validate(['image'])) {

            Yii::$app->response->getHeaders()->set('Vary', 'Accept');
            Yii::$app->response->format = Response::FORMAT_JSON;

            $response = [];

            if ($picture->save(false)) {
                // THIS IS THE RESPONSE UPLOADER REQUIRES!
                $response['files'][] = [
                    'name' => $picture->image->name,
                    'type' => $picture->image->type,
                    'size' => $picture->image->size,
                    'url' => $picture->getImageUrl(),
                    'thumbnailUrl' => $picture->getImageUrl(TourPicture::SMALL_IMAGE),
                    'deleteUrl' => Url::to(['delete', 'id' => $picture->id]),
                    'deleteType' => 'POST'
                ];
            } else {
                $response[] = ['error' => Yii::t('app', 'Unable to save picture')];
            }
            @unlink($picture->image->tempName);
        } else {
            if ($picture->hasErrors(['picture'])) {
                $response[] = ['error' => HtmlHelper::errors($picture)];
            } else {
                throw new HttpException(500, Yii::t('app', 'Could not upload file.'));
            }
        }
        return $response;
    }

}
