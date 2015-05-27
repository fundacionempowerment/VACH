<?php

use yii\db\Schema;
use yii\db\Migration;
use app\models\WheelQuestion;

class m150527_035800_add_wheel_questions_table extends Migration {

    public function up() {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%wheel_question}}', [
            'id' => Schema::TYPE_PK,
            'dimension' => Schema::TYPE_INTEGER . ' NOT NULL',
            'order' => Schema::TYPE_INTEGER . ' NOT NULL',
            'question' => Schema::TYPE_TEXT . ' NOT NULL',
            'answer_type' => Schema::TYPE_INTEGER . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
                ], $tableOptions);


        $questions = [
            // Tiempo libre
            ['¿Me divierto y participo del ocio?', WheelQuestion::ANSWER_NUMBERS_0_TO_4],
            ['¿Tengo claro cuáles son mis momentos de ocio?', WheelQuestion::ANSWER_NUMBERS_0_TO_4],
            ['¿Logro separar mi familia del ocio?', WheelQuestion::ANSWER_NUMBERS_0_TO_4],
            ['¿Logro separar mi trabajo del ocio?', WheelQuestion::ANSWER_NUMBERS_0_TO_4],
            ['¿Desarrollo personal?', WheelQuestion::ANSWER_NUMBERS_0_TO_4],
            ['¿Identifico un hobby en especial?', WheelQuestion::ANSWER_NUMBERS_0_TO_4],
            ['¿Tengo actividades de ocio programadas?', WheelQuestion::ANSWER_NUMBERS_0_TO_4],
            ['¿Tengo actividades de ocio compartidas?', WheelQuestion::ANSWER_NUMBERS_0_TO_4],
            ['¿Me estimula el juntarme con amigos?', WheelQuestion::ANSWER_NUMBERS_0_TO_4],
            ['¿Qué interés tenés en trabajar esta dimensión?', WheelQuestion::ANSWER_NUMBERS_0_TO_4],
            //Trabajo
            ['¿Ambiente y entorno de trabajo?', WheelQuestion::ANSWER_WORST_TO_OPTIMAL],
            ['¿Se valoran mis capacidades?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Mi trabajo es acorde a mi vocación?', WheelQuestion::ANSWER_NONE_TO_ALL],
            ['¿Tengo un trabajo estimulante?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Tengo posibilidad de crecimiento?', WheelQuestion::ANSWER_NONE_TO_ALL],
            ['¿Tengo claridad con mis Proyectos de trabajo?', WheelQuestion::ANSWER_NONE_TO_ALL],
            ['¿Controlo el estrés?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Tengo un presupuesto estable?', WheelQuestion::ANSWER_NONE_TO_ALL],
            ['¿Estado de satisfacción en gral con mi trabajo?', WheelQuestion::ANSWER_NONE_TO_ALL],
            ['¿Qué interés tenés en trabajar esta dimensión?', WheelQuestion::ANSWER_NONE_TO_ALL],
            //Familia
            ['¿Disponibilidad para la familia?', WheelQuestion::ANSWER_NONE_TO_ALL],
            ['¿Relación con los padres?', WheelQuestion::ANSWER_WORST_TO_OPTIMAL],
            ['¿Relación de pareja?', WheelQuestion::ANSWER_WORST_TO_OPTIMAL],
            ['¿Relación con los hijos?', WheelQuestion::ANSWER_WORST_TO_OPTIMAL],
            ['¿Relación con otros miembros de la familia?', WheelQuestion::ANSWER_WORST_TO_OPTIMAL],
            ['¿Actividades recreativas en conjunto?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Comodidades familiares?', WheelQuestion::ANSWER_WORST_TO_OPTIMAL],
            ['¿Proyecto en común?', WheelQuestion::ANSWER_NONE_TO_ALL],
            ['¿Nivel de satisfacción en general?', WheelQuestion::ANSWER_WORST_TO_OPTIMAL],
            ['¿Qué interés tenés en trabajar esta dimensión?', WheelQuestion::ANSWER_NONE_TO_ALL],
            //Dimensión Fisica
            ['¿Apariencia?', WheelQuestion::ANSWER_WORST_TO_OPTIMAL],
            ['¿Energía?', WheelQuestion::ANSWER_WORST_TO_OPTIMAL],
            ['¿Actividad física?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Dieta equilibrada?', WheelQuestion::ANSWER_WORST_TO_OPTIMAL],
            ['¿Estimulación de sentidos?', WheelQuestion::ANSWER_WORST_TO_OPTIMAL],
            ['¿Ambiente y Entorno en general?', WheelQuestion::ANSWER_WORST_TO_OPTIMAL],
            ['¿Nivel de materialización de los Proyectos?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Hábitos que no me gustan?', WheelQuestion::ANSWER_ALL_TO_NONE],
            ['¿Estado de salud en general?', WheelQuestion::ANSWER_WORST_TO_OPTIMAL],
            ['¿Qué interés tenés en trabajar esta dimensión?', WheelQuestion::ANSWER_NONE_TO_ALL],
            //Dimensión Emocional
            ['¿Mis estados de ánimo son estables?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Me conecto con mi pasión?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Me conecto con mi voluntad?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Mi actitud emocional es positiva?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Tengo una vida estimulante?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Cómo es mi autoestima?', WheelQuestion::ANSWER_WORST_TO_OPTIMAL],
            ['¿Ansiedad?', WheelQuestion::ANSWER_ALL_TO_NONE],
            ['¿Nostalgia?', WheelQuestion::ANSWER_ALL_TO_NONE],
            ['¿Miedos?', WheelQuestion::ANSWER_ALL_TO_NONE],
            ['¿Qué interés tenés en trabajar esta dimensión?', WheelQuestion::ANSWER_NONE_TO_ALL],
            //Dimensión Mental
            ['¿Qué grado de felicidad tengo?', WheelQuestion::ANSWER_NONE_TO_ALL],
            ['¿Hay alguna situación que no me deja ser feliz?', WheelQuestion::ANSWER_ALL_TO_NONE],
            ['¿Qué tan seguido me encuentro juzgando las situaciones?', WheelQuestion::ANSWER_ALL_TO_NONE],
            ['¿Qué tan seguido me descubro tejiendo fantasías?', WheelQuestion::ANSWER_ALL_TO_NONE],
            ['¿Estoy esperando algo de alguien?', WheelQuestion::ANSWER_ALWAYS_TO_NEVER],
            ['¿Espero que las personas adivinen lo que necesito?', WheelQuestion::ANSWER_ALWAYS_TO_NEVER],
            ['¿Soy racional?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Mi actitud mental es positiva?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Tengo una mentalidad abierta?', WheelQuestion::ANSWER_NONE_TO_ALL],
            ['¿Qué interés tenés en trabajar esta dimensión?', WheelQuestion::ANSWER_NONE_TO_ALL],
            //Dimensión Existencial
            ['¿Me considero una persona de mucha consciencia?', WheelQuestion::ANSWER_NONE_TO_ALL],
            ['¿Me preocupo por el crecimiento de los demás?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Tengo claridad con mi vocación?', WheelQuestion::ANSWER_NONE_TO_ALL],
            ['¿Tengo claridad en cuál es mi servicio diferencial?', WheelQuestion::ANSWER_NONE_TO_ALL],
            ['¿Qué nivel de Escucha tengo?', WheelQuestion::ANSWER_WORST_TO_OPTIMAL],
            ['¿Qué nivel de Comunicación tengo?', WheelQuestion::ANSWER_WORST_TO_OPTIMAL],
            ['¿Las relaciones con mis Vínculos son sanas?', WheelQuestion::ANSWER_WORST_TO_OPTIMAL],
            ['¿Participo en Comunidades y trabajos sociales?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Existe satisfacción con la Comunidad en que vivo?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Qué interés tenés en trabajar esta dimensión?', WheelQuestion::ANSWER_NONE_TO_ALL],
            //Dimensión Espiritual
            ['¿Creo en algún tipo de configuración superior?', WheelQuestion::ANSWER_NONE_TO_ALL],
            ['¿Soy congruente con ese tipo de configuración?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Tengo claridad con mi propósito de trascendencia?', WheelQuestion::ANSWER_NONE_TO_ALL],
            ['¿Soy congruente en mi vida con mis Valores?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Me mantengo en un continuo con el Amor?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Me mantengo en un continuo con la Verdad?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Doy sin esperar recibir?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Me mantengo en un continuo con la Paciencia?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Tengo una rutina espiritual?', WheelQuestion::ANSWER_NEVER_TO_ALWAYS],
            ['¿Qué interés tenés en trabajar esta dimensión?', WheelQuestion::ANSWER_NONE_TO_ALL],
        ];

        for ($i = 0; $i < count($questions); $i++) {
            $this->addQuestion($i / 10, $questions[$i][0], $questions[$i][1]);
        }

        $this->execute('update {{%wheel_question}} set `order` = `id`');
    }

    private function addQuestion($dimension, $question, $answer) {
        $this->insert('{{%wheel_question}}', [
            'dimension' => $dimension,
            'question' => $question,
            'answer_type' => $answer,
        ]);
    }

    public function down() {
        $this->dropTable('{{%wheel_question}}');
    }

}
