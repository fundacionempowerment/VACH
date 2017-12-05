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
use app\models\TeamType;
use app\controllers\SiteController;

class TeamTypeController extends AdminBaseController
{

    public $layout = '//admin';

    public function actionIndex()
    {
        $teamTypes = TeamType::browse();

        return $this->render('index', [
                    'teamTypes' => $teamTypes,
        ]);
    }

    public function actionView($id)
    {
        $teamType = TeamType::findOne($id);

        if (!isset($teamType)) {
            return $this->redirect(['index']);
        }

        return $this->render('view', [
                    'teamType' => $teamType,
        ]);
    }

    public function actionEdit($id)
    {
        $teamType = TeamType::findOne(['id' => $id]);

        if (!isset($teamType)) {
            return $this->redirect(['index']);
        }

        if ($teamType->load(Yii::$app->request->post()) && $teamType->save()) {
            $this->saveQuestions($teamType);
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully edited.', ['name' => $teamType->name]));
            return $this->redirect(['view', 'id' => $teamType->id]);
        } else {
            SiteController::FlashErrors($teamType);
        }

        return $this->render('form', [
                    'teamType' => $teamType,
        ]);
    }

    public function actionDuplicate($id)
    {
        $teamType = TeamType::findOne($id);

        if (!isset($teamType)) {
            return $this->redirect(['index']);
        }

        $count = 1;

        do {
            $new_name = "$teamType->name (copy" . ($count == 1 ? ')' : "-$count)");
            $exists = TeamType::findOne(['name' => $new_name]);
            $count ++;
        } while ($exists);

        $new_teamType = new TeamType();

        $new_teamType->name = $new_name;
        $new_teamType->product_id = $teamType->product_id;

        $new_teamType->save();

        foreach ($teamType->wheelQuestions as $wheelQuestion) {
            $new_question = new WheelQuestion();
            $new_question->dimension = $wheelQuestion->dimension;
            $new_question->order = $wheelQuestion->order;
            $new_question->type = $wheelQuestion->type;
            $new_question->question_id = $wheelQuestion->question_id;
            $new_question->team_type_id = $new_teamType->id;

            $new_question->save();
        }

        SiteController::addFlash('success', Yii::t('app', '{name} has been successfully duplicated.', ['name' => $teamType->name]));

        return $this->redirect(['index']);
    }

    public function actionDelete($id)
    {
        $teamType = TeamType::findOne($id);

        if (!isset($teamType)) {
            return $this->redirect(['index']);
        }

        if ($teamType->delete()) {
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully deleted.', ['name' => $teamType->name]));
        } else {
            SiteController::FlashErrors($teamType);
        }
        return $this->redirect(['index']);
    }

    private function saveQuestions($teamType)
    {
        foreach (Yii::$app->request->post() as $key => $post) {
            if (strpos($key, 'q-') === false) {
                continue;
            }

            $parts = explode('-', $key);
            $type = $parts[1];
            $dimension = $parts[2];
            $order = $parts[3];

            $current_wheelQuestion = null;
            foreach ($teamType->wheelQuestions as $wheelQuestion) {
                if ($wheelQuestion->type == $type && $wheelQuestion->dimension == $dimension && $wheelQuestion->order == $order) {
                    $current_wheelQuestion = $wheelQuestion;
                    break;
                }
            }

            if (!$current_wheelQuestion) {
                $current_wheelQuestion = new WheelQuestion();
                $current_wheelQuestion->type = $type;
                $current_wheelQuestion->dimension = $dimension;
                $current_wheelQuestion->order = $order;
            }

            $questionId = Question::getId($post);
            $wheelQuestion->question_id = $questionId;
            $wheelQuestion->save();
        }
    }

}
