DROP DATABASE IF EXISTS `daw2_2023_03_organizacion_torneos`;
CREATE DATABASE `daw2_2023_03_organizacion_torneos`
    DEFAULT CHARACTER SET = 'utf8mb4';
USE `daw2_2023_03_organizacion_torneos`;

-- SCHEMA
-- Iniciar transacción
START TRANSACTION;

DROP TABLE IF EXISTS `imagen`;
CREATE TABLE `imagen` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `ruta` varchar(250) UNIQUE NOT NULL
);

DROP TABLE IF EXISTS `torneo`;
CREATE TABLE `torneo` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(1000),
  `participantes_max` int NOT NULL,
  `disciplina_id` bigint NOT NULL,
  `tipo_torneo_id` bigint NOT NULL,
  `clase_id` bigint NOT NULL,
  `fecha_inicio` timestamp NOT NULL,
  `fecha_limite` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_fin` timestamp NULL
);

DROP TABLE IF EXISTS `torneo_imagen`;
CREATE TABLE `torneo_imagen` (
  `torneo_id` bigint NOT NULL,
  `imagen_id` bigint,
  PRIMARY KEY (`torneo_id`, `imagen_id`)
);

DROP TABLE IF EXISTS `disciplina`;
CREATE TABLE `disciplina` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) UNIQUE NOT NULL,
  `descripcion` varchar(1000)
);

DROP TABLE IF EXISTS `tipo_torneo`;
CREATE TABLE `tipo_torneo` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) UNIQUE NOT NULL
);

DROP TABLE IF EXISTS `clase`;
CREATE TABLE `clase` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `descripcion` varchar(1000),
  `imagen_id` bigint
);

DROP TABLE IF EXISTS `normativa`;
CREATE TABLE `normativa` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre` varchar(250) UNIQUE NOT NULL,
  `descripcion` varchar(1000),
  `documento_id` bigint NOT NULL
);

DROP TABLE IF EXISTS `torneo_normativa`;
CREATE TABLE `torneo_normativa` (
  `torneo_id` bigint NOT NULL,
  `normativa_id` bigint NOT NULL,
  PRIMARY KEY (`torneo_id`, `normativa_id`)
);

DROP TABLE IF EXISTS `categoria`;
CREATE TABLE `categoria` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) UNIQUE NOT NULL,
  `edad_min` int NOT NULL,
  `edad_max` int NOT NULL
);

DROP TABLE IF EXISTS `premio`;
CREATE TABLE `premio` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) UNIQUE NOT NULL COMMENT 'trofeo',
  `descripcion` varchar(500),
  `categoria_id` bigint NOT NULL,
  `torneo_id` bigint NOT NULL,
  `equipo_id` bigint
);

DROP TABLE IF EXISTS `torneo_categoria`;
CREATE TABLE `torneo_categoria` (
  `torneo_id` bigint NOT NULL,
  `categoria_id` bigint NOT NULL,
  PRIMARY KEY (`torneo_id`, `categoria_id`)
);

DROP TABLE IF EXISTS `partido`;
CREATE TABLE `partido` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `jornada` int NOT NULL,
  `fecha` timestamp NOT NULL,
  `torneo_id` bigint NOT NULL,
  `reserva_id` bigint
);

DROP TABLE IF EXISTS `partido_equipo`;
CREATE TABLE `partido_equipo` (
  `partido_id` bigint NOT NULL,
  `equipo_id` bigint NOT NULL,
  `puntos` int COMMENT 'Puntos de ese equipo en ese partido',
  PRIMARY KEY (`partido_id`, `equipo_id`)
);

DROP TABLE IF EXISTS `torneo_equipo`;
CREATE TABLE `torneo_equipo` (
  `torneo_id` bigint NOT NULL,
  `equipo_id` bigint NOT NULL,
  PRIMARY KEY (`torneo_id`, `equipo_id`)
);

DROP TABLE IF EXISTS `equipo`;
CREATE TABLE `equipo` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(10000),
  `licencia` varchar(250) NOT NULL COMMENT 'Numero de licencia',
  `categoria_id` bigint NOT NULL,
  `creador_id` bigint
);

DROP TABLE IF EXISTS `equipo_participante`;
CREATE TABLE `equipo_participante` (
  `equipo_id` bigint NOT NULL,
  `participante_id` bigint NOT NULL,
  PRIMARY KEY (`participante_id`, `equipo_id`)
);

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido1` varchar(100) NOT NULL,
  `apellido2` varchar(100) NOT NULL,
  `email` varchar(100) UNIQUE NOT NULL,
  `password` varchar(100)
);

DROP TABLE IF EXISTS `participante`;
CREATE TABLE `participante` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `fecha_nacimiento` date NOT NULL,
  `licencia` varchar(250) UNIQUE NOT NULL,
  `tipo_participante_id` bigint NOT NULL,
  `imagen_id` bigint,
  `usuario_id` bigint NOT NULL
);

DROP TABLE IF EXISTS `participante_documento`;
CREATE TABLE `participante_documento` (
  `participante_id` bigint NOT NULL,
  `documento_id` bigint NOT NULL,
  PRIMARY KEY (`participante_id`, `documento_id`)
);

DROP TABLE IF EXISTS `tipo_participante`;
CREATE TABLE `tipo_participante` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) UNIQUE NOT NULL,
  `descripcion` varchar(500)
);

DROP TABLE IF EXISTS `reserva`;
CREATE TABLE `reserva` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `usuario_id` bigint NOT NULL
);

DROP TABLE IF EXISTS `material`;
CREATE TABLE `material` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) UNIQUE NOT NULL,
  `color` varchar(20) NOT NULL,
  `descripcion` varchar(500)
);

DROP TABLE IF EXISTS `pista`;
CREATE TABLE `pista` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) UNIQUE NOT NULL,
  `descripcion` varchar(100),
  `disciplina_id` bigint NOT NULL,
  `direccion_id` bigint NOT NULL
);

DROP TABLE IF EXISTS `reserva_material`;
CREATE TABLE `reserva_material` (
  `reserva_id` bigint NOT NULL,
  `material_id` bigint NOT NULL,
  PRIMARY KEY (`reserva_id`, `material_id`)
);

DROP TABLE IF EXISTS `reserva_pista`;
CREATE TABLE `reserva_pista` (
  `reserva_id` bigint NOT NULL,
  `pista_id` bigint NOT NULL,
  PRIMARY KEY (`reserva_id`, `pista_id`)
);

DROP TABLE IF EXISTS `documento`;
CREATE TABLE `direccion` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `calle` varchar(100) NOT NULL,
  `numero` int,
  `cod_postal` int NOT NULL,
  `ciudad` varchar(100) NOT NULL,
  `provincia` varchar(100) NOT NULL,
  `pais` varchar(100) NOT NULL
);

DROP TABLE IF EXISTS `documento`;
CREATE TABLE `documento` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `ruta` varchar(250) UNIQUE NOT NULL
);

DROP TABLE IF EXISTS `imagen`;
CREATE TABLE `imagen` (
  `id` bigint PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `ruta` varchar(250) UNIQUE NOT NULL
);

-- LOG

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


--
-- Estructura de tabla para la tabla `log`
--

DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `level` varchar(10) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `log_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `prefix` text DEFAULT NULL,
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indices de la tabla `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de la tabla `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


-- ROLES

drop table if exists `auth_assignment`;
drop table if exists `auth_item_child`;
drop table if exists `auth_item`;
drop table if exists `auth_rule`;

create table `auth_rule`
(
   `name`                 varchar(64) not null,
   `data`                 blob,
   `created_at`           integer,
   `updated_at`           integer,
    primary key (`name`)
) engine InnoDB;

create table `auth_item`
(
   `name`                 varchar(64) not null,
   `type`                 smallint not null,
   `description`          text,
   `rule_name`            varchar(64),
   `data`                 blob,
   `created_at`           integer,
   `updated_at`           integer,
   primary key (`name`),
   foreign key (`rule_name`) references `auth_rule` (`name`) on delete set null on update cascade,
   key `type` (`type`)
) engine InnoDB;

create table `auth_item_child`
(
   `parent`               varchar(64) not null,
   `child`                varchar(64) not null,
   primary key (`parent`, `child`),
   foreign key (`parent`) references `auth_item` (`name`) on delete cascade on update cascade,
   foreign key (`child`) references `auth_item` (`name`) on delete cascade on update cascade
) engine InnoDB;

create table `auth_assignment`
(
   `item_name`            varchar(64) not null,
   `user_id`              varchar(64) not null,
   `created_at`           integer,
   primary key (`item_name`, `user_id`),
   foreign key (`item_name`) references `auth_item` (`name`) on delete cascade on update cascade,
   key `auth_assignment_user_id_idx` (`user_id`)
) engine InnoDB;



ALTER TABLE `torneo` COMMENT = 'Tabla principal de torneo';

ALTER TABLE `disciplina` COMMENT = 'Por ej si el torneo es de fútbol, baloncesto...';

ALTER TABLE `tipo_torneo` COMMENT = 'Por ej: Triangular';

ALTER TABLE `clase` COMMENT = 'Por ej: Campeonato Nacional';

ALTER TABLE `torneo` ADD FOREIGN KEY (`disciplina_id`) REFERENCES `disciplina` (`id`);

ALTER TABLE `torneo` ADD FOREIGN KEY (`tipo_torneo_id`) REFERENCES `tipo_torneo` (`id`);

ALTER TABLE `torneo` ADD FOREIGN KEY (`clase_id`) REFERENCES `clase` (`id`);

ALTER TABLE `torneo_imagen` ADD FOREIGN KEY (`torneo_id`) REFERENCES `torneo` (`id`);

ALTER TABLE `torneo_imagen` ADD FOREIGN KEY (`imagen_id`) REFERENCES `imagen` (`id`);

ALTER TABLE `clase` ADD FOREIGN KEY (`imagen_id`) REFERENCES `imagen` (`id`);

ALTER TABLE `normativa` ADD FOREIGN KEY (`documento_id`) REFERENCES `documento` (`id`);

ALTER TABLE `torneo_normativa` ADD FOREIGN KEY (`torneo_id`) REFERENCES `torneo` (`id`);

ALTER TABLE `torneo_normativa` ADD FOREIGN KEY (`normativa_id`) REFERENCES `normativa` (`id`);

ALTER TABLE `premio` ADD FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`);

ALTER TABLE `premio` ADD FOREIGN KEY (`torneo_id`) REFERENCES `torneo` (`id`);

ALTER TABLE `premio` ADD FOREIGN KEY (`equipo_id`) REFERENCES `equipo` (`id`);

ALTER TABLE `torneo_categoria` ADD FOREIGN KEY (`torneo_id`) REFERENCES `torneo` (`id`);

ALTER TABLE `torneo_categoria` ADD FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`);

ALTER TABLE `partido` ADD FOREIGN KEY (`torneo_id`) REFERENCES `torneo` (`id`);

ALTER TABLE `partido` ADD FOREIGN KEY (`reserva_id`) REFERENCES `reserva` (`id`);

ALTER TABLE `partido_equipo` ADD FOREIGN KEY (`partido_id`) REFERENCES `partido` (`id`);

ALTER TABLE `partido_equipo` ADD FOREIGN KEY (`equipo_id`) REFERENCES `equipo` (`id`);

ALTER TABLE `torneo_equipo` ADD FOREIGN KEY (`torneo_id`) REFERENCES `torneo` (`id`);

ALTER TABLE `torneo_equipo` ADD FOREIGN KEY (`equipo_id`) REFERENCES `equipo` (`id`);

ALTER TABLE `equipo` ADD FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`);

ALTER TABLE `equipo_participante` ADD FOREIGN KEY (`equipo_id`) REFERENCES `equipo` (`id`);

ALTER TABLE `equipo_participante` ADD FOREIGN KEY (`participante_id`) REFERENCES `participante` (`id`);

ALTER TABLE `participante` ADD FOREIGN KEY (`tipo_participante_id`) REFERENCES `tipo_participante` (`id`);

ALTER TABLE `participante` ADD FOREIGN KEY (`imagen_id`) REFERENCES `imagen` (`id`);

ALTER TABLE `participante` ADD FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);

ALTER TABLE `participante_documento` ADD FOREIGN KEY (`participante_id`) REFERENCES `participante` (`id`);

ALTER TABLE `participante_documento` ADD FOREIGN KEY (`documento_id`) REFERENCES `documento` (`id`);

ALTER TABLE `reserva` ADD FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);

ALTER TABLE `pista` ADD FOREIGN KEY (`direccion_id`) REFERENCES `direccion` (`id`);

ALTER TABLE `pista` ADD FOREIGN KEY (`disciplina_id`) REFERENCES `disciplina` (`id`);

ALTER TABLE `reserva_material` ADD FOREIGN KEY (`reserva_id`) REFERENCES `reserva` (`id`);

ALTER TABLE `reserva_material` ADD FOREIGN KEY (`material_id`) REFERENCES `material` (`id`);

ALTER TABLE `reserva_pista` ADD FOREIGN KEY (`reserva_id`) REFERENCES `reserva` (`id`);

ALTER TABLE `reserva_pista` ADD FOREIGN KEY (`pista_id`) REFERENCES `pista` (`id`);

ALTER TABLE `direccion` ADD UNIQUE(`calle`, `numero`, `cod_postal`, `ciudad`, `provincia`, `pais`);

ALTER TABLE `equipo` ADD FOREIGN KEY (`creador_id`) REFERENCES `participante` (`id`);

-- Confirmar la transacción
COMMIT;
