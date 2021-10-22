ALTER TABLE estampillas_pro_boyaca.est_estampillas DROP COLUMN tipo;

ALTER TABLE estampillas_pro_boyaca.con_contratos DROP COLUMN cantidad_pagos;

CREATE TABLE `cuotas_liquidacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_liquidacion` int(11) NOT NULL,
  `valor` double NOT NULL,
  `estado` tinyint(4) NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE estampillas_pro_boyaca.est_facturas ADD id_cuota_liquidacion INT NOT NULL;

INSERT INTO estampillas_pro_boyaca.con_estadoslocales (eslo_id,eslo_nombre)
	VALUES (4,'Modificado');

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nit` double NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `telefono` varchar(100) NOT NULL,
  `id_municipio` int(11) unsigned NOT NULL,
  `nombre_representante` varchar(100) NOT NULL,
  `identificador_representante` double NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `fecha_creacion` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE estampillas_pro_boyaca.users ADD id_empresa INT(11) NULL;

ALTER TABLE estampillas_pro_boyaca.con_contratos ADD id_empresa INT(11) NOT NULL;

ALTER TABLE estampillas_pro_boyaca.est_liquidaciones ADD id_empresa INT(11) NOT NULL;

