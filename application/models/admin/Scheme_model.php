<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scheme_model extends CI_Model {
    
    /*
    |------------------------------------------------
    | start: scaning function
    |------------------------------------------------
    |
    | This function add and update scaning data
    |
   */
    function scheme($scheme_id = null) {
	
		$user_id = $this->flexi_auth->get_user_id();
		
        $post_data = $this->input->post();
		
        $data = array(
				'parentAdpNumber'		=> $post_data['parent_adp_number'],
                'adpyear'      			=> $post_data['adp_year'],
                'adpNumber'      		=> $post_data['adp_number'],
                'schemeDate'      		=> date('Y-m-d', strtotime($post_data['scheme_date'])),
                'schemeName'      		=> $post_data['scheme_name'],
                'documentTypeId'		=> $post_data['document_type_id'],
                'documentTypeChildId'	=> $post_data['document_type_child_id'],
				'miscellaneousType'		=> $post_data['miscellaneous_type'],
        );
		
        $this->db->trans_begin();
        
        if(!$scheme_id) {
		
			//$query = $this->db->query("SELECT UUID() as uuid");
		
			//if(!$query->num_rows() > 0)
				//return false;
			
			//$data['schemeId'] 		= $query->result_array()[0]['uuid'];
			$data['schemeId'] 		= $post_data['uuid'];
			$data['createdDate']	= date('Y-m-d H:i:s');
			$data['createdBy'] 		= $user_id;
		
            $this->db->set($data);
            $this->db->insert('scheme');
            $scheme_id = $data['schemeId'];
			
			$this->file_uploaded($scheme_id, $post_data);
			
        }
        else {
            $this->db->where('schemeId', $scheme_id);
            $this->db->set($data);
            $this->db->update('scheme');
        }

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
			/*File Audit Work*/
			if($scheme_id!=NULL){
				$action = "Add";
				$msg = "#".$scheme_id." file has been added";
			}
			else{
				$action = "Update";
				$msg = "#".$scheme_id." file has been updated";
			}
			
			
			//$this->update_file_audit($action,$msg);
			/*File Audit work ends here*/
			return $scheme_id;
        }
        
    }
    /*---- end: scaning function ----*/
	    
		
		
	/*function clean($string) {
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}*/
		
		
	
	/*
	|------------------------------------------------
    | start: update_media function
    |------------------------------------------------
    |
    | This function update media
    |
   */
    function file_uploaded($file_id, $post_data) {
	
		$user_id = $this->flexi_auth->get_user_id();
		
		$upload_dir = $this->config->item('fileUploadPathScheme');		
		
		$parent_adp_number = $post_data['parent_adp_number'];
		$document_type = $post_data['document_type'];
		$document_type_id = $post_data['document_type_id'];
		$miscellaneous_type = $post_data['miscellaneous_type'];
		
		if(!is_dir($upload_dir.$parent_adp_number)) {
			mkdir($upload_dir.$parent_adp_number, 0777);
			chmod($upload_dir.$parent_adp_number, 0777);
		}
		
		$upload_dir_path = $upload_dir.$parent_adp_number.'/';
		
		if($document_type_id == '4') { // 4 = DRO
		
			$document_type_child = $this->get_document_type(array('documentTypeId' => $post_data['document_type_child_id']));
			$document_type_child = $document_type_child[$post_data['document_type_child_id']]['documentType'];
		
			$adp_number = $post_data['adp_number'].'-'.$post_data['adp_year'];
			
			if(!is_dir($upload_dir_path.'/'.$document_type)) {
				mkdir($upload_dir_path.'/'.$document_type, 0777);
				chmod($upload_dir_path.'/'.$document_type, 0777); 
			}
			
			$upload_dir_path = $upload_dir_path.$document_type.'/'.$adp_number.'/';
			
			if(!is_dir($upload_dir_path)) {
				mkdir($upload_dir_path, 0777);
				chmod($upload_dir_path, 0777); 
			}
		}
		
		$upload_temp_dir = $upload_dir.'temp_files/';
		
		foreach($post_data['file_uploaded_name'] as $key => $get_file_name) {
		
			$infoFile = new SplFileInfo($get_file_name);
			$extension = $infoFile->getExtension();
			$getBasename = $infoFile->getBasename('.'.$extension);
			
			if($document_type_id == '4') { // 4 = DRO
				$new_file_name = clean($document_type_child).'-'.date('dmY').'.'.$extension;
			}
			else if($document_type_id == '7') { // 7 = Miscellaneous
				$new_file_name = clean($document_type).'-'.clean($miscellaneous_type).'-'.date('dmY').'.'.$extension;
			}
			else {
				$new_file_name = clean($document_type).'-'.date('dmY-His').'.'.$extension;
			}
			
			copy($upload_temp_dir.$get_file_name, $upload_dir_path.$get_file_name);
			rename($upload_dir_path.$get_file_name, $upload_dir_path.$new_file_name);
			unlink($upload_temp_dir.$get_file_name);
		
			$data = array(
					'fileDetailId'		=> $file_id,
					'moduleType'		=> 'scheme',
					'OriginalFileName'	=> $post_data['file_uploaded_real_name'][$key],
					'fileName'			=> $new_file_name,
					'fileSize'          => $post_data['file_uploaded_size'][$key],
					'fileType'          => $post_data['file_uploaded_type'][$key],
					'filePath'          => $upload_dir_path.$new_file_name,
			); 
		
			$this->db->trans_begin();
		
			if($post_data['file_uploaded_id'][$key]) {
				$this->db->set($data);
				$this->db->update('file_uploaded');
				$file_uploaded_id = $post_data['file_uploaded_id'][$key];
			}
			else {
				
				$data['fileUploadedDate']	= date('Y-m-d H:i:s');
				$data['fileUploadedBy'] 	= $user_id;
			
				$this->db->set($data);
				$this->db->insert('file_uploaded');
				$file_uploaded_id = $this->db->insert_id();
			}
			
			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$b = $this->db->trans_commit();
				//return $data;
			}
			
		}
		
	}
	/*---- end: update_media function ----*/
	
	
	/*update file audit function starts here*/
	function update_file_audit($action = NULL, $msg = NULL){
		/*Action = Add, Msg = [] file added
		Action = Update, Msg = [] file uploaded 
		Action = Update, Msg = [] file re uploaded*/
		 $data = array(
                'fileAction' 		=> $action,
				'fileMsg' 			=> $msg,
                'actionPerformedBy'	=> $this->flexi_auth->get_user_id(),
                'performedOn'		=> date('Y-m-d H:i:s'),
        );
		$this->db->trans_begin();
        $this->db->set($data);
        $this->db->insert('file_audit');
        $id = $this->db->insert_id();
        $this->db->trans_complete();

        //trans_complete

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
		$b = $this->db->trans_commit();
            return $id;
        }
	}
	/*update file audit function ends here*/
	
	
	/*
    |------------------------------------------------
    | start: get_scheme function
    |------------------------------------------------
    |
    | This function get all scaning data and get column wise
    |
    */
    function get_scheme($db_where = null, $db_select = null, $db_group_by = null, $db_order = null, $array_index = 0) {
        
		if($db_select) {
			$this->db->select($db_select);
		}
		else {
			$this->db->select('sc.*, dt.documentType, fu.fileName, fu.fileSize, fu.filePath, cdt.documentType as documentTypeChild, fu.fileUploadedId');
		}
		
        
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }
		
		$this->db->where('sc.isDeleted', '0');
		
		//$this->db->join('file_types ft', 'ft.fileTypeId = fd.fileTypeId');
		$this->db->join('scheme_document_type dt', 'dt.documentTypeId = sc.documentTypeId');
		$this->db->join('file_uploaded fu', 'fu.fileDetailId = sc.schemeId');
		$this->db->join('scheme_document_type cdt', 'cdt.documentTypeId = sc.documentTypeChildId', 'LEFT');
		
		if($db_group_by != null){
			foreach($db_group_by as $group_by_col){
				if ($group_by_col != "") {
					$this->db->group_by($group_by_col);
				}	
			}	
		}
		$this->db->order_by('schemeDate','desc');
        $result = $this->db->get('scheme sc');
        //echo "<pre>"; print_r($result->result_array()); exit;
        if($result->num_rows() > 0) {
			if($array_index == 1){
				foreach($result->result_array() as $row){
					$data[$row["parentAdpNumber"]] = $row["documentCount"];
					$totalcount +=  $row["documentCount"];
				}
				$data["totalCount"] = $totalcount;
			}
			else{
				$data = $result->result_array();            
			}
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_scheme function ----*/
	
	
    /*
    |------------------------------------------------
    | start: get_scheme_list function
    |------------------------------------------------
    |
    | This function get all scaning data and get column wise
    |
    */
    function get_scheme_list($db_where = null, $db_limit = null, $db_order = null, $db_count = null) {
		
		if($db_count==null){
			//$this->db->select('fd.*, ft.fileType,u.fileName,us.sectionName,g.generalCategoryName,CONCAT (up.upro_first_name, " " ,up.upro_last_name) as created_by_name');
			$this->db->select('sc.*,DATE_FORMAT(u.fileUploadedDate,"%d-%m-%Y") as lastuploadeddocument, dt.documentType');
        }
		else{
			$this->db->select('COUNT(sc.schemeId) as fdCount');
		}
		
		$this->db->from('scheme sc');
        
        if ($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }
		
		$this->db->where('sc.isDeleted', '0');

		if ($db_limit) {
            $this->db->limit($db_limit['limit'], $db_limit['startPageRecord']);
        }
        
        if($db_order) {
            foreach($db_order as $get_order) {
                $this->db->order_by($get_order['title'], $get_order['order_by']);
            } 
        }
		
		$this->db->join('scheme_document_type dt', 'dt.documentTypeId = sc.documentTypeId');
		//$this->db->join('user_section us', 'us.sectionId = fd.sectionId');
		$this->db->join('file_uploaded u', 'u.fileDetailId = sc.schemeId');
		//$this->db->join('general_file_category g', 'g.generalCategoryId = fd.generalCategoryId','LEFT');
		//$this->db->join('user_profiles up', 'up.upro_uacc_fk = fd.createdBy');
		$this->db->order_by('schemeDate','desc');
		$this->db->order_by('fileUploadedDate','desc');
		$this->db->group_by('parentAdpNumber');       
        $query = $this->db->get();
        
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
			if($db_count==null){
				foreach($result as $key => $get_result) {
					$data[$key] = $get_result;
				}
				return $data;
			}
			else{
					foreach ($result as $key => $get_result) {
					$count = $get_result['fdCount'];
					}
				return $count;
			}
            
        } else {
            return NULL;
        }
		
		
	}
    /*---- end: get_scheme_list function ----*/
	
	
	/*
    |------------------------------------------------
    | start: get_document_type function
    |------------------------------------------------
    |
    | This function get all document type and get document type by id and other cloumn
    |
    */
    function get_document_type($db_where = null) {
        
        $this->db->select('dt.*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }
		
        $result = $this->db->get('scheme_document_type dt');
        
        if($result->num_rows() > 0) { 
			foreach($result->result_array() as $row){
				$data[$row["documentTypeId"]] = $row;		
			}           
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_document_type function ----*/
	
	
	/*
    |------------------------------------------------
    | start: delete_scheme function
    |------------------------------------------------
    |
    | This function delete scheme
    |
    */
	function delete_scheme($file_upload_id, $scheme_id) {
	
		$user_id = $this->flexi_auth->get_user_id();
		
		$data = array('isDeleted' => '1', 'deletedBy' => $user_id); 
		
		$this->db->trans_begin();
		
		$this->db->where('schemeId', $scheme_id);
		$this->db->set($data);
		$this->db->update('scheme');
		
		$this->db->trans_complete();
		
		if($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$b = $this->db->trans_commit();
			$this->delete_scheme_file($file_upload_id);
			return TRUE;
		}
		
	}
	/*---- end: delete_scheme function ----*/
	
	
	/*
    |------------------------------------------------
    | start: delete_scheme_file function
    |------------------------------------------------
    |
    | This function delete file details
    |
    */
	function delete_scheme_file($file_upload_id) {
	
		$upload_file_data = $this->get_file(array('fileUploadedId' => $file_upload_id));
		unlink($upload_file_data[$file_upload_id]['filePath']);
	
		$user_id = $this->flexi_auth->get_user_id();
		
		$data = array('isDeleted' => '1', 'deletedBy' => $user_id); 
		
		$this->db->trans_begin();
		
		$this->db->where('fileUploadedId', $file_upload_id);
		$this->db->set($data);
		$this->db->update('file_uploaded');
		
		$this->db->trans_complete();
		
		if($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$b = $this->db->trans_commit();
			return TRUE;
		}
		
	}
	/*---- end: delete_scheme_file function ----*/
	
	
	/*
    |------------------------------------------------
    | start: get_file function
    |------------------------------------------------
    |
    | This function get files data in database
    |
    */
    function get_file($db_where) {
        
		$this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }
		
		$this->db->where('file_uploaded.isDeleted', '0');
        
        $result = $this->db->get('file_uploaded');
        
        if($result->num_rows() > 0) {
			foreach ($result->result_array() as $row) {
				$data[$row["fileUploadedId"]] = $row;
			}
			
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_file function ----*/
	
	/*
	* Generate universal unique id
	*/
	function generate_uuid(){
		$query = $this->db->query("SELECT UUID() as uuid");
	
		if(!$query->num_rows() > 0)
			return false;
		
		return $query->result_array()[0]['uuid'];
	}
	
}