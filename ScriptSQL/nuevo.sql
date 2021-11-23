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

CREATE TABLE `declaraciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_empresa` int(11) NOT NULL,
  `id_estampilla` int(11) NOT NULL,
  `periodo` date NOT NULL,
  `tipo_declaracion` tinyint(4) NOT NULL,
  `declaracion_correccion` bigint(20) DEFAULT NULL,
  `radicacion_correccion` bigint(20) DEFAULT NULL,
  `fecha_correccion` date DEFAULT NULL,
  `periodo_correccion` date DEFAULT NULL,
  `recaudado` double(14,2) NOT NULL,
  `sanciones` double(14,2) NOT NULL,
  `intereses` double(14,2) NOT NULL,
  `total_base` double(14,2) NOT NULL,
  `total_estampillas` double(14,2) NOT NULL,
  `saldo_periodo_anterior` double(14,2) NOT NULL,
  `sanciones_pago` double(14,2) NOT NULL,
  `intereses_mora` double(14,2) NOT NULL,
  `total_cargo` double(14,2) NOT NULL,
  `saldo_favor` double(14,2) NOT NULL,
  `soporte` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT '1',
  `fecha_creacion` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `detalles_declaracion` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_declaracion` int(11) NOT NULL,
  `renglon` int(11) NOT NULL,
  `base` double(14,2) NOT NULL,
  `vigencia_actual` int(4) NOT NULL,
  `vigencia_anterior` int(4) NOT NULL,
  `porcentaje` double(3,2) NOT NULL,
  `valor_estampilla` double(14,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `usuarios_firma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `password` varchar(240) NOT NULL,
  `key_hash` varchar(240) NOT NULL,
  `estado` tinyint DEFAULT 1,
  `change_password` int(11) DEFAULT 1,
  `creado_por` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `update_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `elemento_firma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_declaracion` int(11) NOT NULL,
  `id_usuario_firma` int(11) NOT NULL,
  `key_hash` varchar(240) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `estado` int(11) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `codigo_firma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario_firma` int(11) NOT NULL,
  `codigo` varchar(60) NOT NULL,
  `generado` datetime NOT NULL,
  `expira` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `archivo_firma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_declaracion` int(11) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  `ruta_file` varchar(240) NOT NULL,
  `checksum` varchar(240) NOT NULL,
  `hash_file` varchar(240) NOT NULL,
  `estado` int(11) DEFAULT 1,
  `certificado` int(11) DEFAULT 0,
  `fecha_certificacion` datetime DEFAULT NULL,
  `id_usuario_certifico` int(11) DEFAULT NULL,
  `tipo_archivo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*  */

ALTER TABLE estampillas_pro_boyaca.declaraciones ADD creado_por INT(11) NOT NULL;
ALTER TABLE estampillas_pro_boyaca.declaraciones CHANGE creado_por creado_por INT(11) NOT NULL AFTER estado;

ALTER TABLE estampillas_pro_boyaca.detalles_declaracion MODIFY COLUMN vigencia_actual double(14,2) NOT NULL;
ALTER TABLE estampillas_pro_boyaca.detalles_declaracion MODIFY COLUMN vigencia_anterior double(14,2) NOT NULL;

UPDATE estampillas_pro_boyaca.con_estadoslocales
	SET eslo_nombre='Activo'
	WHERE eslo_id=1;

INSERT INTO estampillas_pro_boyaca.con_estadoslocales (eslo_id,eslo_nombre)
	VALUES (5,'Liquidado');

CREATE TABLE estampillas_pro_boyaca.vencimiento_declaraciones (
	id TINYINT auto_increment NOT NULL,
	ultimo_digito TINYINT NOT NULL,
	dia TINYINT NOT NULL,
	modificado DATETIME NOT NULL,
	CONSTRAINT vencimiento_declaraciones_pk PRIMARY KEY (id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci;

INSERT INTO estampillas_pro_boyaca.vencimiento_declaraciones
(id, ultimo_digito, dia, modificado)
VALUES(1, 0, 15, '2021-01-01 00:00:00');
INSERT INTO estampillas_pro_boyaca.vencimiento_declaraciones
(id, ultimo_digito, dia, modificado)
VALUES(2, 1, 15, '2021-01-01 00:00:00');
INSERT INTO estampillas_pro_boyaca.vencimiento_declaraciones
(id, ultimo_digito, dia, modificado)
VALUES(3, 2, 15, '2021-01-01 00:00:00');
INSERT INTO estampillas_pro_boyaca.vencimiento_declaraciones
(id, ultimo_digito, dia, modificado)
VALUES(4, 3, 15, '2021-01-01 00:00:00');
INSERT INTO estampillas_pro_boyaca.vencimiento_declaraciones
(id, ultimo_digito, dia, modificado)
VALUES(5, 4, 15, '2021-01-01 00:00:00');
INSERT INTO estampillas_pro_boyaca.vencimiento_declaraciones
(id, ultimo_digito, dia, modificado)
VALUES(6, 5, 15, '2021-01-01 00:00:00');
INSERT INTO estampillas_pro_boyaca.vencimiento_declaraciones
(id, ultimo_digito, dia, modificado)
VALUES(7, 6, 15, '2021-01-01 00:00:00');
INSERT INTO estampillas_pro_boyaca.vencimiento_declaraciones
(id, ultimo_digito, dia, modificado)
VALUES(8, 7, 15, '2021-01-01 00:00:00');
INSERT INTO estampillas_pro_boyaca.vencimiento_declaraciones
(id, ultimo_digito, dia, modificado)
VALUES(9, 8, 15, '2021-01-01 00:00:00');
INSERT INTO estampillas_pro_boyaca.vencimiento_declaraciones
(id, ultimo_digito, dia, modificado)
VALUES(10, 9, 15, '2021-01-01 00:00:00');

DROP TABLE empresas;

ALTER TABLE estampillas_pro_boyaca.con_contratantes ADD email varchar(100) NOT NULL AFTER nit;
ALTER TABLE estampillas_pro_boyaca.con_contratantes ADD direccion varchar(200) NOT NULL AFTER email;
ALTER TABLE estampillas_pro_boyaca.con_contratantes ADD telefono varchar(100) NOT NULL AFTER direccion;

ALTER TABLE estampillas_pro_boyaca.con_contratos DROP COLUMN id_empresa;
ALTER TABLE estampillas_pro_boyaca.est_liquidaciones DROP COLUMN id_empresa;

CREATE TABLE estampillas_pro_boyaca.adiciones_contratos (
	id INT auto_increment NOT NULL,
	id_contrato INT UNSIGNED NOT NULL,
	valor double(14,2) NOT NULL,
	observaciones TEXT NULL,
	fecha_creacion DATETIME NOT NULL,
	CONSTRAINT adiciones_contratos_pk PRIMARY KEY (id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci;

ALTER TABLE estampillas_pro_boyaca.cuotas_liquidacion ADD tipo TINYINT DEFAULT 1 NOT NULL;
ALTER TABLE estampillas_pro_boyaca.cuotas_liquidacion CHANGE tipo tipo TINYINT DEFAULT 1 NOT NULL AFTER valor;

CREATE TABLE estampillas_pro_boyaca.correcciones_declaraciones (
	id INT auto_increment NOT NULL,
	id_declaracion INT NOT NULL,
	id_usuario_solicito INT unsigned NOT NULL,
	id_usuario_verifico INT unsigned NULL,
	estado TINYINT DEFAULT 1 NOT NULL,
	fecha_creacion DATETIME NOT NULL,
	CONSTRAINT correcciones_declaraciones_pk PRIMARY KEY (id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci;

CREATE TABLE estampillas_pro_boyaca.notificaciones (
	id BIGINT auto_increment NOT NULL,
	tipo TINYINT NOT NULL,
	texto varchar(300) NOT NULL,
	id_empresa INT(11) unsigned NOT NULL,
	adicional varchar(100) NULL,
	CONSTRAINT notificaciones_pk PRIMARY KEY (id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8
COLLATE=utf8_general_ci;

ALTER TABLE estampillas_pro_boyaca.notificaciones ADD fecha_creacion DATETIME NOT NULL;
