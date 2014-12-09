<?php
class Codegen_model extends CI_Model 
{

    function __construct() {
        parent::__construct();
    }

    
    function get($table,$fields,$where='',$perpage=0,$start=0,$one=false,$array='array',$tablaJoin='', $condicionJoin='')
    {
        
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->limit($perpage,$start);
        if($where){
        $this->db->where($where);
        }
        if($tablaJoin){
        $this->db->join($tablaJoin, $condicionJoin);
        }
        $query = $this->db->get();
        
        $result =  !$one  ? $query->result($array) : $query->row() ;
        return $result;
    }

    //Funcion que extrae coincidencias de una tabla según
    //una cadena especificada
    function getLike($table,$fields,$like='',$match='',$where='')
    {
        
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->like($like, $match);        

        if($where!='')
        {
          $this->db->where($where);
        }

        $query = $this->db->get();
        return $query;
    }
    
    function add($table,$data)
    {
         
         $this->db->insert($table, $data);         

                  if ($this->db->affected_rows() == '1')
                  {   
                      $this->addlog($table,'INSERT',0,$data);
                      return TRUE;
                  }     

                  return FALSE; 
             
         				      
    }
    
    function edit($table,$data,$fieldID,$ID)
    {        
        $this->db->where($fieldID,$ID);
        $this->db->update($table, $data);

        if ($this->db->affected_rows() >= 0)
		{
            $this->addlog($table,'UPDATE',$ID,$data);
			return TRUE;
		}
		
		return FALSE;       
    }
    
    function delete($table,$fieldID,$ID)
    {       
       $this->db->where($fieldID,$ID);
        $this->db->delete($table);
        if ($this->db->affected_rows() == '1')
		{
            $this->addlog($table,'DELETE',$ID);
			return TRUE;
		}
		
		return FALSE;        
    }   

	
	function count($table)
    {
		return $this->db->count_all($table);
	}

    function countwhere ($table,$where) 
    {
         $this->db->select("COUNT(*) AS contador");
         $this->db->from($table);
         $this->db->where($where);
         $query=$this->db->get();  
         return $query->row();
    }


    function max($table, $field, $where='', $tablaJoin='', $equivalentesJoin='')
     {
        $this->db->select_max($field);
        if($where){
        $this->db->where($where);
        }

        if($tablaJoin and $equivalentesJoin)
        {
            $this->db->join($tablaJoin, $equivalentesJoin);
        }

        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
           return $query->row_array(); //return the row as an associative array
        }
           return FALSE;
    }


    function min($table, $field, $where='', $tablaJoin='', $equivalentesJoin='')
     {
        $this->db->select_min($field);
        if($where){
        $this->db->where($where);
        }

        if($tablaJoin and $equivalentesJoin)
        {
            $this->db->join($tablaJoin, $equivalentesJoin);
        }

        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
           return $query->row_array(); //return the row as an associative array
        }
           return FALSE;
    }



    function getSelect($table,$fields,$where='',$join='', $group='', $orderBy='')
    {
        $query = $this->db->query("SELECT ".$fields."  FROM ".$table." ".$join." ".$where." ".$group." ".$orderBy);
        return $query->result();
    }

    function getMunicipios()
    {
        $query = $this->db->query("SELECT m.muni_id,m.muni_nombre,d.depa_nombre FROM par_municipios m  LEFT JOIN par_departamentos d ON d.depa_id=m.muni_departamentoid");
        return $query->result();
    }

    function depend($table,$field,$ID)
    {
        $this->db->where($field, $ID);
        $this->db->from($table);
        
       if ($this->db->count_all_results() > 0)
       {
           return TRUE;
       } else 
       {
           return FALSE;
       }
       

    }

    //Función que registra el log de autenticación
    //en el sistema
    function registerAccesos($accion='')
    {         
         $datos = array('loga_fecha' => date('Y-m-d H:i:s',now()),
                        'loga_tabla' => 'session',
                        'logacodigonombre' => 'no_aplica',
                        'loga_codigoid' => 0,
                        'loga_valoresanteriores' => 'no_aplica',
                        'loga_valoresnuevos' => 'no_aplica',
                        'loga_accion' =>  $accion,
                        'loga_ip' => $this->input->ip_address(),
                        'loga_usuarioid' => $this->ion_auth->get_user_id()
                     );



        $this->db->insert('adm_logactividades', $datos); 

    }


    function addlog($tabla,$accion,$id,$valores=array())
    {

    $fields = $this->db->field_data($tabla);
    $nombreid=$fields[0]->name;
    $field_list='';
    if ($id==0) { 
           $database = $this->db->database; 
           $query = $this->db->query("select k.column_name "
               ."FROM information_schema.table_constraints t "
               ."JOIN information_schema.key_column_usage k "
               ."USING(constraint_name,table_schema,table_name) "
               ."WHERE t.constraint_type='PRIMARY KEY'"
               ."AND t.table_schema='".$database."'"
               ."AND t.table_name='".$tabla."'");

           $result=$query->row();
           $id=$result->column_name;
           $datos_anteriores='';
    } else {
        if ($accion=='UPDATE') {
            foreach ($valores as $key => $value) {
                $field_list.=$key.',';   
            }
            $field_list = substr($field_list, 0, - 1);
        } else {
            $field_list='*';
        }
        
        
        $query = $this->db->query("SELECT ".$field_list."  FROM ".$tabla." WHERE ".$nombreid." = ".$id." LIMIT 1");
        $datos_anteriores=json_encode($result=$query->row());
    }
      $datos_nuevos=json_encode($valores);  

       $datos = array(  'loga_fecha' => date('Y-m-d H:i:s',now()),
                        'loga_tabla' => $tabla,
                        'logacodigonombre' => $nombreid,
                        'loga_codigoid' => $id,
                        'loga_valoresanteriores' => $datos_anteriores,
                        'loga_valoresnuevos' => $datos_nuevos,
                        'loga_accion' =>  $accion,
                        'loga_ip' => $this->input->ip_address(),
                        'loga_usuarioid' => $this->ion_auth->get_user_id()
                     );



        $this->db->insert('adm_logactividades', $datos);         
        if ($this->db->affected_rows() == '1')
        {
            return TRUE;
        }
        
        return FALSE;       
    }

}