<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uploadhandler_model extends CI_Model {
    
    /*
    |------------------------------------------------
    | start: insert_file function
    |------------------------------------------------
    |
    | This function insert files data in database
    |
    */
    function insert_file($file, $file_detail_id, $file_type_id) {
	
		if($file_detail_id) {
			$file_number = $this->generate_file_number($file_type_id);
			
			$infoFile = new SplFileInfo($file->name);
			$extension = $infoFile->getExtension();
			
			$new_file_name = $file_number.'.'.$extension;
			
			
			$new_file_path = 'upload/scaning/'.$new_file_name;
			$old_file_path = 'upload/scaning/'.$file->name;
			
			//die();
			rename ($old_file_path, $new_file_path);
			
			$file_name = $new_file_name;
			$file_url = $new_file_path;
		}
		else {
			$file_name = $file->name;
			$file_url = $file->url;
		}
	
		$user_id = $this->flexi_auth->get_user_id();
        
        $data = array(
                'fileDetailId'		=> $file_detail_id,
                'OriginalFileName'	=> $file_name,
                'fileSize'          => $file->size,
                'fileType'          => $file->type,
                'filePath'          => $file_url,
                'fileUploadedDate'	=> date('Y-m-d H:i:s'),
                'fileUploadedBy'	=> $user_id,
        ); 
        
        $this->db->trans_begin();
        
        $this->db->set($data);
        $this->db->insert('file_uploaded');
        
        $data['file_id'] = $this->db->insert_id();
        $data['file_name'] = $file_name;
        $data['file_url'] = $file_url;
        $data['file_url_delete'] = base_url().'admin/fileupload?file='.$file_name;
        
        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $b = $this->db->trans_commit();
            return $data;
        }
        
    }
    /*---- end: insert_file function ----*/
    
    
    /*
    |------------------------------------------------
    | start: get_file function
    |------------------------------------------------
    |
    | This function get files data in database
    |
    */
    function get_file($db_where_column, $db_where_value, $db_select_value = null) {
        
        if($db_select_value)
            $this->db->select($db_select_value);
        else
            $this->db->select('file_uploaded.*, up.upro_first_name, up.upro_last_name, ua.uacc_username');
        
        if($db_where_value) {
            foreach($db_where_value as $key => $value) {
                $where = $db_where_column[$key];
                $this->db->where($where, $value);
            }
        }
		
		$this->db->where('file_uploaded.isDeleted', '0');
		
		$this->db->join('user_accounts ua', 'ua.uacc_id = file_uploaded.fileUploadedBy');
		$this->db->join('user_profiles up', 'up.upro_uacc_fk = ua.uacc_id');
        
        $result = $this->db->get('file_uploaded');
        
        if($result->num_rows() > 0) {
            
            $data = $result->result();            
            return $data;
            
        }
        else
            return FALSE;
        
    }
    /*---- end: get_file function ----*/
    
    
    /*
    |------------------------------------------------
    | start: delete_file function
    |------------------------------------------------
    |
    | This function delete file
    |
    */
    function delete_file($name) {
        
        $this->db->where('fileName', $name);
        $this->db->delete('file_uploaded');
        
    }
    /*---- end: delete_file function ----*/
	
	
	/*
	|------------------------------------------------
    | start: generate_file_number function
    |------------------------------------------------
    |
    | This function add file number
    |
   */
    function generate_file_number($file_type_id) {
	
		$current_date = date('Ymd');
		$file_number = $current_date;
		
		// count total file uploaded in current date
		$this->db->select('COUNT(fileUploadedId) as total_record');
        $this->db->where('fileUploadedDate like', date('Y-m-d').'%');
        $result = $this->db->get('file_uploaded fu');
        
        if($result->num_rows() > 0) {            
            $data = $result->result_array();
			
			$serial = $data[0]['total_record'];
			
			$serialCount = strlen($serial);
            
            if($serialCount == 1) {
                $file_number .= '0000'.$serial;
            }
            else if($serialCount == 2) {
                $file_number .= '000'.$serial;
            }
			else if($serialCount == 3) {
                $file_number .= '00'.$serial;
            }
			else if($serialCount == 4) {
                $file_number .= '0'.$serial;
            }
            else {
                $file_number .= $serial;
            }
			
        }
        else
            $file_number .= '00001';
		
		
		
	
		if($file_type_id == '1') {
			$file_number .= 'P';
		}
		
		if($file_type_id == '2') {
			$file_number .= 'A';
		}
		
		if($file_type_id == '3') {
			$file_number .= 'G';
		}
		
		return $file_number;
		
		
	}
	/*---- end: generate_file_number function ----*/
    
}