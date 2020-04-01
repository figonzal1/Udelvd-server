-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: us-cdbr-iron-east-05.cleardb.net    Database: heroku_7adc409d4f3a3d7
-- ------------------------------------------------------
-- Server version	5.6.42-log
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_es` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `nombre_en` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_es_UNIQUE` (`nombre_es`),
  UNIQUE KEY `nombre_en_UNIQUE` (`nombre_en`)
) ENGINE = InnoDB AUTO_INCREMENT = 49 DEFAULT CHARSET = utf8 COLLATE = utf8_spanish_ci;

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
    '2020-01-08 01:06:00',
    '2020-02-24 13:07:08'
  ),
  (
    2,
    'Bañarse',
    'Bath',
    '2020-01-08 01:06:00',
    '2020-02-24 15:55:56'
  ),
  (
    3,
    'Ir al baño',
    'Go to the bathroom',
    '2020-01-10 19:45:00',
    '2020-02-24 15:56:09'
  ),
  (
    4,
    'Cortarse las uñas',
    'Cutting the nails',
    '2020-01-10 19:45:00',
    '2020-02-24 15:56:18'
  ),
  (
    5,
    'Vestirse',
    'Get dressed',
    '2020-01-10 19:45:00',
    '2020-02-24 15:56:30'
  ),
  (
    6,
    'Desvestirse',
    'Undress',
    '2020-01-10 19:45:00',
    '2020-02-24 15:57:31'
  ),
  (
    7,
    'Ponerse ropa para dormir',
    'Put on sleepwear',
    '2020-01-10 19:45:00',
    '2020-02-24 15:57:39'
  ),
  (
    8,
    'Acostarse',
    'Go to bed',
    '2020-01-10 19:45:00',
    '2020-02-24 15:55:42'
  ),
  (
    9,
    'Quedarse dormido',
    'Fall asleep',
    '2020-01-10 19:45:00',
    '2020-02-24 16:01:26'
  ),
  (
    10,
    'Cocinar',
    'Cook',
    '2020-01-10 19:45:00',
    '2020-02-24 15:57:46'
  ),
  (
    11,
    'Comer',
    'Eat',
    '2020-01-10 19:45:00',
    '2020-02-24 15:57:56'
  ),
  (
    12,
    'Tender/Hacer la cama',
    'To make the bed',
    '2020-01-10 19:45:00',
    '2020-02-24 15:58:23'
  ),
  (
    13,
    'Lavar loza',
    'Wash crockery',
    '2020-01-10 19:45:00',
    '2020-02-24 15:58:32'
  ),
  (
    14,
    'Levantar la mesa',
    'Clear the table',
    '2020-01-10 19:45:00',
    '2020-02-24 15:59:17'
  ),
  (
    15,
    'Lavar ropa',
    'Wash clothes',
    '2020-01-10 19:45:00',
    '2020-02-24 16:00:23'
  ),
  (
    16,
    'Planchar ropa',
    'Iron clothes',
    '2020-01-10 19:45:00',
    '2020-02-24 16:02:07'
  ),
  (
    17,
    'Hacer aseo superficial',
    'Superficial cleaning',
    '2020-01-10 19:45:00',
    '2020-02-24 16:07:05'
  ),
  (
    18,
    'Hacer aseo profundo',
    'Deep cleaning',
    '2020-01-10 19:45:00',
    '2020-02-24 16:07:09'
  ),
  (
    19,
    'Sacar la basura',
    'Take out the trash',
    '2020-01-10 19:45:00',
    '2020-02-24 16:07:25'
  ),
  (
    20,
    'Ir a comprar a negocios dentro del barrio',
    'Go shopping in the neighborhood',
    '2020-01-10 19:45:00',
    '2020-02-24 16:07:51'
  ),
  (
    21,
    'Ir a comprar a negocios en otra parte de la ciudad',
    'Go shopping in another part of the city',
    '2020-01-10 19:45:00',
    '2020-02-24 16:08:29'
  ),
  (
    22,
    'Salir a caminar en el barrio',
    'Go for a walk in the neighborhood',
    '2020-01-10 19:45:00',
    '2020-02-24 16:08:54'
  ),
  (
    23,
    'Salir a caminar a otra parte de la ciudad',
    'Go for a walk to another part of the city',
    '2020-01-10 19:45:00',
    '2020-02-24 16:09:14'
  ),
  (
    24,
    'Viajar en colectivo o taxi',
    'Travel by taxi',
    '2020-01-10 19:45:00',
    '2020-02-24 16:09:41'
  ),
  (
    25,
    'Viajar en microbus',
    'Travel by bus',
    '2020-01-10 19:45:00',
    '2020-02-24 16:10:09'
  ),
  (
    26,
    'Conducir un automóvil',
    'Driving car',
    '2020-01-10 19:45:00',
    '2020-02-26 17:54:43'
  ),
  (
    27,
    'Realizar trabajo remunerado',
    'Perform paid work',
    '2020-01-10 19:45:00',
    '2020-02-26 17:54:54'
  ),
  (
    28,
    'Recibir visitas en casa',
    'Receive visits at home',
    '2020-01-10 19:45:00',
    '2020-02-26 17:55:01'
  ),
  (
    29,
    'Hacer visitas a otras personas a sus casas',
    'Make visits to other people to their homes',
    '2020-01-10 19:45:00',
    '2020-02-26 17:55:08'
  ),
  (
    30,
    'Hablar por teléfono',
    'Talking on the phone',
    '2020-01-10 19:45:00',
    '2020-02-26 17:55:42'
  ),
  (
    31,
    'Chatear por redes sociales',
    'Chat on social networks',
    '2020-01-10 19:45:00',
    '2020-02-26 17:55:49'
  ),
  (
    32,
    'Ver televisión',
    'Watch TV',
    '2020-01-10 19:45:00',
    '2020-02-26 17:55:53'
  ),
  (
    33,
    'Leer',
    'Read',
    '2020-01-10 19:45:00',
    '2020-02-26 17:55:56'
  ),
  (
    34,
    'Navegar por Internet',
    'Browse the Internet',
    '2020-01-10 19:45:00',
    '2020-02-26 17:56:30'
  ),
  (
    35,
    'Realizar mantenciones en la casa',
    'Perform maintenance in the house',
    '2020-01-10 19:45:00',
    '2020-02-26 17:56:52'
  ),
  (
    36,
    'Cuidar el jardín',
    'Take care of the garden',
    '2020-01-10 19:45:00',
    '2020-02-26 17:57:08'
  ),
  (
    37,
    'Pasatiempo de esfuerzo físico',
    'Physical effort hobby',
    '2020-01-10 19:45:00',
    '2020-02-26 17:57:18'
  ),
  (
    38,
    'Pasatiempo de actividades manuales',
    'Manual activity hobby',
    '2020-01-10 19:45:00',
    '2020-02-26 17:57:33'
  ),
  (
    39,
    'Pasatiempo de esfuerzo mental',
    'Mental effort hobby',
    '2020-01-10 19:45:00',
    '2020-02-26 17:59:01'
  ),
  (
    40,
    'Ir a ceremonias religiosas',
    'Go to religious ceremonies',
    '2020-01-10 19:45:00',
    '2020-02-26 17:59:31'
  ),
  (
    41,
    'Tomar medicamentos',
    'Taking medications',
    '2020-01-10 19:45:00',
    '2020-02-26 17:59:38'
  ),
  (
    42,
    'Chequeos de salud',
    'Health checks',
    '2020-01-10 19:45:00',
    '2020-02-26 17:59:56'
  ),
  (
    43,
    'Ir a consulta médica',
    'Go to medical consultation',
    '2020-01-10 19:45:00',
    '2020-02-26 18:00:05'
  ),
  (
    44,
    'Cuidar a algún familiar enfermo',
    'Caring for a sick relative',
    '2020-01-10 19:45:00',
    '2020-02-26 18:00:11'
  ),
  (
    45,
    'Cuidar niños',
    'Babysit',
    '2020-01-10 19:45:00',
    '2020-02-26 18:00:42'
  ),
  (
    46,
    'Atender familiar que no es niño ni enfermo',
    'Take care of a family member who is not a child or a sick person',
    '2020-01-10 19:45:00',
    '2020-02-26 18:01:02'
  ),
  (
    47,
    'Realizar actividades sociales',
    'Carry out social activities',
    '2020-01-10 19:45:00',
    '2020-02-26 18:01:24'
  ),
  (
    48,
    'Atender mascotas',
    'Attend to pets',
    '2020-01-10 19:45:00',
    '2020-02-26 18:01:33'
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE = InnoDB AUTO_INCREMENT = 41 DEFAULT CHARSET = utf8 COLLATE = utf8_spanish_ci;

/*!40101 SET character_set_client = @saved_cs_client */
;

--
-- Dumping data for table `ciudad`
--
LOCK TABLES `ciudad` WRITE;

/*!40000 ALTER TABLE `ciudad` DISABLE KEYS */
;

INSERT INTO
  `ciudad`
VALUES
  (1, 'Concepción'),
  (2, 'Rancagua'),
  (3, 'Santiago'),
  (4, 'Viña Del Mar');

/*!40000 ALTER TABLE `ciudad` ENABLE KEYS */
;

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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion_es` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion_en` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB AUTO_INCREMENT = 5 DEFAULT CHARSET = utf8 COLLATE = utf8_spanish_ci;

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
    'https://udelvd-dev.herokuapp.com/img/smiley.png',
    'Emoticon que describe felicidad',
    'Emoticon that describes happiness',
    '2020-01-10 02:03:00',
    '2020-02-26 23:53:59'
  ),
  (
    2,
    'https://udelvd-dev.herokuapp.com/img/sad.png',
    'Emoticon que describe tristeza',
    'Emoticon that describes sadness',
    '2020-01-10 02:03:00',
    '2020-02-26 23:54:00'
  ),
  (
    3,
    'https://udelvd-dev.herokuapp.com/img/afraid.png',
    'Emoticon que describe miedo',
    'Emoticon that describes fear',
    '2020-01-08 01:07:00',
    '2020-02-26 23:54:00'
  ),
  (
    4,
    'https://udelvd-dev.herokuapp.com/img/angry.png',
    'Emoticon que describe enojo',
    'Emoticon that describes anger',
    '2020-01-10 02:03:00',
    '2020-02-26 23:54:00'
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_entrevistado` int(11) DEFAULT NULL,
  `id_tipo_entrevista` int(11) NOT NULL,
  `fecha_entrevista` date NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `foreign_id_entrevistado_idx` (`id_entrevistado`),
  KEY `foreign_tipo_entrevista_idx` (`id_tipo_entrevista`),
  CONSTRAINT `foreign_id_entrevistado` FOREIGN KEY (`id_entrevistado`) REFERENCES `entrevistado` (`id`) ON DELETE
  SET
    NULL ON UPDATE CASCADE,
    CONSTRAINT `foreign_tipo_entrevista` FOREIGN KEY (`id_tipo_entrevista`) REFERENCES `tipo_entrevista` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8 COLLATE = utf8_spanish_ci;

--
-- Table structure for table `entrevistado`
--
DROP TABLE IF EXISTS `entrevistado`;

/*!40101 SET @saved_cs_client     = @@character_set_client */
;

/*!50503 SET character_set_client = utf8mb4 */
;

CREATE TABLE `entrevistado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `apellido` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `sexo` varchar(40) COLLATE utf8_spanish_ci NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `jubilado_legal` tinyint(1) NOT NULL,
  `caidas` tinyint(1) NOT NULL,
  `n_caidas` int(11) DEFAULT NULL,
  `n_convivientes_3_meses` int(11) NOT NULL,
  `id_investigador` int(11) NOT NULL,
  `id_ciudad` int(11) NOT NULL,
  `id_nivel_educacional` int(11) DEFAULT NULL,
  `id_estado_civil` int(11) NOT NULL,
  `id_tipo_convivencia` int(11) DEFAULT NULL,
  `id_profesion` int(11) DEFAULT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `foreign_id_investigador_idx` (`id_investigador`),
  KEY `foreign_id_nivel_educacional_idx` (`id_nivel_educacional`),
  KEY `foreign_id_estado_civil_idx` (`id_estado_civil`),
  KEY `foreign_id_tipo_convivencia_idx` (`id_tipo_convivencia`),
  KEY `foreign_id_oficio_idx` (`id_profesion`),
  KEY `foreign_id_ciudad_idx` (`id_ciudad`),
  CONSTRAINT `foreign_id_ciudad` FOREIGN KEY (`id_ciudad`) REFERENCES `ciudad` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `foreign_id_estado_civil` FOREIGN KEY (`id_estado_civil`) REFERENCES `estado_civil` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `foreign_id_investigador` FOREIGN KEY (`id_investigador`) REFERENCES `investigador` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `foreign_id_nivel_educacional` FOREIGN KEY (`id_nivel_educacional`) REFERENCES `nivel_educacional` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `foreign_id_profesion` FOREIGN KEY (`id_profesion`) REFERENCES `profesion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `foreign_id_tipo_conviviente` FOREIGN KEY (`id_tipo_convivencia`) REFERENCES `tipo_convivencia` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8 COLLATE = utf8_spanish_ci;

--
-- Table structure for table `estado_civil`
--
DROP TABLE IF EXISTS `estado_civil`;

/*!40101 SET @saved_cs_client     = @@character_set_client */
;

/*!50503 SET character_set_client = utf8mb4 */
;

CREATE TABLE `estado_civil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_es` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `nombre_en` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_es_UNIQUE` (`nombre_es`),
  UNIQUE KEY `nombre_en_UNIQUE` (`nombre_en`)
) ENGINE = InnoDB AUTO_INCREMENT = 7 DEFAULT CHARSET = utf8 COLLATE = utf8_spanish_ci;

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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_entrevista` int(11) DEFAULT NULL,
  `id_accion` int(11) DEFAULT NULL,
  `id_emoticon` int(11) NOT NULL,
  `justificacion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `hora_evento` time NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_key_triple` (`id_entrevista`, `id_accion`, `id_emoticon`),
  KEY `foreign_id_accion_idx` (`id_accion`),
  KEY `foreign_id_emoticon_idx` (`id_emoticon`),
  KEY `foreign_id_entrevista_idx` (`id_entrevista`),
  CONSTRAINT `foreign_id_accion` FOREIGN KEY (`id_accion`) REFERENCES `accion` (`id`) ON DELETE
  SET
    NULL ON UPDATE CASCADE,
    CONSTRAINT `foreign_id_emoticon` FOREIGN KEY (`id_emoticon`) REFERENCES `emoticon` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
    CONSTRAINT `foreign_id_entrevista` FOREIGN KEY (`id_entrevista`) REFERENCES `entrevista` (`id`) ON DELETE
  SET
    NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 0 DEFAULT CHARSET = utf8 COLLATE = utf8_spanish_ci;

--
-- Table structure for table `investigador`
--
DROP TABLE IF EXISTS `investigador`;

/*!40101 SET @saved_cs_client     = @@character_set_client */
;

/*!50503 SET character_set_client = utf8mb4 */
;

CREATE TABLE `investigador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `apellido` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `id_rol` int(11) NOT NULL,
  `activado` tinyint(1) NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `foreign_id_rol_idx` (`id_rol`),
  CONSTRAINT `foreign_id_rol` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB AUTO_INCREMENT = 2 DEFAULT CHARSET = utf8 COLLATE = utf8_spanish_ci;

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
    'Gonzalez',
    'felipe.gonzalezalarcon94@gmail.com',
    '$2y$10$R79XorNdTjXEcGwBNPEl5.OTISLWoMAb.TgmUsXiYtBe.lMV1AQFW',
    1,
    1,
    '2020-02-26 23:11:59',
    '2020-02-26 23:12:38'
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_es` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `nombre_en` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_es_UNIQUE` (`nombre_es`),
  UNIQUE KEY `nombre_en_UNIQUE` (`nombre_en`)
) ENGINE = InnoDB AUTO_INCREMENT = 8 DEFAULT CHARSET = utf8 COLLATE = utf8_spanish_ci;

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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE = InnoDB AUTO_INCREMENT = 3 DEFAULT CHARSET = utf8 COLLATE = utf8_spanish_ci;

--
-- Dumping data for table `rol`
--
LOCK TABLES `profesion` WRITE;

/*!40000 ALTER TABLE `profesion` DISABLE KEYS */
;

INSERT INTO
  `profesion`
VALUES
  (1, 'Ingeniero'),
  (2, 'Médico');

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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE = InnoDB AUTO_INCREMENT = 3 DEFAULT CHARSET = utf8 COLLATE = utf8_spanish_ci;

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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_es` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `nombre_en` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_es_UNIQUE` (`nombre_es`),
  UNIQUE KEY `nombre_en_UNIQUE` (`nombre_en`)
) ENGINE = InnoDB AUTO_INCREMENT = 6 DEFAULT CHARSET = utf8 COLLATE = utf8_spanish_ci;

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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_es` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `nombre_en` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_es_UNIQUE` (`nombre_es`),
  UNIQUE KEY `nombre_en_UNIQUE` (`nombre_en`)
) ENGINE = InnoDB AUTO_INCREMENT = 3 DEFAULT CHARSET = utf8 COLLATE = utf8_spanish_ci;

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

-- Dump completed on 2020-03-20 12:15:49