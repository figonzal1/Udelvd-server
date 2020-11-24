CREATE DATABASE IF NOT EXISTS `udelvd`
/*!40100 DEFAULT CHARACTER SET utf8 */
/*!80016 DEFAULT ENCRYPTION='N' */
;

USE `udelvd`;

-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: 200.1.24.48    Database: udelvd
-- ------------------------------------------------------
-- Server version	8.0.21
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;

/*!50503 SET NAMES utf8 */
;

/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */
;

/*!40103 SET TIME_ZONE='+00:00' */
;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */
;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */
;

/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */
;

/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */
;

--
-- Table structure for table `accion`
--
DROP TABLE IF EXISTS `accion`;

/*!40101 SET @saved_cs_client     = @@character_set_client */
;

/*!50503 SET character_set_client = utf8mb4 */
;

CREATE TABLE `accion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_es` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `nombre_en` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_es_UNIQUE` (`nombre_es`),
  UNIQUE KEY `nombre_en_UNIQUE` (`nombre_en`)
) ENGINE = InnoDB AUTO_INCREMENT = 49 DEFAULT CHARSET = utf8;

/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Dumping data for table `accion`
--
LOCK TABLES `accion` WRITE;

/*!40000 ALTER TABLE `accion` DISABLE KEYS */
;

INSERT INTO
  `accion`
VALUES
  (
    1,
    'Levantarse de la cama',
    'Wake up',
    '2020-08-27 20:02:13',
    NULL
  ),
  (
    2,
    'Bañarse',
    'Bathe',
    '2020-08-27 20:06:34',
    NULL
  ),
  (
    3,
    'Ir al baño',
    'Go to the bathroom',
    '2020-08-27 20:06:34',
    NULL
  ),
  (
    4,
    'Cortarse las uñas',
    'Cutting the nails',
    '2020-08-27 20:06:34',
    NULL
  ),
  (
    5,
    'Vestirse',
    'Get dressed',
    '2020-08-27 20:06:34',
    NULL
  ),
  (
    6,
    'Desvestirse',
    'Undress',
    '2020-08-27 20:06:34',
    NULL
  ),
  (
    7,
    'Ponerse ropa para dormir',
    'Put on sleepwear',
    '2020-08-27 20:06:34',
    NULL
  ),
  (
    8,
    'Acostarse',
    'Go to bed',
    '2020-08-27 20:07:11',
    NULL
  ),
  (
    9,
    'Quedarse dormido',
    'Fall asleep',
    '2020-08-27 20:07:11',
    NULL
  ),
  (
    10,
    'Cocinar',
    'Cook',
    '2020-08-27 20:07:11',
    NULL
  ),
  (11, 'Comer', 'Eat', '2020-08-27 20:08:35', NULL),
  (
    12,
    'Tender/Hacer la cama',
    'To make the bed',
    '2020-08-27 20:08:35',
    NULL
  ),
  (
    13,
    'Lavar loza',
    'Wash crockery',
    '2020-08-27 20:08:35',
    NULL
  ),
  (
    14,
    'Levantar la mesa',
    'Clear the table',
    '2020-08-27 20:08:35',
    NULL
  ),
  (
    15,
    'Lavar ropa',
    'Wash clothes',
    '2020-08-27 20:09:24',
    NULL
  ),
  (
    16,
    'Planchar ropa',
    'Iron clothes',
    '2020-08-27 20:09:24',
    NULL
  ),
  (
    17,
    'Hacer aseo superficial',
    'Superficial cleaning',
    '2020-08-27 20:09:24',
    NULL
  ),
  (
    18,
    'Hacer aseo profundo',
    'Deep cleaning',
    '2020-08-27 20:09:24',
    NULL
  ),
  (
    19,
    'Sacar la basura',
    'Take out the trash',
    '2020-08-27 20:10:26',
    NULL
  ),
  (
    20,
    'Ir a comprar a negocios dentro del barrio',
    'Go shopping in the neighborhood',
    '2020-08-27 20:10:26',
    NULL
  ),
  (
    21,
    'Ir a comprar a negocios en otra parte de la ciudad',
    'Go shopping in another part of the city',
    '2020-08-27 20:10:26',
    NULL
  ),
  (
    22,
    'Salir a caminar en el barrio',
    'Go for a walk in the neighborhood',
    '2020-08-27 20:10:26',
    NULL
  ),
  (
    23,
    'Salir a caminar a otra parte de la ciudad',
    'Go for a walk to another part of the city',
    '2020-08-27 20:10:26',
    NULL
  ),
  (
    24,
    'Viajar en colectivo o taxi',
    'Travel by taxi',
    '2020-08-27 20:11:05',
    NULL
  ),
  (
    25,
    'Viajar en microbus',
    'Travel by bus',
    '2020-08-27 20:11:05',
    NULL
  ),
  (
    26,
    'Conducir un automóvil',
    'Driving car',
    '2020-08-27 20:11:05',
    NULL
  ),
  (
    27,
    'Realizar trabajo remunerado',
    'Perform paid work',
    '2020-08-27 20:11:33',
    NULL
  ),
  (
    28,
    'Recibir visitas en casa',
    'Receive visits at home',
    '2020-08-27 20:11:33',
    NULL
  ),
  (
    29,
    'Hacer visitas a otras personas a sus casas',
    'Make visits to other people to their homes',
    '2020-08-27 20:12:13',
    NULL
  ),
  (
    30,
    'Hablar por teléfono',
    'Talking on the phone',
    '2020-08-27 20:12:13',
    NULL
  ),
  (
    31,
    'Chatear por redes sociales',
    'Chat on social networks',
    '2020-08-27 20:12:13',
    NULL
  ),
  (
    32,
    'Ver televisión',
    'Watch TV',
    '2020-08-27 20:12:26',
    NULL
  ),
  (33, 'Leer', 'Read', '2020-08-27 20:12:51', NULL),
  (
    34,
    'Navegar por Internet',
    'Browse the Internet',
    '2020-08-27 20:12:51',
    NULL
  ),
  (
    35,
    'Realizar mantenciones en la casa',
    'Perform maintenance in the house',
    '2020-08-27 20:13:33',
    NULL
  ),
  (
    36,
    'Cuidar el jardín',
    'Take care of the garden',
    '2020-08-27 20:13:33',
    NULL
  ),
  (
    37,
    'Pasatiempo de esfuerzo físico',
    'Physical effort hobby',
    '2020-08-27 20:13:33',
    NULL
  ),
  (
    38,
    'Pasatiempo de actividades manuales',
    'Manual activity hobby',
    '2020-08-27 20:13:33',
    NULL
  ),
  (
    39,
    'Pasatiempo de esfuerzo mental',
    'Mental effort hobby',
    '2020-08-27 20:13:47',
    NULL
  ),
  (
    40,
    'Ir a ceremonias religiosas',
    'Go to religious ceremonies',
    '2020-08-27 20:14:29',
    NULL
  ),
  (
    41,
    'Tomar medicamentos',
    'Taking medications',
    '2020-08-27 20:14:29',
    NULL
  ),
  (
    42,
    'Chequeos de salud',
    'Health checks',
    '2020-08-27 20:14:29',
    NULL
  ),
  (
    43,
    'Ir a consulta médica',
    'Go to medical consultation',
    '2020-08-27 20:14:29',
    NULL
  ),
  (
    44,
    'Cuidar a algún familiar enfermo',
    'Caring for a sick relative',
    '2020-08-27 20:15:23',
    NULL
  ),
  (
    45,
    'Cuidar niños',
    'Babysit',
    '2020-08-27 20:15:23',
    NULL
  ),
  (
    46,
    'Atender familiar que no es niño ni enfermo',
    'Take care of a family member who is not a child or a sick person',
    '2020-08-27 20:15:23',
    NULL
  ),
  (
    47,
    'Realizar actividades sociales',
    'Carry out social activities',
    '2020-08-27 20:15:23',
    NULL
  ),
  (
    48,
    'Atender mascotas',
    'Attend to pets',
    '2020-08-27 20:15:23',
    NULL
  );

/*!40000 ALTER TABLE `accion` ENABLE KEYS */
;

UNLOCK TABLES;

--
-- Table structure for table `ciudad`
--
DROP TABLE IF EXISTS `ciudad`;

/*!40101 SET @saved_cs_client     = @@character_set_client */
;

/*!50503 SET character_set_client = utf8mb4 */
;

CREATE TABLE `ciudad` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE = InnoDB AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8;

/*!40101 SET character_set_client = @saved_cs_client */
;

LOCK TABLES `ciudad` WRITE;

INSERT INTO
  `ciudad`
VALUES
  (1, 'Arica'),
  (2, 'Iquique'),
  (3, 'Tocopilla'),
  (4, 'Antofagasta'),
  (5, 'Copiapó'),
  (6, 'Vallenar'),
  (7, 'La Serena'),
  (8, 'Andacollo'),
  (9, 'Coquimbo'),
  (10, 'Los Vilos'),
  (11, 'Valparaíso'),
  (12, 'Viña del Mar'),
  (13, 'Rancagua'),
  (14, 'Santiago'),
  (15, 'Concepción'),
  (16, 'Penco'),
  (17, 'Tomé'),
  (18, 'Pitrufquén'),
  (19, 'Valdivia'),
  (20, 'Osorno'),
  (21, 'Victoria'),
  (22, 'Talcahuano'),
  (23, 'New York'),
  (24, 'Brasilia'),
  (25, 'Chicago'),
  (26, 'Seattle'),
  (27, 'Boston'),
  (28, 'Dallas'),
  (29, 'Sao Paulo'),
  (30, 'Rio de Janeiro');

UNLOCK TABLES;

--
-- Table structure for table `emoticon`
--
DROP TABLE IF EXISTS `emoticon`;

/*!40101 SET @saved_cs_client     = @@character_set_client */
;

/*!50503 SET character_set_client = utf8mb4 */
;

CREATE TABLE `emoticon` (
  `id` int NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `descripcion_es` varchar(255) NOT NULL,
  `descripcion_en` varchar(255) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 5 DEFAULT CHARSET = utf8;

/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Dumping data for table `emoticon`
--
LOCK TABLES `emoticon` WRITE;

/*!40000 ALTER TABLE `emoticon` DISABLE KEYS */
;

INSERT INTO
  `emoticon`
VALUES
  (
    1,
    'https://undiaenlavidade.cl/img/smiley.png',
    'Emoticon que describe felicidad',
    'Emoticon that describes happiness',
    '2020-08-27 20:16:14',
    NULL
  ),
  (
    2,
    'https://undiaenlavidade.cl/img/sad.png',
    'Emoticon que describe tristeza',
    'Emoticon that describes sadness',
    '2020-08-27 20:17:44',
    NULL
  ),
  (
    3,
    'https://undiaenlavidade.cl/img/afraid.png',
    'Emoticon que describe miedo',
    'Emoticon that describes fear',
    '2020-08-27 20:17:44',
    NULL
  ),
  (
    4,
    'https://undiaenlavidade.cl/img/angry.png',
    'Emoticon que describe enojo',
    'Emoticon that describes anger',
    '2020-08-27 20:17:44',
    NULL
  );

/*!40000 ALTER TABLE `emoticon` ENABLE KEYS */
;

UNLOCK TABLES;

--
-- Table structure for table `entrevista`
--
DROP TABLE IF EXISTS `entrevista`;

/*!40101 SET @saved_cs_client     = @@character_set_client */
;

/*!50503 SET character_set_client = utf8mb4 */
;

CREATE TABLE `entrevista` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_entrevistado` int DEFAULT NULL,
  `id_tipo_entrevista` int NOT NULL,
  `fecha_entrevista` date NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `foreign_id_entrevistado_idx` (`id_entrevistado`),
  KEY `foreign_tipo_entrevista_idx` (`id_tipo_entrevista`),
  CONSTRAINT `foreign_id_entrevistado` FOREIGN KEY (`id_entrevistado`) REFERENCES `entrevistado` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `foreign_tipo_entrevista` FOREIGN KEY (`id_tipo_entrevista`) REFERENCES `tipo_entrevista` (`id`) ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8;

/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Table structure for table `entrevistado`
--
DROP TABLE IF EXISTS `entrevistado`;

/*!40101 SET @saved_cs_client     = @@character_set_client */
;

/*!50503 SET character_set_client = utf8mb4 */
;

CREATE TABLE `entrevistado` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `apellido` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `sexo` varchar(40) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `jubilado_legal` tinyint(1) NOT NULL,
  `caidas` tinyint(1) NOT NULL,
  `n_caidas` int DEFAULT NULL,
  `n_convivientes_3_meses` int NOT NULL,
  `id_investigador` int NOT NULL,
  `id_ciudad` int NOT NULL,
  `id_nivel_educacional` int DEFAULT NULL,
  `id_estado_civil` int NOT NULL,
  `id_tipo_convivencia` int DEFAULT NULL,
  `id_profesion` int DEFAULT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `foreign_id_investigador_idx` (`id_investigador`),
  KEY `foreign_id_nivel_educacional_idx` (`id_nivel_educacional`),
  KEY `foreign_id_estado_civil_idx` (`id_estado_civil`),
  KEY `foreign_id_tipo_convivencia_idx` (`id_tipo_convivencia`),
  KEY `foreign_id_oficio_idx` (`id_profesion`),
  KEY `foreign_id_ciudad_idx` (`id_ciudad`),
  CONSTRAINT `foreign_id_ciudad` FOREIGN KEY (`id_ciudad`) REFERENCES `ciudad` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `foreign_id_estado_civil` FOREIGN KEY (`id_estado_civil`) REFERENCES `estado_civil` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `foreign_id_investigador` FOREIGN KEY (`id_investigador`) REFERENCES `investigador` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `foreign_id_nivel_educacional` FOREIGN KEY (`id_nivel_educacional`) REFERENCES `nivel_educacional` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `foreign_id_profesion` FOREIGN KEY (`id_profesion`) REFERENCES `profesion` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `foreign_id_tipo_conviviente` FOREIGN KEY (`id_tipo_convivencia`) REFERENCES `tipo_convivencia` (`id`) ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8;

/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Table structure for table `estadisticas`
--
DROP TABLE IF EXISTS `estadisticas`;

/*!40101 SET @saved_cs_client     = @@character_set_client */
;

/*!50503 SET character_set_client = utf8mb4 */
;

CREATE TABLE `estadisticas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `nombre_es` varchar(255) NOT NULL,
  `nombre_en` varchar(255) NOT NULL,
  `pin_pass` varchar(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_UNIQUE` (`url`)
) ENGINE = InnoDB AUTO_INCREMENT = 3 DEFAULT CHARSET = utf8;

/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Dumping data for table `estadisticas`
--
LOCK TABLES `estadisticas` WRITE;

/*!40000 ALTER TABLE `estadisticas` DISABLE KEYS */
;

INSERT INTO
  `estadisticas`
VALUES
  (
    1,
    'https://rebrand.ly/02z1l03',
    'Distribución eventos',
    'Event distribution',
    '1294'
  ),
  (
    2,
    'https://rebrand.ly/ymb28mf',
    'Resumen de Datos',
    'Summary Data',
    '5825'
  );

/*!40000 ALTER TABLE `estadisticas` ENABLE KEYS */
;

UNLOCK TABLES;

--
-- Table structure for table `estado_civil`
--
DROP TABLE IF EXISTS `estado_civil`;

/*!40101 SET @saved_cs_client     = @@character_set_client */
;

/*!50503 SET character_set_client = utf8mb4 */
;

CREATE TABLE `estado_civil` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_es` varchar(255) NOT NULL,
  `nombre_en` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_es_UNIQUE` (`nombre_es`),
  UNIQUE KEY `nombre_en_UNIQUE` (`nombre_en`)
) ENGINE = InnoDB AUTO_INCREMENT = 7 DEFAULT CHARSET = utf8;

/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Dumping data for table `estado_civil`
--
LOCK TABLES `estado_civil` WRITE;

/*!40000 ALTER TABLE `estado_civil` DISABLE KEYS */
;

INSERT INTO
  `estado_civil`
VALUES
  (1, 'Soltero(a)', 'Single'),
  (2, 'Casado(a)', 'Married'),
  (3, 'Conviviente civil', 'Civil cohabitants'),
  (
    4,
    'Conviviente, no casado y sin acuerdo legal',
    'Cohabitant, unmarried and without legal agreement'
  ),
  (5, 'Separado(a), divorciado(a)', 'Divorce'),
  (6, 'Viudo(a)', 'Widower');

/*!40000 ALTER TABLE `estado_civil` ENABLE KEYS */
;

UNLOCK TABLES;

--
-- Table structure for table `evento`
--
DROP TABLE IF EXISTS `evento`;

/*!40101 SET @saved_cs_client     = @@character_set_client */
;

/*!50503 SET character_set_client = utf8mb4 */
;

CREATE TABLE `evento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_entrevista` int DEFAULT NULL,
  `id_accion` int DEFAULT NULL,
  `id_emoticon` int NOT NULL,
  `justificacion` varchar(255) NOT NULL,
  `hora_evento` time NOT NULL,
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_key_triple` (`id_entrevista`, `id_accion`, `id_emoticon`),
  KEY `foreign_id_accion_idx` (`id_accion`),
  KEY `foreign_id_emoticon_idx` (`id_emoticon`),
  KEY `foreign_id_entrevista_idx` (`id_entrevista`),
  CONSTRAINT `foreign_id_accion` FOREIGN KEY (`id_accion`) REFERENCES `accion` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `foreign_id_emoticon` FOREIGN KEY (`id_emoticon`) REFERENCES `emoticon` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `foreign_id_entrevista` FOREIGN KEY (`id_entrevista`) REFERENCES `entrevista` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8;

/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Table structure for table `investigador`
--
DROP TABLE IF EXISTS `investigador`;

/*!40101 SET @saved_cs_client     = @@character_set_client */
;

/*!50503 SET character_set_client = utf8mb4 */
;

CREATE TABLE `investigador` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `apellido` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `id_rol` int NOT NULL,
  `activado` tinyint(1) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `foreign_id_rol_idx` (`id_rol`),
  CONSTRAINT `foreign_id_rol` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id`) ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8;

/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Dumping data for table `investigador`
--
LOCK TABLES `investigador` WRITE;

/*!40000 ALTER TABLE `investigador` DISABLE KEYS */
;

INSERT INTO
  `investigador`
VALUES
  (
    1,
    'Felipe',
    'González',
    'felipe.gonzalezalarcon94@gmail.com',
    '$2y$10$i5rXCF8L3lACEabZWMXFyeDLgwuyqu7kzKHwglI4iCpYX8.jd//3m',
    1,
    1,
    '2020-08-27 20:34:39',
    NULL
  );

/*!40000 ALTER TABLE `investigador` ENABLE KEYS */
;

UNLOCK TABLES;

--
-- Table structure for table `nivel_educacional`
--
DROP TABLE IF EXISTS `nivel_educacional`;

/*!40101 SET @saved_cs_client     = @@character_set_client */
;

/*!50503 SET character_set_client = utf8mb4 */
;

CREATE TABLE `nivel_educacional` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_es` varchar(255) NOT NULL,
  `nombre_en` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_es_UNIQUE` (`nombre_es`),
  UNIQUE KEY `nombre_en_UNIQUE` (`nombre_en`)
) ENGINE = InnoDB AUTO_INCREMENT = 8 DEFAULT CHARSET = utf8;

/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Dumping data for table `nivel_educacional`
--
LOCK TABLES `nivel_educacional` WRITE;

/*!40000 ALTER TABLE `nivel_educacional` DISABLE KEYS */
;

INSERT INTO
  `nivel_educacional`
VALUES
  (1, 'Sin educación', 'Without education'),
  (
    2,
    'Educación básica incompleta',
    'Primary education incomplete'
  ),
  (
    3,
    'Educación básica completa',
    'Primary education complete'
  ),
  (
    4,
    'Educación media incompleta',
    'Secondary Education incomplete'
  ),
  (
    5,
    'Educación media completa',
    'Secondary Education complete'
  ),
  (
    6,
    'Educación superior incompleta',
    'Tertiary Education incomplete'
  ),
  (
    7,
    'Educación superior completa',
    'Tertiary Education complete'
  );

/*!40000 ALTER TABLE `nivel_educacional` ENABLE KEYS */
;

UNLOCK TABLES;

--
-- Table structure for table `profesion`
--
DROP TABLE IF EXISTS `profesion`;

/*!40101 SET @saved_cs_client     = @@character_set_client */
;

/*!50503 SET character_set_client = utf8mb4 */
;

CREATE TABLE `profesion` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE = InnoDB AUTO_INCREMENT = 6 DEFAULT CHARSET = utf8;

/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Dumping data for table `profesion`
--
LOCK TABLES `profesion` WRITE;

/*!40000 ALTER TABLE `profesion` DISABLE KEYS */
;

INSERT INTO
  `profesion`
VALUES
  (1, 'Accountant'),
  (2, 'Contador/a'),
  (3, 'Actor'),
  (4, 'Archaeologist'),
  (5, 'Arqueólogo/a'),
  (6, 'Architect'),
  (7, 'Arquitecto/a'),
  (8, 'Baker'),
  (9, 'Panadero/a'),
  (10, 'Bricklayer'),
  (11, 'Albañil'),
  (12, 'Carpenter'),
  (13, 'Carpintero/a'),
  (14, 'Dentist'),
  (15, 'Dentista'),
  (16, 'Engineer'),
  (17, 'Ingeniero/a'),
  (18, 'Farmer'),
  (19, 'Agricultor/a'),
  (20, 'Lawyer'),
  (21, 'Abogado/a'),
  (22, 'Taxi driver'),
  (23, 'Taxista'),
  (24, 'Professor'),
  (25, 'Profesor/a'),
  (26, 'Nurse'),
  (27, 'Enfermero/a'),
  (28, 'Mechanic'),
  (29, 'Mecánico/a'),
  (30, 'Feriante')
  /*!40000 ALTER TABLE `profesion` ENABLE KEYS */
;

UNLOCK TABLES;

--
-- Table structure for table `rol`
--
DROP TABLE IF EXISTS `rol`;

/*!40101 SET @saved_cs_client     = @@character_set_client */
;

/*!50503 SET character_set_client = utf8mb4 */
;

CREATE TABLE `rol` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE = InnoDB AUTO_INCREMENT = 3 DEFAULT CHARSET = utf8;

/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Dumping data for table `rol`
--
LOCK TABLES `rol` WRITE;

/*!40000 ALTER TABLE `rol` DISABLE KEYS */
;

INSERT INTO
  `rol`
VALUES
  (1, 'Administrador'),
  (2, 'Investigador');

/*!40000 ALTER TABLE `rol` ENABLE KEYS */
;

UNLOCK TABLES;

--
-- Table structure for table `tipo_convivencia`
--
DROP TABLE IF EXISTS `tipo_convivencia`;

/*!40101 SET @saved_cs_client     = @@character_set_client */
;

/*!50503 SET character_set_client = utf8mb4 */
;

CREATE TABLE `tipo_convivencia` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_es` varchar(255) NOT NULL,
  `nombre_en` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_es_UNIQUE` (`nombre_es`),
  UNIQUE KEY `nombre_en_UNIQUE` (`nombre_en`)
) ENGINE = InnoDB AUTO_INCREMENT = 6 DEFAULT CHARSET = utf8;

/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Dumping data for table `tipo_convivencia`
--
LOCK TABLES `tipo_convivencia` WRITE;

/*!40000 ALTER TABLE `tipo_convivencia` DISABLE KEYS */
;

INSERT INTO
  `tipo_convivencia`
VALUES
  (1, 'Solo', 'Alone'),
  (2, 'Con pareja', 'With couple'),
  (3, 'Con pareja e hijos', 'With couple and sons'),
  (4, 'Con parientes', 'With relatives'),
  (5, 'Con amigos', 'With friends');

/*!40000 ALTER TABLE `tipo_convivencia` ENABLE KEYS */
;

UNLOCK TABLES;

--
-- Table structure for table `tipo_entrevista`
--
DROP TABLE IF EXISTS `tipo_entrevista`;

/*!40101 SET @saved_cs_client     = @@character_set_client */
;

/*!50503 SET character_set_client = utf8mb4 */
;

CREATE TABLE `tipo_entrevista` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_es` varchar(255) NOT NULL,
  `nombre_en` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_es_UNIQUE` (`nombre_es`),
  UNIQUE KEY `nombre_en_UNIQUE` (`nombre_en`)
) ENGINE = InnoDB AUTO_INCREMENT = 3 DEFAULT CHARSET = utf8;

/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Dumping data for table `tipo_entrevista`
--
LOCK TABLES `tipo_entrevista` WRITE;

/*!40000 ALTER TABLE `tipo_entrevista` DISABLE KEYS */
;

INSERT INTO
  `tipo_entrevista`
VALUES
  (1, 'Normal', 'Normal'),
  (2, 'Extraordinaria', 'Extraordinary');

/*!40000 ALTER TABLE `tipo_entrevista` ENABLE KEYS */
;

UNLOCK TABLES;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */
;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */
;

/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */
;

/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */
;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */
;

-- Dump completed on 2020-08-27 17:10:43