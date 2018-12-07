<?php

namespace app\modules\admin\controllers;

use app\controllers\SiteController;
use app\models\LoginModel;
use app\models\Question;
use app\models\RegisterModel;
use app\models\TeamType;
use app\models\TeamTypeDimension;
use app\models\WheelQuestion;
use Yii;

class TeamTypeController extends AdminBaseController {

    public $layout = '//admin';

    public function actionIndex() {
        $teamTypes = TeamType::browse();

        return $this->render('index', [
            'teamTypes' => $teamTypes,
        ]);
    }

    public function actionView($id) {
        $teamType = TeamType::findOne($id);

        if (!isset($teamType)) {
            return $this->redirect(['index']);
        }

        return $this->render('view', [
            'teamType' => $teamType,
        ]);
    }

    public function actionEdit($id) {
        $teamType = TeamType::findOne(['id' => $id]);

        if (!isset($teamType)) {
            return $this->redirect(['index']);
        }

        if ($teamType->load(Yii::$app->request->post()) && $teamType->save()) {
            if (Yii::$app->request->post('action') == 'questions') {
                return $this->redirect(['questions', 'id' => $teamType->id]);
            } else if (Yii::$app->request->post('action') == 'dimensions') {
                return $this->redirect(['dimensions', 'id' => $teamType->id]);
            }

            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully edited.', ['name' => $teamType->name]));
            return $this->redirect(['view', 'id' => $teamType->id]);
        } else {
            SiteController::FlashErrors($teamType);
        }

        return $this->render('form', [
            'teamType' => $teamType,
        ]);
    }

    public function actionQuestions($id) {
        $teamType = TeamType::findOne(['id' => $id]);

        if (!isset($teamType)) {
            return $this->redirect(['index']);
        }

        if (Yii::$app->request->isPost) {
            $this->saveQuestions($teamType);
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully edited.', ['name' => $teamType->name]));
            return $this->redirect(['view', 'id' => $teamType->id]);
        } else {
            SiteController::FlashErrors($teamType);
        }

        return $this->render('questions', [
            'teamType' => $teamType,
        ]);
    }

    public function actionDimensions($id) {
        $teamType = TeamType::findOne(['id' => $id]);

        if (!isset($teamType)) {
            return $this->redirect(['index']);
        }

        if (Yii::$app->request->isPost) {
            $this->saveDimensions($teamType);
            SiteController::addFlash('success', Yii::t('app', '{name} has been successfully edited.', ['name' => $teamType->name]));
            return $this->redirect(['view', 'id' => $teamType->id]);
        } else {
            SiteController::FlashErrors($teamType);
        }

        return $this->render('dimensions', [
            'teamType' => $teamType,
        ]);
    }

    public function actionDuplicate($id) {
        $teamType = TeamType::findOne($id);

        if (!isset($teamType)) {
            return $this->redirect(['index']);
        }

        $count = 1;

        do {
            $new_name = "$teamType->name (copy" . ($count == 1 ? ')' : "-$count)");
            $exists = TeamType::findOne(['name' => $new_name]);
            $count++;
        } while ($exists);

        $new_teamType = new TeamType();

        $new_teamType->name = $new_name;
        $new_teamType->product_id = $teamType->product_id;
        $new_teamType->level_0_name = $teamType->level_0_name;
        $new_teamType->level_0_enabled = $teamType->level_0_enabled;
        $new_teamType->level_1_name = $teamType->level_1_name;
        $new_teamType->level_1_enabled = $teamType->level_1_enabled;
        $new_teamType->level_2_name = $teamType->level_2_name;
        $new_teamType->level_2_enabled = $teamType->level_2_enabled;

        $new_teamType->save();

        foreach ($teamType->rawWheelQuestions as $wheelQuestion) {
            $new_question = new WheelQuestion();
            $new_question->dimension = $wheelQuestion->dimension;
            $new_question->order = $wheelQuestion->order;
            $new_question->type = $wheelQuestion->type;
            $new_question->question_id = $wheelQuestion->question_id;
            $new_question->team_type_id = $new_teamType->id;

            $new_question->save();
        }

        foreach ($teamType->dimensions as $dimension) {
            $new_dimension = new TeamTypeDimension();
            $new_dimension->name = $dimension->name;
            $new_dimension->order = $dimension->order;
            $new_dimension->level = $dimension->level;
            $new_dimension->team_type_id = $new_teamType->id;

            $new_dimension->save();
        }

        SiteController::addFlash('success', Yii::t('app', '{name} has been successfully duplicated.', ['name' => $teamType->name]));

        return $this->redirect(['index']);
    }

    public function actionDelete($id) {
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

    private function saveQuestions($teamType) {
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
            $current_wheelQuestion->question_id = $questionId;
            $current_wheelQuestion->save();
        }
    }

    private function saveDimensions($teamType) {
        foreach (Yii::$app->request->post() as $key => $post) {
            if (strpos($key, 'd-') === false) {
                continue;
            }

            $parts = explode('-', $key);
            $level = $parts[1];
            $order = $parts[2];

            $current_dimension = null;
            foreach ($teamType->dimensions as $dimension) {
                if ($dimension->level == $level && $dimension->order == $order) {
                    $current_dimension = $dimension;
                    break;
                }
            }

            if (!$current_dimension) {
                $current_dimension = new TeamTypeDimension();
                $current_dimension->level = $level;
                $current_dimension->order = $order;
                $current_dimension->team_type_id= $teamType->id;
            }

            $current_dimension->name = $post;
            $current_dimension->save();
        }
    }

}
