<?php
class Liquidaciones_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->helper('Equivalencias');
    }

    
    function get($id){
        $this->db->select('c.cntr_id,c.cntr_numero,co.cont_nit,co.cont_nombre,c.cntr_fecha_firma,c.cntr_objeto,c.cntr_valor,c.cntr_iva_otros,c.cntr_vigencia, cntr_municipio_origen,re.regi_nombre,re.regi_iva,re.regi_id,ti.tico_iva,ti.tico_nombre,cu.cuan_minima,cu.cuan_menor,tc.tpco_nombre,c.cntr_tipocontratoid');
        $this->db->from('con_contratos c');
        $this->db->join('con_contratistas co', 'co.cont_id = c.cntr_contratistaid', 'left');
        $this->db->join('con_tiposcontratos ti', 'ti.tico_id = c.cntr_tipocontratoid', 'left');
        $this->db->join('con_tiposcontratistas tc', 'tc.tpco_id = co.cont_tipocontratistaid', 'left');
        $this->db->join('con_regimenes re', 're.regi_id = co.cont_regimenid', 'left');
        $this->db->join('con_cuantias cu', 'cu.cuan_vigencia = c.cntr_vigencia', 'left');
        $this->db->where('c.cntr_id',$id);
        $query = $this->db->get();
        
        $result = $query->row() ;
        return $result;
    }

    function getliquidartramite($id){
        $this->db->select('l.litr_id,tramitadores.nit as tramitador_nit,tramitadores.nombre as tramitador_nombre,l.litr_fechaliquidacion,l.litr_tramiteid,l.litr_placaVehiculo,tr.tram_nombre');
        $this->db->from('est_liquidartramites l');
        $this->db->join('est_tramites tr', 'tr.tram_id = l.litr_tramiteid', 'left');
        $this->db->join('tramitadores', 'tramitadores.id = l.litr_tramitadorid', 'left');
        $this->db->where('l.litr_id',$id);
        $query = $this->db->get();
        
        $result = $query->row() ;
        return $result;
    }

    function getrecibos($id, $id_liquidacion=null){
        $this->db->select('liqu_id, liqu_codigo,liqu_nombreestampilla,
            liqu_nombrecontratista,liqu_tipocontratista,liqu_nit,
            liqu_numero,liqu_vigencia,liqu_valorsiniva,
            liqu_valorconiva,liqu_valortotal,liqu_tipocontrato,
            liqu_regimen,liqu_cuentas,liqu_porcentajes,
            liqu_contratoid,liqu_soporteobjeto,liqu_usuarioliquida'
        );
        $this->db->from('est_liquidaciones li');

        if($id_liquidacion){
            $this->db->where('li.liqu_id',$id_liquidacion);
        } else {
            $this->db->where('li.liqu_contratoid',$id);
        }
  
        $query = $this->db->get();
        
        $result = $query->row() ;
        return $result;
    }

    function getrecibostramites($id){
        $this->db->select('liqu_id, liqu_codigo,liqu_nombreestampilla,liqu_nombrecontratista,liqu_tipocontratista,liqu_nit, liqu_numero,liqu_vigencia,liqu_valorsiniva,liqu_valorconiva,liqu_valortotal,liqu_tipocontrato,liqu_regimen,liqu_cuentas,liqu_porcentajes,liqu_contratoid,liqu_tramiteid,liqu_usuarioliquida');
        $this->db->from('est_liquidaciones li');
        $this->db->where('li.liqu_tramiteid',$id);
  
        $query = $this->db->get();
        
        $result = $query->row() ;
        return $result;
    }

    function getestampillastramites($id){
        $this->db->select('e.estm_id,e.estm_nombre,e.estm_cuenta,b.banc_nombre,et.estr_porcentaje,e.estm_rutaimagen');
        $this->db->from('est_estampillas e');
        $this->db->join('par_bancos b', 'b.banc_id = e.estm_bancoid', 'left');
        $this->db->join('est_estampillas_tramites et', 'et.estr_estampillaid = e.estm_id and et.estr_porcentaje > 0', 'inner');
        $this->db->where('et.estr_tramiteid',$id);
        $query = $this->db->get();
        return $query->result();
    }

    function getestampillas($id){
        $this->db->select('e.estm_id,e.estm_nombre,e.estm_cuenta,b.banc_nombre,et.esti_porcentaje,e.estm_rutaimagen');
        $this->db->from('est_estampillas e');
        $this->db->join('par_bancos b', 'b.banc_id = e.estm_bancoid', 'left');
        $this->db->join('est_estampillas_tiposcontratos et', 'et.esti_estampillaid = e.estm_id and et.esti_porcentaje > 0', 'inner');
        $this->db->where('et.esti_tipocontratoid',$id);
        $query = $this->db->get();
        return $query->result();
    }
    
    function getfacturas($id){
        $this->db->select('f.fact_id,f.fact_codigo, f.fact_nombre, f.fact_porcentaje, f.fact_valor, f.fact_banco, f.fact_cuenta, f.fact_rutacomprobante,f.fact_rutaimagen, pa.pago_valor, pa.pago_fecha, im.impr_codigopapel, im.impr_estado');
        $this->db->from('est_facturas f');
        $this->db->join('est_pagos pa', 'pa.pago_facturaid = f.fact_id', 'left');
        $this->db->join('est_impresiones im', 'im.impr_facturaid = f.fact_id', 'left');
        $this->db->where('f.fact_liquidacionid',$id);
        $this->db->where('f.tipo !=', Equivalencias::tipoRetencion());
        $query = $this->db->get();
        return $query->result();
    }

    function getSelect($table,$fields,$where=''){
        $query = $this->db->query("SELECT ".$fields."  FROM ".$table." ".$where." ");
        return $query->result();
    }

    function getfactura_legalizada($id, $doc=FALSE){
        $this->db->select('co.cntr_id,li.liqu_contratoid,li.liqu_valorsiniva,
            li.liqu_valorconiva,li.liqu_tipocontratista,li.liqu_regimen,
            li.liqu_tipocontrato,f.fact_id,f.fact_codigo,
            f.fact_nombre, f.fact_porcentaje, f.fact_valor,
            pa.pago_valor, pa.pago_fecha, im.impr_codigopapel,
            im.impr_fecha, im.impr_estampillaid, ct.cont_nombre,
            ct.cont_nit,co.cntr_numero,co.cntr_vigencia,
            f.fact_rutaimagen'
        );
        $this->db->from('est_facturas f');
        $this->db->join('est_pagos pa', 'pa.pago_facturaid = f.fact_id', 'left');
        $this->db->join('est_impresiones im', 'im.impr_facturaid = f.fact_id AND im.impr_estado = 1', 'left');
        $this->db->join('est_liquidaciones li', 'li.liqu_id = f.fact_liquidacionid', 'left');
        $this->db->join('con_contratos co', 'co.cntr_id = li.liqu_contratoid', 'left');
        $this->db->join('con_contratistas ct', 'ct.cont_id = co.cntr_contratistaid', 'left');
        
        $this->db->where('f.fact_id',$id);
       
        $query = $this->db->get();
        
        return $query->row();
    }


    function getfactura_legalizada_tramite($id, $doc=FALSE){
        $this->db->select('li.liqu_nombrecontratista as cont_nombre,lt.litr_id as liqu_contratoid, tramitadores.nit as cont_nit, lt.litr_id as cntr_numero, lt.litr_fechaliquidacion as cntr_vigencia,f.fact_id,f.fact_codigo, f.fact_nombre, f.fact_porcentaje, f.fact_valor,pa.pago_valor, pa.pago_fecha, im.impr_codigopapel, im.impr_fecha, im.impr_estampillaid,f.fact_rutaimagen,li.liqu_valorsiniva,li.liqu_valorconiva,li.liqu_tipocontratista,li.liqu_regimen,li.liqu_tipocontrato');
        $this->db->from('est_facturas f');
        $this->db->join('est_pagos pa', 'pa.pago_facturaid = f.fact_id', 'left');
        $this->db->join('est_impresiones im', 'im.impr_facturaid = f.fact_id AND im.impr_estado = 1', 'left');
        $this->db->join('est_liquidaciones li', 'li.liqu_id = f.fact_liquidacionid', 'left');
        $this->db->join('est_liquidartramites lt', 'lt.litr_id = li.liqu_tramiteid', 'left');
        $this->db->join('tramitadores', 'tramitadores.id = lt.litr_tramitadorid', 'left');
        $this->db->where('f.fact_id',$id);
       
        $query = $this->db->get();
        
        return $query->row();
    }


    function getfacturaIndividual($id){
        $this->db->select('f.fact_id,f.fact_codigo, f.fact_nombre, f.fact_porcentaje, f.fact_valor, f.fact_banco, f.fact_cuenta, f.fact_rutacomprobante,f.fact_rutaimagen,f.fact_estampillaid,l.liqu_nit , pa.pago_valor, pa.pago_fecha, im.impr_codigopapel');
        $this->db->from('est_facturas f');
        $this->db->join('est_pagos pa', 'pa.pago_facturaid = f.fact_id', 'left');
        $this->db->join('est_liquidaciones l', 'f.fact_liquidacionid = l.liqu_id');
        $this->db->join('est_impresiones im', 'im.impr_facturaid = f.fact_id AND impr_estado=1', 'left');
        $this->db->where('f.fact_id',$id);
        $query = $this->db->get();
        return $query->result();
    }

    function obtenerFacturasRetencion($campo, $valor){
        # Para calcular el valor de la cuota se toma el saldo restante repartido entre las cuotas restantes
        $this->db->select('
            factura.fact_id, factura.fact_nombre, (factura.fact_valor - COALESCE(descuento.valor, 0)) AS valor_total,
            contrato.cantidad_pagos,
            (
                FLOOR(
                    ( factura.fact_valor - COALESCE(descuento.valor, 0) ) - COALESCE(SUM(pago.valor), 0)
                ) /
                IF(COALESCE(MAX(pago.numero), 0) >= contrato.cantidad_pagos, 1, (contrato.cantidad_pagos - COALESCE(MAX(pago.numero), 0)))
            ) AS valor_cuota,
            COALESCE(MAX(pago.numero), 0) AS numero_cuota,
            factura.fact_rutaimagen, factura.fact_liquidacionid AS id_liquidacion,
            SUM(pago.valor) AS valor_pagado',
            false
        );
        $this->db->from('est_facturas AS factura');
        $this->db->join('est_liquidaciones liquidacion', 'liquidacion.liqu_id = factura.fact_liquidacionid');
        $this->db->join('con_contratos contrato', 'contrato.cntr_id = liquidacion.liqu_contratoid');

        $this->db->join('pagos_estampillas pago', 'pago.factura_id = factura.fact_id', 'left');
        $this->db->join(
            '(
            	select descuentos.factura_id, SUM(descuentos.valor) as valor
            	from descuentos_estampillas as descuentos
            	group by descuentos.factura_id
            ) descuento',
            'descuento.factura_id = factura.fact_id',
            'left');
        
        $this->db->where('factura.tipo', Equivalencias::tipoRetencion());
        $this->db->where($campo, $valor);

        $this->db->group_by('factura.fact_id');

        $query = $this->db->get();
        return $query->result();
    }


}