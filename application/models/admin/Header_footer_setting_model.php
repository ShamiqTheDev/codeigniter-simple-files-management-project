<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Header_footer_setting_model extends CI_Model {
    
    /*
    |------------------------------------------------
    | start: add_general_setting function
    |------------------------------------------------
    |
    | This function add frontend setting
    |
   */
    function add_general_setting($file_name) {
        
        $setting = $this->input->post();
		$date       = date("Y-m-d H:i:s");
        
        $data = array(
                'header_image'      => $file_name,
                'footer_message'    => $setting['copyright'],
                'timestamp'    		=> $date
        ); 
        
        $this->db->trans_begin();
        
        if(!$setting['setting_id']) {
            $this->db->set($data);
            $this->db->insert('header_footer_detail');
            $setting_id = $this->db->insert_id();
        }
        else {
            $setting_id = $setting['setting_id'];
            $this->db->where('header_footer_id', $setting_id);
            $this->db->set($data);
            $this->db->update('header_footer_detail');
        }

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return $setting_id;
        }
        
    }
    /*---- end: add_general_setting function ----*/
    
    
    /*
    |------------------------------------------------
    | start: get_setting function
    |------------------------------------------------
    |
    | This function get all home and general page setting
    |
    */
    function get_setting($db_where_column, $db_where_value) {
        
        $this->db->select('*');
        
        if($db_where_column) {
            foreach ($db_where_column as $key => $column) {
                if (!empty($db_where_value[$key])) {
                    $this->db->where($column, $db_where_value[$key]);
                }
            }
        }

        $result = $this->db->get('header_footer_detail hf');
        
        if($result->num_rows() > 0) {            
            $data = $result->result_array();            
            return $data[0];
        }
        else
            return FALSE;
        
    }
    /*---- end: get_setting function ----*/
    
}