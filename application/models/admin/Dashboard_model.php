<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_model extends CI_Model {
    
    function get_stats($column,$db_where = NULL,$table = NULL,$like = NULL){
		
		$this->db->select("count(*) as ccount, $column");
        
		$this->db->from($table);
		
		if ($db_where && !empty($db_where)) { // Only for where Condition
			foreach ($db_where as $key => $columnVal) {
				if($like != NULL){
					$this->db->where($key, $columnVal);
				}
				elseif ($columnVal != "") {
					$this->db->where_in($key, $columnVal);
				}
			}
		}       
		$this->db->group_by("$column");
       
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $max = 0;
			//echo "<pre>";print_r($query->result_array());exit;
			foreach($query->result_array() as $data){
				$result[$data[$column]][$column] = $data[$column];
				$result[$data[$column]]["ccount"] = $data["ccount"];
				$max = ($data["ccount"] > $max) ? $data["ccount"] : $max;
				$result[$data[$column]]["max"] = $max;
			}
			
            return $result;
        } else {
            return FALSE;
        }
	}	
}