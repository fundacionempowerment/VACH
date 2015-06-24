<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginModel;
use app\models\RegisterModel;
use app\models\User;
use app\models\CoachModel;
use app\models\ClientModel;
use app\models\Goal;
use app\models\GoalMilestone;
use app\models\GoalResource;
use app\models\Coachee;

class GoalController extends Controller {

    public $layout = 'inner';

    public function actionIndex($id) {
        return $this->redirect(['/goal/view', 'id' => $id]);
    }

    public function actionView($id) {
        $goal = Goal::findOne(['id' => $id]);

        if (count($goal->resources) == 0)
            $this->redirect(['/goal/resources', 'id' => $goal->id]);

        return $this->render('view', [
                    'goal' => $goal,
        ]);
    }

    public function actionNew($coachee_id) {
        $goal = new Goal();
        $goal->coachee_id = $coachee_id;

        $this->save($goal);
        return $this->render('form', [
                    'goal' => $goal,
        ]);
    }

    public function actionEdit($id) {
        $goal = Goal::findOne(['id' => $id]);

        $this->save($goal);

        return $this->render('form', [
                    'goal' => $goal,
        ]);
    }

    private function save($goal) {
        if ($goal->load(Yii::$app->request->post()) && $goal->save()) {
            \Yii::$app->session->addFlash('success', \Yii::t('goal', 'Goal has been succesfully created.'));
            $this->redirect(['/goal/resources', 'id' => $goal->id]);
        } else {
            foreach ($goal->getErrors() as $attribute => $errors)
                foreach ($errors as $error)
                    \Yii::$app->session->addFlash('error', \Yii::t('goal', 'Goal not saved: ') . $error);
        }
    }

    public function actionResources($id) {
        $goal = Goal::findOne(['id' => $id]);

        if (Yii::$app->request->isPost) {

            // add required resources
            $raw_resources = Yii::$app->request->post('required');
            $new_required_resources = explode(PHP_EOL, $raw_resources);

            foreach ($new_required_resources as $new_resource) {
                $new_resource = trim($new_resource);
                if (!$new_resource)
                    continue;

                $exists = false;
                foreach ($goal->resources as $current_resource) {
                    if ($current_resource['description'] == $new_resource) {
                        $exists = true;
                        break;
                    }
                }

                if (!$exists) {
                    $new_goal_resource = new GoalResource();
                    $new_goal_resource->description = $new_resource;
                    $new_goal_resource->is_desired = true;
                    $goal->link('resources', $new_goal_resource, ['goal_id', 'id']);
                }
            }

            // add preventing resources
            $raw_resources = Yii::$app->request->post('prevent');
            $new_prevent_resources = explode(PHP_EOL, $raw_resources);
            foreach ($new_prevent_resources as $new_resource) {
                $new_resource = trim($new_resource);
                if (!$new_resource)
                    continue;

                $exists = false;
                foreach ($goal->resources as $current_resource) {
                    if ($current_resource['description'] == $new_resource) {
                        $exists = true;
                        break;
                    }
                }

                if (!$exists) {
                    $new_goal_resource = new GoalResource();
                    $new_goal_resource->description = $new_resource;
                    $new_goal_resource->is_desired = false;
                    $goal->link('resources', $new_goal_resource, ['goal_id', 'id']);
                }
            }

            // delete resources no longer usefull
            foreach ($goal->resources as $current_resource) {
                $exists = false;
                foreach ($new_required_resources as $new_resource) {
                    $new_resource = trim($new_resource);
                    if (!$new_resource)
                        continue;
                    if ($current_resource['description'] == $new_resource) {
                        $exists = true;
                        break;
                    }
                }
                foreach ($new_prevent_resources as $new_resource) {
                    $new_resource = trim($new_resource);
                    if (!$new_resource)
                        continue;
                    if ($current_resource['description'] == $new_resource) {
                        $exists = true;
                        break;
                    }
                }

                if (!$exists) {
                    $current_resource->delete();
                }
            }

            return $this->redirect(['/goal/assign-resources', 'id' => $goal->id]);
        }

        return $this->render('resources-form', [
                    'goal' => $goal,
        ]);
    }

    public function actionAssignResources($id) {
        $goal = Goal::findOne(['id' => $id]);

        if (Yii::$app->request->isPost) {
            foreach ($goal->resources as $current_resource) {
                $is_had = Yii::$app->request->post('have' . $current_resource->id);

                $current_resource->is_had = $is_had ? : 0;
                $current_resource->save();
            }

            return $this->redirect(['/goal/view', 'id' => $goal->id]);
        }

        return $this->render('assign', [
                    'goal' => $goal,
        ]);
    }

    public function actionReviewResources($id) {
        $goal = Goal::findOne(['id' => $id]);

        $conserve = [];
        $conquer = [];
        $eliminate = [];
        $avoid = [];

        return $this->render('review', [
                    'goal' => $goal,
        ]);
    }

    public function actionNewMilestone($id) {
        $goal = Goal::findOne(['id' => $id]);
        $milestone = new GoalMilestone();

        if ($milestone->load(Yii::$app->request->post())) {
            $goal->link('milestones', $milestone, ['goal_id', 'id']);
            \Yii::$app->session->addFlash('success', \Yii::t('goal', 'Milestone has been succesfully created.'));
            return $this->redirect(['/goal/view', 'id' => $goal->id]);
        }
        return $this->render('milestone-form', [
                    'goal' => $goal,
                    'milestone' => $milestone,
        ]);
    }

    public function actionEditMilestone($id) {
        $milestone = GoalMilestone::findOne(['id' => $id]);
        $goal = $milestone->goal;

        if ($milestone->load(Yii::$app->request->post()) && $milestone->save()) {
            $goal->link('milestones', $milestone, ['goal_id', 'id']);
            \Yii::$app->session->addFlash('success', \Yii::t('goal', 'Milestone has been succesfully created.'));
            return $this->redirect(['/goal/view', 'id' => $goal->id]);
        }
        return $this->render('milestone-form', [
                    'goal' => $goal,
                    'milestone' => $milestone,
        ]);
    }

    public function actionDelete($id) {
        $goal = Goal::findOne(['id' => $id]);
        if ($goal->delete()) {
            \Yii::$app->session->addFlash('success', \Yii::t('goal', 'Goal deleted.'));
        } else {
            \Yii::$app->session->addFlash('error', \Yii::t('goal', 'Goal not delete:')
                    . $goal->getErrors());
        }
        return $this->redirect(['/coachee/view', 'id' => $goal->coachee->id]);
    }

    public function actionPlan($coachee_id) {
        $coachee = Coachee::findOne(['id' => $coachee_id]);
        $milestones = GoalMilestone::getPlan($coachee_id);

        if (Yii::$app->request->get('printable') != null)
            $this->layout = 'printable';

        return $this->render('plan', [
                    'coachee' => $coachee,
                    'milestones' => $milestones,
        ]);
    }

}

