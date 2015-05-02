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
use app\models\WheelModel;

class WheelController extends Controller {

    public $layout = 'inner';
    public $dimensions = [
        'Tiempo libre',
        'Trabajo',
        'Familia',
        'Dimensión física',
        'Dimensión emocional',
        'Dimensión mental',
        'Dimensión existencial',
        'Dimensión espiritual',
    ];
    public $shortDimensions = [
        'Tiempo libre',
        'Trabajo',
        'Familia',
        'D. física',
        'D. emocional',
        'D. mental',
        'D. existencial',
        'D. espiritual',
    ];
    public $questions = [
        // Tiempo libre
        '¿Me divierto y participo del ocio?',
        '¿Tengo claro cuáles son mis momentos de ocio?',
        '¿Logro separar mi familia del ocio?',
        '¿Logro separar mi trabajo del ocio?',
        '¿Desarrollo personal?',
        '¿Identifico un hobby en especial?',
        '¿Tengo actividades de ocio programadas?',
        '¿Tengo actividades de ocio compartidas?',
        '¿Me estimula el juntarme con amigos?',
        '¿Qué interés tenés en trabajar esta dimensión?',
        //Trabajo
        '¿Ambiente y entorno de trabajo?',
        '¿Se valoran mis capacidades?',
        '¿Mi trabajo es acorde a mi vocación?',
        '¿Tengo un trabajo estimulante?',
        '¿Tengo posibilidad de crecimiento?',
        '¿Tengo claridad con mis Proyectos de trabajo?',
        '¿Controlo el estrés?',
        '¿Tengo un presupuesto estable?',
        '¿Estado de satisfacción en gral con mi trabajo?',
        '¿Qué interés tenés en trabajar esta dimensión?',
        //Familia
        '¿Disponibilidad para la familia?',
        '¿Relación con los padres?',
        '¿Relación de pareja?',
        '¿Relación con los hijos?',
        '¿Relación con otros miembros de la familia?',
        '¿Actividades recreativas en conjunto?',
        '¿Comodidades familiares?',
        '¿Proyecto en común?',
        '¿Nivel de satisfacción en general?',
        '¿Qué interés tenés en trabajar esta dimensión?',
        //Dimensión Fisica
        '¿Apariencia?',
        '¿Energía?',
        '¿Actividad física?',
        '¿Dieta equilibrada?',
        '¿Estimulación de sentidos?',
        '¿Ambiente y Entorno en general?',
        '¿Nivel de materialización de los Proyectos?',
        '¿Hábitos que no me gustan?',
        '¿Estado de salud en general?',
        '¿Qué interés tenés en trabajar esta dimensión?',
        //Dimensión Emocional
        ' ¿Mis estados de ánimo son estables?',
        '¿Me conecto con mi pasión?',
        '¿Me conecto con mi voluntad?',
        '¿Mi actitud emocional es positiva?',
        '¿Tengo una vida estimulante?',
        '¿Cómo es mi autoestima?',
        '¿Ansiedad?',
        '¿Nostalgia?',
        '¿Miedos?',
        '¿Qué interés tenés en trabajar esta dimensión?',
        //Dimensión Mental
        '¿Qué grado de felicidad tengo?',
        '¿Hay alguna situación que no me deja ser feliz?',
        '¿Qué tan seguido me encuentro juzgando las situaciones?',
        '¿Qué tan seguido me descubro tejiendo fantasías?',
        '¿Estoy esperando algo de alguien?',
        '¿Espero que las personas adivinen lo que necesito?',
        '¿Soy racional?',
        '¿Mi actitud mental es positiva?',
        '¿Tengo una mentalidad abierta?',
        '¿Qué interés tenés en trabajar esta dimensión?',
        //Dimensión Existencial
        '¿Me considero una persona de mucha consciencia?',
        '¿Me preocupo por el crecimiento de los demás?',
        '¿Tengo claridad con mi vocación?',
        '¿Tengo claridad en cuál es mi servicio diferencial?',
        '¿Qué nivel de Escucha tengo?',
        '¿Qué nivel de Comunicación tengo?',
        '¿Las relaciones con mis Vínculos son sanas?',
        '¿Participo en Comunidades y trabajos sociales?',
        '¿Existe satisfacción con la Comunidad en que vivo?',
        '¿Qué interés tenés en trabajar esta dimensión?',
        //Dimensión Espiritual
        '¿Creo en algún tipo de configuración superior?',
        '¿Soy congruente con ese tipo de configuración?',
        '¿Tengo claridad con mi propósito de trascendencia?',
        '¿Soy congruente en mi vida con mis Valores?',
        '¿Me mantengo en un continuo con el Amor?',
        '¿Me mantengo en un continuo con la Verdad?',
        '¿Doy sin esperar recibir?',
        '¿Me mantengo en un continuo con la Paciencia?',
        '¿Tengo una rutina espiritual?',
        '¿Qué interés tenés en trabajar esta dimensión?',
    ];

    public function actionIndex() {
        if (Yii::$app->request->get('clientid')) {
            Yii::$app->session->set('clientid', Yii::$app->request->get('clientid'));
            Yii::$app->session->set('wheelid', null);
            Yii::$app->session->set('compareid', -1);
        }

        if (Yii::$app->request->get('wheelid')) {
            Yii::$app->session->set('wheelid', Yii::$app->request->get('wheelid'));
        }

        if (Yii::$app->request->get('compareid')) {
            Yii::$app->session->set('compareid', Yii::$app->request->get('compareid'));
        }

        $userId = Yii::$app->session->get('clientid');
        $wheelid = Yii::$app->session->get('wheelid');
        $compareId = Yii::$app->session->get('compareid');

        $model = new WheelModel();
        $model->coacheeId = $userId;

        if ($wheelid == 0) {
            $model->populateLast();
            $wheelid = $model->id;
        } else {
            $model->id = $wheelid;
            $model->populate();
        }

        $compareModel = new WheelModel();
        if ($compareId > 0) {
            $compareModel->id = $compareId;
            $compareModel->populate();
        }


        $wheels = $model->browse();
        $wheelArray;
        foreach ($wheels as $wheel) {
            $wheelArray[$wheel['id']] = $wheel['date'];
        }

        if ($model->id == 0)
            return $this->redirect(['form', 'id' => 0]);
        else
            return $this->render('view', [
                        'model' => $model,
                        'compare' => $compareModel,
                        'wheels' => $wheelArray,
                        'id' => $wheelid,
                        'compareId' => $compareId,
                        'dimensions' => $this->shortDimensions,
            ]);
    }

    public function actionForm() {
        $model = new WheelModel();
        if (Yii::$app->request->isPost) {

            $model->coacheeId = $userId = Yii::$app->session->get('clientid');
            $model->date = date(DATE_ATOM);

            for ($i = 0; $i < 80; $i++) {
                $answer = Yii::$app->request->post('answer' . $i);
                if ($answer)
                    $model->answers[$i] = $answer;
            }

            if ($model->validate()) {
                $model->save();
                return $this->redirect(['index']);
            }
        } else {
            $id = Yii::$app->request->get('Id');

            if ($id > 0) {
                $model->id = $id;
                $model->populate();
            }
        }

        if (defined('YII_DEBUG')) {
            if (!is_array($model->answers)) {
                for ($i = 0; $i < 80; $i++)
                    $model->answers[] = rand(0, 9);
            } else {
                for ($i = 0; $i < 80; $i++)
                    if (!isset($answer[$i]))
                        $answer[$i] = rand(0, 9);
                    else if ($answer[$i] < 0 || $answer[$i] > 9)
                        $answer[$i] = rand(0, 9);
            }
        }

        return $this->render('details', [
                    'model' => $model,
                    'questions' => $this->questions,
                    'dimensions' => $this->dimensions,
        ]);
    }

}
