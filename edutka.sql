-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-11-2022 a las 22:25:18
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `edutka`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `authorization`
--

CREATE TABLE `authorization` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `teacher_id` int(11) NOT NULL DEFAULT 0,
  `course_id` int(11) NOT NULL DEFAULT 0,
  `period_id` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `court` set('unique','first','second','third') NOT NULL DEFAULT 'unique',
  `expires` int(11) NOT NULL DEFAULT 0,
  `status` set('authorized','pending','denied') NOT NULL DEFAULT 'pending',
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `authorization`
--

INSERT INTO `authorization` (`id`, `user_id`, `teacher_id`, `course_id`, `period_id`, `description`, `court`, `expires`, `status`, `time`) VALUES
(1, 4, 44, 65, 15, 'Lo hice :)', 'first', 1649808000, 'authorized', 1643328632);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `preknowledge` text DEFAULT NULL,
  `code` varchar(8) NOT NULL DEFAULT '0',
  `qualification` set('activated','deactivated') NOT NULL DEFAULT 'deactivated',
  `credits` int(11) NOT NULL DEFAULT 0,
  `quota` int(11) NOT NULL,
  `type` set('practice','theoretical') NOT NULL DEFAULT 'practice',
  `schedule` set('daytime','nightly') NOT NULL DEFAULT 'daytime',
  `status` set('activated','deactivated') NOT NULL DEFAULT 'activated',
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `course`
--

INSERT INTO `course` (`id`, `name`, `preknowledge`, `code`, `qualification`, `credits`, `quota`, `type`, `schedule`, `status`, `time`) VALUES
(1, 'Conceptos y métodos matemáticos', '', '7XZO4', 'activated', 3, 24, 'practice', 'nightly', 'activated', 1635860427),
(2, 'Elementos básicos de programación', '', 'Q2PL4E', 'deactivated', 4, 16, 'practice', 'daytime', 'activated', 1635860427),
(3, 'Teorías de la ingeniería informática', '', 'B8ZN0', 'deactivated', 4, 16, 'practice', 'daytime', 'activated', 1635860427),
(4, 'Normas de participación ciudadana e institucional', '', 'R1S5AOL', 'deactivated', 1, 14, 'practice', 'daytime', 'activated', 1635860427),
(5, 'Herramientas pedagógicas y didácticas de la educación a distancia', '', 'PGCJ6O3', 'deactivated', 1, 12, 'theoretical', 'daytime', 'activated', 1635860427),
(6, 'Funciones y métodos del cálculo diferencial e integral', '', 'XF0NBUH', 'deactivated', 3, 20, 'practice', 'daytime', 'activated', 1635860427),
(7, 'Postulados y principios de la física mecánica', '', 'WFPUV', 'deactivated', 3, 24, 'practice', 'daytime', 'activated', 1635860427),
(8, 'Lenguaje de programación funcional', '', 'DKF61', 'deactivated', 4, 19, 'practice', 'daytime', 'activated', 1635860427),
(9, 'Interacción de objetos por medio de programación', '', 'G4BJH3Y', 'activated', 4, 25, 'practice', 'daytime', 'activated', 1635860427),
(10, 'Desarrollo de competencia comunicativa en segunda lengua a 1 1 basico principiante', '', '8BATEO', 'deactivated', 2, 20, 'practice', 'daytime', 'activated', 1635860427),
(11, 'Teoremas y postulados matemáticos', '', '04UO9FS', 'deactivated', 3, 50, 'practice', 'daytime', 'activated', 1635860427),
(12, 'Diseño de un circuito electrónico digital', '', 'BVNLY8', 'deactivated', 3, 16, 'practice', 'nightly', 'activated', 1635860427),
(13, 'Estructuras de datos en la administración de la información', '', 'FBON9', 'deactivated', 4, 0, 'practice', 'daytime', 'activated', 1635860427),
(14, 'Aplicaciones en entorno gráfico con programación orientada a objetos', '', '8OL4AC', 'deactivated', 4, 15, 'practice', 'daytime', 'activated', 1635860427),
(15, 'Conceptos y principios de la contabilidad general', '', 'Z8QVF5', 'deactivated', 2, 0, 'practice', 'daytime', 'activated', 1635860427),
(16, 'Competencia comunicativa en segunda lengua a 1 2 básico principiante', '', 'IERAG', 'deactivated', 1, 0, 'practice', 'daytime', 'activated', 1635860427),
(17, 'Teoría del conocimiento', '', 'TPSGK', 'deactivated', 1, 14, 'practice', 'daytime', 'activated', 1635860427),
(18, 'Razonamiento matemático en vectores y matrices', '', 'IL8QKG', 'deactivated', 3, 20, 'practice', 'daytime', 'activated', 1635860427),
(19, 'Métodos del cálculo vectorial en la computación gráfica', '', 'P28KGE', 'deactivated', 3, 0, 'practice', 'daytime', 'activated', 1635860427),
(20, 'Estructuras de datos no lineales', '', '4GVHA', 'deactivated', 4, 20, 'practice', 'daytime', 'activated', 1635860427),
(21, 'Herramientas informáticas orientadas a eventos', '', '5TCBU', 'deactivated', 4, 20, 'practice', 'daytime', 'activated', 1635860427),
(22, 'Criterios para la selección de hardware', '', 'RGB7XPH', 'deactivated', 4, 20, 'practice', 'daytime', 'activated', 1635860427),
(23, 'Herramientas de ingeniería económica', '', 'PVDQXI3', 'deactivated', 2, 0, 'practice', 'daytime', 'activated', 1635860427),
(24, 'Competencia comunicativa en segunda lengua a 2 1 básico intermedio', '', 'X2FK4', 'deactivated', 1, 0, 'practice', 'daytime', 'activated', 1635860427),
(25, 'Procesos estadísticos y probabilísticos', '', 'HBEMF7Z', 'deactivated', 2, 20, 'practice', 'daytime', 'activated', 1635860427),
(26, 'Representación de información en bases de datos', '', '5FN2L', 'deactivated', 4, 20, 'practice', 'daytime', 'activated', 1635860427),
(27, 'Técnicas del diseño hipermedia en la informática', '', 'AMC8YD7', 'deactivated', 4, 20, 'practice', 'daytime', 'activated', 1635860427),
(28, 'Conceptos básicos de la comunicación electrónica', '', 'DJM4LSH', 'deactivated', 3, 0, 'practice', 'daytime', 'activated', 1635860427),
(29, 'Creatividad empresarial y plan de negocios', '', '8Q3R0GD', 'deactivated', 3, 20, 'practice', 'daytime', 'activated', 1635860427),
(30, 'Competencia comunicativa en segunda lengua a 2 2 básico intermedio', '', 'XJD3T', 'deactivated', 2, 14, 'practice', 'daytime', 'activated', 1635860427),
(31, 'Métodos y técnicas de investigación I', '', '1OL3S', 'deactivated', 2, 20, 'practice', 'daytime', 'activated', 1635860427),
(32, 'Modelos matemáticos y algoritmos', '', '1IEU7', 'deactivated', 2, 20, 'practice', 'daytime', 'activated', 1635860427),
(33, 'Aplicaciones orientadas a la web', '', '8SQA4', 'deactivated', 4, 20, 'practice', 'daytime', 'activated', 1635860427),
(34, 'Procesos involucrados en el modelado de software', '', 'G6FVXC', 'deactivated', 4, 20, 'practice', 'daytime', 'activated', 1635860427),
(35, 'Diseño e implementación de redes de datos', '', '3TDHV', 'activated', 3, 20, 'practice', 'daytime', 'activated', 1635860427),
(36, 'Formulación y evaluación de proyectos', '', 'WSNDL', 'deactivated', 2, 20, 'practice', 'daytime', 'activated', 1635860427),
(37, 'Competencia comunicativa en segunda lengua B 1 1 intermedio principiante', '', 'KDHJLW', 'deactivated', 1, 0, 'practice', 'daytime', 'activated', 1635860427),
(38, 'Seminario de grado I', '', 'WI71V2', 'deactivated', 2, 20, 'practice', 'daytime', 'activated', 1635860427),
(39, 'Métodos matemáticos aplicados en simulación', '', '6EZMSGN', 'deactivated', 3, 20, 'practice', 'daytime', 'activated', 1635860427),
(40, 'Algoritmos lógicos en el desarrollo de software', '', '2TLB9', 'deactivated', 4, 20, 'practice', 'daytime', 'activated', 1635860427),
(41, 'Técnicas para el desarrollo de software', '', 'M8JT1K2', 'deactivated', 4, 20, 'practice', 'daytime', 'activated', 1635860427),
(42, 'Arquitectura de sistemas operativos', '', 'ABX5P', 'deactivated', 3, 20, 'practice', 'daytime', 'activated', 1635860427),
(43, 'Gerencia de proyectos informáticos', '', 'TAZ26OI', 'deactivated', 2, 20, 'practice', 'daytime', 'activated', 1635860427),
(44, 'Competencia comunicativa en segunda lengua B 1 2 intermedio', '', 'MW3YGC', 'deactivated', 1, 0, 'practice', 'daytime', 'activated', 1635860427),
(45, 'Seminario de grado II', '', 'MIPNS', 'deactivated', 2, 20, 'practice', 'daytime', 'activated', 1635860427),
(46, 'Elementos gráficos de la comunicación visual', '', '1MFTB', 'deactivated', 3, 0, 'practice', 'daytime', 'activated', 1635860427),
(47, 'Modelos arquitectónicos en software distribuido', '', 'VLR9WQ', 'deactivated', 4, 20, 'practice', 'daytime', 'activated', 1635860427),
(48, 'Herramientas informáticas para el comercio electrónico', '', '5WI4NLX', 'deactivated', 4, 20, 'practice', 'daytime', 'activated', 1635860427),
(49, 'Técnicas para el diseño de aplicaciones telemáticas', '', 'VQTDGS', 'deactivated', 3, 20, 'practice', 'daytime', 'activated', 1635860427),
(50, 'Principios y normas del derecho informático', '', 'JQCU4', 'deactivated', 1, 20, 'practice', 'daytime', 'activated', 1635860427),
(51, 'Proyecto de investigación', '', 'ENRO4S', 'deactivated', 2, 20, 'practice', 'daytime', 'activated', 1635860427),
(52, 'Electiva', '', 'MT3R4KB', 'deactivated', 4, 20, 'practice', 'daytime', 'activated', 1635860427),
(53, 'Diseño e implementación de sistemas de telemetría', '', '457JHR', 'deactivated', 4, 20, 'practice', 'daytime', 'activated', 1635860427),
(54, 'Técnicas y herramientas de seguridad informática', '', 'UGA1B3S', 'deactivated', 3, 20, 'practice', 'daytime', 'activated', 1635860427),
(55, 'Auditoria de sistemas', '', 'HYRPK2Z', 'deactivated', 2, 25, 'practice', 'daytime', 'activated', 1635860427),
(56, 'Práctica profesional', '', 'EM1VDGZ', 'deactivated', 3, 20, 'practice', 'daytime', 'activated', 1635860427),
(57, 'Leyes éticas y morales', '', '19WV8I', 'deactivated', 1, 20, 'practice', 'daytime', 'activated', 1635860427),
(58, 'Desarrollo del proyecto de investigación', '', 'O3Y4UHN', 'deactivated', 3, 20, 'practice', 'daytime', 'activated', 1635860427),
(59, 'Arte y cultura', '', 'ND2UB31', 'deactivated', 1, 20, 'theoretical', 'daytime', 'activated', 1635860427),
(60, 'Emprendimiento I (Creando conciencia del Ser)', '', 'SOYWI', 'deactivated', 1, 20, 'theoretical', 'daytime', 'activated', 1635860427),
(61, 'CULTURA INSTITUCIONAL', '', 'WH0VC', 'activated', 2, 15, 'theoretical', 'daytime', 'activated', 1643163059),
(62, 'Estructura de Datos en la Administracion de la Informacion', '', '7V8G4IK', 'activated', 3, 19, 'practice', 'daytime', 'activated', 1643163321),
(63, 'Principios Basicos de Contabilidad', '', 'B1ZVTP', 'activated', 2, 16, 'practice', 'daytime', 'activated', 1643163512),
(64, 'Metodos de Calculo Vectorial en Computacion Grafica', '', '4QTM0AX', 'activated', 1, 15, 'practice', 'daytime', 'activated', 1643163554),
(65, 'Elementos Graficos y Comunicacion Visual', '', 'EPUM1X6', 'activated', 2, 17, 'practice', 'daytime', 'activated', 1643163887);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `curriculum`
--

CREATE TABLE `curriculum` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL DEFAULT 0,
  `plan_id` int(11) NOT NULL DEFAULT 0,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `curriculum`
--

INSERT INTO `curriculum` (`id`, `course_id`, `plan_id`, `time`) VALUES
(1, 5, 1, 1643163338),
(2, 4, 1, 1643163338),
(3, 1, 1, 1643163338),
(4, 2, 1, 1643163338),
(5, 3, 1, 1643163338),
(6, 59, 1, 1643163338),
(8, 60, 1, 1643163338),
(9, 8, 1, 1643163338),
(10, 6, 1, 1643163338),
(11, 10, 1, 1643163338),
(12, 9, 1, 1643163338),
(13, 7, 1, 1643163338),
(14, 11, 1, 1643163338),
(15, 12, 1, 1643163338),
(16, 62, 1, 1643163440),
(17, 14, 1, 1643163765),
(18, 17, 1, 1643163765),
(19, 63, 1, 1643163765),
(20, 18, 1, 1643163765),
(21, 64, 1, 1643163765),
(22, 20, 1, 1643163765),
(23, 21, 1, 1643163765),
(24, 22, 1, 1643163766),
(25, 23, 1, 1643163766),
(26, 25, 1, 1643163766),
(27, 28, 1, 1643163766),
(28, 29, 1, 1643163766),
(29, 27, 1, 1643163766),
(30, 26, 1, 1643163766),
(31, 32, 1, 1643163766),
(32, 33, 1, 1643163766),
(33, 34, 1, 1643163766),
(34, 36, 1, 1643163766),
(35, 38, 1, 1643163766),
(36, 39, 1, 1643164007),
(37, 40, 1, 1643164007),
(38, 41, 1, 1643164007),
(39, 42, 1, 1643164007),
(40, 43, 1, 1643164007),
(41, 45, 1, 1643164007),
(42, 35, 1, 1643164007),
(43, 65, 1, 1643164008),
(44, 47, 1, 1643164008),
(45, 48, 1, 1643164008),
(46, 49, 1, 1643164008),
(47, 50, 1, 1643164008),
(48, 51, 1, 1643164008),
(49, 31, 1, 1643164008),
(50, 58, 1, 1643164008),
(51, 52, 1, 1643164008),
(52, 53, 1, 1643164008),
(53, 54, 1, 1643164008),
(54, 55, 1, 1643164008),
(55, 56, 1, 1643164008),
(56, 57, 1, 1643164008),
(57, 61, 1, 1643164089);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enrolled`
--

CREATE TABLE `enrolled` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `period_id` int(11) NOT NULL DEFAULT 0,
  `course_id` int(11) NOT NULL DEFAULT 0,
  `program_id` int(11) NOT NULL DEFAULT 0,
  `type` set('program','course') NOT NULL DEFAULT 'program',
  `status` set('registered','cancelled') NOT NULL DEFAULT 'registered',
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `enrolled`
--

INSERT INTO `enrolled` (`id`, `user_id`, `period_id`, `course_id`, `program_id`, `type`, `status`, `time`) VALUES
(1, 14, 0, 0, 1, 'program', 'registered', 1643165092),
(2, 14, 2, 5, 1, 'course', 'registered', 1643165569),
(3, 14, 2, 4, 1, 'course', 'registered', 1643165606),
(5, 14, 2, 2, 1, 'course', 'registered', 1643165643),
(6, 14, 2, 3, 1, 'course', 'registered', 1643165655),
(7, 14, 2, 59, 1, 'course', 'registered', 1643165668),
(8, 14, 2, 61, 1, 'course', 'registered', 1643165677),
(9, 14, 2, 60, 1, 'course', 'registered', 1643165686),
(10, 14, 3, 8, 1, 'course', 'registered', 1643165744),
(11, 14, 3, 6, 1, 'course', 'registered', 1643165754),
(12, 14, 3, 10, 1, 'course', 'registered', 1643165771),
(13, 14, 3, 9, 1, 'course', 'registered', 1643165781),
(14, 14, 3, 7, 1, 'course', 'registered', 1643165792),
(15, 14, 10, 11, 1, 'course', 'registered', 1643165823),
(16, 14, 10, 12, 1, 'course', 'registered', 1643165836),
(17, 14, 10, 62, 1, 'course', 'registered', 1643165846),
(18, 14, 10, 14, 1, 'course', 'registered', 1643165902),
(19, 14, 10, 17, 1, 'course', 'registered', 1643165926),
(20, 14, 10, 63, 1, 'course', 'registered', 1643165939),
(21, 14, 11, 18, 1, 'course', 'registered', 1643241566),
(22, 14, 11, 64, 1, 'course', 'registered', 1643241577),
(23, 14, 11, 20, 1, 'course', 'registered', 1643241607),
(24, 14, 11, 21, 1, 'course', 'registered', 1643241642),
(25, 14, 11, 22, 1, 'course', 'registered', 1643241657),
(26, 14, 12, 25, 1, 'course', 'registered', 1643241707),
(27, 14, 12, 29, 1, 'course', 'registered', 1643241742),
(28, 14, 12, 27, 1, 'course', 'registered', 1643241771),
(29, 14, 12, 26, 1, 'course', 'registered', 1643241794),
(30, 14, 13, 32, 1, 'course', 'registered', 1643241841),
(31, 14, 13, 33, 1, 'course', 'registered', 1643241862),
(32, 14, 13, 34, 1, 'course', 'registered', 1643241885),
(33, 14, 13, 36, 1, 'course', 'registered', 1643241910),
(34, 14, 13, 38, 1, 'course', 'registered', 1643241944),
(35, 14, 14, 39, 1, 'course', 'registered', 1643241972),
(36, 14, 14, 40, 1, 'course', 'registered', 1643241992),
(37, 14, 14, 41, 1, 'course', 'registered', 1643242013),
(38, 14, 14, 42, 1, 'course', 'registered', 1643242037),
(39, 14, 14, 43, 1, 'course', 'registered', 1643242057),
(40, 14, 14, 45, 1, 'course', 'registered', 1643242070),
(41, 14, 14, 35, 1, 'course', 'cancelled', 1643242103),
(42, 14, 15, 65, 1, 'course', 'registered', 1643242124),
(43, 14, 15, 47, 1, 'course', 'registered', 1643242147),
(44, 14, 15, 48, 1, 'course', 'registered', 1643242171),
(45, 14, 15, 49, 1, 'course', 'registered', 1643242195),
(46, 14, 15, 50, 1, 'course', 'registered', 1643242248),
(47, 14, 15, 51, 1, 'course', 'registered', 1643242276),
(48, 14, 15, 31, 1, 'course', 'registered', 1643242380),
(49, 14, 8, 58, 1, 'course', 'registered', 1643242415),
(50, 14, 8, 52, 1, 'course', 'registered', 1643242439),
(51, 14, 8, 53, 1, 'course', 'registered', 1643242462),
(52, 14, 8, 54, 1, 'course', 'registered', 1643242482),
(53, 14, 8, 55, 1, 'course', 'registered', 1643242492),
(54, 14, 8, 56, 1, 'course', 'registered', 1643242511),
(55, 14, 8, 57, 1, 'course', 'registered', 1643242528),
(56, 14, 2, 1, 1, 'course', 'registered', 1643313491),
(57, 14, 13, 26, 1, 'course', 'registered', 1643314961),
(58, 14, 15, 35, 1, 'course', 'registered', 1643319654),
(61, 3, 0, 0, 1, 'program', 'registered', 1643335375),
(62, 3, 14, 35, 1, 'course', 'registered', 1643335402),
(63, 3, 23, 35, 1, 'course', 'registered', 1644173951);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `faculty`
--

CREATE TABLE `faculty` (
  `id` int(11) NOT NULL,
  `name` varchar(16) NOT NULL,
  `status` set('activated','deactivated') DEFAULT 'deactivated',
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `faculty`
--

INSERT INTO `faculty` (`id`, `name`, `status`, `time`) VALUES
(1, 'Ingeniería', 'activated', 1635940791),
(2, 'Contaduria', 'activated', 1635940791);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `form`
--

CREATE TABLE `form` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `form_key` varchar(16) NOT NULL,
  `access` text NOT NULL,
  `expire` int(11) NOT NULL DEFAULT 0,
  `status` set('activated','deactivated') NOT NULL DEFAULT 'deactivated',
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `form`
--

INSERT INTO `form` (`id`, `user_id`, `form_key`, `access`, `expire`, `status`, `time`) VALUES
(1, 1, '2GZD9UCR5TPL', '213123123,123123123,123', 1637366400, 'deactivated', 1636663881),
(2, 1, 'OLDPW1VIS5', '1143413822, 12345678,87654321,1234532,123219391', 1643328000, 'activated', 1636663905);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `note`
--

CREATE TABLE `note` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `course_id` int(11) NOT NULL DEFAULT 0,
  `period_id` int(11) NOT NULL DEFAULT 0,
  `program_id` int(11) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `observation` varchar(500) CHARACTER SET utf8mb4 DEFAULT NULL,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `note`
--

INSERT INTO `note` (`id`, `user_id`, `course_id`, `period_id`, `program_id`, `notes`, `observation`, `time`) VALUES
(1, 14, 5, 2, 1, '[[\"3.7\"],[\"3.7\"],[\"4.5\"]]', NULL, 1643165569),
(2, 14, 4, 2, 1, '[[\"4.5\"],[\"4.0\"],[\"3.5\"]]', NULL, 1643165606),
(3, 14, 1, 1, 1, '[0,0,0]', NULL, 1643165617),
(4, 14, 2, 2, 1, '[[\"4.4\"],[\"5.0\"],[\"4.8\"]]', NULL, 1643165643),
(5, 14, 3, 2, 1, '[[\"4.2\"],[\"4.5\"],[\"4.2\"]]', NULL, 1643165655),
(6, 14, 59, 2, 1, '[[\"5\"],[\"5\"],[\"5\"]]', NULL, 1643165668),
(7, 14, 61, 2, 1, '[[\"4.5\"],[\"4.1\"],[\"4.9\"]]', NULL, 1643165677),
(8, 14, 60, 2, 1, '[[\"4.0\"],[\"4.0\"],[\"5.0\"]]', NULL, 1643165686),
(9, 14, 8, 3, 1, '[[\"3.9\"],[\"4.1\"],[\"3.9\"]]', NULL, 1643165744),
(10, 14, 6, 3, 1, '[[\"4.3\"],[\"4.3\"],[\"3.8\"]]', NULL, 1643165754),
(11, 14, 10, 3, 1, '[[\"4.5\"],[\"4.0\"],[\"4.5\"]]', NULL, 1643165771),
(12, 14, 9, 3, 1, '[[\"3.1\"],[\"3.8\"],[\"4.5\"]]', NULL, 1643165781),
(13, 14, 7, 3, 1, '[[\"4.4\"],[\"4.5\"],[\"4.8\"]]', NULL, 1643165792),
(14, 14, 11, 10, 1, '[[\"4.4\"],[\"4.5\"],[\"4.8\"]]', NULL, 1643165823),
(15, 14, 12, 10, 1, '[[\"3.4\"],[\"3.4\"],[\"3.8\"]]', NULL, 1643165836),
(16, 14, 62, 10, 1, '[[\"3.1\"],[\"3.3\"],0]', NULL, 1643165846),
(17, 14, 14, 10, 1, '[[\"3.2\"],[\"4.7\"],[\"3.5\"]]', NULL, 1643165902),
(18, 14, 17, 10, 1, '[[\"3.9\"],[\"3.1\"],[\"3.5\"]]', NULL, 1643165926),
(19, 14, 63, 10, 1, '[[\"3.5\"],[\"2.3\"],0]', NULL, 1643165939),
(20, 14, 18, 11, 1, '[[\"3.5\"],[\"3.5\"],[\"3.7\"]]', NULL, 1643241566),
(21, 14, 64, 11, 1, '[[\"4.1\"],[\"4.2\"],[\"4.0\"]]', NULL, 1643241577),
(22, 14, 20, 11, 1, '[[\"3.8\"],[\"4.5\"],[\"4.4\"]]', NULL, 1643241607),
(23, 14, 21, 11, 1, '[[\"4.0\"],[\"4.0\"],[\"4.7\"]]', NULL, 1643241642),
(24, 14, 22, 11, 1, '[[\"4.8\"],[\"4.4\"],[\"4.7\"]]', NULL, 1643241657),
(25, 14, 25, 12, 1, '[[\"3.8\"],[\"4.4\"],[\"4.3\"]]', NULL, 1643241707),
(26, 14, 29, 12, 1, '[[\"0.8\"],[\"4.2\"],0]', NULL, 1643241742),
(27, 14, 27, 12, 1, '[[\"4.6\"],[\"5.0\"],[\"5.0\"]]', NULL, 1643241771),
(28, 14, 26, 12, 1, '[[\"1.0\"],[\"2.0\"],0]', 'Los trabajo no fue hecho siguiendo los parametros y lineamientos establecidos desde el inicio de semestre para cada corte, ademas de estar incompletos.', 1643241794),
(29, 14, 32, 13, 1, '[[\"4.4\"],[\"4.3\"],[\"4.4\"]]', NULL, 1643241841),
(30, 14, 33, 13, 1, '[[\"4.1\"],[\"4.0\"],[\"4.5\"]]', NULL, 1643241862),
(31, 14, 34, 13, 1, '[[\"4.1\"],[\"3.8\"],[\"3.5\"]]', NULL, 1643241886),
(32, 14, 36, 13, 1, '[[\"3.8\"],[\"4.2\"],[\"4.5\"]]', NULL, 1643241910),
(33, 14, 38, 13, 1, '[[\"4.5\"],[\"5.0\"],[\"5.0\"]]', NULL, 1643241944),
(34, 14, 39, 14, 1, '[[\"4.3\"],[\"4.6\"],[\"4.2\"]]', NULL, 1643241972),
(35, 14, 40, 14, 1, '[[\"3.2\"],[\"4.3\"],[\"4.3\"]]', NULL, 1643241992),
(36, 14, 41, 14, 1, '[[\"4.5\"],[\"4.2\"],[\"3.6\"]]', NULL, 1643242013),
(37, 14, 42, 14, 1, '[[\"4.2\"],[\"4.1\"],[\"3.2\"]]', NULL, 1643242037),
(38, 14, 43, 14, 1, '[[\"4.4\"],[\"4.7\"],[\"4.1\"]]', NULL, 1643242057),
(39, 14, 45, 14, 1, '[[\"5\"],[\"5\"],[\"5\"]]', NULL, 1643242070),
(40, 14, 35, 14, 1, '[0,0,0]', NULL, 1643242103),
(41, 14, 65, 15, 1, '[[\"3.9\"],[\"3.6\"],[\"4.4\"]]', NULL, 1643242124),
(42, 14, 47, 15, 1, '[[\"5\"],[\"4.0\"],[\"5\"]]', NULL, 1643242147),
(43, 14, 48, 15, 1, '[[\"4.2\"],[\"4.8\"],[\"4.8\"]]', NULL, 1643242171),
(44, 14, 49, 15, 1, '[[\"4.2\"],[\"4.2\"],[\"4.1\"]]', NULL, 1643242195),
(45, 14, 50, 15, 1, '[[\"4.6\"],[\"4.4\"],[\"4.4\"]]', NULL, 1643242248),
(46, 14, 51, 15, 1, '[[\"4.0\"],[\"3.5\"],[\"4.5\"]]', NULL, 1643242276),
(47, 14, 31, 15, 1, '[[\"4.1\"],[\"4.1\"],[\"4.1\"]]', NULL, 1643242380),
(48, 14, 58, 8, 1, '[[\"3.9\"],[\"4.6\"],[\"4.6\"]]', NULL, 1643242415),
(49, 14, 52, 8, 1, '[[\"4.8\"],[\"5.0\"],[\"4.5\"]]', NULL, 1643242439),
(50, 14, 53, 8, 1, '[[\"4.3\"],[\"4.2\"],[\"4.6\"]]', NULL, 1643242462),
(51, 14, 54, 8, 1, '[[\"4.3\"],[\"4.7\"],[\"4.5\"]]', NULL, 1643242482),
(52, 14, 55, 8, 1, '[[\"4.4\"],[\"4.0\"],[\"4.7\"]]', NULL, 1643242492),
(53, 14, 56, 8, 1, '[[\"5\"],[\"5\"],[\"5\"]]', NULL, 1643242511),
(54, 14, 57, 8, 1, '[[\"4.5\"],[\"4.5\"],[\"4.5\"]]', NULL, 1643242528),
(55, 14, 1, 2, 1, '[[\"4.7\"],[\"3.9\"],[\"4.2\"]]', NULL, 1643313491),
(56, 14, 26, 13, 1, '[[\"4.2\"],[\"3.9\"],[\"4.4\"]]', NULL, 1643314961),
(57, 14, 35, 15, 1, '[[\"4.3\"],[\"3.8\"],[\"3.9\"]]', NULL, 1643319654),
(60, 3, 35, 14, 1, '[0,0,0]', NULL, 1643335402),
(61, 3, 35, 23, 1, '[[\"4\"],[\"3\"],[\"2\"]]', NULL, 1644173951);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `to_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL DEFAULT 0,
  `seen` int(11) NOT NULL DEFAULT 0,
  `type` varchar(20) NOT NULL,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `notification`
--

INSERT INTO `notification` (`id`, `from_id`, `to_id`, `course_id`, `seen`, `type`, `time`) VALUES
(1, 14, 7, 1, 1644625237, 'enroll', 1643313491),
(2, 14, 23, 26, 0, 'enroll', 1643314961),
(3, 14, 28, 26, 0, 'enroll', 1643314966),
(4, 14, 34, 35, 1643335234, 'enroll', 1643319654),
(5, 14, 1, 65, 1644802182, 'req_qualification', 1643326961),
(6, 14, 4, 65, 1659965882, 'req_qualification', 1643326964),
(7, 4, 36, 65, 1643328767, 'auth_authorized', 1643328759),
(8, 3, 34, 35, 0, 'enroll', 1643335402),
(9, 3, 1, 35, 1644802182, 'req_qualification', 1644363219),
(10, 3, 4, 35, 1659965882, 'req_qualification', 1644363220);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `page`
--

CREATE TABLE `page` (
  `id` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  `text` text DEFAULT NULL,
  `hexco` varchar(10) NOT NULL DEFAULT 'blue',
  `active` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `page`
--

INSERT INTO `page` (`id`, `type`, `text`, `hexco`, `active`) VALUES
(1, 'terms-of-use', '<h4>1- Write the terms of use of your website.</h4>  \n\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam hendrerit sem quis urna commodo gravida ac eget diam. Sed tristique id quam auctor iaculis. Donec eget volutpat elit. Curabitur in enim eu nulla aliquet pellentesque nec quis neque. Maecenas ac nulla convallis, aliquam dui posuere, dignissim mi. Maecenas iaculis arcu vitae velit aliquet, vitae blandit ante mattis. Mauris varius sollicitudin purus, mattis blandit urna consequat condimentum. Curabitur vulputate sem finibus ipsum eleifend, et convallis felis egestas. Quisque condimentum faucibus maximus.\n<br><br>\nVestibulum sagittis, ex non condimentum lacinia, lorem libero ornare elit, quis placerat sapien nisi ac eros. Mauris in justo porta, auctor ante non, pulvinar enim. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sit amet pulvinar leo. Duis ut velit vitae felis bibendum feugiat. Donec urna orci, dapibus ut erat non, pellentesque cursus tellus. Nullam accumsan rhoncus felis, vel porta tortor suscipit pellentesque. Cras non ante ac orci elementum mattis vel et metus. Proin luctus consequat suscipit. Quisque pharetra lorem augue, nec aliquet nulla aliquam quis. Phasellus ac dictum dui. Etiam mollis ultrices dolor, nec suscipit ex ornare semper. Donec aliquet velit varius volutpat vehicula. Pellentesque interdum urna ac turpis gravida, quis rhoncus ligula ornare.\n<br><br>\nCurabitur vulputate tellus ac mauris pellentesque euismod. Nulla gravida erat nulla, non dictum ipsum faucibus in. In hac habitasse platea dictumst. Morbi libero nulla, laoreet sit amet sagittis et, semper ut ex. Integer eleifend ac justo porta semper. Suspendisse bibendum turpis et odio ullamcorper tincidunt. Sed at lectus est. Pellentesque semper eros arcu, et aliquet mi eleifend et. Donec quis ipsum condimentum, tristique massa a, bibendum turpis. Nunc volutpat tristique urna a hendrerit. In hac habitasse platea dictumst. Nam ligula sem, ultricies non condimentum at, tempus eget sapien. Nulla facilisi. Morbi sit amet sodales nulla, ut dapibus velit.\n<br><br>\nQuisque at leo vel lacus iaculis pharetra. Cras rhoncus eros sit amet nulla fermentum, eget tempor est imperdiet. Sed placerat nec felis eu maximus. Mauris ut purus gravida, rhoncus justo vel, iaculis purus. Nam vitae arcu nisl. Pellentesque efficitur est nulla, quis sollicitudin tellus venenatis eget. In pellentesque mi eu luctus porta. Vestibulum ultricies ex nisl, in scelerisque metus egestas vitae. Fusce suscipit orci quam, sed ornare lorem imperdiet sit amet. In non viverra sapien.\n<br><br>\nIn blandit turpis nec sapien dictum pulvinar. Donec venenatis non tellus eget venenatis. Donec lobortis ipsum sed nulla lacinia, eu pretium eros pretium. Sed volutpat ante et dolor vulputate tempus. Morbi erat felis, viverra vel elit eget, pretium efficitur tellus. Duis malesuada eget ipsum eget placerat. In aliquet felis nisi, ut luctus metus interdum ac. Sed nec egestas magna. Ut aliquet, ante vitae ullamcorper dignissim, ipsum leo fringilla ligula, sed accumsan odio ante ac risus. Vivamus dolor nunc, porta vel neque id, tristique fringilla ex. Vivamus sit amet sapien eu nulla tincidunt viverra nec nec lorem. Integer nibh dolor, varius sit amet nisl vitae, euismod gravida nulla. Nullam volutpat volutpat luctus. Praesent fermentum sapien semper, lacinia augue sed, viverra odio. Maecenas vel arcu volutpat magna gravida imperdiet. Proin eleifend tincidunt diam, vel elementum arcu.\n<br><br>\nNunc tempor vel tortor ac lacinia. Phasellus feugiat feugiat lorem, vel rhoncus dui aliquet ut. Nam ultricies placerat nibh vitae congue. Vestibulum fermentum gravida sem, eget rhoncus urna efficitur ac. Aenean eget ligula ipsum. Aenean vel fermentum mauris. Duis volutpat ligula diam, in sollicitudin lacus ullamcorper eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce elit lacus, posuere ac venenatis quis, sollicitudin vel eros. Mauris laoreet elit non sem tempus, non ornare mi luctus. In viverra porttitor nisi a viverra.\n<br><br>\nUt metus nunc, semper eu dui nec, laoreet sollicitudin ante. Fusce iaculis velit eget feugiat blandit. Curabitur eu mollis sem. Curabitur vehicula sapien in volutpat semper. Nullam fermentum, lectus et blandit scelerisque, dolor odio hendrerit ligula, eu iaculis ex velit ac urna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam lobortis porttitor odio, ac vestibulum urna elementum ac. Sed sem sem, blandit consectetur lorem id, convallis rhoncus diam. In mi tortor, malesuada et nulla eget, mattis varius ligula. Ut dignissim molestie lorem. In bibendum pharetra leo in mattis.\n<br><br>\nDuis et erat sit amet ex dictum accumsan at eu quam. Morbi eget tempor eros, eu tempus neque. Donec lacinia, ante id porta pellentesque, arcu augue dictum nibh, quis pretium est turpis sed eros. Cras et arcu id tellus venenatis luctus. Aenean id nisi ut magna scelerisque finibus. Ut non erat id magna euismod interdum. Proin urna mauris, suscipit quis velit condimentum, tincidunt convallis neque. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Curabitur tortor elit, aliquam ac tincidunt efficitur, elementum eget tortor. Maecenas ligula ipsum, rhoncus id accumsan vel, elementum nec sapien. Mauris neque dolor, vehicula quis magna eu, laoreet eleifend risus. Vivamus sed sem sapien. Aliquam erat volutpat. Integer tristique porttitor est. Donec maximus fermentum diam nec bibendum.\n<br><br>\nNam in mauris nec est rutrum vestibulum. Fusce consequat pulvinar tincidunt. Proin at sem vel est euismod semper vel ac mi. Mauris posuere sapien a efficitur semper. Nulla facilisi. Duis condimentum hendrerit luctus. Pellentesque mattis hendrerit libero, id vestibulum metus rhoncus vel. Etiam ut ante nulla. Aliquam eget urna quam.\n<br><br>\nNunc ut eros sed dolor elementum lacinia non quis tortor. Nulla facilisi. Morbi blandit pharetra augue aliquet lobortis. Donec nisi lorem, lacinia quis tempus at, viverra nec nibh. Ut luctus placerat mi id commodo. Etiam lorem magna, varius eu ex eu, suscipit laoreet velit. Nullam posuere nunc vel lacinia porta. Praesent porttitor lectus luctus, fringilla ipsum ac, semper elit. Cras rutrum scelerisque arcu, eu venenatis lectus consectetur eu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae;\n<br><br>\n<h4>2- Random title</h4>\n\nAliquam non suscipit nisl. Donec posuere mollis dignissim. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent leo turpis, tempor at massa et, dapibus accumsan ex. Etiam sed consequat eros, ac ornare magna. Sed leo magna, facilisis vitae metus eget, mattis vestibulum felis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Proin mauris risus, sodales tempor neque sit amet, posuere volutpat mauris. Aliquam facilisis imperdiet quam, sed cursus quam pretium ut. Donec interdum libero vitae fermentum lobortis. Nullam tincidunt risus vitae nunc auctor porta. Nulla nec nulla bibendum, dapibus nisi vel, consequat leo. In congue accumsan nunc ut tincidunt. Nullam ut odio dapibus, tristique orci sed, maximus eros.\n<br><br>\nDonec tincidunt, ipsum eu consequat varius, justo tortor laoreet nunc, sed blandit felis ligula quis est. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In viverra dolor massa, id pellentesque tortor porttitor eu. Nunc venenatis non eros et lacinia. Suspendisse finibus nunc sed erat ullamcorper luctus. Sed ultricies placerat sollicitudin. Maecenas nisl ante, venenatis at nisi ut, varius consectetur tortor. Etiam vitae vehicula erat. Pellentesque orci tellus, interdum sed lorem a, varius aliquam justo. Etiam ac tincidunt quam, ac accumsan turpis. Maecenas laoreet sapien at commodo tincidunt. Cras at rutrum dolor. Sed blandit diam ac lacus commodo, a aliquam erat euismod. Nam ac metus ex. Aenean hendrerit interdum eros at condimentum.\n<br><br>\nUt et risus id est aliquet laoreet vel sit amet eros. Nullam dui nisl, bibendum et lobortis vitae, commodo eu eros. Vivamus ac magna eleifend, tempus justo a, lobortis justo. Mauris a tincidunt ante, ut pharetra erat. Donec ante metus, accumsan sed neque quis, pulvinar sagittis metus. Nullam quis convallis nisl, non tempus purus. Pellentesque non felis eget velit posuere finibus. Nullam arcu metus, tincidunt et gravida id, ultricies nec erat. Phasellus gravida velit vitae fringilla maximus. Donec vestibulum pulvinar mollis. Curabitur sit amet aliquam dui. Donec at eros pretium est ullamcorper laoreet. Nulla interdum accumsan justo, eget auctor ipsum posuere nec. Donec vitae elit a justo facilisis lobortis. Nam eu libero eu lacus varius luctus a sed velit. Praesent in augue augue.\n', 'blue', 1),
(2, 'privacy-policy', '<h4>1- Write the policy policy for your website.</h4>  \r\n\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam hendrerit sem quis urna commodo gravida ac eget diam. Sed tristique id quam auctor iaculis. Donec eget volutpat elit. Curabitur in enim eu nulla aliquet pellentesque nec quis neque. Maecenas ac nulla convallis, aliquam dui posuere, dignissim mi. Maecenas iaculis arcu vitae velit aliquet, vitae blandit ante mattis. Mauris varius sollicitudin purus, mattis blandit urna consequat condimentum. Curabitur vulputate sem finibus ipsum eleifend, et convallis felis egestas. Quisque condimentum faucibus maximus.\r\n<br><br>\r\nVestibulum sagittis, ex non condimentum lacinia, lorem libero ornare elit, quis placerat sapien nisi ac eros. Mauris in justo porta, auctor ante non, pulvinar enim. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sit amet pulvinar leo. Duis ut velit vitae felis bibendum feugiat. Donec urna orci, dapibus ut erat non, pellentesque cursus tellus. Nullam accumsan rhoncus felis, vel porta tortor suscipit pellentesque. Cras non ante ac orci elementum mattis vel et metus. Proin luctus consequat suscipit. Quisque pharetra lorem augue, nec aliquet nulla aliquam quis. Phasellus ac dictum dui. Etiam mollis ultrices dolor, nec suscipit ex ornare semper. Donec aliquet velit varius volutpat vehicula. Pellentesque interdum urna ac turpis gravida, quis rhoncus ligula ornare.\r\n<br><br>\r\nCurabitur vulputate tellus ac mauris pellentesque euismod. Nulla gravida erat nulla, non dictum ipsum faucibus in. In hac habitasse platea dictumst. Morbi libero nulla, laoreet sit amet sagittis et, semper ut ex. Integer eleifend ac justo porta semper. Suspendisse bibendum turpis et odio ullamcorper tincidunt. Sed at lectus est. Pellentesque semper eros arcu, et aliquet mi eleifend et. Donec quis ipsum condimentum, tristique massa a, bibendum turpis. Nunc volutpat tristique urna a hendrerit. In hac habitasse platea dictumst. Nam ligula sem, ultricies non condimentum at, tempus eget sapien. Nulla facilisi. Morbi sit amet sodales nulla, ut dapibus velit.\r\n<br><br>\r\nQuisque at leo vel lacus iaculis pharetra. Cras rhoncus eros sit amet nulla fermentum, eget tempor est imperdiet. Sed placerat nec felis eu maximus. Mauris ut purus gravida, rhoncus justo vel, iaculis purus. Nam vitae arcu nisl. Pellentesque efficitur est nulla, quis sollicitudin tellus venenatis eget. In pellentesque mi eu luctus porta. Vestibulum ultricies ex nisl, in scelerisque metus egestas vitae. Fusce suscipit orci quam, sed ornare lorem imperdiet sit amet. In non viverra sapien.\r\n<br><br>\r\nIn blandit turpis nec sapien dictum pulvinar. Donec venenatis non tellus eget venenatis. Donec lobortis ipsum sed nulla lacinia, eu pretium eros pretium. Sed volutpat ante et dolor vulputate tempus. Morbi erat felis, viverra vel elit eget, pretium efficitur tellus. Duis malesuada eget ipsum eget placerat. In aliquet felis nisi, ut luctus metus interdum ac. Sed nec egestas magna. Ut aliquet, ante vitae ullamcorper dignissim, ipsum leo fringilla ligula, sed accumsan odio ante ac risus. Vivamus dolor nunc, porta vel neque id, tristique fringilla ex. Vivamus sit amet sapien eu nulla tincidunt viverra nec nec lorem. Integer nibh dolor, varius sit amet nisl vitae, euismod gravida nulla. Nullam volutpat volutpat luctus. Praesent fermentum sapien semper, lacinia augue sed, viverra odio. Maecenas vel arcu volutpat magna gravida imperdiet. Proin eleifend tincidunt diam, vel elementum arcu.\r\n<br><br>\r\nNunc tempor vel tortor ac lacinia. Phasellus feugiat feugiat lorem, vel rhoncus dui aliquet ut. Nam ultricies placerat nibh vitae congue. Vestibulum fermentum gravida sem, eget rhoncus urna efficitur ac. Aenean eget ligula ipsum. Aenean vel fermentum mauris. Duis volutpat ligula diam, in sollicitudin lacus ullamcorper eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce elit lacus, posuere ac venenatis quis, sollicitudin vel eros. Mauris laoreet elit non sem tempus, non ornare mi luctus. In viverra porttitor nisi a viverra.\r\n<br><br>\r\nUt metus nunc, semper eu dui nec, laoreet sollicitudin ante. Fusce iaculis velit eget feugiat blandit. Curabitur eu mollis sem. Curabitur vehicula sapien in volutpat semper. Nullam fermentum, lectus et blandit scelerisque, dolor odio hendrerit ligula, eu iaculis ex velit ac urna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam lobortis porttitor odio, ac vestibulum urna elementum ac. Sed sem sem, blandit consectetur lorem id, convallis rhoncus diam. In mi tortor, malesuada et nulla eget, mattis varius ligula. Ut dignissim molestie lorem. In bibendum pharetra leo in mattis.\r\n<br><br>\r\nDuis et erat sit amet ex dictum accumsan at eu quam. Morbi eget tempor eros, eu tempus neque. Donec lacinia, ante id porta pellentesque, arcu augue dictum nibh, quis pretium est turpis sed eros. Cras et arcu id tellus venenatis luctus. Aenean id nisi ut magna scelerisque finibus. Ut non erat id magna euismod interdum. Proin urna mauris, suscipit quis velit condimentum, tincidunt convallis neque. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Curabitur tortor elit, aliquam ac tincidunt efficitur, elementum eget tortor. Maecenas ligula ipsum, rhoncus id accumsan vel, elementum nec sapien. Mauris neque dolor, vehicula quis magna eu, laoreet eleifend risus. Vivamus sed sem sapien. Aliquam erat volutpat. Integer tristique porttitor est. Donec maximus fermentum diam nec bibendum.\r\n<br><br>\r\nNam in mauris nec est rutrum vestibulum. Fusce consequat pulvinar tincidunt. Proin at sem vel est euismod semper vel ac mi. Mauris posuere sapien a efficitur semper. Nulla facilisi. Duis condimentum hendrerit luctus. Pellentesque mattis hendrerit libero, id vestibulum metus rhoncus vel. Etiam ut ante nulla. Aliquam eget urna quam.\r\n<br><br>\r\nNunc ut eros sed dolor elementum lacinia non quis tortor. Nulla facilisi. Morbi blandit pharetra augue aliquet lobortis. Donec nisi lorem, lacinia quis tempus at, viverra nec nibh. Ut luctus placerat mi id commodo. Etiam lorem magna, varius eu ex eu, suscipit laoreet velit. Nullam posuere nunc vel lacinia porta. Praesent porttitor lectus luctus, fringilla ipsum ac, semper elit. Cras rutrum scelerisque arcu, eu venenatis lectus consectetur eu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae;\r\n<br><br>\r\n<h4>2- Random title</h4>\r\n\r\nAliquam non suscipit nisl. Donec posuere mollis dignissim. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent leo turpis, tempor at massa et, dapibus accumsan ex. Etiam sed consequat eros, ac ornare magna. Sed leo magna, facilisis vitae metus eget, mattis vestibulum felis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Proin mauris risus, sodales tempor neque sit amet, posuere volutpat mauris. Aliquam facilisis imperdiet quam, sed cursus quam pretium ut. Donec interdum libero vitae fermentum lobortis. Nullam tincidunt risus vitae nunc auctor porta. Nulla nec nulla bibendum, dapibus nisi vel, consequat leo. In congue accumsan nunc ut tincidunt. Nullam ut odio dapibus, tristique orci sed, maximus eros.\r\n<br><br>\r\nDonec tincidunt, ipsum eu consequat varius, justo tortor laoreet nunc, sed blandit felis ligula quis est. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In viverra dolor massa, id pellentesque tortor porttitor eu. Nunc venenatis non eros et lacinia. Suspendisse finibus nunc sed erat ullamcorper luctus. Sed ultricies placerat sollicitudin. Maecenas nisl ante, venenatis at nisi ut, varius consectetur tortor. Etiam vitae vehicula erat. Pellentesque orci tellus, interdum sed lorem a, varius aliquam justo. Etiam ac tincidunt quam, ac accumsan turpis. Maecenas laoreet sapien at commodo tincidunt. Cras at rutrum dolor. Sed blandit diam ac lacus commodo, a aliquam erat euismod. Nam ac metus ex. Aenean hendrerit interdum eros at condimentum.\r\n<br><br>\r\nUt et risus id est aliquet laoreet vel sit amet eros. Nullam dui nisl, bibendum et lobortis vitae, commodo eu eros. Vivamus ac magna eleifend, tempus justo a, lobortis justo. Mauris a tincidunt ante, ut pharetra erat. Donec ante metus, accumsan sed neque quis, pulvinar sagittis metus. Nullam quis convallis nisl, non tempus purus. Pellentesque non felis eget velit posuere finibus. Nullam arcu metus, tincidunt et gravida id, ultricies nec erat. Phasellus gravida velit vitae fringilla maximus. Donec vestibulum pulvinar mollis. Curabitur sit amet aliquam dui. Donec at eros pretium est ullamcorper laoreet. Nulla interdum accumsan justo, eget auctor ipsum posuere nec. Donec vitae elit a justo facilisis lobortis. Nam eu libero eu lacus varius luctus a sed velit. Praesent in augue augue.\r\n', 'blue', 1),
(3, 'cookies-policy', '<h4>1- Write the cookies policy for your website.</h4>    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam hendrerit sem quis urna commodo gravida ac eget diam. Sed tristique id quam auctor iaculis. Donec eget volutpat elit. Curabitur in enim eu nulla aliquet pellentesque nec quis neque. Maecenas ac nulla convallis, aliquam dui posuere, dignissim mi. Maecenas iaculis arcu vitae velit aliquet, vitae blandit ante mattis. Mauris varius sollicitudin purus, mattis blandit urna consequat condimentum. Curabitur vulputate sem finibus ipsum eleifend, et convallis felis egestas. Quisque condimentum faucibus maximus. <br><br> Vestibulum sagittis, ex non condimentum lacinia, lorem libero ornare elit, quis placerat sapien nisi ac eros. Mauris in justo porta, auctor ante non, pulvinar enim. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sit amet pulvinar leo. Duis ut velit vitae felis bibendum feugiat. Donec urna orci, dapibus ut erat non, pellentesque cursus tellus. Nullam accumsan rhoncus felis, vel porta tortor suscipit pellentesque. Cras non ante ac orci elementum mattis vel et metus. Proin luctus consequat suscipit. Quisque pharetra lorem augue, nec aliquet nulla aliquam quis. Phasellus ac dictum dui. Etiam mollis ultrices dolor, nec suscipit ex ornare semper. Donec aliquet velit varius volutpat vehicula. Pellentesque interdum urna ac turpis gravida, quis rhoncus ligula ornare. <br><br> Curabitur vulputate tellus ac mauris pellentesque euismod. Nulla gravida erat nulla, non dictum ipsum faucibus in. In hac habitasse platea dictumst. Morbi libero nulla, laoreet sit amet sagittis et, semper ut ex. Integer eleifend ac justo porta semper. Suspendisse bibendum turpis et odio ullamcorper tincidunt. Sed at lectus est. Pellentesque semper eros arcu, et aliquet mi eleifend et. Donec quis ipsum condimentum, tristique massa a, bibendum turpis. Nunc volutpat tristique urna a hendrerit. In hac habitasse platea dictumst. Nam ligula sem, ultricies non condimentum at, tempus eget sapien. Nulla facilisi. Morbi sit amet sodales nulla, ut dapibus velit. <br><br> Quisque at leo vel lacus iaculis pharetra. Cras rhoncus eros sit amet nulla fermentum, eget tempor est imperdiet. Sed placerat nec felis eu maximus. Mauris ut purus gravida, rhoncus justo vel, iaculis purus. Nam vitae arcu nisl. Pellentesque efficitur est nulla, quis sollicitudin tellus venenatis eget. In pellentesque mi eu luctus porta. Vestibulum ultricies ex nisl, in scelerisque metus egestas vitae. Fusce suscipit orci quam, sed ornare lorem imperdiet sit amet. In non viverra sapien. <br><br> In blandit turpis nec sapien dictum pulvinar. Donec venenatis non tellus eget venenatis. Donec lobortis ipsum sed nulla lacinia, eu pretium eros pretium. Sed volutpat ante et dolor vulputate tempus. Morbi erat felis, viverra vel elit eget, pretium efficitur tellus. Duis malesuada eget ipsum eget placerat. In aliquet felis nisi, ut luctus metus interdum ac. Sed nec egestas magna. Ut aliquet, ante vitae ullamcorper dignissim, ipsum leo fringilla ligula, sed accumsan odio ante ac risus. Vivamus dolor nunc, porta vel neque id, tristique fringilla ex. Vivamus sit amet sapien eu nulla tincidunt viverra nec nec lorem. Integer nibh dolor, varius sit amet nisl vitae, euismod gravida nulla. Nullam volutpat volutpat luctus. Praesent fermentum sapien semper, lacinia augue sed, viverra odio. Maecenas vel arcu volutpat magna gravida imperdiet. Proin eleifend tincidunt diam, vel elementum arcu. <br><br> Nunc tempor vel tortor ac lacinia. Phasellus feugiat feugiat lorem, vel rhoncus dui aliquet ut. Nam ultricies placerat nibh vitae congue. Vestibulum fermentum gravida sem, eget rhoncus urna efficitur ac. Aenean eget ligula ipsum. Aenean vel fermentum mauris. Duis volutpat ligula diam, in sollicitudin lacus ullamcorper eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce elit lacus, posuere ac venenatis quis, sollicitudin vel eros. Mauris laoreet elit non sem tempus, non ornare mi luctus. In viverra porttitor nisi a viverra. <br><br> Ut metus nunc, semper eu dui nec, laoreet sollicitudin ante. Fusce iaculis velit eget feugiat blandit. Curabitur eu mollis sem. Curabitur vehicula sapien in volutpat semper. Nullam fermentum, lectus et blandit scelerisque, dolor odio hendrerit ligula, eu iaculis ex velit ac urna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam lobortis porttitor odio, ac vestibulum urna elementum ac. Sed sem sem, blandit consectetur lorem id, convallis rhoncus diam. In mi tortor, malesuada et nulla eget, mattis varius ligula. Ut dignissim molestie lorem. In bibendum pharetra leo in mattis. <br><br> Duis et erat sit amet ex dictum accumsan at eu quam. Morbi eget tempor eros, eu tempus neque. Donec lacinia, ante id porta pellentesque, arcu augue dictum nibh, quis pretium est turpis sed eros. Cras et arcu id tellus venenatis luctus. Aenean id nisi ut magna scelerisque finibus. Ut non erat id magna euismod interdum. Proin urna mauris, suscipit quis velit condimentum, tincidunt convallis neque. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Curabitur tortor elit, aliquam ac tincidunt efficitur, elementum eget tortor. Maecenas ligula ipsum, rhoncus id accumsan vel, elementum nec sapien. Mauris neque dolor, vehicula quis magna eu, laoreet eleifend risus. Vivamus sed sem sapien. Aliquam erat volutpat. Integer tristique porttitor est. Donec maximus fermentum diam nec bibendum. <br><br> Nam in mauris nec est rutrum vestibulum. Fusce consequat pulvinar tincidunt. Proin at sem vel est euismod semper vel ac mi. Mauris posuere sapien a efficitur semper. Nulla facilisi. Duis condimentum hendrerit luctus. Pellentesque mattis hendrerit libero, id vestibulum metus rhoncus vel. Etiam ut ante nulla. Aliquam eget urna quam. <br><br> Nunc ut eros sed dolor elementum lacinia non quis tortor. Nulla facilisi. Morbi blandit pharetra augue aliquet lobortis. Donec nisi lorem, lacinia quis tempus at, viverra nec nibh. Ut luctus placerat mi id commodo. Etiam lorem magna, varius eu ex eu, suscipit laoreet velit. Nullam posuere nunc vel lacinia porta. Praesent porttitor lectus luctus, fringilla ipsum ac, semper elit. Cras rutrum scelerisque arcu, eu venenatis lectus consectetur eu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; <br><br> <h4>2- Random title</h4>  Aliquam non suscipit nisl. Donec posuere mollis dignissim. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent leo turpis, tempor at massa et, dapibus accumsan ex. Etiam sed consequat eros, ac ornare magna. Sed leo magna, facilisis vitae metus eget, mattis vestibulum felis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Proin mauris risus, sodales tempor neque sit amet, posuere volutpat mauris. Aliquam facilisis imperdiet quam, sed cursus quam pretium ut. Donec interdum libero vitae fermentum lobortis. Nullam tincidunt risus vitae nunc auctor porta. Nulla nec nulla bibendum, dapibus nisi vel, consequat leo. In congue accumsan nunc ut tincidunt. Nullam ut odio dapibus, tristique orci sed, maximus eros. <br><br> Donec tincidunt, ipsum eu consequat varius, justo tortor laoreet nunc, sed blandit felis ligula quis est. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In viverra dolor massa, id pellentesque tortor porttitor eu. Nunc venenatis non eros et lacinia. Suspendisse finibus nunc sed erat ullamcorper luctus. Sed ultricies placerat sollicitudin. Maecenas nisl ante, venenatis at nisi ut, varius consectetur tortor. Etiam vitae vehicula erat. Pellentesque orci tellus, interdum sed lorem a, varius aliquam justo. Etiam ac tincidunt quam, ac accumsan turpis. Maecenas laoreet sapien at commodo tincidunt. Cras at rutrum dolor. Sed blandit diam ac lacus commodo, a aliquam erat euismod. Nam ac metus ex. Aenean hendrerit interdum eros at condimentum. <br><br> Ut et risus id est aliquet laoreet vel sit amet eros. Nullam dui nisl, bibendum et lobortis vitae, commodo eu eros. Vivamus ac magna eleifend, tempus justo a, lobortis justo. Mauris a tincidunt ante, ut pharetra erat. Donec ante metus, accumsan sed neque quis, pulvinar sagittis metus. Nullam quis convallis nisl, non tempus purus. Pellentesque non felis eget velit posuere finibus. Nullam arcu metus, tincidunt et gravida id, ultricies nec erat. Phasellus gravida velit vitae fringilla maximus. Donec vestibulum pulvinar mollis. Curabitur sit amet aliquam dui. Donec at eros pretium est ullamcorper laoreet. Nulla interdum accumsan justo, eget auctor ipsum posuere nec. Donec vitae elit a justo facilisis lobortis. Nam eu libero eu lacus varius luctus a sed velit. Praesent in augue augue.', 'red', 1),
(4, 'about-us', '<h4>1- Write the about us for your website.</h4>    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam hendrerit sem quis urna commodo gravida ac eget diam. Sed tristique id quam auctor iaculis. Donec eget volutpat elit. Curabitur in enim eu nulla aliquet pellentesque nec quis neque. Maecenas ac nulla convallis, aliquam dui posuere, dignissim mi. Maecenas iaculis arcu vitae velit aliquet, vitae blandit ante mattis. Mauris varius sollicitudin purus, mattis blandit urna consequat condimentum. Curabitur vulputate sem finibus ipsum eleifend, et convallis felis egestas. Quisque condimentum faucibus maximus. <br><br> Vestibulum sagittis, ex non condimentum lacinia, lorem libero ornare elit, quis placerat sapien nisi ac eros. Mauris in justo porta, auctor ante non, pulvinar enim. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur sit amet pulvinar leo. Duis ut velit vitae felis bibendum feugiat. Donec urna orci, dapibus ut erat non, pellentesque cursus tellus. Nullam accumsan rhoncus felis, vel porta tortor suscipit pellentesque. Cras non ante ac orci elementum mattis vel et metus. Proin luctus consequat suscipit. Quisque pharetra lorem augue, nec aliquet nulla aliquam quis. Phasellus ac dictum dui. Etiam mollis ultrices dolor, nec suscipit ex ornare semper. Donec aliquet velit varius volutpat vehicula. Pellentesque interdum urna ac turpis gravida, quis rhoncus ligula ornare. <br><br> Curabitur vulputate tellus ac mauris pellentesque euismod. Nulla gravida erat nulla, non dictum ipsum faucibus in. In hac habitasse platea dictumst. Morbi libero nulla, laoreet sit amet sagittis et, semper ut ex. Integer eleifend ac justo porta semper. Suspendisse bibendum turpis et odio ullamcorper tincidunt. Sed at lectus est. Pellentesque semper eros arcu, et aliquet mi eleifend et. Donec quis ipsum condimentum, tristique massa a, bibendum turpis. Nunc volutpat tristique urna a hendrerit. In hac habitasse platea dictumst. Nam ligula sem, ultricies non condimentum at, tempus eget sapien. Nulla facilisi. Morbi sit amet sodales nulla, ut dapibus velit. <br><br> Quisque at leo vel lacus iaculis pharetra. Cras rhoncus eros sit amet nulla fermentum, eget tempor est imperdiet. Sed placerat nec felis eu maximus. Mauris ut purus gravida, rhoncus justo vel, iaculis purus. Nam vitae arcu nisl. Pellentesque efficitur est nulla, quis sollicitudin tellus venenatis eget. In pellentesque mi eu luctus porta. Vestibulum ultricies ex nisl, in scelerisque metus egestas vitae. Fusce suscipit orci quam, sed ornare lorem imperdiet sit amet. In non viverra sapien. <br><br> In blandit turpis nec sapien dictum pulvinar. Donec venenatis non tellus eget venenatis. Donec lobortis ipsum sed nulla lacinia, eu pretium eros pretium. Sed volutpat ante et dolor vulputate tempus. Morbi erat felis, viverra vel elit eget, pretium efficitur tellus. Duis malesuada eget ipsum eget placerat. In aliquet felis nisi, ut luctus metus interdum ac. Sed nec egestas magna. Ut aliquet, ante vitae ullamcorper dignissim, ipsum leo fringilla ligula, sed accumsan odio ante ac risus. Vivamus dolor nunc, porta vel neque id, tristique fringilla ex. Vivamus sit amet sapien eu nulla tincidunt viverra nec nec lorem. Integer nibh dolor, varius sit amet nisl vitae, euismod gravida nulla. Nullam volutpat volutpat luctus. Praesent fermentum sapien semper, lacinia augue sed, viverra odio. Maecenas vel arcu volutpat magna gravida imperdiet. Proin eleifend tincidunt diam, vel elementum arcu. <br><br> Nunc tempor vel tortor ac lacinia. Phasellus feugiat feugiat lorem, vel rhoncus dui aliquet ut. Nam ultricies placerat nibh vitae congue. Vestibulum fermentum gravida sem, eget rhoncus urna efficitur ac. Aenean eget ligula ipsum. Aenean vel fermentum mauris. Duis volutpat ligula diam, in sollicitudin lacus ullamcorper eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce elit lacus, posuere ac venenatis quis, sollicitudin vel eros. Mauris laoreet elit non sem tempus, non ornare mi luctus. In viverra porttitor nisi a viverra. <br><br> Ut metus nunc, semper eu dui nec, laoreet sollicitudin ante. Fusce iaculis velit eget feugiat blandit. Curabitur eu mollis sem. Curabitur vehicula sapien in volutpat semper. Nullam fermentum, lectus et blandit scelerisque, dolor odio hendrerit ligula, eu iaculis ex velit ac urna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nam lobortis porttitor odio, ac vestibulum urna elementum ac. Sed sem sem, blandit consectetur lorem id, convallis rhoncus diam. In mi tortor, malesuada et nulla eget, mattis varius ligula. Ut dignissim molestie lorem. In bibendum pharetra leo in mattis. <br><br> Duis et erat sit amet ex dictum accumsan at eu quam. Morbi eget tempor eros, eu tempus neque. Donec lacinia, ante id porta pellentesque, arcu augue dictum nibh, quis pretium est turpis sed eros. Cras et arcu id tellus venenatis luctus. Aenean id nisi ut magna scelerisque finibus. Ut non erat id magna euismod interdum. Proin urna mauris, suscipit quis velit condimentum, tincidunt convallis neque. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Curabitur tortor elit, aliquam ac tincidunt efficitur, elementum eget tortor. Maecenas ligula ipsum, rhoncus id accumsan vel, elementum nec sapien. Mauris neque dolor, vehicula quis magna eu, laoreet eleifend risus. Vivamus sed sem sapien. Aliquam erat volutpat. Integer tristique porttitor est. Donec maximus fermentum diam nec bibendum. <br><br> Nam in mauris nec est rutrum vestibulum. Fusce consequat pulvinar tincidunt. Proin at sem vel est euismod semper vel ac mi. Mauris posuere sapien a efficitur semper. Nulla facilisi. Duis condimentum hendrerit luctus. Pellentesque mattis hendrerit libero, id vestibulum metus rhoncus vel. Etiam ut ante nulla. Aliquam eget urna quam. <br><br> Nunc ut eros sed dolor elementum lacinia non quis tortor. Nulla facilisi. Morbi blandit pharetra augue aliquet lobortis. Donec nisi lorem, lacinia quis tempus at, viverra nec nibh. Ut luctus placerat mi id commodo. Etiam lorem magna, varius eu ex eu, suscipit laoreet velit. Nullam posuere nunc vel lacinia porta. Praesent porttitor lectus luctus, fringilla ipsum ac, semper elit. Cras rutrum scelerisque arcu, eu venenatis lectus consectetur eu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; <br><br> <h4>2- Random title</h4>  Aliquam non suscipit nisl. Donec posuere mollis dignissim. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Praesent leo turpis, tempor at massa et, dapibus accumsan ex. Etiam sed consequat eros, ac ornare magna. Sed leo magna, facilisis vitae metus eget, mattis vestibulum felis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Proin mauris risus, sodales tempor neque sit amet, posuere volutpat mauris. Aliquam facilisis imperdiet quam, sed cursus quam pretium ut. Donec interdum libero vitae fermentum lobortis. Nullam tincidunt risus vitae nunc auctor porta. Nulla nec nulla bibendum, dapibus nisi vel, consequat leo. In congue accumsan nunc ut tincidunt. Nullam ut odio dapibus, tristique orci sed, maximus eros. <br><br> Donec tincidunt, ipsum eu consequat varius, justo tortor laoreet nunc, sed blandit felis ligula quis est. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. In viverra dolor massa, id pellentesque tortor porttitor eu. Nunc venenatis non eros et lacinia. Suspendisse finibus nunc sed erat ullamcorper luctus. Sed ultricies placerat sollicitudin. Maecenas nisl ante, venenatis at nisi ut, varius consectetur tortor. Etiam vitae vehicula erat. Pellentesque orci tellus, interdum sed lorem a, varius aliquam justo. Etiam ac tincidunt quam, ac accumsan turpis. Maecenas laoreet sapien at commodo tincidunt. Cras at rutrum dolor. Sed blandit diam ac lacus commodo, a aliquam erat euismod. Nam ac metus ex. Aenean hendrerit interdum eros at condimentum. <br><br> Ut et risus id est aliquet laoreet vel sit amet eros. Nullam dui nisl, bibendum et lobortis vitae, commodo eu eros. Vivamus ac magna eleifend, tempus justo a, lobortis justo. Mauris a tincidunt ante, ut pharetra erat. Donec ante metus, accumsan sed neque quis, pulvinar sagittis metus. Nullam quis convallis nisl, non tempus purus. Pellentesque non felis eget velit posuere finibus. Nullam arcu metus, tincidunt et gravida id, ultricies nec erat. Phasellus gravida velit vitae fringilla maximus. Donec vestibulum pulvinar mollis. Curabitur sit amet aliquam dui. Donec at eros pretium est ullamcorper laoreet. Nulla interdum accumsan justo, eget auctor ipsum posuere nec. Donec vitae elit a justo facilisis lobortis. Nam eu libero eu lacus varius luctus a sed velit. Praesent in augue augue.', 'red', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parameter`
--

CREATE TABLE `parameter` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `teacher_id` int(11) NOT NULL DEFAULT 0,
  `parameters` text NOT NULL,
  `modified` int(11) NOT NULL DEFAULT 0,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `parameter`
--

INSERT INTO `parameter` (`id`, `user_id`, `teacher_id`, `parameters`, `modified`, `time`) VALUES
(1, 15, 1, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643249358),
(2, 15, 18, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643249477),
(3, 16, 2, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643249760),
(4, 7, 3, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643313559),
(5, 7, 10, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643313581),
(6, 6, 4, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643250217),
(7, 12, 5, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643250251),
(8, 12, 12, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643250282),
(9, 9, 6, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643250330),
(10, 17, 7, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643313743),
(11, 13, 8, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643250737),
(12, 18, 9, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643250789),
(13, 18, 17, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643250897),
(14, 18, 22, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643250978),
(15, 19, 11, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643251095),
(16, 11, 13, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643251146),
(17, 11, 14, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643251209),
(18, 11, 21, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643251304),
(19, 11, 26, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643251391),
(20, 11, 32, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643251491),
(21, 20, 59, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643251735),
(22, 20, 20, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643251797),
(23, 21, 16, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643251875),
(24, 22, 19, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643251968),
(25, 23, 23, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643252037),
(26, 23, 30, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643252127),
(27, 28, 31, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643311116),
(28, 28, 33, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643311132),
(29, 29, 34, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643311301),
(30, 29, 51, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643311438),
(31, 29, 50, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643311537),
(32, 30, 35, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643311655),
(33, 31, 36, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643311695),
(34, 31, 42, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643311774),
(35, 32, 37, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643311913),
(36, 33, 38, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643311998),
(37, 33, 39, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643312047),
(38, 33, 53, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643312136),
(39, 34, 40, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643312252),
(40, 34, 48, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643312419),
(41, 34, 43, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643312430),
(42, 34, 47, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643312471),
(43, 39, 49, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643312547),
(44, 40, 52, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643312698),
(45, 41, 54, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643312774),
(46, 42, 55, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643312834),
(47, 43, 56, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643312904),
(48, 44, 57, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643312964),
(49, 45, 58, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643313055),
(50, 24, 24, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643313324),
(51, 27, 28, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643314434),
(52, 24, 29, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643314506),
(53, 35, 41, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643315084),
(54, 36, 44, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643315207),
(55, 37, 45, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643315268),
(56, 38, 46, '[[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}],[{\"name\":\"Nota global\",\"percent\":\"100\"}]]', 0, 1643315325),
(57, 7, 62, '[[{\"name\":\"Global\",\"percent\":\"100\"}],[{\"name\":\"Global\",\"percent\":\"100\"}],[{\"name\":\"Global\",\"percent\":\"100\"}]]', 0, 1644174199);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `period`
--

CREATE TABLE `period` (
  `id` int(11) NOT NULL,
  `name` varchar(7) NOT NULL,
  `dates` text DEFAULT NULL,
  `start` int(11) NOT NULL DEFAULT 0,
  `final` int(11) NOT NULL DEFAULT 0,
  `status` set('enabled','disabled') NOT NULL DEFAULT 'disabled',
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `period`
--

INSERT INTO `period` (`id`, `name`, `dates`, `start`, `final`, `status`, `time`) VALUES
(1, '2017-1', NULL, 1487808000, 1501372800, 'disabled', 1635858927),
(2, '2017-2', NULL, 1503014400, 1513728000, 'disabled', 1635858927),
(3, '2018-1', NULL, 1518825600, 1532304000, 'disabled', 1635858927),
(8, '2021-2', '[\"2021-08-30\",\"2021-01-06\",\"2021-01-21\",\"2021-01-05\",\"2021-01-14\",\"2021-08-30\",\"2021-08-30\",\"2021-08-30\",\"2021-07-01\",\"2021-08-30\",\"2021-12-06\",\"2021-12-09\",\"2021-12-11\",\"2021-12-13\",\"2021-06-18\",\"2021-06-25\",\"2021-07-24\",\"2021-12-18\"]', 1629331200, 1639958400, 'disabled', 1637377875),
(10, '2018-2', NULL, 1535587200, 1545350400, 'disabled', 1643162160),
(11, '2019-1', NULL, 1550102400, 1563753600, 'disabled', 1643162196),
(12, '2019-2', NULL, 1567123200, 1577232000, 'disabled', 1643162223),
(13, '2020-1', NULL, 1581465600, 1594857600, 'disabled', 1643162265),
(14, '2020-2', NULL, 1597795200, 1608076800, 'disabled', 1643162286),
(15, '2021-1', NULL, 1612915200, 1626480000, 'disabled', 1643162311),
(23, '2022-1', '[\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"2022-02-01\",\"2022-02-03\",\"2022-02-28\",\"2022-03-01\",\"\",\"\",\"\",\"\"]', 1643673600, 1658966400, 'enabled', 1644173936);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `plan`
--

CREATE TABLE `plan` (
  `id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(32) NOT NULL,
  `resolution` int(11) NOT NULL,
  `date_approved` int(11) NOT NULL,
  `duration` int(11) NOT NULL,
  `credits` int(11) NOT NULL,
  `note_mode` set('100','30-30-40') NOT NULL DEFAULT '30-30-40',
  `status` set('enabled','disabled') NOT NULL DEFAULT 'disabled',
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `plan`
--

INSERT INTO `plan` (`id`, `program_id`, `name`, `resolution`, `date_approved`, `duration`, `credits`, `note_mode`, `status`, `time`) VALUES
(1, 1, 'Ingeniería Informática', 8869, 1434672000, 7, 162, '30-30-40', 'enabled', 1635944589),
(2, 2, 'Contaduría Pública', 213123, 1635897600, 10, 200, '100', 'enabled', 1636847453),
(5, 3, 'Tecnologo en decoración de inter', 19111, 1515542400, 7, 102, '30-30-40', 'enabled', 1642956883);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `program`
--

CREATE TABLE `program` (
  `id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(52) NOT NULL,
  `title` varchar(52) NOT NULL,
  `snies` int(11) NOT NULL,
  `level` set('pre','tec','tecn') NOT NULL DEFAULT 'pre',
  `semesters` tinyint(4) NOT NULL DEFAULT 9,
  `mode` set('presential','distance','virtual') NOT NULL DEFAULT 'presential',
  `status` set('activated','deactivated') NOT NULL DEFAULT 'activated',
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `program`
--

INSERT INTO `program` (`id`, `faculty_id`, `name`, `title`, `snies`, `level`, `semesters`, `mode`, `status`, `time`) VALUES
(1, 1, 'Ingeniería Informática', 'Ingeniero Informático', 102883, 'pre', 9, 'distance', 'activated', 1635858629),
(2, 2, 'Contaduría pública', 'Contador público', 102322, 'pre', 9, 'distance', 'activated', 1636046655),
(3, 1, 'Tecnologo en decoración de inter', 'Tecnologia en decoración de interiores', 110106, 'tecn', 6, 'presential', 'activated', 1636047013);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `qualification`
--

CREATE TABLE `qualification` (
  `id` int(11) NOT NULL,
  `note_id` int(11) NOT NULL DEFAULT 0,
  `note` varchar(3) DEFAULT NULL,
  `status` set('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `qualification`
--

INSERT INTO `qualification` (`id`, `note_id`, `note`, `status`, `time`) VALUES
(7, 61, NULL, 'pending', 1644363282);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rule`
--

CREATE TABLE `rule` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `rules` text NOT NULL,
  `file` varchar(128) CHARACTER SET latin1 DEFAULT NULL,
  `status` set('enabled','disabled') NOT NULL DEFAULT 'disabled',
  `modified` int(11) NOT NULL DEFAULT 0,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `rule`
--

INSERT INTO `rule` (`id`, `user_id`, `rules`, `file`, `status`, `modified`, `time`) VALUES
(1, 4, '&lt;b&gt;ARTÍCULO 59º.&lt;/b&gt; Las calificaciones en la Corporación Universitaria, se presentarán             <br>en términos numéricos de cero (0) a cinco (5.0) en unidades y décimas, las             <br>centésimas que resultaren se aproximarán así: de una a cuatro centésimas se             <br>desecharán, de cinco a nueve centésimas se aproximarán a la décima             <br>inmediatamente superior.             <br>             <br>&lt;b&gt;ARTÍCULO 60º.&lt;/b&gt; Las evaluaciones y pruebas en la Corporación Universitaria, se             <br>aprobarán con calificación de tres (3.0) para asignaturas teóricas y de tres cinco             <br>(3.5) para las teórico-practicas y las prácticas, con excepción de lo establecido en             <br>los ARTÍCULO 48 de este Reglamento.             <br>             <br>&lt;b&gt;PARÁGRAFO 1:&lt;/b&gt; La Decanatura respectiva reglamentará las asignaturas que en             <br>cada programa se consideren teóricas, teórico-prácticas y prácticas.             <br>&lt;b&gt;PARÁGRAFO 2:&lt;/b&gt; La asignatura cuya metodología es el seminario, tendrá un             <br>solo reporte al final del periodo académico. Este será el resultado de las             <br>calificaciones parciales, las cuales deberán ser informadas oportunamente al             <br>estudiante.             <br>             <br>&lt;b&gt;ARTÍCULO 61º.&lt;/b&gt; Los docentes y/o tutores deberán pasar a la Oficina de Registro y             <br>Control Académico las calificaciones previas, las calificaciones de las             <br>evaluaciones finales, las calificaciones definitivas y las de cualquier otra             <br>evaluación, obtenidas por los alumnos en sus asignaturas, dentro de los cinco (5)             <br>días siguientes a la realización de la evaluación.             <br>             <br>&lt;b&gt;&lt;b&gt;ARTÍCULO 62º.&lt;/b&gt;&lt;/b&gt; Copia de las calificaciones previas y finales y de las evaluaciones             <br>que se practiquen en la Corporación Universitaria será fijada en el computador             <br>instalado para tal fin.             <br>&lt;b&gt;PARAGRAFO:&lt;/b&gt; Una vez publicada la nota sólo se puede corregir por error             <br>aritmético, omisión del reporte de la calificación y omisión o error en el nombre o             <br>código del estudiante.             <br>             <br>&lt;b&gt;ARTÍCULO 63º.&lt;/b&gt; El estudiante que obtenga un promedio aritmético inferior a tres             <br>dos (3.2) en las asignaturas del semestre será sometido a prueba académica,             <br>debiendo obtener en el semestre siguiente un promedio superior a dicha nota. En             <br>caso de no obtener dicho promedio será suspendido por un semestre.', NULL, 'disabled', 0, 1637436380),
(2, 4, '===&lt;b&gt;ARTÍCULO {&gt;&gt;42&lt;&lt;}º.&lt;/b&gt; Para tener derecho a presentar evaluación final el estudiante                                                                       <br>deberá tener una calificación promedio no inferior a dos ({#NNEVF-&gt;2.0}).===                               <br>                        <br>===&lt;b&gt;ARTÍCULO {&gt;&gt;43&lt;&lt;}º.&lt;/b&gt; La evaluación final tendrá un valor del 40% de la calificación                                                                <br>definitiva y versará sobre la totalidad del programa de la materia. Las evaluaciones                                                                <br>finales podrán ser orales, escritos o la ejecución de trabajos de investigación,                                                                <br>según la naturaleza de la asignatura o el criterio del profesor respectivo.                                                                <br>                                                                <br>PARÁGRAFO 1: La Nota mínima de la evaluación final será de uno cinco ({#NMTC-&gt;1.5}).                                                                <br>Cuando la calificación sea inferior, esa será la nota definitiva.                                                                <br>PARÁGRAFO 2: Para poder presentar la evaluación final, el estudiante deberá                                                                <br>encontrarse a Paz y Salvo con todas las dependencias de la Corporación                                                              <br>Universitaria.===                                 <br>                      <br>===&lt;b&gt;ARTÍCULO {&gt;&gt;48&lt;&lt;}º.&lt;/b&gt; El estudiante que al finalizar el semestre lectivo hubiere perdido                                                              <br>una o dos ({#NMHEAP-&gt;2}) asignaturas habilitables con calificación igual o superior a dos ({#NMDPH-&gt;2.0})                                                              <br>podrá presentar evaluación de habilitación en las fechas señaladas por el Concejo                                                              <br>Académico.===                                   <br>                       <br>===&lt;b&gt;ARTÍCULO {&gt;&gt;50&lt;&lt;}º.&lt;/b&gt; La materia habilitada se aprobará con una calificación no inferior a                                                               <br>tres dos ({#NMAT-&gt;3.2}) si es teórica, y no inferior a tres cinco ({#NMANT-&gt;3.5}) si es teórico- práctica; en                                                               <br>ningún caso habrá rehabilitación.===                           <br>                           <br>===&lt;b&gt;ARTÍCULO {&gt;&gt;59&lt;&lt;}º.&lt;/b&gt; Las calificaciones en la Corporación Universitaria, se presentarán                                                <br>en términos numéricos de cero (0) a cinco ({#NM-&gt;5.0}) en unidades y décimas, las                                                <br>centésimas que resultaren se aproximarán así: de una a cuatro centésimas se                                                <br>desecharán, de cinco a nueve centésimas se aproximarán a la décima                                                <br>inmediatamente superior.===                               <br>                           <br>===&lt;b&gt;ARTÍCULO {&gt;&gt;60&lt;&lt;}º.&lt;/b&gt; Las evaluaciones y pruebas en la Corporación Universitaria, se                                      <br>aprobarán con calificación de tres ({#NMCT-&gt;3.0}) para asignaturas teóricas y de tres cinco                                      <br>({#NMCNT-&gt;3.5}) para las teórico-practicas y las prácticas, con excepción de lo establecido en                                      <br>los ARTÍCULO 48 de este Reglamento.===                                 <br>                           <br>===&lt;b&gt;ARTÍCULO {&gt;&gt;64&lt;&lt;}º.&lt;/b&gt; El estudiante que perdiere cuatro ({#CERS-&gt;4}) o más asignaturas deberá                                                                <br>repetirlas en el semestre siguiente.===                           <br>                             <br>&lt;div class=&quot;color-red&quot;&gt;Hasta aqui termine&lt;/div&gt;                          <br>________________________________________________________________________________________________                           <br>                                                                                                   <br>                                                       <br>                         <br>&lt;div class=&quot;color-red&quot;&gt;Las que vienen son VALIDACIONES&lt;/div&gt;                          <br>________________________________________________________________________________________________                          <br>                         <br>===&lt;b&gt;ARTÍCULO {&gt;&gt;53&lt;&lt;}º.&lt;/b&gt; Las validaciones por pérdida son aquéllas que se originan                                                                 <br>cuando un estudiante pierde la asignatura. La evaluación será optativa y versará sobre el                                                                <br>programa cursado. La autorización de estas pruebas es competencia de la                                                                <br>Decanatura correspondiente. El estudiante tiene derecho a validar por pérdida una                                                                <br>sola vez ({#TMVEA-&gt;1}) por asignatura y un total de tres ({#NVP-&gt;3}) asignaturas en toda la carrera.===                                                                <br>                                                                <br>===&lt;b&gt;ARTÍCULO {&gt;&gt;54&lt;&lt;}º.&lt;/b&gt; Las validaciones por suficiencia son aquéllas que el estudiante                                                                <br>presenta para acreditar la idoneidad en una asignatura. Esta prueba será                                                                <br>autorizada por el Decano correspondiente al estudiante para que acredite los                                                                <br>conocimientos que correspondan a una determinada asignatura no cursada. Se                                                                <br>autoriza una sola vez por asignatura y con un máximo de dos ({#NVS-&gt;2}) asignaturas en                                                                <br>el curso de la carrera.===                                                                <br>                                                                <br>===&lt;b&gt;ARTÍCULO {&gt;&gt;56&lt;&lt;}º.&lt;/b&gt; La nota aprobatoria de la evaluación de validación es de tres                                                                <br>cinco ({#NMV-&gt;3.5}).===       ', 'uploads/rules/2/aa601f487942b52512c028cb55d74f3d.pdf', 'enabled', 0, 1637455909);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `session`
--

CREATE TABLE `session` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_id` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `session`
--

INSERT INTO `session` (`id`, `user_id`, `session_id`, `details`, `time`) VALUES
(1, 1, '6542e107985cf49f61413c0ae7f5a9d8ac5e4953206795e9298a3a058340df53bb8c46d9', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.61 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.61\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1633351667),
(2, 1, '2967b67fff09fe95cc921a0261945230239ebfbc0a270aec6acf8e518a2f8bd42d329088', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.61 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.61\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1633353565),
(3, 1, 'd8b08ec5b40b3c2d62aab073c6adab323b851811f6955c17225be78acec84b6f7c7f19b2', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.61 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.61\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1633358543),
(5, 1, '6542e107985cf49f61413c0ae7f5a9d8ac5e4953206795e9298a3a058340df53bb8c46d9', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.61 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.61\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1633351667),
(6, 1, '2967b67fff09fe95cc921a0261945230239ebfbc0a270aec6acf8e518a2f8bd42d329088', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.61 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.61\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1633353565),
(7, 1, 'd8b08ec5b40b3c2d62aab073c6adab323b851811f6955c17225be78acec84b6f7c7f19b2', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.61 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.61\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1633358543),
(8, 1, '6542e107985cf49f61413c0ae7f5a9d8ac5e4953206795e9298a3a058340df53bb8c46d9', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.61 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.61\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1633351667),
(9, 1, '2967b67fff09fe95cc921a0261945230239ebfbc0a270aec6acf8e518a2f8bd42d329088', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.61 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.61\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1633353565),
(10, 1, 'd8b08ec5b40b3c2d62aab073c6adab323b851811f6955c17225be78acec84b6f7c7f19b2', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.61 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.61\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1633358543),
(12, 1, '6542e107985cf49f61413c0ae7f5a9d8ac5e4953206795e9298a3a058340df53bb8c46d9', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.61 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.61\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1633351667),
(13, 1, '2967b67fff09fe95cc921a0261945230239ebfbc0a270aec6acf8e518a2f8bd42d329088', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.61 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.61\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1633353565),
(14, 1, 'd8b08ec5b40b3c2d62aab073c6adab323b851811f6955c17225be78acec84b6f7c7f19b2', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.61 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.61\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1633358543),
(16, 1, '8b683b0009b2c6bdc162a8aa6f6ce13a33f393637fa6b82b00885cc4639798badc696203', NULL, 1633434445),
(42, 1, '070d061d92d5b94c44ade0f0c7fc17d874905d5e5c66c1bf22b1c324a127c4f36fbcb00c', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.81 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.81\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1635189377),
(45, 1, '48e0d2a948a74acade72a5e6e8d4e3d1c14453bd7e609d76c2f93871fb0e360da855f439', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.54 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"95.0.4638.54\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1635215922),
(46, 1, '66679a2d839361e6b11b51541983d5c419e5c5fea97d0ff4b2274f9cf4dad0d893ebb535', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1635856839),
(48, 1, 'd9f94da8c39b052f6bfba7db0a2b155b84e03c9a9721e748d5de4cac0ebff438bd170ff9', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1635960646),
(49, 3, '079168ab49735a2d596fa1cbfbefa335ca394c2f9a4984311f8d82fdf2456402a77d2d21', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1635968557),
(53, 1, 'a9c831aec9c1b3331ecd1007f087c0f41e1d9849b0b4ebd53b83b7ee2d016c8fdd3f0fd8', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1636206769),
(54, 3, '0c16433dddc64d4899cf4cb69211419ecbb9d2ca9aa733d21ded3ca85e466716c3eda1be', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1636209433),
(62, 4, 'a3a440e5d8c7f5323fb7e3ea8efcce529c28dfcc755580d253ffd0f986f2b2dffac41bfd', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1636250456),
(66, 1, '321c77dc9ab23b75a7de055e8b3cdf4bfbe605b27452ca8e396f9c1fc8f08b2919f02e07', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1636327928),
(80, 1, '3a0d4d41574e9d8a2a3de6164eac72d90e1df6825930a5c795081060155f9d6e78bb2f23', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1636391683),
(81, 1, 'afe10180334a52fce1a435faee6ecdaf976344e49d9953d8e1da9e7a6855aa5a5021788b', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1636407608),
(82, 1, 'ff7b79c03229d05a251c68196650eacbae8718dbea1175aaa3b25af2f4ff126b21eaef30', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1636479370),
(83, 3, 'e9727c0915fdced265fc41ed35aeca6ca0ce47daf23b33dd5d00aaaccad0993922583c0a', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1636494652),
(102, 8, '6061625b55a57b9f1f06a937dc7c05d409d690e1d76bb8e4335ccd6ecf0df7adb6f26aff', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1636508856),
(112, 1, '879a02cb7fcbf528104ffabb3ad06ae65f5288c4485b63e6f0d21085cd4c02c005d5ffc1', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1636513111),
(130, 6, 'e69c40021d40aa841bb457838c4934bb42a1f6ab8c60a9912e3b378d686cf5112e28bdd6', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1636679846),
(133, 3, '957d15c324e33a6acbf83065a69000b233f45e87bc21f483809b84327e227a2974d4db92', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1636815508),
(144, 1, '8e9de208f4e05daec8849fa8b89ad59ddb3205905693a8c3222bf3fc09af4e09b7e216a7', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1636922716),
(146, 5, '50d94413c188c3288a1c88b3daff9d1cee62f988ee5c0875884697f72abc0f1ea95f2f8a', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1636927786),
(154, 1, '0ef0edb67c183f9c8aa54a73ad3c037c2e3a561b80ed317cf9ce0180be2bbc16a94ead67', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1636938516),
(164, 3, 'a970b20cb84e81dca62bcdf376c160188a1f0f8d510b885a23c5ca58d734f51d5390cab8', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1636978577),
(166, 1, '7c85935ca0359179a2a4076ac41ac411c54dfe37d6fa890dc1e32805fc5018ed1345689f', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1636979005),
(167, 3, 'ea2ed0b335939e6737d67b90542420b2c6c1618510b615a616d523b5228f21696d545b54', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1636982908),
(185, 1, '38bda11b5fecab968a523e86a5a8a508c534282884cd0fd201b9d008ce758dca25aa8b6a', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1637008310),
(187, 7, '521a5782f72907ef56c4fe11cf1cfae55d45e10dabd769abd7d71ed3224bbf38810f1979', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1637023193),
(194, 6, 'e4435a8e43b4e1a6de522afe3a96b5ac1863cf93967e48b22fc739b9ddf6b4391e3d22e6', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1637027130),
(199, 1, 'f10b4428c49b66bf0cfca4d69e679093ef0049f124fb98f9b02cd486caa235cbd0f3b01a', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1637028073),
(213, 1, 'c8bd652b7458bfdd826a1cea3daeaea087b799c661a2819c9979269841e6ba62adb841e2', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1637088567),
(216, 3, 'bb716b1cac3968282d7aada3f563dd08f349f3e388adc41424331811fe971676172b5e1b', '{\"ip\":\"192.168.1.63\",\"userAgent\":\"Mozilla/5.0 (iPhone; CPU iPhone OS 15_0_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Mobile/15E148 Safari/604.1\",\"name\":\"Apple Safari\",\"version\":\"15.0\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Safari|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1637093319),
(224, 1, '050b9221228b32e686561ca2384b2fbaa3c07d0ac2eeeefd5076b80b3d154a323b080dcd', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1637174426),
(234, 1, '6c3f865de37db3e911c5eb87f9449a40fba95d405595d39af428a211b257328cec51485d', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1637268980),
(241, 1, 'e0a9b91a9e5c27566ee6f08ca891c4c57780ae44fcf36b8952d564a95968c790ec36da40', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1637370785),
(242, 9, '52d7752e8e909f3c4cd97d871d7dfd2674a4513a5b57253983377d89275058676f6fcc82', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1637371575),
(251, 1, '2377062da398d5a8abd7a604ca8093d1a727ca642a7f293d0ba56fcf4f7051bec297b6f7', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1637427538),
(256, 3, '0985d883701965ec50d15847029f3d77c37c9b23dcdc1f43c00fdba7c9e82fc9c5cad727', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.71 Safari/537.36 OPR/80.0.4170.40\",\"name\":\"Google Chrome\",\"version\":\"94.0.4606.71\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1637513991),
(257, 3, '04fbe66d3e137ead8be5799c18f3aeb674c1db2c0544c25831a4e890609f8edff7c6a989', '{\"ip\":\"192.168.1.63\",\"userAgent\":\"Mozilla/5.0 (iPhone; CPU iPhone OS 15_0_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Mobile/15E148 Safari/604.1\",\"name\":\"Apple Safari\",\"version\":\"15.0\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Safari|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1637522757),
(260, 4, '3885bbd65f682fdc79127d446f56487222dee2aacf174c98fc7cc7cfdfa051c4cf859245', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36 OPR/81.0.4196.54\",\"name\":\"Google Chrome\",\"version\":\"95.0.4638.69\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1637685692),
(261, 3, '0c3ae77e06f3e0274b5369d9794b12a648823f7f853b5914aaa45b9eb0c1d1d5ed2fe073', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.69 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"95.0.4638.69\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1637703743),
(265, 6, '8bf900c4df5836cb55a1dcfa5a67ab00ff1482557f9021da08c1d292d857edea05ea4d4d', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.55 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"96.0.4664.55\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1638108584),
(267, 4, '1b1d912ba391688095966b8f398eba51e6cef5e4c20a1e3cb5c730a20f4ffa361f2a83b1', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.55 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"96.0.4664.55\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1638110756),
(270, 4, '2dd1edf2fdf2af12c6f75a5fc1654ec31ca7d89b7324c6bf71a8fa77b594c4356f53769e', '{\"ip\":\"192.168.1.60\",\"userAgent\":\"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.55 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"96.0.4664.55\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1638224804),
(305, 4, '3a7e3a8371d3c7339dcd4b8a16560869408a0ce2f44badafcdc431e420ca268561989d6f', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36 Edg/97.0.1072.55\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.71\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642185925),
(307, 12, '77d2906c23ed1e0d1a110a90c62cbff3d62e031a19f41ff1fe7c31d5db85794d2404f7c3', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36 Edg/97.0.1072.55\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.71\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642189505),
(310, 4, '58cad5d4c514bb2dfba1335bd961a32d0095500a3a7530ec61a0f8efdcea56a2a213c6f4', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36 Edg/97.0.1072.62\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.71\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642373193),
(311, 3, 'a4a8a27cef547f16ad32ca3ed90fc5bf4032dd18e4c99b6f31d9c2f5262a80251f55574f', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36 Edg/97.0.1072.62\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.71\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642375614),
(318, 3, 'c0c6674ff39765d9a3c5deea5630d10b245a1326ee3533463fb91a6580407044ffaf66c7', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36 Edg/97.0.1072.62\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.71\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642456720),
(419, 3, 'b640e97147290fda3e653211d4a4c4e68c6643a22947ca52a358ee97bbc13d67b02a6d6a', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36 Edg/97.0.1072.62\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.71\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642640098),
(421, 12, 'eee87c92ed3e08a9b008dd26d0397c83475c9f441d8d3821b70f95b73e6b02998e00fdd3', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36 Edg/97.0.1072.62\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.71\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642647593),
(422, 3, '1f922efe0338b2091c3d3f70d97fded4c6b682ef48349975b3feffb2673e2bca235db2f0', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36 Edg/97.0.1072.62\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.71\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642683981),
(424, 3, 'ddb3f37a29b61a2bbca785e3d7a9cb2cce7d6438bffb204d57d7d906b624b2bc2355b1cf', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36 Edg/97.0.1072.62\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.71\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642720403),
(429, 4, '8df2a93bd26926a257ab882f6b2efd6ea485a92f42742e497ebb0c9580bb538b1dc7ac04', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36 Edg/97.0.1072.62\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.71\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642725711),
(430, 3, '61144370d67490b02d9a7e79cbaf38509c83375038a8ce7f34281073a2743546c18bed8a', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36 Edg/97.0.1072.62\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.71\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642726988),
(431, 4, 'c2744b17ec4a278c00b803326362fbaef054b5d649b1a720d7823cccf8f169f4c15042be', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36 Edg/97.0.1072.62\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.71\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642772431),
(432, 3, 'b3636f74be3d54201066ff902b3408b360e4ab389e3b55c7e08698b694e76df7129c23e1', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.71 Safari/537.36 Edg/97.0.1072.62\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.71\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642772483),
(452, 4, 'bb3d594bf6960e5cd9c0c4f0a27e46152a50cf3358fb268f7d7c76464cadaaffea73a906', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642895093),
(460, 12, '7508dff6be6ff58d370177be87d373b8fe77580630fbfb603797dfdaa53bfe83561f5373', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642908252),
(464, 3, '1ea0665df41df7f444d1451a763e7f05fa34d2e0ccf12aae7cd7e02a69d892306763803a', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642952371),
(465, 3, '7be0c044ac61608d3534772c65ff012f5a3579e3d668796ba6c0a18c1ef2b4b64a3f7479', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642965803),
(466, 3, 'b89b984ba7731c851f5410955d481f68d4ee48f8e6b9f1d198ef8946ec52f7f05615b58e', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642969086),
(467, 3, '60bcbe5ef1ebb32109318ebfe6c1cee9b9c40a50941ee908d8d9deab13f8029350b4c114', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642976811),
(492, 3, 'e54d8446463b4b5845c3c4c065e8c9df061a99e79bfcc2c7d4e1826c061e85faf150715c', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642991913),
(495, 3, '2a8d301b2d9aac515127b58ac8fdbfcf33ace95b698f163047c50748b2e2bb092098c899', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642993457),
(496, 3, '820316749e9995dc193ac884a3261cfa407e9bf23b7896a3e0faa6be7ce72abdc7faaa51', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642993499),
(497, 3, '78b24e4eea333164ea6aa92169c82ef03c37b35794bde8fa68e37471d9cdf5cff14c2e81', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1642994441),
(499, 4, 'df60d419f62a82260629a41c354fdddb1475ae364acec88d5f7c18420a7587fcd5b23c02', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643051504),
(500, 3, '570a15707ca71be05e06fe07bc646725ab622d814f23637ba380a68f746c2ac7ad08b43b', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643068404),
(501, 3, '3caedcff59bca965b7311e7088d69899cd2838bd512b3a7e54519d7ed5eab5f9055e052d', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643071390),
(503, 4, '54d4a91e152570a76601c447d5679ea61c569fd9997e388c6adb11fc07fde13f7c962080', '{\"ip\":\"::1\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643118795),
(504, 3, '871757b9074e8d7e2a8ce13946996d59725e66f7bed873625af241e6a4c9720d71ebd485', '{\"ip\":\"192.168.1.65\",\"userAgent\":\"Mozilla/5.0 (iPhone; CPU iPhone OS 15_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.2 Mobile/15E148 Safari/604.1\",\"name\":\"Apple Safari\",\"version\":\"15.2\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Safari|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643120945),
(506, 3, 'd69572bb25d2588e2bce918a1043f3a21b2ccaa53c2b6d5e338948b021a70cf9acc7d5c2', '{\"ip\":\"192.168.1.65\",\"userAgent\":\"Mozilla/5.0 (iPhone; CPU iPhone OS 15_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.2 Mobile/15E148 Safari/604.1\",\"name\":\"Apple Safari\",\"version\":\"15.2\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Safari|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643121095),
(507, 3, '475d52adb9a4c1393e1b0e9000091a2fe71967c9def6d8820862eab04da45d86fbabb825', '{\"ip\":\"192.168.1.65\",\"userAgent\":\"Mozilla/5.0 (iPhone; CPU iPhone OS 15_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.2 Mobile/15E148 Safari/604.1\",\"name\":\"Apple Safari\",\"version\":\"15.2\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Safari|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643121110),
(509, 4, '94f661e6a7b5f46b299496161efb1be2106f0f593e770de6c19a3670f1140498cc894616', '{\"ip\":\"192.168.1.65\",\"userAgent\":\"Mozilla/5.0 (iPhone; CPU iPhone OS 15_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.2 Mobile/15E148 Safari/604.1\",\"name\":\"Apple Safari\",\"version\":\"15.2\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Safari|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643122091),
(511, 5, '2691c8303813404dca19e242f372ae2165a56de777a3360ca312abc989e3a929c6eb5290', '{\"ip\":\"192.168.1.52\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643124975),
(512, 4, '04a4b772d37580248c4a4f7809b31a2ccc926a751149df3508e8c38acebc6037593fe56b', '{\"ip\":\"192.168.1.65\",\"userAgent\":\"Mozilla/5.0 (iPhone; CPU iPhone OS 15_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.2 Mobile/15E148 Safari/604.1\",\"name\":\"Apple Safari\",\"version\":\"15.2\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Safari|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643128826),
(513, 12, '653d86969175e7051e018d7fd238c9e89a86138fe18020841bb246ea7f44b2c922664c8d', '{\"ip\":\"192.168.1.52\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643153327),
(514, 4, '99dbf0106ee438e15950955b96bb1e06167bfceffb12cd9c97b5295b5d43d1ffd6851428', '{\"ip\":\"192.168.1.65\",\"userAgent\":\"Mozilla/5.0 (iPhone; CPU iPhone OS 15_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.2 Mobile/15E148 Safari/604.1\",\"name\":\"Apple Safari\",\"version\":\"15.2\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Safari|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643158084),
(515, 14, '5f23965340f0e51ab2380156bc84adde73e2ae57d3b100aca7bcce738763e50cc77f3634', '{\"ip\":\"192.168.1.52\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643164283),
(517, 4, '2512956982f56a05670798577cbf64e81972a9047ea252e1823e9dc2b3e939f1b181ecf4', '{\"ip\":\"192.168.1.52\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643165874),
(521, 4, '0d7824ab7dcac697b3902978ac2acdec66e2e99825c42e09bdbd459121dd71509fc2a82c', '{\"ip\":\"192.168.1.52\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643249383),
(537, 23, '7e9adf33cef38974b7356c710c3cf73f192160d86f9e983a436c3577aba91252d625e10c', '{\"ip\":\"192.168.1.52\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643252019),
(570, 1, '0a1c27557e78a8333f6f0e9d329c7a6014ef657208110f44f64fa6095433c112ee24b54e', '{\"ip\":\"192.168.1.52\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643330057),
(573, 4, 'a474df2e00d031148580d2a4f02430b60127c8d4db7f2912fa48fd7b8c72e3583107ced2', '{\"ip\":\"192.168.1.52\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643332691),
(574, 4, '9ecd9376758319fb3383a8e1e85e10c05da9bdc768f9a15f6e6b8e8e30fee063022ef991', '{\"ip\":\"192.168.1.52\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643334766),
(576, 4, '7487cf6f57e3182b4cb1cbc4e8524b16244dc473ce644f238dc3d528dc17feb6c9d41d74', '{\"ip\":\"192.168.1.52\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643336557),
(577, 4, '4dfeac1580c5bf5cbbc464a44d405df81a0ed85c4c7ccf87f5583d83c4817c18a036a4c4', '{\"ip\":\"192.168.1.52\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643371992),
(579, 4, '3b24d3826284d6ec0a739a6facb79b6b81d042b5edacc862350092f839877cbb1e5f3d72', '{\"ip\":\"192.168.1.52\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643372292),
(580, 14, 'fbf945788d3f5beac13822855bb4ed51cabc7182868f17cd16e1c7d50766fca9a0905ce5', '{\"ip\":\"192.168.1.52\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.69\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643388858),
(581, 4, 'd7414ae4660d6ccaed3cc82d234c5d28222f34dde7f09c9bb36846c35f542cead3b3e0e4', '{\"ip\":\"192.168.1.52\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.76\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643417402),
(582, 14, '6af7602dccd56c9ce38ee51e1087986ae0d385a17a1b028628375f6ab927d148bdf723c3', '{\"ip\":\"192.168.1.52\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.76\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643419138),
(583, 4, '543140118a8a4b2725cbc70df3c8f17669fdd412e10e17dc2e4aa07b42bf718932ec6691', '{\"ip\":\"192.168.1.52\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.76\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643419731),
(585, 4, '8d6e4c03bfe181a2baf0dbf55c234864ddf44456f4234d7b4b14adc6990459cc3f805b2b', '{\"ip\":\"192.168.1.52\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.76\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643425588),
(586, 4, 'f18b5f9449894cb8a957c9dbd4371c0fc8f8a78d714c526be069ebe748ff4675a2f79b83', '{\"ip\":\"2800:e2:3580:24f4:c574:12a6:a947:de1d\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.76\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643428455),
(587, 4, '8fa22297c1e1a74c73f0e7b3b66f0adec269e9627b7aaca89016038db25070dbd7f2a0c9', '{\"ip\":\"2800:e2:3580:24f4:9d84:95b3:2474:2d8a\",\"userAgent\":\"Mozilla/5.0 (iPhone; CPU iPhone OS 15_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.2 Mobile/15E148 Safari/604.1\",\"name\":\"Apple Safari\",\"version\":\"15.2\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Safari|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643457734),
(589, 4, 'a989d5bf2d3197ae8af46e97b2e738bc43aae465a4a6262497f36585823068192d71fda3', '{\"ip\":\"191.95.128.135\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.76\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643462511),
(591, 1, 'f6efd86d94fc00ab39301962a6caf6e6118c3ed427c8d16488128bd9a94a804326f90832', '{\"ip\":\"2800:e2:3580:1c95:1097:a926:2fc5:250b\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643489597),
(611, 4, 'e581b7887d29ed0ca667aaafca52bb9a327c1f363b20f8c40d49684ceeff00397b9673f1', '{\"ip\":\"191.95.128.135\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.76\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643509893),
(613, 4, '1b665852d8b87b5c63c0a4e73ba589fd4e0098576799646fa66be868c5505fb9c25e9aa5', '{\"ip\":\"191.95.128.135\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.76\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643511466),
(614, 4, '443cf5ee20da02334daf3bcd3d6cc35f1c27c789a0baccce41bc152afbf2cb7eea47dc6d', '{\"ip\":\"191.95.128.135\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.76\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643564474),
(616, 4, 'b19350ef62cea941b7642881d6c0d0a84dfaa949947ce73ae4311411b8b0401cdc42ba32', '{\"ip\":\"2800:e2:3580:24f4:b4ac:d08:a13a:617\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.76\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643665744),
(617, 4, '5bd12e0f3b39fd496af80ba5f8fca8aa43e25073ae22e5a52ee994223a9893a82000ecc7', '{\"ip\":\"2800:e2:3580:24f4:b4ac:d08:a13a:617\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.76\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643666930),
(618, 4, '4d6a8772e3c6719ab79446185fa9ef0e0b7ca5c04e128eaf903adcea72e0e0dd7b96cf86', '{\"ip\":\"2800:e2:3580:24f4:bc07:15e:9724:8dab\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.76\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643728688),
(619, 4, 'afaec8f4acd85e32bb41f4523ae49d408b6a1ead48518081ac87ef42c2c817aa39e7cf9d', '{\"ip\":\"2800:e2:3580:24f4:bc07:15e:9724:8dab\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.76\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643736106);
INSERT INTO `session` (`id`, `user_id`, `session_id`, `details`, `time`) VALUES
(620, 12, 'f4d77b920ceec1bc437819cdc950de32bb8ddbffd7576e15a2cd91bb3b657877225884ac', '{\"ip\":\"2800:e2:3580:1c95:5418:fbf2:1be:6238\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1643746414),
(625, 4, '61ec5501c2d7e02d7350b64236a61331242e6a21a312ffcdd5cc6a3ce1dee894b5bda1fa', '{\"ip\":\"2800:e2:3580:24f4:b062:f15b:3fb0:86e4\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36 Edg/97.0.1072.76\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644000115),
(628, 14, 'a19e07a2a7a24fc28e84358c487f78c30e3f603d1595ca37e2940ee6b265414354a9eea0', '{\"ip\":\"190.242.41.138\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.43\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644090758),
(629, 4, 'af2f208338a7d7f3ac6a5b879f8beec288f11ff90a276fb4cfe1e99ed1f65bcf1c9b1268', '{\"ip\":\"191.95.128.150\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.43\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644092490),
(632, 36, 'e43f92ded560bde496be524a58f489678bdef35bd172b7b7599830c695d516f67d5ec748', '{\"ip\":\"191.95.128.150\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.43\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644093642),
(633, 4, '40334a4158f74ed490e5651f74f2327bc813339efdad344c56d44bd878ca988d93bdfc32', '{\"ip\":\"2800:e2:3580:24f4:814e:2e38:b08d:a579\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.43\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644171962),
(634, 4, 'ca0ea82a2b439f91629738d5632bb52fb1b1d3b43ea71ae0eb36e38e758a220148c567bc', '{\"ip\":\"2800:e2:3580:24f4:814e:2e38:b08d:a579\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.43\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644172014),
(635, 4, '50ec9ee3b9fe8f699abcb68f484d656d1e66aaf017263f5fc8631e2729f05892a78bee89', '{\"ip\":\"2800:e2:3580:24f4:814e:2e38:b08d:a579\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.43\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644172670),
(636, 4, 'ce977c05017a02e20c3139b9238c1577a13e7c47f3f2eeb1c87bb82b0b02eeac8b3e3145', '{\"ip\":\"2800:e2:3580:24f4:814e:2e38:b08d:a579\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.43\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644173270),
(637, 4, 'f1f75f3eb656e9a8f022a4aac7a0de27ad89685df23771518ae00e9b8a66c9642b6e763c', '{\"ip\":\"2800:e2:3580:24f4:814e:2e38:b08d:a579\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.43\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644173507),
(638, 4, '9b73f2117d35a4a2d118353798713169acc6d1958d13450aee1cf9bb3198fa7ae92e4c00', '{\"ip\":\"2800:e2:3580:24f4:814e:2e38:b08d:a579\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.43\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644173547),
(640, 3, '194141cb6990a556bf6df1ff159d90b749d3a22f7b4681df4771dc644e965bd8bda71631', '{\"ip\":\"2800:e2:3580:24f4:814e:2e38:b08d:a579\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.43\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644173623),
(642, 4, 'da4f23409afacfcf0aea9b93454bd7d50723cc4393004571b558d98ddd015a187225a41b', '{\"ip\":\"2800:e2:3580:24f4:814e:2e38:b08d:a579\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.43\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644174478),
(644, 4, '2ccc8810bc3b7a71164b783343d3803fbb15d7a7a66a624cadafa7d252e5661e94ce2598', '{\"ip\":\"2800:e2:3580:24f4:814e:2e38:b08d:a579\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.43\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644175305),
(645, 4, '8e01ffcdfc0a64c74c0a32933ca667f049e68b331b6e4172508d2e6c46fe2be9e9ee3ca4', '{\"ip\":\"2800:e2:3580:24f4:814e:2e38:b08d:a579\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.43\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644175324),
(646, 4, 'a73d128557fe3bb15d271c021e4decab96477fc87245fdcff8298ea4fa1d41309d32037e', '{\"ip\":\"2800:e2:3580:24f4:814e:2e38:b08d:a579\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.43\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644175346),
(651, 4, '92c00fb24c8135935b1a6d97544fc4dd1203a3d5f1f8ea61733a82d277738e4c65572e1f', '{\"ip\":\"2800:e2:3580:24f4:814e:2e38:b08d:a579\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.43\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644185134),
(652, 12, '1c4cf2ce1cb3b2d73e820a8151501ebfbaeaff3277278b2394b0e3c31884ff22591ece91', '{\"ip\":\"190.242.41.138\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.81 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.81\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644189633),
(656, 12, '20073dc6d18ff84bbe1eb480d24d741770aa653a608ac29e1f1ede72c20be667036ade44', '{\"ip\":\"2800:e2:3580:1c95:2901:a41b:33ea:5b99\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.99 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.99\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644251455),
(657, 1, 'c94827e51b61914e971d4acfa52fa0bc6926990fa58321760c9ffd591aec1991575064c6', '{\"ip\":\"2800:e2:3580:1c95:8d10:40fc:ec9e:59bf\",\"userAgent\":\"Mozilla/5.0 (Linux; Android 10; SM-N9600) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/97.0.4692.98 Mobile Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"97.0.4692.98\",\"platform\":\"Linux\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644276157),
(677, 4, '90e0ca3608c6004e44d2430ea40938beca40e3a8a689715f252a4ea1085b76fc77bf5279', '{\"ip\":\"190.242.41.138\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.81 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.81\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644319898),
(683, 13, 'ae37533ca411d0e773d0de744a71f0afdc64007eebcfc0bf5c624b5b034650fa7b510b6b', '{\"ip\":\"190.242.41.138\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.81 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.81\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644341208),
(697, 12, '137e3acf015708f1c18b2483e6f52a7acbcdbf21bd6e1086ab77e8451db2d0afcfc6f944', '{\"ip\":\"190.242.41.138\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.81 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.81\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644363560),
(698, 7, 'f85c42cc885a753534cbbf4e55eee16cec0c0c6e3f5c7b9b6f315f8e9e6a565ff7536703', '{\"ip\":\"2800:e2:3580:24f4:d1f1:777c:ac66:1e7b\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.43\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644364672),
(699, 14, '8f2544e80ec464d107e6b72118e3e35d8a249a922d7b67b2b64ce08f4821b8028251e084', '{\"ip\":\"2800:e2:3580:24f4:d1f1:777c:ac66:1e7b\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.43\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644366730),
(704, 12, 'c4be1f65b4ef771888b6939f6b9b945503b94c661c4344c23c7ee4c41ecc9f626b5c7cd3', '{\"ip\":\"190.242.41.138\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.82 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.82\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644596015),
(708, 4, '2d925338acd67a1456d22daabd7acc578efa677ef34d2f288d6fa06951ed87c6d97be6bd', '{\"ip\":\"2800:e2:3580:24f4:9114:12a:b413:38b2\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.43\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644630839),
(711, 4, 'ac9f39ae51da2476a54470a24a18bca63b654879e7e563acdec2433142f48f70c0404ad8', '{\"ip\":\"2800:e2:3580:24f4:9114:12a:b413:38b2\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.50\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644686577),
(715, 7, 'fac8c3020eb5c5277f66a560c5044bfa8d1806fe5f861ab13d97d690830288afebf484cd', '{\"ip\":\"190.242.41.138\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.82 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.82\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644787312),
(718, 14, 'f0e8a3209bd52613eb126f342d54791e2c38c0e685fb9338484f5b91c01208392bc4f2e9', '{\"ip\":\"190.242.41.138\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.82 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.82\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644798420),
(721, 4, '67dc597b032c94d61d4b5102938b58df5327e29863525707f67482be69be0fe3425aabe5', '{\"ip\":\"2800:e2:3580:24f4:a9c1:ba39:8249:adde\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.50\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644802187),
(722, 4, '79160f3d54c4c7d09d250f73a8f2d1ba45fe2a8daaebb7c3d7fea13b09bf2c142fa5b652', '{\"ip\":\"2800:e2:3580:24f4:a9c1:ba39:8249:adde\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.50\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644846792),
(731, 4, '6b475515ebdf282a708f2361279e3d502b720db9db21e09d22512a977fe8837807688495', '{\"ip\":\"2800:e2:3580:1c95:bc36:282f:2cfe:685c\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.82 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.82\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644896525),
(732, 1, '1f5e08b0ba1a8e11f72af51c89ab04ca55ed6bd225edb5beda5cefdc7828068447b595f3', '{\"ip\":\"2800:e2:3580:24f4:6c0d:f28f:c9c4:d6ab\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.50\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644945785),
(733, 1, '5209adf9c8d2deaf92a479c8414de53073808c2c4ed6d71ca8bd0693275dd524fb20f7f2', '{\"ip\":\"2800:e2:3580:24f4:6c0d:f28f:c9c4:d6ab\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.80 Safari/537.36 Edg/98.0.1108.50\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.80\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1644966792),
(734, 4, 'fb0642c2398425d1fe85c7bd52acecad730fb37646d956cbae1f464ff7ff5b7bc59896e2', '{\"ip\":\"2800:e2:3580:1c95:3989:8da3:9341:e409\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.82 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.82\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1645026532),
(737, 4, '870fda4fc2a528fc4186908ae5bf1eadb742664228befdc0aeff63107c5d329d35d0fe2f', '{\"ip\":\"2800:e2:3580:24f4:513d:2bf5:bf24:1357\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36 Edg/98.0.1108.55\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.102\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1645115194),
(738, 4, 'fcc6419e95caa2bb466700373cf3d8cc12f3b41f12864b0e47d7f0ab5abc432e37bc3bf5', '{\"ip\":\"190.242.41.138\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.102\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1645116742),
(746, 4, '7c31159c0e44dbb808d047aa24aa4761fa8fc8fa50d46e5072d3b66d71cbec34464f6dd0', '{\"ip\":\"2800:e2:3580:24f4:a488:cb7a:5f05:44ba\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36 Edg/98.0.1108.56\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.102\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1645313978),
(751, 14, '152667e52f045ad022cb8b1a82475baffd72a49f93323f70f2079e7a4f11cf348f34ea3d', '{\"ip\":\"2800:e2:3580:24f4:a488:cb7a:5f05:44ba\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36 Edg/98.0.1108.56\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.102\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1645327909),
(754, 4, '93b28f310ba6815cc9e36521d3a7d2b1b13287ab3051eca90f7ecf7b116d7570e018b3c3', '{\"ip\":\"191.95.128.220\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36 Edg/98.0.1108.56\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.102\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1645407669),
(757, 1, '815292d7d59ecda83bbe24552f6d823a38a707c0ed9d1a6a566a33e33d2a78e112a5b8f8', '{\"ip\":\"2803:1800:5117:37cd:6951:cfa8:73f0:5acc\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.102\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1645546773),
(758, 4, '7e51d66566354a74bc571a690c6c302b87d17c5e4e5c19ae8aec8596afab520941bdba6a', '{\"ip\":\"2800:e2:3580:24f4:e41f:2c57:417:2a78\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36 Edg/98.0.1108.62\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.102\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1645928659),
(759, 4, '341cdc016337f3ef823a62aa5b18812ce5e247a80abd95c003ba0b60a84a3943ce2e69c8', '{\"ip\":\"2800:e2:3580:24f4:5d5e:776:9730:24e7\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36 Edg/98.0.1108.62\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.102\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1646163651),
(760, 4, '3102f14e61b604c7c61179ead8e98cb20f730685beb3cf9387379af5f9cfc600786b040f', '{\"ip\":\"2800:e2:3580:24f4:ed59:5a35:3b3d:f2dd\",\"userAgent\":\"Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Mobile Safari/537.36 Edg/98.0.1108.62\",\"name\":\"Google Chrome\",\"version\":\"98.0.4758.102\",\"platform\":\"Linux\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1646435970),
(761, 1, '035fe9878ec18e2121388cbb4a5156af24ec2e69c099afccadf0388466c2df4589e7caa8', '{\"ip\":\"2803:1800:5114:9842:4ce2:2948:6b0:194b\",\"userAgent\":\"Mozilla/5.0 (iPhone; CPU iPhone OS 15_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/99.0.4844.59 Mobile/15E148 Safari/604.1\",\"name\":\"Apple Safari\",\"version\":\"604.1\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Safari|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1647363239),
(762, 4, 'c81f888c27543c698a8c34b340e8cacdfa46d6ec1a21cad4d79fc6e31bb94a16bb25e26d', '{\"ip\":\"2800:e2:3580:24f4:bce6:7637:d00e:4bbb\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.51 Safari/537.36 Edg/99.0.1150.39\",\"name\":\"Google Chrome\",\"version\":\"99.0.4844.51\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1647365165),
(763, 4, 'c6b28cc68342e786fa9ae3ec36aeea1924cc901ab91ed41a2c0f93da6550f1e6912e89b4', '{\"ip\":\"191.95.135.14\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.51 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"99.0.4844.51\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1647366895),
(769, 7, 'c2d5c702ce345a7524e2dbb339c672b974566a965fe14526577c0ed6d3a15e3d5557e3f1', '{\"ip\":\"2800:e2:3580:24f4:e5a5:adf3:77fd:7578\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.74 Safari/537.36 Edg/99.0.1150.46\",\"name\":\"Google Chrome\",\"version\":\"99.0.4844.74\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1647978663),
(771, 14, 'b35927daa0da0693decc8c2c690e89a166d965c354ac7f4a6e0afa35e26da8a33bb6e314', '{\"ip\":\"2800:e2:3580:1073:7830:5c5:819c:6fcb\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.74 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"99.0.4844.74\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1647978681),
(772, 4, 'd72fc55e4edf5ea5917be07dc8367b346f0b7b0ed7978f3e45c4c7870896a379c6b66e15', '{\"ip\":\"191.95.135.19\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.74 Safari/537.36 Edg/99.0.1150.46\",\"name\":\"Google Chrome\",\"version\":\"99.0.4844.74\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1647999139),
(778, 14, '07bb747af5d6695f080a1585030531658843081eb4e2936f266ed6710d4761c1be089f92', '{\"ip\":\"2800:e2:3580:24f4:4972:b23e:8286:b2bb\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.74 Safari/537.36 Edg/99.0.1150.55\",\"name\":\"Google Chrome\",\"version\":\"99.0.4844.74\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1648832091),
(780, 14, '60554b4cde703c5f9673464131af6d7d73d1b4549b7b9972306a43531df4ea143c061258', '{\"ip\":\"2800:e2:3580:24f4:4972:b23e:8286:b2bb\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.74 Safari/537.36 Edg/99.0.1150.55\",\"name\":\"Google Chrome\",\"version\":\"99.0.4844.74\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1648856204),
(781, 4, '6e5d12fa622d3726b1212a2acbfdf8f1d2a974665870613ae8304a8f268f634df7e648dd', '{\"ip\":\"2800:e2:3580:24f4:4972:b23e:8286:b2bb\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.74 Safari/537.36 Edg/99.0.1150.55\",\"name\":\"Google Chrome\",\"version\":\"99.0.4844.74\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1648856903),
(782, 4, '40ea3c031dc431629993c4e352951de0f449d68e2537c07855f021a8c8177afd4aa39e69', '{\"ip\":\"2803:1800:5114:b8d5:e80b:88a4:80b3:ddd9\",\"userAgent\":\"Mozilla/5.0 (iPhone; CPU iPhone OS 15_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.3 Mobile/15E148 Safari/604.1\",\"name\":\"Apple Safari\",\"version\":\"15.3\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Safari|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1648906195),
(783, 14, '01e17a2808fd731332432ae2422eac1319f7463a5a25f215ddeea8a113fa17b55538114f', '{\"ip\":\"2803:1800:5114:b8d5:e80b:88a4:80b3:ddd9\",\"userAgent\":\"Mozilla/5.0 (iPhone; CPU iPhone OS 15_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.3 Mobile/15E148 Safari/604.1\",\"name\":\"Apple Safari\",\"version\":\"15.3\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Safari|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1648907346),
(785, 14, '21222b1ea2a31ccedb9ba2045169f6375b1447946f4c96e6a52e12430f64ffc05d14b773', '{\"ip\":\"190.14.242.194\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"99.0.4844.84\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1648908646),
(786, 4, '863b68934a9a19bcf21a46734f0e0253aa91d664569c567a266ca1a1cc4ecc771aab789d', '{\"ip\":\"190.14.242.194\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Safari/537.36\",\"name\":\"Google Chrome\",\"version\":\"99.0.4844.84\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1648909888),
(787, 4, '8ccae6aed3f07af015967f52070e1d7e29b432a2cfdaad6c0bed53a54a796a74df249a68', '{\"ip\":\"2800:e2:3580:24f4:1dbd:ff81:d32f:5c0e\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.127 Safari/537.36 Edg/100.0.1185.44\",\"name\":\"Google Chrome\",\"version\":\"100.0.4896.127\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1650420733),
(788, 4, 'e002263a58e77c580c3ae6d33531307319362533d18a2f0e6cf19c582b5ec33435bc766b', '{\"ip\":\"2800:e2:3580:24f4:f5a6:7518:6900:2549\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.127 Safari/537.36 Edg/100.0.1185.44\",\"name\":\"Google Chrome\",\"version\":\"100.0.4896.127\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1650476693),
(789, 14, 'a0dbfed7cc51094c03bfc390da52a9955fd30bb27b9d4a42a6ac30ccce896d8d2f79159f', '{\"ip\":\"2803:1800:5100:acb9:c9c7:bcae:81f7:a247\",\"userAgent\":\"Mozilla/5.0 (iPhone; CPU iPhone OS 15_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/101.0.4951.44 Mobile/15E148 Safari/604.1\",\"name\":\"Apple Safari\",\"version\":\"604.1\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Safari|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1651629172),
(791, 4, '6f595ad268a2f5bf5b203dfdaad8b9850027c0284ee9b95e993f85ad38adc37421a97e7b', '{\"ip\":\"2803:1800:510f:37e8:b054:c5bc:b0c7:f235\",\"userAgent\":\"Mozilla/5.0 (iPhone; CPU iPhone OS 15_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.4 Mobile/15E148 Safari/604.1\",\"name\":\"Apple Safari\",\"version\":\"15.4\",\"platform\":\"Mac\",\"pattern\":\"#(?<browser>Version|Safari|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1655066029),
(792, 4, '370aec46d13e69076d9bded6e8cb5b3e81f3c8f0f4582e3f4876f67ab83b5febfc672e88', '{\"ip\":\"2800:e2:3580:24f4:bce6:7637:d00e:4bbb\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.5060.134 Safari/537.36 Edg/103.0.1264.77\",\"name\":\"Google Chrome\",\"version\":\"103.0.5060.134\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1659830015),
(793, 4, '490dd9d06556911ad42c591674b9d06a86b94340fe4f9fe5badb33c075fe76714f5a48f5', '{\"ip\":\"2800:e2:3580:24f4:bce6:7637:d00e:4bbb\",\"userAgent\":\"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.5112.81 Safari/537.36 Edg/104.0.1293.47\",\"name\":\"Google Chrome\",\"version\":\"104.0.5112.81\",\"platform\":\"Windows\",\"pattern\":\"#(?<browser>Version|Chrome|other)[/ ]+(?<version>[0-9.|a-zA-Z.]*)#\"}', 1659962322);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `setting`
--

CREATE TABLE `setting` (
  `name` varchar(128) NOT NULL,
  `value` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `setting`
--

INSERT INTO `setting` (`name`, `value`) VALUES
('authentication', 'on'),
('description', 'Estudia por pasion'),
('email', 'contact@edutka.soyvillareal.com'),
('from_email', 'no-reply@phpmagazine.soyvillareal.com'),
('keyword', 'Edutka'),
('language', 'es'),
('recaptcha', 'on'),
('recaptcha_private_key', ''),
('recaptcha_public_key', ''),
('server_type', 'smtp'),
('smtp_encryption', 'tls'),
('smtp_host', 'email-smtp.us-east-1.amazonaws.com'),
('smtp_password', ''),
('smtp_port', '587'),
('smtp_username', ''),
('theme', 'default'),
('title', 'Edutka'),
('validate_email', 'on');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `teacher`
--

CREATE TABLE `teacher` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `course_id` int(11) NOT NULL DEFAULT 0,
  `period_id` int(11) NOT NULL DEFAULT 0,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `teacher`
--

INSERT INTO `teacher` (`id`, `user_id`, `course_id`, `period_id`, `time`) VALUES
(1, 15, 5, 2, 1643244485),
(2, 16, 4, 2, 1643244556),
(3, 7, 1, 2, 1643244587),
(4, 6, 2, 2, 1643244606),
(5, 12, 3, 2, 1643244622),
(6, 9, 59, 2, 1643244639),
(7, 17, 61, 2, 1643244657),
(8, 13, 60, 2, 1643244674),
(9, 18, 8, 3, 1643244705),
(10, 7, 6, 3, 1643244793),
(11, 19, 10, 3, 1643244815),
(12, 12, 9, 3, 1643244857),
(13, 11, 7, 3, 1643244869),
(14, 11, 11, 10, 1643244888),
(16, 21, 62, 10, 1643244938),
(17, 18, 14, 10, 1643244955),
(18, 15, 17, 10, 1643244970),
(19, 22, 63, 10, 1643244986),
(20, 20, 18, 11, 1643245008),
(21, 11, 64, 11, 1643245024),
(22, 18, 20, 11, 1643245045),
(23, 23, 21, 11, 1643245064),
(24, 24, 22, 11, 1643245079),
(25, 25, 23, 11, 1643245093),
(26, 11, 25, 12, 1643245111),
(27, 26, 28, 12, 1643245126),
(28, 27, 29, 12, 1643245143),
(29, 24, 27, 12, 1643245168),
(30, 23, 26, 12, 1643245189),
(31, 28, 26, 13, 1643245214),
(32, 11, 32, 13, 1643245231),
(33, 28, 33, 13, 1643245246),
(34, 29, 34, 13, 1643245259),
(35, 30, 36, 13, 1643245278),
(36, 31, 38, 13, 1643245297),
(37, 32, 39, 14, 1643245324),
(38, 33, 40, 14, 1643245339),
(39, 33, 41, 14, 1643245355),
(40, 34, 42, 14, 1643245372),
(41, 35, 43, 14, 1643245386),
(42, 31, 45, 14, 1643245401),
(43, 34, 35, 14, 1643245423),
(44, 36, 65, 15, 1643247116),
(45, 37, 47, 15, 1643247134),
(46, 38, 48, 15, 1643247151),
(47, 34, 49, 15, 1643247167),
(48, 34, 35, 15, 1643247188),
(49, 39, 50, 15, 1643247202),
(50, 29, 51, 15, 1643247220),
(51, 29, 31, 15, 1643247234),
(52, 40, 58, 8, 1643247327),
(53, 33, 52, 8, 1643247340),
(54, 41, 53, 8, 1643247355),
(55, 42, 54, 8, 1643247367),
(56, 43, 55, 8, 1643247381),
(57, 44, 56, 8, 1643247395),
(58, 45, 57, 8, 1643247410),
(59, 20, 12, 10, 1643251659),
(62, 7, 35, 23, 1644174119);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `ukey` varchar(32) NOT NULL,
  `dni` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `cellphone` varchar(15) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `ip` varchar(43) NOT NULL,
  `password` varchar(255) NOT NULL,
  `names` varchar(50) NOT NULL DEFAULT '',
  `surnames` varchar(50) NOT NULL DEFAULT '',
  `gender` tinyint(1) NOT NULL DEFAULT 1,
  `token` varchar(50) NOT NULL,
  `language` varchar(2) NOT NULL DEFAULT 'en',
  `avatar` varchar(100) NOT NULL DEFAULT 'images/default-avatar.jpg',
  `province` varchar(16) NOT NULL DEFAULT 'antioquia',
  `municipality` int(11) NOT NULL DEFAULT 0,
  `date_birthday` int(11) NOT NULL DEFAULT 0,
  `about` text DEFAULT NULL,
  `status` set('pending','active','deactivated') NOT NULL DEFAULT 'pending',
  `role` set('admin','academic','teacher','student') NOT NULL DEFAULT 'student',
  `age_changed` int(11) NOT NULL DEFAULT 0,
  `authentication` tinyint(1) NOT NULL DEFAULT 0,
  `change_email` varchar(255) DEFAULT NULL,
  `time` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `ukey`, `dni`, `email`, `cellphone`, `phone`, `ip`, `password`, `names`, `surnames`, `gender`, `token`, `language`, `avatar`, `province`, `municipality`, `date_birthday`, `about`, `status`, `role`, `age_changed`, `authentication`, `change_email`, `time`) VALUES
(1, 'AekYR3PaZQLg9lBw', 123456, 'test@gmail.com', NULL, NULL, '2800:e2:3580:1073:7830:5c5:819c:6fcb', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Mercedes', 'Gomez Burgos', 2, 'e7799241151273f1c52812f2412e852b', 'es', 'images/default-favatar.jpg', 'bolivar', 176, 930009600, '', 'active', 'academic', 0, 0, NULL, 1611596407),
(3, 'vR4qONB06n8t', 1234567, 'test1@gmail.com', '3026823456', NULL, '2800:e2:3580:24f4:a488:cb7a:5f05:44ba', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Oscar David', 'Garces Gomez', 1, '2eaabc45bd959a86e9ded2878409f4926bca037d', 'es', 'uploads/images/2022/01/9cc9b7e49aba45500489722a268d2c77055c8c46_25_59UZJOMN02L13_avatar.jpeg', 'antioquia', 1, 338601600, '', 'active', 'student', 0, 0, NULL, 1633544741),
(4, 'aYgk9lBZQAPLeR3w', 12345678, 'test2@gmail.com', NULL, NULL, '2800:e2:3580:24f4:bce6:7637:d00e:4bbb', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'David Gerardo', 'Garces Villareal', 1, 'ec4894d6553e574452de9d25e46e03d0', 'es', 'images/default-avatar.jpg', 'bolivar', 176, 930009600, '', 'active', 'admin', 0, 0, NULL, 1611596407),
(5, 'aYgk9lBZQAPLeR3w', 123456789, 'test3p@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Candida Isabel', 'Velilla Arrieta', 2, 'c5e92423128f2f22b11541251e778791', 'es', 'images/default-favatar.jpg', 'bolivar', 176, 930009600, '', 'active', 'teacher', 0, 0, NULL, 1611596407),
(6, 'MIOZ3E4B8SAN', 347901015, 'ejemplo2@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Luis Alfredo', 'Blanquicett Benavides', 1, '72f2c0796201913485f3ea3604689748fb8caa94', 'es', 'images/default-avatar.jpg', 'bolivar', 176, 930009600, '', 'active', 'teacher', 0, 0, NULL, 1611596407),
(7, '7UM5IRX24B8OKNSV', 1042014165, 'ejemplo3@gmail.com', NULL, NULL, '2800:e2:3580:1073:7830:5c5:819c:6fcb', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Rafael', 'Cortina Rodriguez', 1, '576d1f3a36a2ec6a49044a436e5d3aa85ac8090c', 'es', 'images/default-avatar.jpg', 'bolivar', 176, 930009600, '', 'active', 'teacher', 0, 0, NULL, 1611596407),
(8, '7Q1JTNKSFLURM35D', 595741588, 'ejemplo4@gmail.com', NULL, NULL, '192.168.1.60', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Nicolas Enrique', 'Velez Vergara', 1, '68685c475b1329f0cf76a56e166b44d5a4e36600', 'es', 'images/default-avatar.jpg', 'bolivar', 176, 930009600, '', 'active', 'teacher', 0, 0, NULL, 1611596407),
(9, '12OU630JNEGS', 458243597, 'ejemplo5@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Jose David', 'Jaraba', 1, '534e511d552423f3361a10feed112b11fedfb624', 'es', 'images/default-avatar.jpg', 'bolivar', 176, 930009600, '', 'active', 'teacher', 0, 0, NULL, 1611596407),
(10, 'aYgk9lBZQAPLeR3w', 1007169002, 'ejemplo6@gmail.com', NULL, NULL, '::1', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Didier David', 'Junco Perez', 1, 'c4b115228fe21127a9139487f2b122c5', 'es', 'images/default-avatar.jpg', 'bolivar', 176, 930009600, '', 'active', 'student', 0, 0, NULL, 1611596407),
(11, 'X2M1N5P9YLCO', 186989385, 'ejemplo7@gmail.com', NULL, NULL, '190.242.41.138', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Edwin', 'Alfaro Rodriguez', 1, 'd37b36673ac45cde4322d12bec294f8e9c5a584b', 'es', 'images/default-avatar.jpg', 'bolivar', 176, 930009600, '', 'active', 'teacher', 0, 0, NULL, 1611596407),
(12, 'TVM2375R8KXJFB', 215177850, 'ejemplo8@gmail.com', NULL, NULL, '190.242.41.138', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Emerson Nicolas', 'Madrid Morgan', 1, '8697c5621f93e7caab9f2544bdd930e6f4ce3b0b', 'es', 'images/default-avatar.jpg', 'bolivar', 176, 930009600, '', 'active', 'teacher', 0, 0, NULL, 1611596407),
(13, 'HCU4RYXWA589I3SV', 211861748, 'ejemplo9@gmail.com', NULL, NULL, '190.242.41.138', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Carlos Eduardo', 'Caicedo Bautista', 1, '3c116cf25972417c8b112b02e8f8674031a34a9c', 'es', 'images/default-avatar.jpg', 'bolivar', 176, 930009600, '', 'active', 'teacher', 0, 0, NULL, 1611596407),
(14, 'Q735R2GTWY6I', 1143412220, 'test4@gmail.com', '3026823456', NULL, '2803:1800:510a:d4fc:45bb:4e61:a15c:f65a', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Luis Fernando', 'Avendaño Cumplido', 1, '1c855e9c64c1ab3c1b86396e71f0cd60e382dcea', 'es', 'images/default-avatar.jpg', 'antioquia', 1, 517190400, '', 'active', 'student', 0, 0, NULL, 1637026645),
(15, 'OE0DP1G4T7Z9UQ', 1068165165, 'ejemplo10@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Nestor Leonardo', 'Gonzalez Del Valle', 1, 'e2d909c6c88a674603db8aacbdbb2946653cccfc', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(16, 'VTCRGNPMDHFOWEIX', 729970541, 'ejemplo11@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Carlos Alberto', 'Cordoba Peñate', 1, '8a7f850b2aa36d23f6834673460cc62da84ebea2', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(17, '2Y6PMBQ1VSX9LN', 423473681, 'ejemplo12@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Juan Carlos', 'Valderrama Martinez', 1, '50f48c12907153c2819298568b2491038d4030a3', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(18, '6N2Q7FZPG8WJ9B', 1119366244, 'ejemplo13@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Cristian Enrique', 'Sarabia Mendoza', 1, '59c9154556686bcca3934d7d97c0acb2cc5ba3dc', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(19, 'IN86TZMS5ABP', 980193345, 'ejemplo14@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Leidy Dairy', 'Cortes Mendez', 1, '93a6568a410e72522ac98bc1c22bc5ca56f3f9c0', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(20, 'KOBWT5XDALR8G40', 941284719, 'ejemplo14@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Tulio Enrique', 'Muentes Cervantes', 1, '40923b11f004e984dfc94076bdb76d6103e65fdb', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(21, 'BXTQ79K5D2OWE', 74184640, 'ejemplo15@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Xibia Cecilia', 'Hurtado Rocha', 1, '03cde924f783c2ce670b4da4dfc948aa52bb3b16', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(22, 'V5RGUTICB28LPOM', 525214052, 'ejemplo16@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Hernando', 'Zapata Belalcazar', 1, '6b3159a0ec0c541ec989596fdaf96ad8433b4589', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(23, '20X4AVH756WEFTG3', 819303843, 'ejemplo17@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Jhon Carlos', 'Arrieta Arrieta', 1, '53ac81bc05dcec801623a568f3a2346d08883a6c', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(24, 'OALF7EQD1GWPI', 580483729, 'ejemplo18@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Danilo Santiago', 'Vargas Jimenez', 1, '18edd76b7ee27771d188ca9563701e117435043b', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(25, 'D0B9FPGTV3YW', 427921845, 'ejemplo19@gmail.com', NULL, NULL, '::1', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Keren', 'Brunal Ramos', 1, 'd156294950dd5f724a82eec77f4ba10f6f88531e', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(26, '6KEF8ZUYNIXB', 568470985, 'ejemplo20@gmail.com', NULL, NULL, '::1', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Carlos Andres', 'Arenas Correa', 1, '8c0b5fe6fb935cbc484a6b9b22b6481a9799df04', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(27, 'U4RPGM326JA5ZD', 829636082, 'ejemplo21@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Ricardo Luis', 'Fuenmayor Acosta', 1, 'a5c0a589263d390ea9c19c7a150b325ba6c8f868', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(28, 'XLCT3K8JDMUFEA9', 618698306, 'ejemplo22@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Lizeth', 'Hernandez Saleme', 1, 'c9d8147c869f4515debcda436206cb1bb53f5b82', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(29, 'W80LHGJB9YV37X', 815167967, 'ejemplo23@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Jonathan', 'Berthel Castro', 1, '1b8deb78b5578d5db6be4e1999fd64725dea2810', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(30, 'LXS4KR182UFAE3', 170758497, 'ejemplo24@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Pablo', 'Senior Narváez', 1, 'bbe7531eea8ad05058a5b7d0adf0209b22c8c3fc', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(31, 'C5XWZIT2HULO', 752496708, 'ejemplo25@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Yesid', 'Lidueñas Bastidas', 1, '44de4fb2b50941365f262d76a0ae0e77168bd627', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(32, 'SBJ0XP275L8RM', 527310431, 'ejemplo26@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Ana', 'Gutierrez', 1, '1c61681b92420922fbec3ebec85745a4eb3f2e86', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(33, 'IYWU187QLKAZG6', 372287710, 'ejemplo27@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Nestor', 'Suat', 1, 'dfd8c6d5b0f72a7fb90333e40d3c2099e8777ea9', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(34, 'FWSV9X1MHB6YN7R', 439511983, 'ejemplo28@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Obeth', 'Romero', 1, '842f1efc4cb9f4895800dc83fad4bc88991d218b', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(35, 'YW06FS2EHIQ9X', 568874343, 'ejemplo29@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Fabio', 'Acuña John', 1, 'a9af1886210c0853ee97ab1155fc99b27e511074', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(36, 'M6TPEG1JFKHB4OZ', 626794005, 'ejemplo30@gmail.com', NULL, NULL, '191.95.128.150', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Jeiner Fernando', 'Cadena Plazas', 1, 'af120b7fc9a74c0039f4b458978c1801645bbdb3', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(37, 'X5MWBRH1GNTP', 965649429, 'ejemplo31@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'David', 'Ramirez', 1, 'fdce02add722323b148a6cc7a3acb61a2ce034e8', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(38, '4RN625GQPEZK', 682009189, 'ejemplo32@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Jorge Ivan', 'Vargas Florez', 1, '4354b4f7e7e05f764ffb5de33772d918edf2f275', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(39, 'QWDA3H0FO1ME4', 965345852, 'ejemplo33@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Viviana', 'Pulido', 1, '7e17efaaec81416a6a3c538c01783e56aa2c22f3', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(40, 'G3A42KBC7ZERIOFY', 784207910, 'ejemplo34@gmail.com', NULL, NULL, '190.242.41.138', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Maria Del Pilar', 'Wilches', 1, '61a3d93677061782db42e9516a9685ac7154062a', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(41, 'N0K6B3SJ4MVI', 553455286, 'ejemplo35@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Sindi Yolenis', 'Ortega Botero', 1, '8b4c270e9a540010cd3dd74e1646293f2c2e980c', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(42, 'Q7FZ6E8BOWH9T', 72290005, 'ejemplo36@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Omar', 'Vargas', 1, '465b8a5f6a2bf262fddbf5539ccf526fbd0f2cb2', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(43, '19QA4B8ZY7XULOD', 103906255, 'ejemplo37@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Camilo Andres', 'Marin Martinez', 1, '9acbacdc9238d6803f95c9d85e0d9ba0b28e7adb', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(44, '3WNVF2IPEKHM', 875333798, 'ejemplo38@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Diana Linena', 'Velásquez', 1, 'e9430a79ea01f737f85eafa9faa25b5bdb0fb151', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919),
(45, '9VQ10DMSEO3P7T', 75868647, 'ejemplo39@gmail.com', NULL, NULL, '192.168.1.52', '20eabe5d64b0e216796e834f52d61fd0b70332fc', 'Hugo Yesid', 'Garcia Jimenez', 1, '9737e15e804c2bfe54825f85b0bb9f0474c3fa4d', 'es', 'images/default-avatar.jpg', 'antioquia', 0, 46396800, NULL, 'active', 'teacher', 0, 0, NULL, 1642602919);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `word`
--

CREATE TABLE `word` (
  `word` varchar(160) NOT NULL,
  `en` text DEFAULT NULL,
  `es` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `word`
--

INSERT INTO `word` (`word`, `en`, `es`) VALUES
('404_description', 'The page you were looking for doesn\'t exist.', 'La página que buscabas no existe.'),
('404_title', '404, page not found', '404 Pagina no encontrada'),
('about_us', 'About us', 'Sobre nosotros'),
('academic', 'Academic', 'Académico'),
('academic_calendar', 'Academic calendar', 'Calendario académico'),
('academic_enrollment', 'Academic enrollment', 'Matrículas académicas'),
('academic_loads', 'Academic loads', 'Cargas académicas'),
('academic_load_limit', 'Academic load limit', 'Límite de cargas académicas'),
('academic_period', 'Academic period', 'Período académico'),
('academic_periods', 'Academic periods', 'Períodos académicos'),
('academic_programs', 'Academic programs', 'Programas académicos'),
('academic_regulations', 'Academic regulations', 'Reglamentos académicos'),
('accepted', 'Accepted', 'Aceptada'),
('accepted_request_authorization_course', 'You have just accepted your request for authorization for the course', 'Acaba de aceptar tu solicitud de habilitación para el curso'),
('access', 'Access', 'Accesos'),
('account_is_not_active', 'Your account is not active yet, please confirm your E-mail.', 'Su correo aún no se ha podido verificar, por favor confirme su email.'),
('action', 'Action', 'Acción'),
('activated', 'Activated', 'Activada'),
('active', 'Active', 'Activa'),
('add', 'Add', 'Añadir'),
('add_new_academic_term', 'Add a new academic term', 'Agregar un nuevo período académico'),
('add_new_course', 'Add a new course', 'Agregar un nuevo curso'),
('add_new_faculty', 'Add a new faculty', 'Agregar una nueva facultad'),
('add_new_form', 'Add a new form', 'Agregar un nuevo formulario'),
('add_new_program', 'Add a new program', 'Agregar un nuevo programa'),
('add_new_study_plan', 'Add a new study plan', 'Agregar un nuevo plan de estudio'),
('administrator', 'Administrator', 'Administrador'),
('all', 'All', 'Todo'),
('all_information_will_sent_then_will_contact', 'All your information will be sent and then we will contact you through it.', 'Toda su información será enviada y luego nos pondremos en contacto con usted mediante esta misma.'),
('all_rights_reserved', 'All rights reserved', 'Todos los derechos reservados'),
('already_have_account', 'Already have an account?', '¿Ya tienes una cuenta?'),
('already_logged_in', 'You are already logged in', 'Ya has iniciado sesión'),
('and', 'and', 'y'),
('and_was_set_to', 'and was set to', 'y fue configurada en'),
('applicant', 'Applicant', 'Solicitante'),
('approval_date', 'Approval date', 'Fecha de aprobación'),
('approved', 'Approved', 'Aprobado'),
('april', 'April', 'Abril'),
('area', 'Area', 'Area'),
('arent_trying_access', 'Aren\'t you the one trying to access?', '¿No eres tú quien intenta acceder?'),
('are_sure_want_make_registration', 'Are you sure you want to make this registration?', '¿Esta seguro de que desea realizar esta matricula?'),
('article', 'Article', 'Articulo'),
('articles_with_mandatory_parameters', 'Articles with mandatory parameters', 'Artículos con parámetros obligatorios'),
('assignment', 'Assignment', 'Asignación'),
('assignments', 'Assignments', 'Asignaciones'),
('assignment_created_successfully', 'Assignment created successfully', 'Asignación creada con éxito'),
('assignment_successfully_removed', 'Assignment successfully removed!', '¡Asignación eliminada con éxito!'),
('assignment_updated_successfully', 'Assignment updated successfully!', '¡Asignación actualizada con éxito!'),
('assign_teachers', 'Assign teachers', 'Asignar profesores'),
('august', 'August', 'Agosto'),
('authentication', 'Two-factor authentication', 'Autenticación de dos factores'),
('authorization', 'Authorization', 'Autorización'),
('authorizations', 'Authorizations', 'Autorizaciones'),
('authorization_answered_cannot_edit', 'Your authorization was answered, you cannot edit it', 'Tu autorizacion fue respondida, no puedes editarla'),
('authorization_dates', 'Authorization dates', 'Fechas realización habilitaciones'),
('authorization_must_expire_before_end_period', 'The authorization must expire before the end of the period', 'La autorización debe expirar antes del final del período'),
('authorization_must_expire_within_period', 'The authorization must expire within the period', 'La autorización debe expirar en el transcurso del periodo'),
('authorization_opening_date', 'Authorization opening date', 'Fecha apertura habilitaciones'),
('authorize', 'Authorize', 'Autorizar'),
('authorized', 'Authorized', 'Autorizado'),
('authorized_upload_qualification_note_course', 'You have just authorized to upload qualification note in the course', 'Acaba de autorizarte para subir nota de habilitación en el curso'),
('authorize_', 'Authorize', 'Autoriza'),
('average', 'Average', 'Promedio'),
('average_grade_n_n_present_final', 'Average grade (N1 + N2) / 2 to present final evaluation.', 'Nota promedio (N1+N2)/2 para presentar evaluación final.'),
('a_single_note', 'A single note', 'Una sola nota'),
('browser', 'Browser', 'Navegador'),
('by_cut', 'By cut', 'Por corte'),
('cancel', 'Cancel', 'Cancelar'),
('cancelled', 'cancelled', 'Cancelado'),
('can_enable_course_if_request_such_authorization', 'You can enable this course. If you request such authorization, your data will be sent and you will be contacted as soon as possible.', 'You can enable this course. If you request such authorization, your data will be sent and you will be contacted as soon as possible.'),
('can_only_change_age_once', 'You can only change your age once', 'Sólo puedes cambiar tu edad una vez'),
('can_upload_notes_however_before_uploading_notes', 'You can upload notes. However, before uploading notes you must first request an authorization in one of the courts', 'Usted puede subir notas. Sin embargo, antes de subir notas usted primero debe solicitar una autorización en uno de los cortes'),
('cell_phone', 'Cell phone', 'Celular'),
('change', 'Change', 'Cambiar'),
('change_password', 'Change Password', 'Cambiar contraseña'),
('change_photo', 'Change photo', 'Cambiar foto'),
('change_the_parameter', 'change the parameter', 'cambie el parámetro'),
('change_your_password', 'change your password', 'cambiar tu contraseña'),
('check_your_email', 'Check your email', 'Verifica tu correo'),
('check_your_new_email', 'Check your new email', 'Verifica tu nuevo correo electrónico'),
('cited_article', 'Cited article', 'Artículo citado'),
('close', 'Close', 'Cerrar'),
('code', 'Code', 'Código'),
('command', 'Command', 'Comando'),
('complete_names', 'Complete names', 'Nombres completos'),
('completion_of_activities', 'Completion of activities', 'Finalización de actividades'),
('confirmation', 'Confirmation!', '¡Confirmación!'),
('confirm_are_who_trying_enter', 'Confirm that you are the one who is trying to enter.', 'Confirma que eres tú quien intenta ingresar.'),
('confirm_code', 'Enter your confirmation code', 'Introduce tu código de confirmación'),
('confirm_new_password', 'Confirm new password', 'Confirmar contraseña nueva'),
('confirm_password', 'Confirm Password', 'Confirmar contraseña'),
('contact_our_helpdesk', 'contact our helpdesk.', 'ponte en contacto con nuestro servicio de asistencia.'),
('cookies_policy', 'Cookies policy', 'Política de cookies'),
('copyright', 'Copyright © {$year_now} {$settings->title}.', 'Copyright © {$year_now} {$settings->title}.'),
('course', 'Course', 'Curso'),
('courses', 'Courses', 'Cursos'),
('course_added_successfully', 'Course added successfully!', '¡Curso agregado con éxito!'),
('course_removed_successfully', 'Course removed successfully!', '¡Curso eliminado con éxito!'),
('course_updated_successfully', 'Course updated successfully!', '¡Curso actualizado con éxito!'),
('court', 'Court', 'Corte'),
('created', 'Created', 'Creado'),
('created_by', 'Created by', 'Creado por'),
('create_account', 'Create Account', 'Crear cuenta'),
('create_an_account', 'Create an account!', '¡Crea una cuenta!'),
('credit', 'Credit', 'Crédito'),
('credits', 'Credits', 'Créditos'),
('current_license_plates', 'Current license plates', 'Matrículas actuales'),
('current_password', 'Current password', 'Contraseña actual'),
('current_password_dont_match', 'Current password doesn\'t match.', 'La contraseña actual no coincide.'),
('curriculum', 'Curriculum', 'Malla curricular'),
('curriculum_mesh_successfully_removed', 'Curriculum mesh successfully removed!', '¡Malla curricular eliminada con éxito!'),
('curriculum_updated_successfully', 'Curriculum updated successfully!', '¡Facultad actualizada con éxito!'),
('cuts', 'Cuts', 'Cortes'),
('date', 'Date', 'Fecha'),
('dates', 'Dates', 'Fechas'),
('date_of_birth', 'Date of birth', 'Fecha de nacimiento'),
('day', 'day', 'día'),
('days', 'days', 'días'),
('daytime', 'Daytime', 'Diurna'),
('deactivate', 'Deactivate', 'Desactivar'),
('deactivated', 'Deactivated', 'Desactivada'),
('deactivate_this_account', 'Deactivate this account', 'Desactivar esta cuenta'),
('deadline_adding_academic_spaces', 'Deadline for adding academic spaces', 'Fecha límite adición de espacios académicos'),
('deadline_cancellation_academic_spaces', 'Deadline cancellation of academic spaces', 'Fecha límite cancelación de espacios académicos'),
('deadline_uploading_notes_already_passed', 'The deadline for uploading notes has already passed', 'El plazo para cargar notas ya se ha cumplido'),
('december', 'December', 'Diciembre'),
('definitive_minimum_mark_present', 'Definitive minimum mark to present qualification', 'Nota mínima definitiva para presentar habilitación'),
('delete', 'Delete', 'Eliminar'),
('denied', 'Denied', 'Denegado'),
('deny', 'Deny', 'Denegar'),
('description', 'Description', 'Descripción'),
('developed_by', 'This degree project was developed by:', 'Este proyecto de grado fue desarrollado por:'),
('developer_team', 'Developer team', 'Equipo desarrollador'),
('didnt_create_this_account', 'Didn\'t create this account?', '¿No creaste esta cuenta?'),
('did_want_reset_password', 'Do you want to reset your password?', '¿querías restablecer la contraseña?'),
('disabled', 'Disabled', 'Desactivado'),
('distance', 'Distance', 'Distancia'),
('document_already_exists', 'Este documento ya existe', 'This document already exists'),
('document_number', 'Document number', 'Número de documento'),
('does', 'ago', 'hace'),
('duration', 'Duration', 'Duración'),
('edit', 'Edit', 'Editar'),
('edit_academic_period', 'Edit academic period', 'Editar período académico'),
('edit_assignments', 'Edit assignments', 'Editar asignaciones'),
('edit_authorization', 'Edit authorization', 'Editar autorización'),
('edit_user', 'Edit user', 'Editar usuario'),
('email', 'Email', 'Correo'),
('email_address', 'E-mail address', 'Dirección de correo electrónico'),
('email_exists', 'This e-mail is already in use', 'Este correo electrónico ya está en uso'),
('email_invalid_characters', 'E-mail is invalid', 'El correo electrónico es invalido'),
('email_not_exist', 'E-mail not exist', 'Este correo no existe'),
('email_sent', 'E-mail sent successfully', 'Correo enviado correctamente'),
('enabled', 'Enabled', 'Activado'),
('enable_upgraded_successfully', 'Enable upgraded successfully!', '¡Habilitación actualizada con éxito!'),
('ending', 'Ending', 'Finalización'),
('enroll', 'Enroll', 'Matricular'),
('enrollment', 'Enrollment', 'Matrícula'),
('enroll_in_the_period', 'Enroll in the period:', 'Matricular en el periodo:'),
('enter_code', 'Enter code', 'Ingresar código'),
('enter_course_code', 'Enter the course code', 'Introduce el código del curso'),
('error', 'Oops! An error has occurred', '¡Ups! ha ocurrido un error'),
('error_occurred_to_send_mail', 'Oops an error occurred while trying to send the mail. Try it again later.', 'Ups ocurrió un error al intentar enviar el correo. Inténtelo de nuevo mas tarde.'),
('example', 'Example', 'Ejemplo'),
('expiration', 'Expiration', 'Expiración'),
('explanation', 'Explanation', 'Explicación'),
('extraordinary_academic_enrollment', 'Extraordinary academic enrollment', 'Matrícula académica extraordinaria'),
('extra_commands', 'Extra commands', 'Comandos extras'),
('faculties', 'Faculties', 'Facultades'),
('faculty', 'Faculty', 'Facultad'),
('faculty_added_successfully', 'Faculty added successfully!', '¡Facultad agregada con éxito!'),
('faculty_successfully_removed', 'Faculty successfully removed!', '¡Facultad eliminada con éxito!'),
('faculty_updated_successfully', 'Faculty updated successfully!', '¡Facultad actualizada con éxito!'),
('february', 'February', 'Febrero'),
('female', 'Female', 'Femenino'),
('file', 'File', 'Archivo'),
('file_deleted_successfully', 'File deleted successfully!', '¡Archivo eliminado con éxito!'),
('file_not_supported', 'Invalid file format', 'El formato del archivo no es válido'),
('final', 'Final', 'Final'),
('finalized', 'Finalized', 'Finalizada'),
('financial_tuition', 'Financial tuition', 'Matricula financiera'),
('first', 'First', 'Primero'),
('first_cut', 'First cut', 'Primer corte'),
('first_have_fill_information', 'First you have to fill in the information', 'Primero tiene que llenar la información'),
('follow', 'Follow', 'Seguir'),
('follow_us', 'Follow us', 'Síganos'),
('forgot_your_password', 'Forgot your password?', '¿Olvidaste tu contraseña?'),
('form', 'Form', 'Formulario'),
('forms', 'Forms', 'Formularios'),
('form_added_successfully', 'Form added successfully!', '¡Formulario agregado con éxito!'),
('form_successfully_removed', 'Form removed successfully!', '¡Formulario eliminado con éxito!'),
('form_updated_successfully', 'Form updated successfully!', '¡Formulario actualizada con éxito!'),
('from_engineering_with_love', 'From Computer Engineering with love', 'Desde Ingeniería Informática con amor'),
('from_the', 'From the', 'Desde el'),
('full_surnames', 'Full surnames', 'Apellidos completos'),
('gender', 'Gender', 'Género'),
('gender_is_invalid', 'Gender is invalid', 'Género no válido'),
('general', 'General', 'General'),
('go', 'Go', 'Ir'),
('got_your_password', 'Got your password?', '¿Tienes tu contraseña?'),
('go_to_website', 'Go to website', 'Ir al sitio web'),
('have_withdrawn_authorization_upload_qualification_note', 'You have just withdrawn your authorization to upload qualification note in the course', 'Acaba de retirar tu autorizacion para subir nota de habilitación en el curso'),
('hello', 'Hello {$name}!', '¡Hola {$name}!'),
('here', 'here', 'aquí'),
('here_save_notify', 'Your notifications are saved here', 'Aquí se guardan tus notificaciones'),
('hi', 'Hi', 'Hola'),
('home', 'Home', 'Inicio'),
('hour', 'hour', 'hora'),
('hours', 'hours', 'horas'),
('if_you_need_more_help', 'If you need more help,', 'Si necesitas más ayuda, '),
('if_you_want_add_course', 'If you want to add another course, you can do it here', 'Si desea agregar otro curso, puede hacerlo aquí'),
('if_you_want_add_faculty', 'If you want to add another faculty, you can do it here', 'Si desea agregar otra facultad, puede hacerlo aquí'),
('if_you_want_add_form', 'If you want to add another form, you can do it here', 'Si desea agregar otro formulario, puede hacerlo aquí'),
('if_you_want_add_period', 'If you want to add another period, you can do it here', 'Si desea agregar otro periodo, puede hacerlo aquí'),
('if_you_want_add_program', 'If you want to add another program, you can do it here', 'Si desea agregar otro programa, puede hacerlo aquí'),
('if_you_want_add_regulation', 'If you want to add another regulation, you can do it here', 'Si desea agregar otro reglamento, puede hacerlo aquí'),
('if_you_want_add_study_plan', 'If you want to add another study plan, you can do it here', 'Si desea agregar otro plan de estudio, puede hacerlo aquí'),
('if_you_want_add_user', 'If you want to add another user, you can do it here', 'Si desea agregar otro usuario, puede hacerlo aquí'),
('if_you_want_request_authorization', 'If you want to request another authorization, you can do it here', 'Si desea solicitar otra autorizacion, puedes hacerlo aquí'),
('important', 'Important!', '¡Importante!'),
('important_articles', 'Important articles', 'Artículos importantes'),
('instructions', 'Instructions', 'Instrucciones'),
('invalid_cell_phone', 'Invalid cell phone', 'Celular inválido'),
('invalid_dni', 'This document number does not exist.', 'Este número de documento no existe.'),
('invalid_document_characters', 'Invalid document characters', 'Caracteres del documento no válidos'),
('invalid_password', 'Password is incorrect.', 'Contraseña es incorrecta.'),
('invalid_phone', 'Invalid phone', 'Teléfono inválido'),
('invalid_request', 'Invalid request', 'Solicitud no válida'),
('in_progress', 'En progress', 'En curso'),
('ip_address', 'IP Address', 'Dirección IP'),
('ip_adress', 'IP adress', 'Dirección IP'),
('it_period_that_force', 'It is the period that is in force, so the teachers of this course will be assigned, in this period', 'es el periodo que se encuentra vigente, por lo que se asignaran los docentes de este curso, en este periodo'),
('january', 'January', 'Enero'),
('july', 'July', 'Julio'),
('june', 'June', 'Junio'),
('just_applied_authorization_in_the_course', 'You have just applied for an authorization in the course', 'Acaba de solicitar una autorización en el curso'),
('just_enrolled_in_your_course', 'Just enrolled in your course', 'Acaba de matricularse en tu curso'),
('just_ignore_this_message', 'just ignore this message', 'solamente ignora este mensaje'),
('just_uploaded_note_qualification_course', 'You just uploaded the note of your qualification in the course', 'Acaba de subir la nota de tu habilitación en el curso'),
('just_uploaded_your_grade_course', 'Just uploaded your grade in the course', 'Acaba de subir tu nota en el curso'),
('late_enrollment_deadline', 'Late enrollment deadline', 'Fecha límite de matrícula extemporánea'),
('legal', 'Legal', 'Legal'),
('let_us_know', 'let us know', 'avísanos'),
('level', 'Level', 'Nivel'),
('license_plates', 'License plates', 'Matrículas'),
('license_plates_of', 'License plates of', 'Matrículas de'),
('link', 'Link', 'Enlace'),
('login', 'Log In', 'Acceder'),
('logout', 'Log out', 'Cerrar sesión'),
('log_into_your_account', 'Log in into your account', 'Accede a tu cuenta'),
('log_out', 'Log out', 'Cerrar sesión'),
('male', 'Male', 'Masculino'),
('march', 'March', 'Marzo'),
('maximum_grade_from_university', 'Maximum grade from the university', 'Nota máxima de la universidad'),
('maximum_limit_validations_academic_space', 'Maximum limit of validations per academic space', 'Tope máximo de validaciones por espacio académico'),
('may', 'May', 'Mayo'),
('minimum_grade_theoretical_room', 'Minimum grade not theoretical room', 'Nota mínima habitación no teórica'),
('minimum_mark_must_be_equal_greater_than', 'The minimum grade to pass must be equal to or greater than', 'La nota mínima para aprobar debe ser igual o mayor que '),
('minimum_mark_third_cut', 'Minimum mark third cut', 'Nota mínima tercer corte'),
('minimum_validation_mark', 'Minimum validation mark', 'Nota mínima validación'),
('minute', 'minute', 'minuto'),
('minutes', 'minutes', 'minutos'),
('modality', 'Modality', 'Modalidad'),
('modified', 'Modified', 'Modificado'),
('modules', 'Modules', 'Módulos'),
('month', 'month', 'mes'),
('months', 'months', 'meses'),
('more', 'More', 'Más'),
('more_information', 'More information', 'Más información'),
('municipality', 'Municipality', 'Municipio'),
('must_be_less_completion', 'Must be less than completion', 'Debe ser menor a la finalización'),
('must_wait_previous_authorization_respond', 'You must wait for them to respond to your previous authorization', 'Debes esperar que den respuesta a tu autorización anterior'),
('name', 'Name', 'Nombre'),
('navigation', 'Navigation', 'Navegación'),
('new_password', 'New password', 'Nueva contraseña'),
('new_password_dont_match', 'New password doesn\'t match.', 'La nueva contraseña no coincide.'),
('new_regulation', 'New regulation', 'Nuevo Reglamento'),
('nightly', 'Nightly', 'Nocturna'),
('note', 'Note', 'Nota'),
('notes', 'Notes', 'Notas'),
('note_updated_successfully', 'Note updated successfully', 'Nota actualizada con éxito'),
('notifications', 'Notifications', 'Notificaciones'),
('not_create_account_associated_email', 'If you did not create this account associated with this email {$email}, please let us know so that we can take appropriate action to disable this account.', 'Si no creó esta cuenta asociada con este correo electrónico {$email}, háganoslo saber para que podamos tomar las medidas adecuadas para deshabilitar esta cuenta.'),
('november', 'November', 'Noviembre'),
('no_authorizations_found', 'No authorizations found', 'No se encontraron autorizaciones'),
('no_courses_found', 'No courses found', 'No se encontraron cursos'),
('no_faculties_found', 'No faculties found', 'No se encontraron facultades'),
('no_forms_found', 'No forms found', 'No se encontraron formularios'),
('no_license_plates_found', 'No license plates found', 'No se encontraron matrículas'),
('no_logins_found', 'No logins found', 'No se encontraron inicios de sesión'),
('no_notes_found', 'No notes found', 'No se encontraron notas'),
('no_notifications_one', 'You will receive a notification here each time one of your teachers uploads a grade in one of your enrolled courses.', 'Recibirás una notificación aquí cada vez que uno de tus docentes suba una nota en alguno de tus cursos matriculados.'),
('no_notifications_two', 'You will receive a notification here each time our students enroll in one of your courses.', 'Recibirás una notificación aquí cada vez que nuestros estudiantes se matriculen en uno de tus cursos.'),
('no_periods_found', 'No periods found', 'No se encontraron periodos'),
('no_programs_found', 'No programs found', 'No se encontraron programas'),
('no_qualifications_found', 'No qualifications found', 'No se encontraron habilitaciones'),
('no_regulations_found', 'No regulations found', 'No se encontraron reglamentos'),
('no_result_for', 'Sorry, no results found for', 'Lo sentimos, no se encontraron resultados para'),
('no_study_plans_found', 'No study plans found', 'No se encontraron planes de estudio'),
('no_users_found', 'No users found', 'No se encontraron usuarios'),
('number', 'Number', 'Número'),
('number_qualifications_academic_space_period', 'Number of qualifications of an academic space in the period', 'Numero de habilitaciones de un espacio académico en el periodo'),
('number_spaces_that_semester_fails', 'Number of spaces that semester fails', 'Cantidad espacios que reprueba semestre'),
('number_validations_loss', 'Number of validations for loss', 'Número de validaciones por pérdida'),
('number_validations_sufficiency', 'Number of validations for sufficiency', 'Número de validaciones por suficiencia'),
('observation', 'Observation', 'Observación'),
('october', 'October', 'Octubre'),
('of', 'of', 'de'),
('one_courses_already_assigned_curriculum', 'One of the courses is already assigned to a curriculum.', 'Uno de los cursos ya está asignado a un plan de estudios.'),
('one_deleted_courses_already_assignments', 'One of the deleted courses already has assignments', 'Uno de los cursos eliminado ya tiene asignaciones'),
('one_teacher_already_assigned_period', 'One of the teachers is already assigned for this period', 'Uno de los profesores ya está asignado para este periodo'),
('one_who_has_registered_this_account', 'If you are not the one who has registered this account', 'Si no has sido tú quien ha registrado esta cuenta'),
('operating_system', 'Operating system', 'Sistema operativo'),
('optional', 'Optional', 'Opcional'),
('ordinary_academic_registration', 'Ordinary academic registration', 'Matrícula académica ordinaria'),
('ordinary_enrollment_deadline', 'Ordinary enrollment deadline', 'Fecha límite de matrícula ordinaria'),
('parameter', 'Parameter', 'Parámetro'),
('parameters', 'Parameters', 'Parámetros'),
('password', 'Password', 'Contraseña'),
('password_is_short', 'Password is too short', 'La contraseña es demasiado corta'),
('password_not_match', 'Password not match', 'La contraseña no coincide'),
('pending', 'Pending', 'Pendiente'),
('percentage', 'Percentage', 'Porcentaje'),
('period', 'Period', 'Periodo'),
('periods', 'Periods', 'Periodos'),
('period_added_successfully', 'Period added successfully!', '¡Período agregado exitosamente!'),
('period_has_already_been_assigned', 'This period has already been assigned, I cannot deactivate it', 'Este periodo ya fue asignado, no puedo desactivarlo'),
('period_removed_successfully', 'Period removed successfully!', '¡Período eliminado con éxito!'),
('period_updated_successfully', 'Period updated successfully!', '¡Período actualizado con éxito!'),
('phone', 'Phone', 'Teléfono'),
('please_complete_information_before_sending', 'Please, complete the information before sending it', 'Por favor, completa la información antes de enviarla'),
('please_complete_the_information', 'Please, complete the information', 'Por favor, completa la informacion'),
('please_enter_code_this_course', 'Please enter the code for this course', 'Por favor, introduce el codigo de este curso'),
('please_enter_valid_date', 'Enter a valid date', 'Introduce una fecha válida'),
('please_enter_valid_value', 'Enter a valid value', 'Introduce un valor valido'),
('please_see_instructions_fill', 'Please see the instructions to fill out the regulations before editing or creating a new one, you can see the instructions', 'Por favor, vea las instrucciones para diligenciar los reglamentos antes de editar o crear uno nuevo, puede ver las instrucciones'),
('please_select_academic_period', 'Please select an academic period', 'Por favor, seleccione un periodo académico'),
('please_wait', 'Please wait..', 'Por favor espera..'),
('practice', 'Practice', 'Practica'),
('presential', 'Presential', 'Presencial'),
('previous_example_you_indicating', 'in the previous example you are indicating that the average grade of the first and second cut to be able to present the final evaluation is 2.0. Instead of entering the number, you go to the corresponding article and enter the parameter already filled in, as shown in the example marked in green.', 'en el ejemplo anterior usted esta indicando que, la nota promedio del primer y segundo corte para poder presentar la evaluación final es de 2.0. En lugar de colocar el numero, usted va al articulo correspondiente y coloca el parámetro ya diligenciado, como se muestra en el ejemplo marcado en verde.'),
('previous_regulation_will_deleted', 'The previous regulation will be deleted, do you want to continue?', 'Se eliminará el reglamento anterior, ¿desea continuar?'),
('pre_knowledge', 'Pre-knowledge', 'Presaberes'),
('privacy_policy', 'Privacy Policy', 'Política de privacidad'),
('profile_picture', 'Profile picture', 'Foto de perfil'),
('program', 'Program', 'Programa'),
('programs', 'Programs', 'Programas'),
('program_added_successfully', 'Successfully added program!', '¡Programa agregado exitosamente!'),
('program_removed_successfully', 'Program removed successfully!', '¡Programa eliminado con éxito!'),
('program_updated_successfully', 'Successfully updated program!', '¡Programa actualizado con éxito!'),
('prompt_payment_deadline', 'Prompt payment deadline', 'Fecha límite de pronto pago'),
('province', 'Province', 'Departamento'),
('qualification', 'Qualification', 'Habilitación'),
('qualifications', 'Qualifications', 'Habilitaciones'),
('quota', 'Quota', 'Cupo'),
('quotas', 'Quotas', 'Cupos'),
('recaptcha_error', 'Please Check the re-captcha.', 'Por favor, compruebe la re-captcha.'),
('redirection_notice', 'Redirection notice', 'Aviso de redirección'),
('registered', 'Registered', 'Matriculado'),
('register_better_university_experience', 'Register and have a better university experience', 'Regístrate y vive una mejor experiencia universitaria'),
('registration_canceled_successfully', 'Registration canceled successfully!', '¡Matricula cancelada con éxito!'),
('registration_deadline_ratings_notes', 'Registration deadline for ratings notes', 'Fecha límite registro notas habilitaciones'),
('registration_format_legalization_deadline', 'Registration format legalization deadline', 'Fecha límite legalización formato de matrícula'),
('registration_forms', 'Registration forms', 'Formularios de registro'),
('regulation', 'Regulation', 'Reglamento'),
('regulation_created_successfully', 'Regulation created successfully!', '¡Reglamento creado con éxito!'),
('regulation_its_original_language', 'This regulation is in its original language', 'Este reglamento se encuentra en su idioma original'),
('regulation_removed_successfully', 'Regulation removed successfully!', '¡Reglamento eliminado con éxito!'),
('regulation_updated_successfully', 'Regulation updated successfully!', '¡Reglamento actualizado con éxito!'),
('rejected', 'Rejected', 'Rechazada'),
('rejected_request_qualify_course', 'Rejected your request to qualify for the course', 'Acaba de rechazar tu solicitud de habilitación para el curso'),
('repeated_parameters_unique', 'is repeated, the parameters are unique. You cannot repeat them.', 'se repite, los parámetros son únicos. No puede repetirlos.'),
('reprobate', 'Reprobate', 'Reprobado'),
('request', 'Request', 'Solicitar'),
('requested', 'Requested', 'Solicitada'),
('request_an_authorization', 'Request an authorization', 'Solicitar una autorización'),
('request_new_password', 'Send an e-mail', 'Enviar correo electrónico'),
('request_not_found', 'Request not found', 'Solicitud no encontrada'),
('request_sent_successfully', 'Request sent successfully!', '¡Solicitud enviada con éxito!'),
('resend_code', 'Resend code', 'Reenviar código'),
('resend_email', 'Resend E-mail', 'Reenviar email'),
('reset', 'Reset', 'Resetear'),
('reset_your_password', 'Reset your password', 'Restablece tu contraseña'),
('resolution', 'Resolution', 'Resolución'),
('return_to', 'Return to home', 'Regresar al inicio'),
('role', 'Role', 'Rol'),
('rules', 'Rules', 'Reglas'),
('save', 'Save', 'Guardar'),
('search', 'Search', 'Buscar'),
('second', 'second', 'segundo'),
('seconds', 'seconds', 'segundos'),
('second_cut', 'Second cut', 'Segundo corte'),
('security', 'Security', 'Seguridad'),
('see_full_regulation', 'See full regulation', 'Ver reglamento completo'),
('select', 'Select', 'Seleccionar'),
('select_a_cut', 'Select a cut', 'Seleccionar un corte'),
('select_a_period', 'Select a period', 'Seleccionar un periodo'),
('select_course', 'Select course', 'Seleccionar curso'),
('select_state', 'Select state', 'Seleccionar estado'),
('semester', 'Semester', 'Semestre'),
('semesters', 'Semesters', 'Semestres'),
('semester_average', 'Semester average', 'Promedio semestral'),
('september', 'September', 'Septiembre'),
('settings', 'Settings', 'Ajustes'),
('setting_updated', 'Updated configuration!', '¡Configuración actualizada!'),
('sign_up', 'Sign up', 'Registrarse'),
('snies_code', 'Snies code', 'Código snies'),
('someone_has_reset_password', 'Someone (hopefully you) has asked us to reset your {$settings->title} account password. Click the button below to do so. If you did not request to reset your password, you can ignore this message.', 'Alguien (esperemos que tú) nos ha solicitado restablecer la contraseña de tu cuenta de {$settings->title}. Haz clic en el botón siguiente para hacerlo. Si no solicitaste restablecer la contraseña, puedes ignorar este mensaje.'),
('start', 'Start', 'Comienzo'),
('status', 'Status', 'Estado'),
('student', 'Student', 'Estudiante'),
('study_plan', 'Study plan', 'Plan de estudio'),
('study_plans', 'Study plans', 'Planes de estudio'),
('study_plan_added_successfully', 'Study plan successfully added!', '¡Plan de estudio agregado exitosamente!'),
('study_plan_removed_successfully', 'Study plan successfully removed!', '¡Plan estudio eliminado con éxito!'),
('study_plan_updated_successfully', 'Study plan updated successfully!', '¡Plan de estudio actualizado con éxito!'),
('successfully_joined_desc', 'Registration successful! We have sent you an email, Please check your inbox/spam to verify your account.', '¡Registro exitoso! Te hemos enviado un correo electrónico, verifica tu bandeja de entrada o spam para verificar tu cuenta.'),
('sum_these_values_must_be', 'The sum of these values must be 100', 'La suma de estos valores debe ser 100'),
('sure_you_wan_log_out', 'Are you sure you want to log out of this device?', '¿Está seguro que desea cerrar sesión en este dispositivo?'),
('teacher', 'Teacher', 'Profesor'),
('teacher_already_assigned', 'One of the teachers is already assigned', 'Uno de los docentes ya está asignado'),
('teacher_tutor_change_dates', 'Teacher-tutor change dates', 'Fechas cambio docente-tutor'),
('technique', 'Technique', 'Tecnica'),
('technologist', 'Technologist', 'Tecnóloga'),
('terms_agreement', 'By creating your account, you agree to our', 'Al crear su cuenta, usted acepta nuestra'),
('terms_of_use', 'Terms of use', 'Términos de uso'),
('thanks_for_letting_us_know', 'Thanks for letting us know. We have disabled the account.', 'Gracias por informarnos. Hemos deshabilitado la cuenta.'),
('theoretical', 'Theoretical', 'Teórica'),
('theoretical_qualification_minimum_mark', 'Theoretical qualification minimum mark', 'Nota mínima habilitación teórica'),
('there_already_active_period', 'There is already an active period', 'Ya existe un período activo'),
('there_already_authorization_court_period', 'There is already an authorization in this court for the period', 'Ya existe una autorización en este corte para el periodo'),
('there_already_period_these_dates', 'There is already a period on these dates', 'Ya existe un periodo en estas fechas'),
('there_already_registration_this_period', 'There is already a registration for this period', 'Ya existe una matrícula para este periodo'),
('there_error_uploading_file', 'There was an error uploading the file', 'Hubo un error al subir el archivo'),
('there_no_active_period_moment', 'There is no active period at the moment', 'No hay ningún período activo en estos momentos'),
('there_no_seats_available_this_course', 'There are no seats available for this course', 'No hay cupos disponibles para este curso'),
('there_problems_with_some_fields', 'There are problems with some fields', 'Hay problemas con algunos campos'),
('they_answered_request_authorization_in', 'They answered your request for authorization in #REPLACE#!', '¡Respondieron tu solicitud de habilitación en #REPLACE#!'),
('they_asked_authorization_in', 'They asked you for authorization in #REPLACE#!', '¡Te solicitaron habilitación en #REPLACE#!'),
('they_canceled_authorization_in', 'They canceled your authorization in #REPLACE#!', '¡Cancelaron tu autorización en #REPLACE#!'),
('they_enrolled_one_courses', 'They enrolled in one of your courses', 'Se matricularon en uno de tus cursos de'),
('they_responded_request_in', 'They responded to your request in #REPLACE#!', '¡Respondieron a tu solicitud en #REPLACE#!'),
('they_uploaded_note_qualification_in', 'They uploaded the note of your qualification in #REPLACE#!', '¡Subieron la nota de tu habilitación en #REPLACE#!'),
('they_uploaded_your_note_in', 'They uploaded your note in #REPLACE#!', '¡Subieron tu nota en #REPLACE#!'),
('the_account_been_deactivated', 'The account has been deactivated.', 'La cuenta ha sido desactivada.'),
('the_field_create_edit_regulation_accepts', 'The field to create or edit a regulation accepts the use of HTML tags, you can use them.', 'El campo para crear o editar un reglamento acepta el uso de etiquetas HTML, puede usarlas.'),
('the_maximum_grade_is', 'The maximum grade is 5', 'La nota máxima es 5'),
('the_operation_canceled_connection_interrupted', 'The operation was canceled or the connection was interrupted', 'La operación se canceló o la conexión fue interrumpida'),
('the_parameter', 'The parameter', 'El parámetro'),
('third', 'Third', 'Tercero'),
('third_cut', 'Third cut', 'Tercer corte'),
('this_action_cannot_undone_continue', 'This action cannot be undone, do you want to continue?', 'Esta acción no se puede deshacer, ¿Desea continuar?'),
('this_currently_active_calendar_which', 'This is the currently active calendar, which may or may not be current.', 'Este es el calendario que se encuentra activo, el cual puede o no estar vigente.'),
('this_field_captures_all_text_referring', 'This field captures all the text referring to the regulation, as you place it. However, there are some parameters that should be used in some articles (if necessary for that article), these and the parameters can be found here along with the instructions.', 'Este campo captura todo el texto referente al reglamento, tal y como usted lo coloca. Sin embargo, existen algunos parámetros que deben utilizarse en algunos artículos (si es necesario para dicho artículo), estos mismos y los parámetros los encuentra aquí junto a las instrucciones.'),
('this_field_is_empty', 'This field is empty', 'Este campo está vacío'),
('this_is_my_account', 'This is my account', 'Esta es mi cuenta'),
('this_period_already_exists', 'This period already exists', 'Este período ya existe'),
('this_period_not_valid_but_can_be_assigned', 'This period is not valid, but can be assigned.', 'Este periodo no se encuentra vigente, pero puede ser asignado.'),
('title_awarded', 'Title awarded', 'Titulo otorgado'),
('total_average', 'Total average', 'Promedio total'),
('type', 'Type', 'Tipo'),
('undefined', 'Undefined', 'Sin definir'),
('undergraduate', 'Undergraduate', 'Pregrado'),
('unique', 'Unique', 'Unico'),
('unique_cut', 'Unique cut', 'Corte único'),
('until_the', 'until the', 'hasta el'),
('unverified', 'Unverified', 'Sin verificar'),
('upload_note', 'Upload note', 'Subir nota'),
('used_specify_item', 'Used to specify an item', 'Sirve para especificar un artículo'),
('used_specify_number_item', 'Used to specify the number of an item', 'Sirve para especificar el número de un artículo'),
('user', 'User', 'Usuario'),
('users', 'Users', 'Usuarios'),
('user_updated_successfully', 'Successfully updated user!', '¡Usuario actualizado con éxito!'),
('verify_your_account', 'Verify your account', 'Verifica tu cuenta'),
('virtual', 'Virtual', 'Virtual'),
('was_denied', 'was denied', 'fue denegada'),
('watch', 'Watch', 'Ver'),
('were_asked_permission_upload_grade_in', 'You were asked for permission to upload a grade in #REPLACE#!', '¡Te solicitaron autorización para subir nota en #REPLACE#!'),
('were_authorized_upload_note_in', 'You were authorized to upload a note in #REPLACE#!', '¡Te autorizaron para subir nota en #REPLACE#!'),
('we_have_sent_code', 'We have sent a 6 digit code to your email', 'Hemos enviado un código de 6 dígitos a su correo electrónico'),
('we_recommend_you', 'we recommend you', 'te recomendamos'),
('without_registering', 'without_registering', 'Sin matricular'),
('working_day', 'Working day', 'Jornada'),
('wrong_code', 'Wrong code', 'Código equivocado'),
('wrong_confirm_code', 'Wrong confirmation code', 'Código de confirmación incorrecto'),
('year', 'Year', 'Año'),
('years', 'Years', 'Años'),
('your_authorization_denied_upload_grades_course_denied', 'Your authorization was denied to upload grades in the course of', 'Su autorización fue denegada para subir notas en el curso de'),
('your_email_address', 'Enter your email address.', 'Introduce tu dirección de correo electrónico.'),
('your_old_photo_will_deleted', 'Your old photo will be deleted, do you want to continue?', 'Se eliminará su foto anterior, ¿desea continuar?'),
('you_authorized_upload_grades_course', 'You are not authorized to upload grades in this course', 'Usted no está autorizado para subir notas en este curso'),
('you_authorized_upload_grades_course_of', 'You were authorized to upload grades in the course of', 'Usted fue autorizado para subir notas en el curso de'),
('you_cannot_delete', 'It is not possible to delete. This item already has assignments', 'No es posible eliminar. Este elemento ya tiene asignaciones'),
('you_cant_upload_grades_because_teacher', 'You can\'t upload grades because the teacher hasn\'t set any parameters yet.', 'Usted no puede subir calificaciones porque el docente no ha configurado ningún parámetro todavía.'),
('you_cant_upload_ratings_yet', 'You can\'t upload ratings yet, please first set the parameters by clicking', 'Usted no puede subir calificaciones todavía, por favor primero, configure los parámetros haciendo clic'),
('you_do_not_have_access_this_form', 'You do not have access to this form', 'Usted no tiene acceso a este formulario'),
('you_have_authorized_teacher_successfully', 'You have authorized this teacher successfully!', '¡Has autorizado a este maestro con éxito!'),
('you_have_just_applied_course', 'You have just applied for the course', 'Acaba de solicitar habilitación para el curso'),
('you_have_successfully_enrolled', 'You have successfully enrolled!', '¡Te has matriculado con éxito!'),
('you_missed_more_subjects', 'You missed 4 or more subjects', 'Perdiste más 4 o más asignaturas'),
('you_must_first_approve_prequalifications', 'You must first approve your pre-qualifications', 'Primero debes aprobar tus presaberes'),
('you_will_redirected_verify_email_continue', 'You will be redirected to verify your email, do you want to continue?', 'Serás redireccionado para verificar tu correo, ¿Deseas seguir?'),
('you_write_verification_code', 'You can also write this verification code:', 'También puedes escribir este código de verificación:');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `authorization`
--
ALTER TABLE `authorization`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `period_id` (`period_id`);

--
-- Indices de la tabla `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `curriculum`
--
ALTER TABLE `curriculum`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indices de la tabla `enrolled`
--
ALTER TABLE `enrolled`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `period_id` (`period_id`);

--
-- Indices de la tabla `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `form`
--
ALTER TABLE `form`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `period_id` (`period_id`);

--
-- Indices de la tabla `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `to_id` (`to_id`) USING BTREE,
  ADD KEY `from_id` (`from_id`) USING BTREE,
  ADD KEY `course_id` (`course_id`);

--
-- Indices de la tabla `page`
--
ALTER TABLE `page`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `parameter`
--
ALTER TABLE `parameter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `period`
--
ALTER TABLE `period`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `plan`
--
ALTER TABLE `plan`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faculty_id` (`faculty_id`);

--
-- Indices de la tabla `qualification`
--
ALTER TABLE `qualification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `note_id` (`note_id`);

--
-- Indices de la tabla `rule`
--
ALTER TABLE `rule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`name`);

--
-- Indices de la tabla `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `period_id` (`period_id`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `word`
--
ALTER TABLE `word`
  ADD PRIMARY KEY (`word`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `authorization`
--
ALTER TABLE `authorization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de la tabla `curriculum`
--
ALTER TABLE `curriculum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `enrolled`
--
ALTER TABLE `enrolled`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de la tabla `faculty`
--
ALTER TABLE `faculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `form`
--
ALTER TABLE `form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `note`
--
ALTER TABLE `note`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `page`
--
ALTER TABLE `page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `parameter`
--
ALTER TABLE `parameter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `period`
--
ALTER TABLE `period`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `plan`
--
ALTER TABLE `plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `program`
--
ALTER TABLE `program`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `qualification`
--
ALTER TABLE `qualification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `rule`
--
ALTER TABLE `rule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `session`
--
ALTER TABLE `session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=794;

--
-- AUTO_INCREMENT de la tabla `teacher`
--
ALTER TABLE `teacher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `session`
--
ALTER TABLE `session`
  ADD CONSTRAINT `session_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
