<?php

namespace app\components;

use app\models\Wheel;

class Dimensions
{

    const descriptions = [
        Wheel::TYPE_INDIVIDUAL => [
            0 => 'La condición neutra personal que pasa a ser compartida en los ámbitos posteriores donde el sujeto puede estar consigo mismo y conectado con su creatividad, su pasividad, etc.',
            1 => 'Ámbito donde desempeñamos capacidades y conductas que generarían un intercambio de procesos para satisfacer las necesidades personales y del ámbito anterior. Grupo secundario.',
            2 => 'Grupo vincular básico de socialización del cual tomamos las creencias y conductas principales para la adaptación social.',
            3 => 'Refiere a nuestro cuerpo y a aquellas cosas materializables por nosotros mismos, a acciones específicas.',
            4 => 'Es el ámbito de las relaciones o los vínculos, donde se vivencia la intención personal frente a sí mismo, a otro, a una experiencia o cosa, invitándonos a alejarnos o a acercarnos al mismo.',
            5 => 'Refiere al proceso organizativo personal que invita a la reflexión o a la elección de emociones y conductas particulares frente a personas, sucesos o cosas.',
            6 => 'Refiere a la visión personal a la manera como queremos vivenciar nuestro propósito en la vida, a la manera como decidimos experimentar nuestra condición humana.',
            7 => 'Es el ámbito de nuestra misión, de aquello que somos en la vida, con nuestro propósito o manera personal de estar.',
        ],
        Wheel::TYPE_GROUP => [
            0 => 'Habilidad para originar ideas novedosas y desarrollar acciones para su implementación.',
            1 => 'Grado de compromiso hacia los Valores aceptados y reconocidos por el grupo de pertenencia.',
            2 => 'Nivel de experticia e idoneidad reconocida por los miembros del Equipo, en la disciplina de desempeño .',
            3 => 'Disposición para integrarse a equipos de trabajo contribuyendo al cumplimiento de los objetivos, fortaleciendo las relaciones interpersonales de sus miembros.',
            4 => 'Actitud para adherir positiva y flexiblemente a cambios en el contexto y en los procesos de trabajo.',
            5 => 'Grado y modo de interacción con pares en el contexto de trabajo.',
            6 => 'Capacidad para guiar a sus colaboradores hacia el logro de los objetivos, generando un alto nivel de motivación.',
            7 => 'Nivel de aceptación y reconocimiento de las decisiones y conductas, en su contexto de trabajo.',
        ],
        Wheel::TYPE_ORGANIZATIONAL => [
            0 => 'Habilidad para originar ideas novedosas y desarrollar acciones concretas para su implementación.',
            1 => 'Capacidad para entender, anticiparse y satisfacer las necesidades del Cliente, tanto interno como externo.',
            2 => 'Actitud constante hacia el logro y la superación de los objetivos individuales y del grupo. Incluye la preocupación por el uso adecuado de los recursos y por el incremento sostenido de la productividad.',
            3 => 'Actitud hacia la búsqueda permanente del mejoramiento de los productos/procesos y de la excelencia.',
            4 => 'Capacidad para promover e implementar los cambios requeridos a fin de mantener la ventaja competitiva de la empresa en el mercado.',
            5 => 'Resolver eficazmente diferencias en distintas posiciones, modificando posturas personales y/ o grupales en beneficio de todas las partes involucradas.',
            6 => 'Habilidad para imaginar escenarios futuros y formular estrategias orientadas a mejorar el posicionamiento de su equipo/área dentro de la Organización.',
            7 => 'Pleno compromiso e identificación con los Valores y la Cultura de la Organización.',
        ],
    ];

}
