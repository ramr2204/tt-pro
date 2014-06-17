<?php
class Codegen_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    
    function get($table,$fields,$where='',$perpage=0,$start=0,$one=false,$array='array',$join=''){
        
        $this->db->select($fields);
        $this->db->from($table);
        $this->db->limit($perpage,$start);
        if($where){
        $this->db->where($where);
        }
        if($join){
        $this->db->join($join);
        }
        $query = $this->db->get();
        
        $result =  !$one  ? $query->result($array) : $query->row() ;
        return $result;
    }
    
    function add($table,$data){
        $this->db->insert($table, $data);         
        if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}
		
		return FALSE;       
    }
    
    function edit($table,$data,$fieldID,$ID){
        $this->db->where($fieldID,$ID);
        $this->db->update($table, $data);

        if ($this->db->affected_rows() >= 0)
		{
			return TRUE;
		}
		
		return FALSE;       
    }
    
    function delete($table,$fieldID,$ID){
        $this->db->where($fieldID,$ID);
        $this->db->delete($table);
        if ($this->db->affected_rows() == '1')
		{
			return TRUE;
		}
		
		return FALSE;        
    }   

	
	function count($table){
		return $this->db->count_all($table);
	}

    function max($table, $field) {
        $this->db->select_max($field);
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
           return $query->row_array(); //return the row as an associative array
        }
           return FALSE;
    }

    function getSelect($table,$fields,$where='',$join=''){
        $query = $this->db->query("SELECT ".$fields."  FROM ".$table." ".$where." ".$join." ");
        return $query->result();
    }

    function getMunicipios(){
        $query = $this->db->query("SELECT m.muni_id,m.muni_nombre,d.depa_nombre FROM par_municipios m  LEFT JOIN par_departamentos d ON d.depa_id=m.muni_departamentoid");
        return $query->result();
    }

    function depend($table,$field,$ID){
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
}