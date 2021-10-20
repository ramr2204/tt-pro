ALTER TABLE estampillas_pro_boyaca.est_estampillas DROP COLUMN tipo;

ALTER TABLE estampillas_pro_boyaca.con_contratos DROP COLUMN cantidad_pagos;

ALTER TABLE estampillas_pro_boyaca.est_facturas ADD id_cuota_liquidacion INT NOT NULL;

INSERT INTO estampillas_pro_boyaca.con_estadoslocales (eslo_id,eslo_nombre)
	VALUES (4,'Modificado');

ALTER TABLE estampillas_pro_boyaca.users ADD id_empresa INT(11) NULL;

ALTER TABLE estampillas_pro_boyaca.con_contratos ADD id_empresa INT(11) NOT NULL;

ALTER TABLE estampillas_pro_boyaca.est_liquidaciones ADD id_empresa INT(11) NOT NULL;

