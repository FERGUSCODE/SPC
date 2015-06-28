CREATE TABLE IF NOT EXISTS `base` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `base_planta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `base_id` int(10) unsigned NOT NULL,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `base_planta_sector` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `planta_id` int(10) unsigned NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `medida` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `base_planta_sector_maquina` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sector_id` int(10) unsigned NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `min` float NOT NULL,
  `max` float NOT NULL,
  `unidad` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `planilla` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `UUID` binary(16) NOT NULL,
  `sector_id` int(10) unsigned NOT NULL,
  `tiempo_inicio` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tiempo_final` datetime NOT NULL
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `planilla_acceso` (
  `planilla_id` int(10) unsigned NOT NULL,
  `usuario_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`planilla_id`,`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `planilla_dato` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `planilla_id` int(10) unsigned NOT NULL,
  `usuario_id` int(10) unsigned NOT NULL,
  `maquina_id` int(10) unsigned NOT NULL,
  `valor` float unsigned NOT NULL,
  `tiempo` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` varchar(45) NOT NULL,
  `contrasena` varchar(45) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `planta_id` int(10) unsigned NOT NULL,
  `es_admin` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;