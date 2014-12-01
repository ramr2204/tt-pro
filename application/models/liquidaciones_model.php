<?php
class Liquidaciones_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    
    function get($id){
        $this->db->select('c.cntr_id,c.cntr_numero,co.cont_nit,co.cont_nombre,c.cntr_fecha_firma,c.cntr_objeto,c.cntr_valor,c.cntr_vigencia,re.regi_nombre,re.regi_iva,ti.tico_nombre,cu.cuan_minima,cu.cuan_menor,tc.tpco_nombre,c.cntr_tipocontratoid');
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
        $this->db->select('l.litr_id,l.litr_tramitadorid,l.litr_tramitadornombre,l.litr_fechaliquidacion,l.litr_tramiteid,tr.tram_nombre');
        $this->db->from('est_liquidartramites l');
        $this->db->join('est_tramites tr', 'tr.tram_id = l.litr_tramiteid', 'left');
        $this->db->where('l.litr_id',$id);
        $query = $this->db->get();
        
        $result = $query->row() ;
        return $result;
    }

    function getrecibos($id){
        $this->db->select('liqu_id, liqu_codigo,liqu_nombreestampilla,liqu_nombrecontratista,liqu_tipocontratista,liqu_nit, liqu_numero,liqu_vigencia,liqu_valorsiniva,liqu_valorconiva,liqu_valortotal,liqu_tipocontrato,liqu_regimen,liqu_cuentas,liqu_porcentajes,liqu_contratoid');
        $this->db->from('est_liquidaciones li');
        $this->db->where('li.liqu_contratoid',$id);
  
        $query = $this->db->get();
        
        $result = $query->row() ;
        return $result;
    }

    function getrecibostramites($id){
        $this->db->select('liqu_id, liqu_codigo,liqu_nombreestampilla,liqu_nombrecontratista,liqu_tipocontratista,liqu_nit, liqu_numero,liqu_vigencia,liqu_valorsiniva,liqu_valorconiva,liqu_valortotal,liqu_tipocontrato,liqu_regimen,liqu_cuentas,liqu_porcentajes,liqu_contratoid,liqu_tramiteid');
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
        $query = $this->db->get();
        return $query->result();
    }

    function getSelect($table,$fields,$where=''){
        $query = $this->db->query("SELECT ".$fields."  FROM ".$table." ".$where." ");
        return $query->result();
    }

    function getfactura_legalizada($id, $doc=FALSE){
        $this->db->select('co.cntr_id,li.liqu_contratoid,f.fact_id,f.fact_codigo, f.fact_nombre, f.fact_porcentaje, f.fact_valor,pa.pago_valor, pa.pago_fecha, im.impr_codigopapel,ct.cont_nombre,ct.cont_nit,co.cntr_numero,co.cntr_vigencia,f.fact_rutaimagen,li.liqu_valorsiniva');
        $this->db->from('est_facturas f');
        $this->db->join('est_pagos pa', 'pa.pago_facturaid = f.fact_id', 'left');
        $this->db->join('est_impresiones im', 'im.impr_facturaid = f.fact_id AND im.impr_estado = 1', 'left');
        $this->db->join('est_liquidaciones li', 'li.liqu_id = f.fact_liquidacionid', 'left');
        $this->db->join('con_contratos co', 'co.cntr_id = li.liqu_contratoid', 'left');
        $this->db->join('est_liquidartramites tr', 'tr.litr_id = li.liqu_tramiteid', 'left');
        $this->db->join('con_contratistas ct', 'ct.cont_id = co.cntr_contratistaid', 'left');
        
        $this->db->where('f.fact_id',$id);
       // $this->db->where('f.fact_id',$id);
        $query = $this->db->get();
        //  echo  $this->db->last_query();
        return $query->row();
    }


    function getfacturaIndividual($id){
        $this->db->select('f.fact_id,f.fact_codigo, f.fact_nombre, f.fact_porcentaje, f.fact_valor, f.fact_banco, f.fact_cuenta, f.fact_rutacomprobante,f.fact_rutaimagen, pa.pago_valor, pa.pago_fecha, im.impr_codigopapel');
        $this->db->from('est_facturas f');
        $this->db->join('est_pagos pa', 'pa.pago_facturaid = f.fact_id', 'left');
        $this->db->join('est_impresiones im', 'im.impr_facturaid = f.fact_id AND impr_estado=1', 'left');
        $this->db->where('f.fact_id',$id);
        $query = $this->db->get();
        return $query->result();
    }


}