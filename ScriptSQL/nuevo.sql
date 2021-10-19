ALTER TABLE estampillas_pro_boyaca.est_estampillas DROP COLUMN tipo;

ALTER TABLE estampillas_pro_boyaca.con_contratos DROP COLUMN cantidad_pagos;

ALTER TABLE estampillas_pro_boyaca.est_facturas ADD id_cuota_liquidacion INT NOT NULL;

INSERT INTO estampillas_pro_boyaca.con_estadoslocales (eslo_id,eslo_nombre)
	VALUES (4,'Modificado');