<?php

namespace app\controllers;

use yii\web\Controller;

class RunController extends Controller {

    public function actionIndex($a, $b, $c) {
        return $this->redirect(['wheel/run', 'token' => $a . '-' . $b . '-' . $c]);
    }

}