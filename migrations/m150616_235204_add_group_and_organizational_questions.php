<?php

use yii\db\Schema;
use yii\db\Migration;

class m150616_235204_add_group_and_organizational_questions extends Migration {

    const ANSWER_NUMBERS_0_TO_4 = 0;
    const ANSWER_WORST_TO_OPTIMAL = 1;
    const ANSWER_NEVER_TO_ALWAYS = 2;
    const ANSWER_NONE_TO_ALL = 3;
    const ANSWER_NOTHING_TO_ABSOLUTLY = 4;
    const ANSWER_OPTIMAL_TO_WORST = 101;
    const ANSWER_ALWAYS_TO_NEVER = 102;
    const ANSWER_ALL_TO_NONE = 103;
    const ANSWER_ABSOLUTLY_TO_NOTHING = 104;
    const TYPE_INDIVIDUAL = 0;
    const TYPE_GROUP = 1;
    const TYPE_ORGANIZATIONAL = 2;

    public $groupQuestions = [
        // INICIATIVA
        ['¿Es proactivo?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Ve las oportunidades de mejora?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Está comprometid@ con la mejora continua?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Tiene un criterio independiente?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Diseña diversos procesos/proyectos de trabajo?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Propone políticas organizacionales?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Promueve la participación de sus compañeros?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Está comprometid@ con la Iniciativa?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        //PERTINENCIA
        ['¿Se considera idóneo en su trabajo?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Habla con fundamentos?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Considera que sus intervenciones aportan al Equipo?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Es consciente del efecto de sus comportamientos?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Es consciente de su inteligencia emocional?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Es ordenado?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Se capacita?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Está comprometid@ con la Pertinencia?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        //PERTENENCIA
        ['¿Es consciente de que necesita de su Equipo para crecer?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Está satisfecho con la trayectoria de la Empresa?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Está satisfecho con la trayectoria de los integrantes del Equipo?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Siente que la Org. también es un producto suyo?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Sabe identificar los sentimientos de los demás?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Cuida los vínculos?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Toma su lugar en el Equipo?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Está comprometid@ con la Pertenencia?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        //TRABAJO EN EQUIPO
        ['¿Busca el equilibrio entre el bien individual y el bien común?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Colabora con sus compañeros?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Tiene un buen desempeño en el Equipo?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Respeta los tiempos de trabajo de los miembros del Equipo?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Se siente integrado al Equipo?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Considera que es importante su feedback al Equipo?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Considera que es importante el feedback que recibe de su Equipo?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Está comprometid@ con el Equipo?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        //FLEXIBILIDAD
        ['¿Es flexible?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Transforma las debilidades en fortalezas?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Valora perspectivas distintas?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Modifica sus opiniones para poder acordar?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Se ajusta a los nuevos escenarios?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Aborda los supuestos que se instalan en el Equipo?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Hace una revisión crítica de sí mismo?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Está comprometid@ con la Flexibilidad?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        //COMUNICACIÓN
        ['¿Escucha profundamente?', self::ANSWER_NONE_TO_ALL],
        ['¿Chequea que su mensaje sea comprendido?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Mantiene una comunicación regular con sus pares?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Es consciente que la información que gestiona llegue en tiempo y forma a destino?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Maneja alguna estrategia de control sobre la información que administra?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Tiene en cuenta el contexto al momento del diálogo?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Aporta activamente a la relación del Equipo?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Está comprometid@ con la Comunicación?', self::ANSWER_NONE_TO_ALL],
        //LIDERAZGO
        ['¿Consigue delegar responsablemente sus actividades?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Gestiona el desempeño de su gente en forma sistemática y regular?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Transmite y logra el compromiso de su gente frente a los objetivos del negocio?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Es reconocido como líder en su Equipo?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Es un ejemplo para los demás?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Cree que le da lo suficiente a su Equipo?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Le preocupa el desarrollo de su Gente?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Está comprometid@ con el Liderazgo?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        //LEGITIMACIÓN
        ['¿Está tranquilo cuando otros lo representan?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Se fía de lo que sus pares y subordinados le cuentan?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Cree que la valoración del otro (hacia usted) es importante?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Considera importante su presencia en el Equipo?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Confían en Usted?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Reconoce las potencialidades de los demás?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Dice y actúa según lo que piensa?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Está comprometid@ con la Legitimación?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
    ];
    public $organizationalQuestions = [
        // CREATIVIDAD
        ['¿Adhiere a la creatividad por convicción?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Reconoce esta competencia como una exigencia propia del contexto del mercado?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Se considera una persona creativa?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Considera que su Entorno l@ valora como una persona proclive a la innovación?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Alienta y valoriza que otra persona del Equipo sea creativa?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Considera que la creatividad mejora el servicio?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Considera que la creatividad mejora la competitividad?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Está comprometid@ con la creatividad?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        //ORIENTACIÓN A LOS RESULTADOS
        ['¿Es productiv@ en sus tareas?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Alcanza las metas y los objetivos establecidos?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Orienta su comportamiento hacia la superación constante de los objetivos planteados?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Se involucra en el pronóstico de las metas y los objetivos a alcanzar?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Establece indicadores para la mejora continua?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Hace un seguimiento regular del grado de evolución de esos indicadores?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Reconoce la importancia de hacer seguimiento y medición del avance de los resultados?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Está comprometid@ con la orientación a los resultados?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        //ORIENTACIÓN AL CLIENTE (externo e interno)
        ['¿Actúa con sensibilidad ante las necesidades del cliente?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Tiene la capacidad de anticiparse ante las necesidades futuras del cliente?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Conoce la totalidad de los productos y servicios de la Org.?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Presenta propuestas y soluciones que agregan valor a sus clientes?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Tiene la capacidad de empatizar con sus clientes?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Responde al sentido de urgencia de las demandas de sus clientes?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Se preocupa por satisfacer plenamente al cliente?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Está comprometid@ con la atención al cliente?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        //ORIENTACIÓN A LA CALIDAD
        ['¿Cuida el detalle al momento de presentar los trabajos?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Se reconoce eficiente al momento de afectar los recursos para su labor?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Respalda sus fundamentos con información verificable?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Establece estrategias de procesos?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Mide regularmente la Calidad de su trabajo?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Está atent@ a las necesidades del Equipo?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Está atent@ a las necesidades de la Org.?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Está comprometid@ con la calidad?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        //GESTIÓN DEL CAMBIO
        ['¿Adhiere a la gestión del cambio por convicción?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Entiende el cambio como una dinámica propia del Negocio?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿L@ consideran una persona flexible a los cambios?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Considera que el cambio es mejora?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Tiene la capacidad de comprender y apreciar perspectivas diferentes?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Hace una revisión crítica de las estrategias y objetivos de su Área?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Alienta y valoriza los cambios?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Está comprometid@ con la gestión del cambio?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        //RESOLUCIÓN DE CONFLICTOS
        ['¿Sabe abordar y resolver conflictos?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Asume negociaciones y acuerdos?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Busca que las resoluciones sean grupales?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Intenta enseñar la lógica y los beneficios de su posición?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Intenta hacer lo que es necesario para evitar tensiones inútiles?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Tiene en cuenta todo lo que conscierne a Usted y al otro?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Propone e indaga necesidades?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Está comprometid@ con la resolución de conflictos?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        //VISIÓN ESTRATÉGICA
        ['¿Proyecta en su tarea diaria escenarios futuros?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Reconoce esta competencia como una exigencia propia del contexto del mercado?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Se considera una persona que trabaja “mirando más allá”?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Considera que sus compañeros l@ ven como una persona con visión a futuro?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Promueve la planificación?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Alienta y valoriza el análisis estratégico de la gestión que hace su Equipo?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Está de acuerdo con que la visión estratégica permite adelantarse al futuro y optimizar decisiones?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Está comprometid@ con la visión estratégica de la Org.?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        //IDENTIDAD
        ['¿Tiene presente cuál es la Visión de la Org.?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Tiene presente cuál es la Misión de la Org.?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Tiene presente cuáles son los Valores de la Org.?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Se ajustan sus acciones a las necesidades de la Org.?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Sabe lo que se esperan de Ud. en su trabajo?', self::ANSWER_NEVER_TO_ALWAYS],
        ['¿Considera que su aporte es importante para la Misión de la Org.?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Reconoce los canales regulares de toma de decisiones?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
        ['¿Está comprometid@ con la identidad?', self::ANSWER_NOTHING_TO_ABSOLUTLY],
    ];

    public function up() {
        $this->addColumn('{{%wheel_question}}', 'type', Schema::TYPE_INTEGER . ' NOT NULL DEFAULT ' . self::TYPE_INDIVIDUAL);

        for ($i = 0; $i < count($this->groupQuestions); $i++) {
            $this->addQuestion(self::TYPE_GROUP, $i / 8, $this->groupQuestions[$i][0], $this->groupQuestions[$i][1], $i + 1);
        }

        for ($i = 0; $i < count($this->organizationalQuestions); $i++) {
            $this->addQuestion(self::TYPE_ORGANIZATIONAL, $i / 8, $this->organizationalQuestions[$i][0], $this->organizationalQuestions[$i][1], $i + 1);
        }
    }

    private function addQuestion($type, $dimension, $question, $answer, $order) {
        $this->insert('{{%wheel_question}}', [
            'type' => $type,
            'dimension' => $dimension,
            'question' => $question,
            'answer_type' => $answer,
            'order' => $order,
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    public function down() {
        $this->execute('delete from {{%wheel_question}}
            where type > 0');

        $this->dropColumn('{{%wheel_question}}', 'type');
    }

}
