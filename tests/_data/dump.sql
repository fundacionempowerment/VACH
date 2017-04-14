SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `assessment` (
  `id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `individual_status` int(11) NOT NULL,
  `group_status` int(11) NOT NULL,
  `organizational_status` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `assessment` (`id`, `team_id`, `created_at`, `updated_at`, `individual_status`, `group_status`, `organizational_status`, `name`, `version`) VALUES
(1, 1, 1492197123, 1492197123, 0, 0, 0, 'Inicial', 2),
(2, 1, 1492197137, 1492197137, 0, 0, 0, 'Final', 2);

CREATE TABLE `assessment_coach` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `coach_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `coach_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `company` (`id`, `coach_id`, `name`, `email`, `phone`, `created_at`, `updated_at`) VALUES
(1, 2, 'ACME', 'acme@c.com', '(123)4567890', 1492196895, 1492196895);

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `effectiveness` tinyint(4) NOT NULL,
  `efficience` tinyint(4) NOT NULL,
  `satisfaction` tinyint(4) NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datetime` datetime NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `individual_report` (
  `id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `perception` text COLLATE utf8_unicode_ci,
  `relations` text COLLATE utf8_unicode_ci,
  `competences` text COLLATE utf8_unicode_ci,
  `emergents` text COLLATE utf8_unicode_ci,
  `summary` text COLLATE utf8_unicode_ci,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `performance` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `coach_id` int(11) DEFAULT NULL,
  `text` text COLLATE utf8_unicode_ci,
  `datetime` datetime DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `migration` (
  `version` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `apply_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `migration` (`version`, `apply_time`) VALUES
('m000000_000000_base', 1492102011),
('m150501_231226_add_user_tables', 1492102019),
('m150502_025947_add_wheel_tables', 1492102021),
('m150523_032250_answer_scale_to_0_to_4', 1492102021),
('m150527_035800_add_wheel_questions_table', 1492102215),
('m150528_041349_change_wheel_user_to_coachee', 1492102216),
('m150602_233124_add_goal_tables', 1492102217),
('m150606_160839_add_administrator', 1492102217),
('m150606_194443_add_company', 1492102220),
('m150609_001157_add_user_phone', 1492102220),
('m150609_053234_add_team_tables', 1492102226),
('m150611_044001_add_assessment_tables', 1492102227),
('m150613_021630_add_assessment_wheel_fields', 1492102234),
('m150616_235204_add_group_and_organizational_questions', 1492102383),
('m150617_015144_add_dimension_to_answers', 1492102383),
('m150623_031725_decrease_question_orders', 1492102383),
('m150624_025741_dimension_to_tinyint', 1492102384),
('m150704_015414_add_autofill_answers_field', 1492102385),
('m150721_023053_add_feedback_table', 1492102386),
('m150723_223230_add_user_to_feedback', 1492102386),
('m150724_065744_add_assessment_name', 1492102387),
('m150724_070020_delete_unused_tables_and_fields', 1492102388),
('m150725_000559_add_technical_report', 1492102392),
('m150725_224449_add_question_index', 1492102392),
('m150920_183258_add_event_log_table', 1492102394),
('m151114_012747_add_relation_analisys', 1492102394),
('m151114_013636_add_individual_performance_analisys', 1492102395),
('m151216_041047_team_blocked_field', 1492102396),
('m160614_041645_person_gender', 1492102397),
('m160618_191226_assessment_version', 1492102397),
('m160701_033528_add_person', 1492102398),
('m160701_033529_add_company', 1492102400),
('m160701_034038_fill_person', 1492102400),
('m160701_034039_fill_company', 1492102400),
('m160701_040351_move_fk_to_person', 1492102405),
('m160701_040352_move_fk_to_company', 1492102407),
('m160701_045821_clean_user_table', 1492102410),
('m170215_042851_add_active_to_person', 1492102411),
('m170227_044825_add_product_and_stock', 1492102414),
('m170228_044825_add_payment', 1492102417),
('m170309_044329_null_in_reports', 1492102425),
('m170325_050124_add_question_table', 1492102429),
('m170325_053850_drop_anwser_type_field', 1492102430),
('m170326_043036_bind_answer_to_question', 1492102431),
('m170326_192255_add_creator_to_stock', 1492102433),
('m170403_030521_question_fk', 1492102435),
('m170403_040521_creator_fk', 1492102437),
('m170404_030521_add_assessment_coach', 1492102440);

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `uuid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `coach_id` int(11) NOT NULL,
  `stock_id` int(11) NOT NULL,
  `concept` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('init','pending','paid','partial','error') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'init',
  `external_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stamp` datetime NOT NULL,
  `creator_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `payment_log` (
  `id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `status` enum('init','pending','paid','partial','error') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'init',
  `external_id` text COLLATE utf8_unicode_ci,
  `external_data` text COLLATE utf8_unicode_ci,
  `stamp` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `person` (
  `id` int(11) NOT NULL,
  `coach_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gender` int(11) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `person` (`id`, `coach_id`, `name`, `surname`, `email`, `phone`, `gender`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ariel', 'A', 'ariel@a.com', '(123)4567890', 2, 1492196613, 1492196994),
(2, 1, 'Beatriz', 'B', 'beatriz@b.com', '(123)4567890', 1, 1492196616, 1492196954),
(3, 1, 'Carlos', 'C', 'carlos@c.com', '(123)4567890', 0, 1492196619, 1492196987),
(4, 1, 'Patricio', 'P', 'patricio@p.com', '(234)12345678', 0, 1492197048, 1492197048);

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `price` decimal(10,2) NOT NULL COMMENT 'USD',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `product` (`id`, `name`, `description`, `price`, `created_at`, `updated_at`) VALUES
(1, 'Team Assessment Licence', 'Team Assessment Licence', '18.00', 1492102411, 1492102411);

CREATE TABLE `question` (
  `id` int(11) NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `question` (`id`, `text`) VALUES
(1, '¿Me divierto y participo del ocio?'),
(2, '¿Tengo claro cuáles son mis momentos de ocio?'),
(3, '¿Logro separar mi familia del ocio?'),
(4, '¿Logro separar mi trabajo del ocio?'),
(5, '¿Desarrollo personal?'),
(6, '¿Identifico un hobby en especial?'),
(7, '¿Tengo actividades de ocio programadas?'),
(8, '¿Tengo actividades de ocio compartidas?'),
(9, '¿Me estimula el juntarme con amigos?'),
(10, '¿Qué interés tenés en trabajar esta dimensión?'),
(11, '¿Ambiente y entorno de trabajo?'),
(12, '¿Se valoran mis capacidades?'),
(13, '¿Mi trabajo es acorde a mi vocación?'),
(14, '¿Tengo un trabajo estimulante?'),
(15, '¿Tengo posibilidad de crecimiento?'),
(16, '¿Tengo claridad con mis Proyectos de trabajo?'),
(17, '¿Controlo el estrés?'),
(18, '¿Tengo un presupuesto estable?'),
(19, '¿Estado de satisfacción en gral con mi trabajo?'),
(20, '¿Disponibilidad para la familia?'),
(21, '¿Relación con los padres?'),
(22, '¿Relación de pareja?'),
(23, '¿Relación con los hijos?'),
(24, '¿Relación con otros miembros de la familia?'),
(25, '¿Actividades recreativas en conjunto?'),
(26, '¿Comodidades familiares?'),
(27, '¿Proyecto en común?'),
(28, '¿Nivel de satisfacción en general?'),
(29, '¿Apariencia?'),
(30, '¿Energía?'),
(31, '¿Actividad física?'),
(32, '¿Dieta equilibrada?'),
(33, '¿Estimulación de sentidos?'),
(34, '¿Ambiente y Entorno en general?'),
(35, '¿Nivel de materialización de los Proyectos?'),
(36, '¿Hábitos que no me gustan?'),
(37, '¿Estado de salud en general?'),
(38, '¿Mis estados de ánimo son estables?'),
(39, '¿Me conecto con mi pasión?'),
(40, '¿Me conecto con mi voluntad?'),
(41, '¿Mi actitud emocional es positiva?'),
(42, '¿Tengo una vida estimulante?'),
(43, '¿Cómo es mi autoestima?'),
(44, '¿Ansiedad?'),
(45, '¿Nostalgia?'),
(46, '¿Miedos?'),
(47, '¿Qué grado de felicidad tengo?'),
(48, '¿Hay alguna situación que no me deja ser feliz?'),
(49, '¿Qué tan seguido me encuentro juzgando las situaciones?'),
(50, '¿Qué tan seguido me descubro tejiendo fantasías?'),
(51, '¿Estoy esperando algo de alguien?'),
(52, '¿Espero que las personas adivinen lo que necesito?'),
(53, '¿Soy racional?'),
(54, '¿Mi actitud mental es positiva?'),
(55, '¿Tengo una mentalidad abierta?'),
(56, '¿Me considero una persona de mucha consciencia?'),
(57, '¿Me preocupo por el crecimiento de los demás?'),
(58, '¿Tengo claridad con mi vocación?'),
(59, '¿Tengo claridad en cuál es mi servicio diferencial?'),
(60, '¿Qué nivel de Escucha tengo?'),
(61, '¿Qué nivel de Comunicación tengo?'),
(62, '¿Las relaciones con mis Vínculos son sanas?'),
(63, '¿Participo en Comunidades y trabajos sociales?'),
(64, '¿Existe satisfacción con la Comunidad en que vivo?'),
(65, '¿Creo en algún tipo de configuración superior?'),
(66, '¿Soy congruente con ese tipo de configuración?'),
(67, '¿Tengo claridad con mi propósito de trascendencia?'),
(68, '¿Soy congruente en mi vida con mis Valores?'),
(69, '¿Me mantengo en un continuo con el Amor?'),
(70, '¿Me mantengo en un continuo con la Verdad?'),
(71, '¿Doy sin esperar recibir?'),
(72, '¿Me mantengo en un continuo con la Paciencia?'),
(73, '¿Tengo una rutina espiritual?'),
(74, '¿Es proactivo?'),
(75, '¿Ve las oportunidades de mejora?'),
(76, '¿Está comprometid@ con la mejora continua?'),
(77, '¿Tiene un criterio independiente?'),
(78, '¿Diseña diversos procesos/proyectos de trabajo?'),
(79, '¿Propone políticas organizacionales?'),
(80, '¿Promueve la participación de sus compañeros?'),
(81, '¿Está comprometid@ con la Iniciativa?'),
(82, '¿Se considera idóneo en su trabajo?'),
(83, '¿Habla con fundamentos?'),
(84, '¿Considera que sus intervenciones aportan al Equipo?'),
(85, '¿Es consciente del efecto de sus comportamientos?'),
(86, '¿Es consciente de su inteligencia emocional?'),
(87, '¿Es ordenado?'),
(88, '¿Se capacita?'),
(89, '¿Está comprometid@ con la Pertinencia?'),
(90, '¿Es consciente de que necesita de su Equipo para crecer?'),
(91, '¿Está satisfecho con la trayectoria de la Empresa?'),
(92, '¿Está satisfecho con la trayectoria de los integrantes del Equipo?'),
(93, '¿Siente que la Org. también es un producto suyo?'),
(94, '¿Sabe identificar los sentimientos de los demás?'),
(95, '¿Cuida los vínculos?'),
(96, '¿Toma su lugar en el Equipo?'),
(97, '¿Está comprometid@ con la Pertenencia?'),
(98, '¿Busca el equilibrio entre el bien individual y el bien común?'),
(99, '¿Colabora con sus compañeros?'),
(100, '¿Tiene un buen desempeño en el Equipo?'),
(101, '¿Respeta los tiempos de trabajo de los miembros del Equipo?'),
(102, '¿Se siente integrado al Equipo?'),
(103, '¿Considera que es importante su feedback al Equipo?'),
(104, '¿Considera que es importante el feedback que recibe de su Equipo?'),
(105, '¿Está comprometid@ con el Equipo?'),
(106, '¿Es flexible?'),
(107, '¿Transforma las debilidades en fortalezas?'),
(108, '¿Valora perspectivas distintas?'),
(109, '¿Modifica sus opiniones para poder acordar?'),
(110, '¿Se ajusta a los nuevos escenarios?'),
(111, '¿Aborda los supuestos que se instalan en el Equipo?'),
(112, '¿Hace una revisión crítica de sí mismo?'),
(113, '¿Está comprometid@ con la Flexibilidad?'),
(114, '¿Escucha profundamente?'),
(115, '¿Chequea que su mensaje sea comprendido?'),
(116, '¿Mantiene una comunicación regular con sus pares?'),
(117, '¿Es consciente que la información que gestiona llegue en tiempo y forma a destino?'),
(118, '¿Maneja alguna estrategia de control sobre la información que administra?'),
(119, '¿Tiene en cuenta el contexto al momento del diálogo?'),
(120, '¿Aporta activamente a la relación del Equipo?'),
(121, '¿Está comprometid@ con la Comunicación?'),
(122, '¿Consigue delegar responsablemente sus actividades?'),
(123, '¿Gestiona el desempeño de su gente en forma sistemática y regular?'),
(124, '¿Transmite y logra el compromiso de su gente frente a los objetivos del negocio?'),
(125, '¿Es reconocido como líder en su Equipo?'),
(126, '¿Es un ejemplo para los demás?'),
(127, '¿Cree que le da lo suficiente a su Equipo?'),
(128, '¿Le preocupa el desarrollo de su Gente?'),
(129, '¿Está comprometid@ con el Liderazgo?'),
(130, '¿Está tranquilo cuando otros lo representan?'),
(131, '¿Se fía de lo que sus pares y subordinados le cuentan?'),
(132, '¿Cree que la valoración del otro (hacia usted) es importante?'),
(133, '¿Considera importante su presencia en el Equipo?'),
(134, '¿Confían en Usted?'),
(135, '¿Reconoce las potencialidades de los demás?'),
(136, '¿Dice y actúa según lo que piensa?'),
(137, '¿Está comprometid@ con la Legitimación?'),
(138, '¿Adhiere a la creatividad por convicción?'),
(139, '¿Reconoce esta competencia como una exigencia propia del contexto del mercado?'),
(140, '¿Se considera una persona creativa?'),
(141, '¿Considera que su Entorno l@ valora como una persona proclive a la innovación?'),
(142, '¿Alienta y valoriza que otra persona del Equipo sea creativa?'),
(143, '¿Considera que la creatividad mejora el servicio?'),
(144, '¿Considera que la creatividad mejora la competitividad?'),
(145, '¿Está comprometid@ con la creatividad?'),
(146, '¿Es productiv@ en sus tareas?'),
(147, '¿Alcanza las metas y los objetivos establecidos?'),
(148, '¿Orienta su comportamiento hacia la superación constante de los objetivos planteados?'),
(149, '¿Se involucra en el pronóstico de las metas y los objetivos a alcanzar?'),
(150, '¿Establece indicadores para la mejora continua?'),
(151, '¿Hace un seguimiento regular del grado de evolución de esos indicadores?'),
(152, '¿Reconoce la importancia de hacer seguimiento y medición del avance de los resultados?'),
(153, '¿Está comprometid@ con la orientación a los resultados?'),
(154, '¿Actúa con sensibilidad ante las necesidades del cliente?'),
(155, '¿Tiene la capacidad de anticiparse ante las necesidades futuras del cliente?'),
(156, '¿Conoce la totalidad de los productos y servicios de la Org.?'),
(157, '¿Presenta propuestas y soluciones que agregan valor a sus clientes?'),
(158, '¿Tiene la capacidad de empatizar con sus clientes?'),
(159, '¿Responde al sentido de urgencia de las demandas de sus clientes?'),
(160, '¿Se preocupa por satisfacer plenamente al cliente?'),
(161, '¿Está comprometid@ con la atención al cliente?'),
(162, '¿Cuida el detalle al momento de presentar los trabajos?'),
(163, '¿Se reconoce eficiente al momento de afectar los recursos para su labor?'),
(164, '¿Respalda sus fundamentos con información verificable?'),
(165, '¿Establece estrategias de procesos?'),
(166, '¿Mide regularmente la Calidad de su trabajo?'),
(167, '¿Está atent@ a las necesidades del Equipo?'),
(168, '¿Está atent@ a las necesidades de la Org.?'),
(169, '¿Está comprometid@ con la calidad?'),
(170, '¿Adhiere a la gestión del cambio por convicción?'),
(171, '¿Entiende el cambio como una dinámica propia del Negocio?'),
(172, '¿L@ consideran una persona flexible a los cambios?'),
(173, '¿Considera que el cambio es mejora?'),
(174, '¿Tiene la capacidad de comprender y apreciar perspectivas diferentes?'),
(175, '¿Hace una revisión crítica de las estrategias y objetivos de su Área?'),
(176, '¿Alienta y valoriza los cambios?'),
(177, '¿Está comprometid@ con la gestión del cambio?'),
(178, '¿Sabe abordar y resolver conflictos?'),
(179, '¿Asume negociaciones y acuerdos?'),
(180, '¿Busca que las resoluciones sean grupales?'),
(181, '¿Intenta enseñar la lógica y los beneficios de su posición?'),
(182, '¿Intenta hacer lo que es necesario para evitar tensiones inútiles?'),
(183, '¿Tiene en cuenta todo lo que conscierne a Usted y al otro?'),
(184, '¿Propone e indaga necesidades?'),
(185, '¿Está comprometid@ con la resolución de conflictos?'),
(186, '¿Proyecta en su tarea diaria escenarios futuros?'),
(187, '¿Se considera una persona que trabaja “mirando más allá”?'),
(188, '¿Considera que sus compañeros l@ ven como una persona con visión a futuro?'),
(189, '¿Promueve la planificación?'),
(190, '¿Alienta y valoriza el análisis estratégico de la gestión que hace su Equipo?'),
(191, '¿Está de acuerdo con que la visión estratégica permite adelantarse al futuro y optimizar decisiones?'),
(192, '¿Está comprometid@ con la visión estratégica de la Org.?'),
(193, '¿Tiene presente cuál es la Visión de la Org.?'),
(194, '¿Tiene presente cuál es la Misión de la Org.?'),
(195, '¿Tiene presente cuáles son los Valores de la Org.?'),
(196, '¿Se ajustan sus acciones a las necesidades de la Org.?'),
(197, '¿Sabe lo que se esperan de Ud. en su trabajo?'),
(198, '¿Considera que su aporte es importante para la Misión de la Org.?'),
(199, '¿Reconoce los canales regulares de toma de decisiones?'),
(200, '¿Está comprometid@ con la identidad?');

CREATE TABLE `report` (
  `id` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `introduction` text COLLATE utf8_unicode_ci,
  `effectiveness` text COLLATE utf8_unicode_ci,
  `performance` text COLLATE utf8_unicode_ci,
  `competences` text COLLATE utf8_unicode_ci,
  `emergents` text COLLATE utf8_unicode_ci,
  `summary` text COLLATE utf8_unicode_ci,
  `action_plan` text COLLATE utf8_unicode_ci,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `relations` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `stock` (
  `id` int(11) NOT NULL,
  `coach_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('invalid','valid','error') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'invalid',
  `stamp` datetime NOT NULL,
  `creator_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `stock` (`id`, `coach_id`, `product_id`, `quantity`, `price`, `total`, `status`, `stamp`, `creator_id`) VALUES
(1, 2, 1, 100, '18.00', '0.00', 'valid', '2017-04-14 19:03:27', 1);

CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `coach_id` int(11) NOT NULL,
  `sponsor_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `blocked` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `team` (`id`, `name`, `coach_id`, `sponsor_id`, `company_id`, `created_at`, `updated_at`, `blocked`) VALUES
(1, 'Núcleo', 2, 4, 1, 1492197030, 1492197079, 1);

CREATE TABLE `team_member` (
  `id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `team_member` (`id`, `team_id`, `person_id`, `created_at`, `updated_at`, `active`) VALUES
(1, 1, 1, 1492197064, 1492197064, 1),
(2, 1, 2, 1492197067, 1492197067, 1),
(3, 1, 3, 1492197070, 1492197070, 1);

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `is_administrator` tinyint(1) NOT NULL DEFAULT '0',
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `user` (`id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `name`, `surname`, `status`, `created_at`, `updated_at`, `is_administrator`, `phone`) VALUES
(1, 'admin', 'TKOsEC2v04JpORUhnbQEuuHS3PnaFGmf', '$2y$13$3FyxUh9XpoBYsn39Y7X1FO1Qa06SdFKpZohrbc3QCFd5I2vjhfbK2', NULL, 'admin@example.com', 'Administror', 'A', 10, 1429313351, 1492197214, 1, '(345)1234567'),
(2, 'coach', 'bn7LboYGkEEvp2BIQtbhBF3qf8V4KL3-', '$2y$13$3FyxUh9XpoBYsn39Y7X1FO1Qa06SdFKpZohrbc3QCFd5I2vjhfbK2', NULL, 'coach@example.com', 'Coach', 'C', 10, 1430540056, 1492197337, 0, '(432)1098765'),
(3, 'assisstant', 'Wb7v9hgzxjTrmiZ2NFxQhfoMN2oamovk', '$2y$13$3FyxUh9XpoBYsn39Y7X1FO1Qa06SdFKpZohrbc3QCFd5I2vjhfbK2', NULL, 'assisstant@example.com', 'Assisstant', 'A', 10, 0, 1492197406, 0, '(012)1234567');

CREATE TABLE `wheel` (
  `id` int(11) NOT NULL,
  `observer_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `assessment_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `observed_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `wheel` (`id`, `observer_id`, `date`, `created_at`, `updated_at`, `assessment_id`, `type`, `token`, `observed_id`) VALUES
(11, 1, '2017-04-14', 1492197123, 1492197123, 1, 0, '799-123-455', 1),
(12, 1, '2017-04-14', 1492197123, 1492197123, 1, 1, '749-972-437', 1),
(13, 1, '2017-04-14', 1492197123, 1492197123, 1, 1, '749-972-437', 2),
(14, 1, '2017-04-14', 1492197123, 1492197123, 1, 1, '749-972-437', 3),
(15, 1, '2017-04-14', 1492197123, 1492197123, 1, 2, '326-952-011', 1),
(16, 1, '2017-04-14', 1492197123, 1492197123, 1, 2, '326-952-011', 2),
(17, 1, '2017-04-14', 1492197123, 1492197123, 1, 2, '326-952-011', 3),
(18, 2, '2017-04-14', 1492197123, 1492197123, 1, 0, '295-015-471', 2),
(19, 2, '2017-04-14', 1492197123, 1492197123, 1, 1, '072-444-085', 1),
(20, 2, '2017-04-14', 1492197123, 1492197123, 1, 1, '072-444-085', 2),
(21, 2, '2017-04-14', 1492197123, 1492197123, 1, 1, '072-444-085', 3),
(22, 2, '2017-04-14', 1492197123, 1492197123, 1, 2, '812-331-347', 1),
(23, 2, '2017-04-14', 1492197123, 1492197123, 1, 2, '812-331-347', 2),
(24, 2, '2017-04-14', 1492197123, 1492197123, 1, 2, '812-331-347', 3),
(25, 3, '2017-04-14', 1492197123, 1492197123, 1, 0, '152-820-545', 3),
(26, 3, '2017-04-14', 1492197123, 1492197123, 1, 1, '989-581-797', 1),
(27, 3, '2017-04-14', 1492197123, 1492197123, 1, 1, '989-581-797', 2),
(28, 3, '2017-04-14', 1492197123, 1492197123, 1, 1, '989-581-797', 3),
(29, 3, '2017-04-14', 1492197123, 1492197123, 1, 2, '398-230-990', 1),
(30, 3, '2017-04-14', 1492197123, 1492197123, 1, 2, '398-230-990', 2),
(31, 3, '2017-04-14', 1492197123, 1492197123, 1, 2, '398-230-990', 3),
(32, 1, '2017-04-14', 1492197137, 1492197137, 2, 0, '305-660-713', 1),
(33, 1, '2017-04-14', 1492197137, 1492197137, 2, 1, '658-054-732', 1),
(34, 1, '2017-04-14', 1492197137, 1492197137, 2, 1, '658-054-732', 2),
(35, 1, '2017-04-14', 1492197137, 1492197137, 2, 1, '658-054-732', 3),
(36, 1, '2017-04-14', 1492197137, 1492197137, 2, 2, '438-517-666', 1),
(37, 1, '2017-04-14', 1492197137, 1492197137, 2, 2, '438-517-666', 2),
(38, 1, '2017-04-14', 1492197137, 1492197137, 2, 2, '438-517-666', 3),
(39, 2, '2017-04-14', 1492197137, 1492197137, 2, 0, '277-638-061', 2),
(40, 2, '2017-04-14', 1492197137, 1492197137, 2, 1, '677-153-449', 1),
(41, 2, '2017-04-14', 1492197137, 1492197137, 2, 1, '677-153-449', 2),
(42, 2, '2017-04-14', 1492197137, 1492197137, 2, 1, '677-153-449', 3),
(43, 2, '2017-04-14', 1492197137, 1492197137, 2, 2, '085-897-442', 1),
(44, 2, '2017-04-14', 1492197137, 1492197137, 2, 2, '085-897-442', 2),
(45, 2, '2017-04-14', 1492197137, 1492197137, 2, 2, '085-897-442', 3),
(46, 3, '2017-04-14', 1492197137, 1492197137, 2, 0, '547-298-853', 3),
(47, 3, '2017-04-14', 1492197137, 1492197137, 2, 1, '311-931-808', 1),
(48, 3, '2017-04-14', 1492197137, 1492197137, 2, 1, '311-931-808', 2),
(49, 3, '2017-04-14', 1492197137, 1492197137, 2, 1, '311-931-808', 3),
(50, 3, '2017-04-14', 1492197137, 1492197137, 2, 2, '866-893-870', 1),
(51, 3, '2017-04-14', 1492197137, 1492197137, 2, 2, '866-893-870', 2),
(52, 3, '2017-04-14', 1492197137, 1492197137, 2, 2, '866-893-870', 3);

CREATE TABLE `wheel_answer` (
  `id` int(11) NOT NULL,
  `wheel_id` int(11) NOT NULL,
  `answer_order` smallint(6) NOT NULL,
  `answer_value` smallint(6) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `dimension` smallint(6) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `wheel_question` (
  `id` int(11) NOT NULL,
  `dimension` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `wheel_question` (`id`, `dimension`, `order`, `created_at`, `updated_at`, `type`, `question_id`) VALUES
(1, 0, -1, 1492102211, 1492102211, 0, 1),
(2, 0, 0, 1492102211, 1492102211, 0, 2),
(3, 0, 1, 1492102211, 1492102211, 0, 3),
(4, 0, 2, 1492102211, 1492102211, 0, 4),
(5, 0, 3, 1492102211, 1492102211, 0, 5),
(6, 0, 4, 1492102211, 1492102211, 0, 6),
(7, 0, 5, 1492102211, 1492102211, 0, 7),
(8, 0, 6, 1492102211, 1492102211, 0, 8),
(9, 0, 7, 1492102211, 1492102211, 0, 9),
(10, 0, 8, 1492102211, 1492102211, 0, 10),
(11, 1, 9, 1492102212, 1492102212, 0, 11),
(12, 1, 10, 1492102212, 1492102212, 0, 12),
(13, 1, 11, 1492102212, 1492102212, 0, 13),
(14, 1, 12, 1492102212, 1492102212, 0, 14),
(15, 1, 13, 1492102212, 1492102212, 0, 15),
(16, 1, 14, 1492102212, 1492102212, 0, 16),
(17, 1, 15, 1492102212, 1492102212, 0, 17),
(18, 1, 16, 1492102212, 1492102212, 0, 18),
(19, 1, 17, 1492102212, 1492102212, 0, 19),
(20, 1, 18, 1492102212, 1492102212, 0, 10),
(21, 2, 19, 1492102212, 1492102212, 0, 20),
(22, 2, 20, 1492102212, 1492102212, 0, 21),
(23, 2, 21, 1492102212, 1492102212, 0, 22),
(24, 2, 22, 1492102212, 1492102212, 0, 23),
(25, 2, 23, 1492102212, 1492102212, 0, 24),
(26, 2, 24, 1492102212, 1492102212, 0, 25),
(27, 2, 25, 1492102212, 1492102212, 0, 26),
(28, 2, 26, 1492102212, 1492102212, 0, 27),
(29, 2, 27, 1492102212, 1492102212, 0, 28),
(30, 2, 28, 1492102212, 1492102212, 0, 10),
(31, 3, 29, 1492102212, 1492102212, 0, 29),
(32, 3, 30, 1492102212, 1492102212, 0, 30),
(33, 3, 31, 1492102212, 1492102212, 0, 31),
(34, 3, 32, 1492102212, 1492102212, 0, 32),
(35, 3, 33, 1492102213, 1492102213, 0, 33),
(36, 3, 34, 1492102213, 1492102213, 0, 34),
(37, 3, 35, 1492102213, 1492102213, 0, 35),
(38, 3, 36, 1492102213, 1492102213, 0, 36),
(39, 3, 37, 1492102213, 1492102213, 0, 37),
(40, 3, 38, 1492102213, 1492102213, 0, 10),
(41, 4, 39, 1492102213, 1492102213, 0, 38),
(42, 4, 40, 1492102213, 1492102213, 0, 39),
(43, 4, 41, 1492102213, 1492102213, 0, 40),
(44, 4, 42, 1492102213, 1492102213, 0, 41),
(45, 4, 43, 1492102213, 1492102213, 0, 42),
(46, 4, 44, 1492102213, 1492102213, 0, 43),
(47, 4, 45, 1492102213, 1492102213, 0, 44),
(48, 4, 46, 1492102213, 1492102213, 0, 45),
(49, 4, 47, 1492102213, 1492102213, 0, 46),
(50, 4, 48, 1492102213, 1492102213, 0, 10),
(51, 5, 49, 1492102213, 1492102213, 0, 47),
(52, 5, 50, 1492102213, 1492102213, 0, 48),
(53, 5, 51, 1492102213, 1492102213, 0, 49),
(54, 5, 52, 1492102213, 1492102213, 0, 50),
(55, 5, 53, 1492102213, 1492102213, 0, 51),
(56, 5, 54, 1492102213, 1492102213, 0, 52),
(57, 5, 55, 1492102214, 1492102214, 0, 53),
(58, 5, 56, 1492102214, 1492102214, 0, 54),
(59, 5, 57, 1492102214, 1492102214, 0, 55),
(60, 5, 58, 1492102214, 1492102214, 0, 10),
(61, 6, 59, 1492102214, 1492102214, 0, 56),
(62, 6, 60, 1492102214, 1492102214, 0, 57),
(63, 6, 61, 1492102214, 1492102214, 0, 58),
(64, 6, 62, 1492102214, 1492102214, 0, 59),
(65, 6, 63, 1492102214, 1492102214, 0, 60),
(66, 6, 64, 1492102214, 1492102214, 0, 61),
(67, 6, 65, 1492102214, 1492102214, 0, 62),
(68, 6, 66, 1492102214, 1492102214, 0, 63),
(69, 6, 67, 1492102214, 1492102214, 0, 64),
(70, 6, 68, 1492102214, 1492102214, 0, 10),
(71, 7, 69, 1492102214, 1492102214, 0, 65),
(72, 7, 70, 1492102214, 1492102214, 0, 66),
(73, 7, 71, 1492102214, 1492102214, 0, 67),
(74, 7, 72, 1492102214, 1492102214, 0, 68),
(75, 7, 73, 1492102214, 1492102214, 0, 69),
(76, 7, 74, 1492102214, 1492102214, 0, 70),
(77, 7, 75, 1492102215, 1492102215, 0, 71),
(78, 7, 76, 1492102215, 1492102215, 0, 72),
(79, 7, 77, 1492102215, 1492102215, 0, 73),
(80, 7, 78, 1492102215, 1492102215, 0, 10),
(81, 0, 0, 1492102376, 1492102376, 1, 74),
(82, 0, 1, 1492102376, 1492102376, 1, 75),
(83, 0, 2, 1492102376, 1492102376, 1, 76),
(84, 0, 3, 1492102376, 1492102376, 1, 77),
(85, 0, 4, 1492102376, 1492102376, 1, 78),
(86, 0, 5, 1492102376, 1492102376, 1, 79),
(87, 0, 6, 1492102377, 1492102377, 1, 80),
(88, 0, 7, 1492102377, 1492102377, 1, 81),
(89, 1, 8, 1492102377, 1492102377, 1, 82),
(90, 1, 9, 1492102377, 1492102377, 1, 83),
(91, 1, 10, 1492102377, 1492102377, 1, 84),
(92, 1, 11, 1492102377, 1492102377, 1, 85),
(93, 1, 12, 1492102377, 1492102377, 1, 86),
(94, 1, 13, 1492102377, 1492102377, 1, 87),
(95, 1, 14, 1492102377, 1492102377, 1, 88),
(96, 1, 15, 1492102377, 1492102377, 1, 89),
(97, 2, 16, 1492102377, 1492102377, 1, 90),
(98, 2, 17, 1492102377, 1492102377, 1, 91),
(99, 2, 18, 1492102377, 1492102377, 1, 92),
(100, 2, 19, 1492102377, 1492102377, 1, 93),
(101, 2, 20, 1492102377, 1492102377, 1, 94),
(102, 2, 21, 1492102377, 1492102377, 1, 95),
(103, 2, 22, 1492102377, 1492102377, 1, 96),
(104, 2, 23, 1492102377, 1492102377, 1, 97),
(105, 3, 24, 1492102378, 1492102378, 1, 98),
(106, 3, 25, 1492102378, 1492102378, 1, 99),
(107, 3, 26, 1492102378, 1492102378, 1, 100),
(108, 3, 27, 1492102378, 1492102378, 1, 101),
(109, 3, 28, 1492102378, 1492102378, 1, 102),
(110, 3, 29, 1492102378, 1492102378, 1, 103),
(111, 3, 30, 1492102378, 1492102378, 1, 104),
(112, 3, 31, 1492102378, 1492102378, 1, 105),
(113, 4, 32, 1492102378, 1492102378, 1, 106),
(114, 4, 33, 1492102378, 1492102378, 1, 107),
(115, 4, 34, 1492102378, 1492102378, 1, 108),
(116, 4, 35, 1492102378, 1492102378, 1, 109),
(117, 4, 36, 1492102378, 1492102378, 1, 110),
(118, 4, 37, 1492102378, 1492102378, 1, 111),
(119, 4, 38, 1492102378, 1492102378, 1, 112),
(120, 4, 39, 1492102378, 1492102378, 1, 113),
(121, 5, 40, 1492102378, 1492102378, 1, 114),
(122, 5, 41, 1492102378, 1492102378, 1, 115),
(123, 5, 42, 1492102378, 1492102378, 1, 116),
(124, 5, 43, 1492102378, 1492102378, 1, 117),
(125, 5, 44, 1492102378, 1492102378, 1, 118),
(126, 5, 45, 1492102379, 1492102379, 1, 119),
(127, 5, 46, 1492102379, 1492102379, 1, 120),
(128, 5, 47, 1492102379, 1492102379, 1, 121),
(129, 6, 48, 1492102379, 1492102379, 1, 122),
(130, 6, 49, 1492102379, 1492102379, 1, 123),
(131, 6, 50, 1492102379, 1492102379, 1, 124),
(132, 6, 51, 1492102379, 1492102379, 1, 125),
(133, 6, 52, 1492102379, 1492102379, 1, 126),
(134, 6, 53, 1492102379, 1492102379, 1, 127),
(135, 6, 54, 1492102379, 1492102379, 1, 128),
(136, 6, 55, 1492102379, 1492102379, 1, 129),
(137, 7, 56, 1492102379, 1492102379, 1, 130),
(138, 7, 57, 1492102379, 1492102379, 1, 131),
(139, 7, 58, 1492102379, 1492102379, 1, 132),
(140, 7, 59, 1492102379, 1492102379, 1, 133),
(141, 7, 60, 1492102379, 1492102379, 1, 134),
(142, 7, 61, 1492102379, 1492102379, 1, 135),
(143, 7, 62, 1492102379, 1492102379, 1, 136),
(144, 7, 63, 1492102379, 1492102379, 1, 137),
(145, 0, 0, 1492102379, 1492102379, 2, 138),
(146, 0, 1, 1492102379, 1492102379, 2, 139),
(147, 0, 2, 1492102380, 1492102380, 2, 140),
(148, 0, 3, 1492102380, 1492102380, 2, 141),
(149, 0, 4, 1492102380, 1492102380, 2, 142),
(150, 0, 5, 1492102380, 1492102380, 2, 143),
(151, 0, 6, 1492102380, 1492102380, 2, 144),
(152, 0, 7, 1492102380, 1492102380, 2, 145),
(153, 1, 8, 1492102380, 1492102380, 2, 146),
(154, 1, 9, 1492102380, 1492102380, 2, 147),
(155, 1, 10, 1492102380, 1492102380, 2, 148),
(156, 1, 11, 1492102380, 1492102380, 2, 149),
(157, 1, 12, 1492102380, 1492102380, 2, 150),
(158, 1, 13, 1492102380, 1492102380, 2, 151),
(159, 1, 14, 1492102380, 1492102380, 2, 152),
(160, 1, 15, 1492102380, 1492102380, 2, 153),
(161, 2, 16, 1492102380, 1492102380, 2, 154),
(162, 2, 17, 1492102380, 1492102380, 2, 155),
(163, 2, 18, 1492102380, 1492102380, 2, 156),
(164, 2, 19, 1492102380, 1492102380, 2, 157),
(165, 2, 20, 1492102380, 1492102380, 2, 158),
(166, 2, 21, 1492102380, 1492102380, 2, 159),
(167, 2, 22, 1492102380, 1492102380, 2, 160),
(168, 2, 23, 1492102380, 1492102380, 2, 161),
(169, 3, 24, 1492102380, 1492102380, 2, 162),
(170, 3, 25, 1492102381, 1492102381, 2, 163),
(171, 3, 26, 1492102381, 1492102381, 2, 164),
(172, 3, 27, 1492102381, 1492102381, 2, 165),
(173, 3, 28, 1492102381, 1492102381, 2, 166),
(174, 3, 29, 1492102381, 1492102381, 2, 167),
(175, 3, 30, 1492102381, 1492102381, 2, 168),
(176, 3, 31, 1492102381, 1492102381, 2, 169),
(177, 4, 32, 1492102381, 1492102381, 2, 170),
(178, 4, 33, 1492102381, 1492102381, 2, 171),
(179, 4, 34, 1492102381, 1492102381, 2, 172),
(180, 4, 35, 1492102381, 1492102381, 2, 173),
(181, 4, 36, 1492102381, 1492102381, 2, 174),
(182, 4, 37, 1492102381, 1492102381, 2, 175),
(183, 4, 38, 1492102381, 1492102381, 2, 176),
(184, 4, 39, 1492102381, 1492102381, 2, 177),
(185, 5, 40, 1492102381, 1492102381, 2, 178),
(186, 5, 41, 1492102381, 1492102381, 2, 179),
(187, 5, 42, 1492102382, 1492102382, 2, 180),
(188, 5, 43, 1492102382, 1492102382, 2, 181),
(189, 5, 44, 1492102382, 1492102382, 2, 182),
(190, 5, 45, 1492102382, 1492102382, 2, 183),
(191, 5, 46, 1492102382, 1492102382, 2, 184),
(192, 5, 47, 1492102382, 1492102382, 2, 185),
(193, 6, 48, 1492102382, 1492102382, 2, 186),
(194, 6, 49, 1492102382, 1492102382, 2, 139),
(195, 6, 50, 1492102382, 1492102382, 2, 187),
(196, 6, 51, 1492102382, 1492102382, 2, 188),
(197, 6, 52, 1492102382, 1492102382, 2, 189),
(198, 6, 53, 1492102382, 1492102382, 2, 190),
(199, 6, 54, 1492102382, 1492102382, 2, 191),
(200, 6, 55, 1492102382, 1492102382, 2, 192),
(201, 7, 56, 1492102382, 1492102382, 2, 193),
(202, 7, 57, 1492102382, 1492102382, 2, 194),
(203, 7, 58, 1492102382, 1492102382, 2, 195),
(204, 7, 59, 1492102382, 1492102382, 2, 196),
(205, 7, 60, 1492102383, 1492102383, 2, 197),
(206, 7, 61, 1492102383, 1492102383, 2, 198),
(207, 7, 62, 1492102383, 1492102383, 2, 199),
(208, 7, 63, 1492102383, 1492102383, 2, 200);


ALTER TABLE `assessment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_assessment_team` (`team_id`);

ALTER TABLE `assessment_coach`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_assessment_coach_assessment` (`assessment_id`),
  ADD KEY `fk_assessment_coach_coach` (`coach_id`);

ALTER TABLE `company`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_company_coach` (`coach_id`);

ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `individual_report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_individual_report_report` (`report_id`),
  ADD KEY `fk_individual_report_user` (`person_id`);

ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_log_user` (`coach_id`);

ALTER TABLE `migration`
  ADD PRIMARY KEY (`version`);

ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payment_coach` (`coach_id`),
  ADD KEY `fk_payment_stock` (`stock_id`),
  ADD KEY `fk_payment_creator` (`creator_id`);

ALTER TABLE `payment_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payment_log_payment` (`payment_id`);

ALTER TABLE `person`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_person_coach` (`coach_id`);

ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `question`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `report`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_report_assessment` (`assessment_id`);

ALTER TABLE `stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_stock_coach` (`coach_id`),
  ADD KEY `fk_stock_product` (`product_id`),
  ADD KEY `fk_stock_creator` (`creator_id`);

ALTER TABLE `team`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_team_coach` (`coach_id`),
  ADD KEY `fk_team_sponsor` (`sponsor_id`),
  ADD KEY `fk_team_company` (`company_id`);

ALTER TABLE `team_member`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_team_member_team` (`team_id`),
  ADD KEY `fk_team_member_person` (`person_id`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `wheel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_wheel_assessment` (`assessment_id`),
  ADD KEY `fk_wheel_observed` (`observed_id`),
  ADD KEY `fk_wheel_observer` (`observer_id`);

ALTER TABLE `wheel_answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_wheel_answer_wheel` (`wheel_id`),
  ADD KEY `fk_wheel_answer_question` (`question_id`);

ALTER TABLE `wheel_question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `index_order` (`order`,`type`),
  ADD KEY `fk_wheel_question_question` (`question_id`);


ALTER TABLE `assessment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `assessment_coach`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `individual_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `payment_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `person`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `team_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `wheel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `wheel_answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `wheel_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `assessment`
  ADD CONSTRAINT `fk_assessment_team` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE CASCADE;

ALTER TABLE `assessment_coach`
  ADD CONSTRAINT `fk_assessment_coach_assessment` FOREIGN KEY (`assessment_id`) REFERENCES `assessment` (`id`),
  ADD CONSTRAINT `fk_assessment_coach_coach` FOREIGN KEY (`coach_id`) REFERENCES `user` (`id`);

ALTER TABLE `company`
  ADD CONSTRAINT `fk_company_coach` FOREIGN KEY (`coach_id`) REFERENCES `user` (`id`);

ALTER TABLE `individual_report`
  ADD CONSTRAINT `fk_individual_report_report` FOREIGN KEY (`report_id`) REFERENCES `report` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_individual_report_user` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE;

ALTER TABLE `log`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`coach_id`) REFERENCES `user` (`id`);

ALTER TABLE `payment`
  ADD CONSTRAINT `fk_payment_coach` FOREIGN KEY (`coach_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_payment_creator` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_payment_stock` FOREIGN KEY (`stock_id`) REFERENCES `stock` (`id`);

ALTER TABLE `payment_log`
  ADD CONSTRAINT `fk_payment_log_payment` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`id`);

ALTER TABLE `person`
  ADD CONSTRAINT `fk_person_coach` FOREIGN KEY (`coach_id`) REFERENCES `user` (`id`);

ALTER TABLE `report`
  ADD CONSTRAINT `fk_report_assessment` FOREIGN KEY (`assessment_id`) REFERENCES `assessment` (`id`) ON DELETE CASCADE;

ALTER TABLE `stock`
  ADD CONSTRAINT `fk_stock_coach` FOREIGN KEY (`coach_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_stock_creator` FOREIGN KEY (`creator_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_stock_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

ALTER TABLE `team`
  ADD CONSTRAINT `fk_team_coach` FOREIGN KEY (`coach_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `fk_team_company` FOREIGN KEY (`company_id`) REFERENCES `company` (`id`),
  ADD CONSTRAINT `fk_team_sponsor` FOREIGN KEY (`sponsor_id`) REFERENCES `person` (`id`);

ALTER TABLE `team_member`
  ADD CONSTRAINT `fk_team_member_person` FOREIGN KEY (`person_id`) REFERENCES `person` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_team_member_team` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE CASCADE;

ALTER TABLE `wheel`
  ADD CONSTRAINT `fk_wheel_assessment` FOREIGN KEY (`assessment_id`) REFERENCES `assessment` (`id`),
  ADD CONSTRAINT `fk_wheel_observed` FOREIGN KEY (`observed_id`) REFERENCES `person` (`id`),
  ADD CONSTRAINT `fk_wheel_observer` FOREIGN KEY (`observer_id`) REFERENCES `person` (`id`);

ALTER TABLE `wheel_answer`
  ADD CONSTRAINT `fk_wheel_answer_question` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`),
  ADD CONSTRAINT `fk_wheel_answer_wheel` FOREIGN KEY (`wheel_id`) REFERENCES `wheel` (`id`) ON DELETE CASCADE;

ALTER TABLE `wheel_question`
  ADD CONSTRAINT `fk_wheel_question_question` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
