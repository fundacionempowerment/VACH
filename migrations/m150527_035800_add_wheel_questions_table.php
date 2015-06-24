<?php

use yii\db\Schema;
use yii\db\Migration;

class m150527_035800_add_wheel_questions_table extends Migration {

    const ANSWER_NUMBERS_0_TO_4 = 0;
    const ANSWER_WORST_TO_OPTIMAL = 1;
    const ANSWER_NEVER_TO_ALWAYS = 2;
    const ANSWER_NONE_TO_ALL = 3;
    const ANSWER_NOTHING_TO_ABSOLUTLY = 4;
    const ANSWER_OPTIMAL_TO_WORST = 101;
    const ANSWER_ALWAYS_TO_NEVER = 102;
    const ANSWER_ALL_TO_NONE = 103;
    const ANSWER_ABSOLUTLY_TO_NOTHING = 104;

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
            ['¿Me divierto y participo del ocio?', self::ANSWER_NUMBERS_0_TO_4],
            ['¿Tengo claro cuáles son mis momentos de ocio?', self::ANSWER_NUMBERS_0_TO_4],
            ['¿Logro separar mi familia del ocio?', self::ANSWER_NUMBERS_0_TO_4],
            ['¿Logro separar mi trabajo del ocio?', self::ANSWER_NUMBERS_0_TO_4],
            ['¿Desarrollo personal?', self::ANSWER_NUMBERS_0_TO_4],
            ['¿Identifico un hobby en especial?', self::ANSWER_NUMBERS_0_TO_4],
            ['¿Tengo actividades de ocio programadas?', self::ANSWER_NUMBERS_0_TO_4],
            ['¿Tengo actividades de ocio compartidas?', self::ANSWER_NUMBERS_0_TO_4],
            ['¿Me estimula el juntarme con amigos?', self::ANSWER_NUMBERS_0_TO_4],
            ['¿Qué interés tenés en trabajar esta dimensión?', self::ANSWER_NUMBERS_0_TO_4],
            //Trabajo
            ['¿Ambiente y entorno de trabajo?', self::ANSWER_WORST_TO_OPTIMAL],
            ['¿Se valoran mis capacidades?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Mi trabajo es acorde a mi vocación?', self::ANSWER_NONE_TO_ALL],
            ['¿Tengo un trabajo estimulante?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Tengo posibilidad de crecimiento?', self::ANSWER_NONE_TO_ALL],
            ['¿Tengo claridad con mis Proyectos de trabajo?', self::ANSWER_NONE_TO_ALL],
            ['¿Controlo el estrés?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Tengo un presupuesto estable?', self::ANSWER_NONE_TO_ALL],
            ['¿Estado de satisfacción en gral con mi trabajo?', self::ANSWER_NONE_TO_ALL],
            ['¿Qué interés tenés en trabajar esta dimensión?', self::ANSWER_NONE_TO_ALL],
            //Familia
            ['¿Disponibilidad para la familia?', self::ANSWER_NONE_TO_ALL],
            ['¿Relación con los padres?', self::ANSWER_WORST_TO_OPTIMAL],
            ['¿Relación de pareja?', self::ANSWER_WORST_TO_OPTIMAL],
            ['¿Relación con los hijos?', self::ANSWER_WORST_TO_OPTIMAL],
            ['¿Relación con otros miembros de la familia?', self::ANSWER_WORST_TO_OPTIMAL],
            ['¿Actividades recreativas en conjunto?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Comodidades familiares?', self::ANSWER_WORST_TO_OPTIMAL],
            ['¿Proyecto en común?', self::ANSWER_NONE_TO_ALL],
            ['¿Nivel de satisfacción en general?', self::ANSWER_WORST_TO_OPTIMAL],
            ['¿Qué interés tenés en trabajar esta dimensión?', self::ANSWER_NONE_TO_ALL],
            //Dimensión Fisica
            ['¿Apariencia?', self::ANSWER_WORST_TO_OPTIMAL],
            ['¿Energía?', self::ANSWER_WORST_TO_OPTIMAL],
            ['¿Actividad física?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Dieta equilibrada?', self::ANSWER_WORST_TO_OPTIMAL],
            ['¿Estimulación de sentidos?', self::ANSWER_WORST_TO_OPTIMAL],
            ['¿Ambiente y Entorno en general?', self::ANSWER_WORST_TO_OPTIMAL],
            ['¿Nivel de materialización de los Proyectos?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Hábitos que no me gustan?', self::ANSWER_ALL_TO_NONE],
            ['¿Estado de salud en general?', self::ANSWER_WORST_TO_OPTIMAL],
            ['¿Qué interés tenés en trabajar esta dimensión?', self::ANSWER_NONE_TO_ALL],
            //Dimensión Emocional
            ['¿Mis estados de ánimo son estables?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Me conecto con mi pasión?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Me conecto con mi voluntad?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Mi actitud emocional es positiva?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Tengo una vida estimulante?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Cómo es mi autoestima?', self::ANSWER_WORST_TO_OPTIMAL],
            ['¿Ansiedad?', self::ANSWER_ALL_TO_NONE],
            ['¿Nostalgia?', self::ANSWER_ALL_TO_NONE],
            ['¿Miedos?', self::ANSWER_ALL_TO_NONE],
            ['¿Qué interés tenés en trabajar esta dimensión?', self::ANSWER_NONE_TO_ALL],
            //Dimensión Mental
            ['¿Qué grado de felicidad tengo?', self::ANSWER_NONE_TO_ALL],
            ['¿Hay alguna situación que no me deja ser feliz?', self::ANSWER_ALL_TO_NONE],
            ['¿Qué tan seguido me encuentro juzgando las situaciones?', self::ANSWER_ALL_TO_NONE],
            ['¿Qué tan seguido me descubro tejiendo fantasías?', self::ANSWER_ALL_TO_NONE],
            ['¿Estoy esperando algo de alguien?', self::ANSWER_ALWAYS_TO_NEVER],
            ['¿Espero que las personas adivinen lo que necesito?', self::ANSWER_ALWAYS_TO_NEVER],
            ['¿Soy racional?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Mi actitud mental es positiva?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Tengo una mentalidad abierta?', self::ANSWER_NONE_TO_ALL],
            ['¿Qué interés tenés en trabajar esta dimensión?', self::ANSWER_NONE_TO_ALL],
            //Dimensión Existencial
            ['¿Me considero una persona de mucha consciencia?', self::ANSWER_NONE_TO_ALL],
            ['¿Me preocupo por el crecimiento de los demás?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Tengo claridad con mi vocación?', self::ANSWER_NONE_TO_ALL],
            ['¿Tengo claridad en cuál es mi servicio diferencial?', self::ANSWER_NONE_TO_ALL],
            ['¿Qué nivel de Escucha tengo?', self::ANSWER_WORST_TO_OPTIMAL],
            ['¿Qué nivel de Comunicación tengo?', self::ANSWER_WORST_TO_OPTIMAL],
            ['¿Las relaciones con mis Vínculos son sanas?', self::ANSWER_WORST_TO_OPTIMAL],
            ['¿Participo en Comunidades y trabajos sociales?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Existe satisfacción con la Comunidad en que vivo?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Qué interés tenés en trabajar esta dimensión?', self::ANSWER_NONE_TO_ALL],
            //Dimensión Espiritual
            ['¿Creo en algún tipo de configuración superior?', self::ANSWER_NONE_TO_ALL],
            ['¿Soy congruente con ese tipo de configuración?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Tengo claridad con mi propósito de trascendencia?', self::ANSWER_NONE_TO_ALL],
            ['¿Soy congruente en mi vida con mis Valores?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Me mantengo en un continuo con el Amor?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Me mantengo en un continuo con la Verdad?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Doy sin esperar recibir?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Me mantengo en un continuo con la Paciencia?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Tengo una rutina espiritual?', self::ANSWER_NEVER_TO_ALWAYS],
            ['¿Qué interés tenés en trabajar esta dimensión?', self::ANSWER_NONE_TO_ALL],
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
