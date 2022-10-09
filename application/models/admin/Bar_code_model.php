<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class bar_code_model extends CI_Model {
		
		
		
		
		/*
		|------------------------------------------------
		| start: get_receipt function
		|------------------------------------------------
		|
		| This function get all receipt and get receipt by id and other cloumn
		|
		*/
		function get_bar_code($db_where = null, $db_or_where = null, $db_where_in = null, $db_limit = null, $db_order = null, $db_select = null) {
			
			if($db_select) {
				$this->db->select($db_select);
			}
			else {
				$this->db->select('*');
			}
			
			if($db_where) {
				$this->db->group_start();
				foreach ($db_where as $key => $columnVal) {
					if ($columnVal != "") {
						$this->db->where($key, $columnVal);
					}
				}
				$this->db->group_end();
			}
			
			if($db_or_where) {
				$this->db->group_start();
				foreach ($db_or_where as $key => $columnVal) {
					if ($columnVal != "") {
						$this->db->or_where($key, $columnVal);
					}
				}
				$this->db->group_end();
			}
			
			if($db_where_in) {
				$this->db->group_start();
				foreach ($db_where_in as $key => $columnVal) {
					if ($columnVal != "") {
						$this->db->where_in($key, $columnVal);
					}
				}
				$this->db->group_end();
			}
			
			if($db_limit) {
				$this->db->limit($db_limit['limit'], $db_limit['startPageRecord']);
			}
			
			if($db_order) {
				foreach($db_order as $get_order) {
					$this->db->order_by($get_order['title'], $get_order['order_by']);
				} 
			}

			$result = $this->db->get('generated_barcodes gb');
			
			if($result->num_rows() > 0) { 
				foreach($result->result_array() as $row){
					$data[$row["generated_date"]] = $row;
					
				}           
				return $data;
			}
			else
				return FALSE;
			
		}
		/*---- end: get_receipt function ----*/
		
	}	