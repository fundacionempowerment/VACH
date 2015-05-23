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
        ['¿Me divierto y participo del ocio?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Tengo claro cuáles son mis momentos de ocio?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Logro separar mi familia del ocio?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Logro separar mi trabajo del ocio?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Desarrollo personal?', WheelModel::NONE_TO_ALL],
        ['¿Identifico un hobby en especial?', WheelModel::NONE_TO_ALL],
        ['¿Tengo actividades de ocio programadas?', WheelModel::NONE_TO_ALL],
        ['¿Tengo actividades de ocio compartidas?', WheelModel::NONE_TO_ALL],
        ['¿Me estimula el juntarme con amigos?', WheelModel::NONE_TO_ALL],
        ['¿Qué interés tenés en trabajar esta dimensión?', WheelModel::NONE_TO_ALL],
        //Trabajo
        ['¿Ambiente y entorno de trabajo?', WheelModel::WORST_TO_OPTIMAL],
        ['¿Se valoran mis capacidades?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Mi trabajo es acorde a mi vocación?', WheelModel::NONE_TO_ALL],
        ['¿Tengo un trabajo estimulante?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Tengo posibilidad de crecimiento?', WheelModel::NONE_TO_ALL],
        ['¿Tengo claridad con mis Proyectos de trabajo?', WheelModel::NONE_TO_ALL],
        ['¿Controlo el estrés?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Tengo un presupuesto estable?', WheelModel::NONE_TO_ALL],
        ['¿Estado de satisfacción en gral con mi trabajo?', WheelModel::NONE_TO_ALL],
        ['¿Qué interés tenés en trabajar esta dimensión?', WheelModel::NONE_TO_ALL],
        //Familia
        ['¿Disponibilidad para la familia?', WheelModel::NONE_TO_ALL],
        ['¿Relación con los padres?', WheelModel::WORST_TO_OPTIMAL],
        ['¿Relación de pareja?', WheelModel::WORST_TO_OPTIMAL],
        ['¿Relación con los hijos?', WheelModel::WORST_TO_OPTIMAL],
        ['¿Relación con otros miembros de la familia?', WheelModel::WORST_TO_OPTIMAL],
        ['¿Actividades recreativas en conjunto?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Comodidades familiares?', WheelModel::WORST_TO_OPTIMAL],
        ['¿Proyecto en común?', WheelModel::NONE_TO_ALL],
        ['¿Nivel de satisfacción en general?', WheelModel::WORST_TO_OPTIMAL],
        ['¿Qué interés tenés en trabajar esta dimensión?', WheelModel::NONE_TO_ALL],
        //Dimensión Fisica
        ['¿Apariencia?', WheelModel::WORST_TO_OPTIMAL],
        ['¿Energía?', WheelModel::WORST_TO_OPTIMAL],
        ['¿Actividad física?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Dieta equilibrada?', WheelModel::WORST_TO_OPTIMAL],
        ['¿Estimulación de sentidos?', WheelModel::WORST_TO_OPTIMAL],
        ['¿Ambiente y Entorno en general?', WheelModel::WORST_TO_OPTIMAL],
        ['¿Nivel de materialización de los Proyectos?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Hábitos que no me gustan?', WheelModel::ALL_TO_NONE],
        ['¿Estado de salud en general?', WheelModel::WORST_TO_OPTIMAL],
        ['¿Qué interés tenés en trabajar esta dimensión?', WheelModel::NONE_TO_ALL],
        //Dimensión Emocional
        ['¿Mis estados de ánimo son estables?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Me conecto con mi pasión?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Me conecto con mi voluntad?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Mi actitud emocional es positiva?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Tengo una vida estimulante?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Cómo es mi autoestima?', WheelModel::WORST_TO_OPTIMAL],
        ['¿Ansiedad?', WheelModel::ALL_TO_NONE],
        ['¿Nostalgia?', WheelModel::ALL_TO_NONE],
        ['¿Miedos?', WheelModel::ALL_TO_NONE],
        ['¿Qué interés tenés en trabajar esta dimensión?', WheelModel::NONE_TO_ALL],
        //Dimensión Mental
        ['¿Qué grado de felicidad tengo?', WheelModel::NONE_TO_ALL],
        ['¿Hay alguna situación que no me deja ser feliz?', WheelModel::ALL_TO_NONE],
        ['¿Qué tan seguido me encuentro juzgando las situaciones?', WheelModel::ALL_TO_NONE],
        ['¿Qué tan seguido me descubro tejiendo fantasías?', WheelModel::ALL_TO_NONE],
        ['¿Estoy esperando algo de alguien?', WheelModel::ALWAYS_TO_NEVER],
        ['¿Espero que las personas adivinen lo que necesito?', WheelModel::ALWAYS_TO_NEVER],
        ['¿Soy racional?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Mi actitud mental es positiva?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Tengo una mentalidad abierta?', WheelModel::NONE_TO_ALL],
        ['¿Qué interés tenés en trabajar esta dimensión?', WheelModel::NONE_TO_ALL],
        //Dimensión Existencial
        ['¿Me considero una persona de mucha consciencia?', WheelModel::NONE_TO_ALL],
        ['¿Me preocupo por el crecimiento de los demás?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Tengo claridad con mi vocación?', WheelModel::NONE_TO_ALL],
        ['¿Tengo claridad en cuál es mi servicio diferencial?', WheelModel::NONE_TO_ALL],
        ['¿Qué nivel de Escucha tengo?', WheelModel::WORST_TO_OPTIMAL],
        ['¿Qué nivel de Comunicación tengo?', WheelModel::WORST_TO_OPTIMAL],
        ['¿Las relaciones con mis Vínculos son sanas?', WheelModel::WORST_TO_OPTIMAL],
        ['¿Participo en Comunidades y trabajos sociales?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Existe satisfacción con la Comunidad en que vivo?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Qué interés tenés en trabajar esta dimensión?', WheelModel::NONE_TO_ALL],
        //Dimensión Espiritual
        ['¿Creo en algún tipo de configuración superior?', WheelModel::NONE_TO_ALL],
        ['¿Soy congruente con ese tipo de configuración?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Tengo claridad con mi propósito de trascendencia?', WheelModel::NONE_TO_ALL],
        ['¿Soy congruente en mi vida con mis Valores?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Me mantengo en un continuo con el Amor?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Me mantengo en un continuo con la Verdad?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Doy sin esperar recibir?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Me mantengo en un continuo con la Paciencia?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Tengo una rutina espiritual?', WheelModel::NEVER_TO_ALWAYS],
        ['¿Qué interés tenés en trabajar esta dimensión?', WheelModel::NONE_TO_ALL],
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
        $showMissingAnswers = false;

        $model = new WheelModel();
        if (Yii::$app->request->isPost) {
            $showMissingAnswers = true;

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
                    $model->answers[] = rand(0, 4);
            } else {
                for ($i = 0; $i < 80; $i++)
                    if (!isset($answer[$i]))
                        $answer[$i] = rand(0, 4);
                    else if ($answer[$i] < 0 || $answer[$i] > 4)
                        $answer[$i] = rand(0, 4);
            }
        }

        return $this->render('details', [
                    'model' => $model,
                    'questions' => $this->questions,
                    'dimensions' => $this->dimensions,
                    'showMissingAnswers' => $showMissingAnswers,
        ]);
    }

}
