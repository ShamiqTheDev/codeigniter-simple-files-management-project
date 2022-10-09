<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Scaning_model extends CI_Model {
    
    /*
    |------------------------------------------------
    | start: scaning function
    |------------------------------------------------
    |
    | This function add and update scaning data
    |
   */
    function scaning($file_id = null) {
	
		$user_id = $this->flexi_auth->get_user_id();
		
		/*echo '<pre>'; print_r($query->result_array()[0]['uuid']);

		foreach($query->result_array() as $row)
		{
			echo '<pre>'; print_r($row); 
		}*/
        
		//die($uuid);
		
        $post_data = $this->input->post();
		
		//$this->update_section_count($post_data);
		//echo '<pre>'; print_r($post_data); die();
        
        $data = array(
                //'fileId'      		=> 'uuid()',
                'fileTypeId'      	=> $post_data['file_type_id'],
                'generalCategoryId'	=> $post_data['general_category_id'],
                'employeeName'    	=> trim($post_data['employee_name']),
                'employeeCNIC'    	=> $post_data['employee_cnic'],
                'oldFileNumber'    	=> $post_data['old_file_number'],
                //'appointmentYear'	=> $post_data['appointment_year'],
                'seniorityNo'    	=> $post_data['seniority_no'],
                'subject'    		=> $post_data['subject'],
                'sectionId'    		=> $post_data['section_id'],
                //'assignedBy'		=> $post_data['assigned_by'],
                //'reportYear'    	=> $post_data['report_year'],
                //'assignedDate'		=> date('Y-m-d', strtotime($post_data['assigned_date'])),
				//'createdDate'		=> date('Y-m-d H:i:s'),
				//'createdBy'			=> $this->flexi_auth->get_user_id()
        );
		
        $this->db->trans_begin();
        
        if(!$file_id) {
		
			$query = $this->db->query("SELECT UUID() as uuid");
		
			if(!$query->num_rows() > 0)
				return false;
			
			$data['fileId'] 		= $query->result_array()[0]['uuid'];
			$data['createdDate']	= date('Y-m-d H:i:s');
			$data['createdBy'] 		= $user_id;
		
            $this->db->set($data);
            $this->db->insert('file_detail');
            $file_id = $data['fileId'];
			
			// create and insert file number
			//$file_number = $this->generate_file_number();
			//$this->file_uploaded($file_id, $post_data['file_uploaded_id'], $file_number);
			$this->file_uploaded($file_id, $post_data);
			
			//if($post_data['total_scanend_count'] >= $post_data['total_count']) {
			$this->update_section_count($post_data);
			//}
        }
        else {
            $this->db->where('fileId', $file_id);
            $this->db->set($data);
            $this->db->update('file_detail');
        }

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
			/*File Audit Work*/
			if($file_id!=NULL){
				$action = "Add";
				$msg = "#".$file_id." file has been added";
			}
			else{
				$action = "Update";
				$msg = "#".$file_id." file has been updated";
			}
			
			
			$this->update_file_audit($action,$msg);
			/*File Audit work ends here*/
			return $file_id;
        }
        
    }
    /*---- end: scaning function ----*/
	
	
	function update_section_count($post_data) {
	
		$files_scanned = $this->get_total_files_scanned($post_data['section_id'], $post_data['file_type_id']);
		
		//echo '<pre>'; print_r($files_scanned); die();
	
		// count total file uploaded in current date
		$this->db->select('*');
        $this->db->where('fileTypeId', $post_data['file_type_id']);
        $this->db->where('sectionId', $post_data['section_id']);
        $result = $this->db->get('manage_section_count');
		
		//die('');
		
		if($result->num_rows() > 0) {		
			$manage_section_count = $result->result_array();
			$manage_section_count = $manage_section_count[0];
				
			if($files_scanned['total_file_scanned'] > $manage_section_count['totalFileCount']) {
		
				$data = array(
						'totalFileCount' => $manage_section_count['totalFileCount']+(isset($post_data['file_count']) ? $post_data['file_count'] : 1),
						'syncFile' => '0'
				); 
				
				$this->db->trans_begin();
				
				$this->db->where('manageSectionCountId', $manage_section_count['manageSectionCountId']);
				$this->db->set($data);
				$this->db->update('manage_section_count');

				$this->db->trans_complete();
				
				if($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					return FALSE;
				} else {
					$this->db->trans_commit();
					$action = 'update';
					//return true;
				}
			}
			else {
				return false;
			}
		}
		else {
		
			$query = $this->db->query("SELECT UUID() as uuid");
		
			if(!$query->num_rows() > 0)
				return false;
		
			$data = array(
					'manageSectionCountId'	=> $query->result_array()[0]['uuid'],
					'fileTypeId' 			=> $post_data['file_type_id'],
					'sectionId' 			=> $post_data['section_id'],
					'totalFileCount' 		=> (isset($post_data['file_count']) ? $post_data['file_count'] : 1),
					'enteredBy' 			=> '0',
					'enteredDate' 			=> date('Y-m-d H:i:s'),
			); 
			
			$this->db->trans_begin();
			
			$this->db->set($data);
			$this->db->insert('manage_section_count');

			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				$action = 'add';
				//return true;
			}
		}
		
		
		$data_manage_section = array(
			'sectionId' 	=> $post_data['section_id'],
			'fileTypeId'	=> $post_data['file_type_id'],
			'startDate' 	=> null,
			'endDate' 		=> null,
			'fileCount' 	=> (isset($post_data['file_count']) ? $post_data['file_count'] : 1),
			'action' 		=> $action,
			'enteredBy'		=> '0'
		);
		
		$this->manage_section_count_history($data_manage_section);
		
		
	}
    
	
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
		
		$upload_dir = $this->config->item('fileUploadPath');
		//$upload_dir = 'upload/scaning/';
		
		list($day, $month, $year) = explode('-', date('d-m-Y'));
		
		if(!is_dir($upload_dir.$year)) {
			mkdir($upload_dir.$year, 0777);
			chmod($upload_dir.$year, 0777); 
		}
		
		if(!is_dir($upload_dir.$year.'/'.$month)) {
			mkdir($upload_dir.$year.'/'.$month, 0777);
			chmod($upload_dir.$year.'/'.$month, 0777); 
		}
		
		if(!is_dir($upload_dir.$year.'/'.$month.'/'.$day)) {
			mkdir($upload_dir.$year.'/'.$month.'/'.$day, 0777);
			chmod($upload_dir.$year.'/'.$month.'/'.$day, 0777); 
		}
		
		$upload_dir_path = $upload_dir.$year.'/'.$month.'/'.$day.'/';
		$upload_temp_dir = $upload_dir.'temp_files/';
		
		foreach($post_data['file_uploaded_name'] as $key => $get_file_name) {
		
			$infoFile = new SplFileInfo($get_file_name);
			$extension = $infoFile->getExtension();
			
			$new_file_name = $file_id.'.'.$extension;
			
			copy($upload_temp_dir.$get_file_name, $upload_dir_path.$get_file_name);
			rename($upload_dir_path.$get_file_name, $upload_dir_path.$new_file_name);
			unlink($upload_temp_dir.$get_file_name);
		
			$data = array(
					'fileDetailId'		=> $file_id,
					'moduleType'		=> 'scaning',
					'OriginalFileName'	=> $post_data['file_uploaded_real_name'][$key],
					'fileName'			=> $new_file_name,
					'fileSize'          => $post_data['file_uploaded_size'][$key],
					'fileType'          => $post_data['file_uploaded_type'][$key],
					'filePath'          => $upload_dir_path.$new_file_name,
					//'fileUploadedDate'	=> date('Y-m-d H:i:s'),
					//'fileUploadedBy'	=> $user_id
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
		
			/*$this->db->select('*');
			$this->db->where('fileUploadedId', $get_file_uploaded_id);
			$result = $this->db->get('file_uploaded fu');
			
			if($result->num_rows() > 0) {            
				$file_uploaded_data = $result->result_array();
				$file_uploaded_data = $file_uploaded_data[0];
				
				//echo $file_uploaded_data['img_url']
				
				$infoFile = new SplFileInfo($file_uploaded_data['OriginalFileName']);
				$extension = $infoFile->getExtension();
				
				$new_file_name = $file_number.'.'.$extension;
				
				
				$new_file_path = 'upload/scaning/'.$new_file_name;
				$old_file_path = 'upload/scaning/'.$file_uploaded_data['OriginalFileName'];
				
				
				
				rename($old_file_path, $new_file_path);
				//unlink($old_file_path);
					
				$data = array(
						'fileDetailId' => $file_id,
						'fileName' => $new_file_name,
						'filePath' => $new_file_path
				); 
				
				$this->db->trans_begin();
				
				$this->db->where('fileUploadedId', $get_file_uploaded_id);
				$this->db->set($data);
				$this->db->update('file_uploaded');

				$this->db->trans_complete();
				
				if($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					return FALSE;
				} else {
					$this->db->trans_commit();
					//return;
				}
			}*/
		
		}
		
	}
	/*---- end: update_media function ----*/
	
	
	/*
	|------------------------------------------------
    | start: generate_file_number function
    |------------------------------------------------
    |
    | This function add file number
    |
   */
    function generate_file_number() {
	
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
		
		
		
	
		if($this->input->post('file_type_id') == '1') {
			$file_number .= 'P';
		}
		
		if($this->input->post('file_type_id') == '2') {
			$file_number .= 'A';
		}
		
		if($this->input->post('file_type_id') == '3') {
			$file_number .= 'G';
		}
		
		return $file_number;
		
		/*$data = array(
				'fileNumber' => $file_number
		);
		
		
		$this->db->trans_begin();
		
		$this->db->where('fileId', $file_id);
		$this->db->set($data);
		$this->db->update('file_detail');

		$this->db->trans_complete();
		
		if($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return $file_number;
		}*/
		
	}
	/*---- end: generate_file_number function ----*/
	
	
	
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
    | start: get_scaning function
    |------------------------------------------------
    |
    | This function get all scaning data and get column wise
    |
    */
    function get_scaning($db_where = null, $db_select = null) {
        
		if($db_select) {
			$this->db->select($db_select);
		}
		else {
			$this->db->select('fd.*, ft.fileType, s.sectionName, fu.fileName, fc.generalCategoryName, fu.fileSize, fu.filePath,CONCAT (up.upro_first_name, " " ,up.upro_last_name) as created_by_name, fu.OriginalFileName');
		}
		
        
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }
		
		$this->db->join('file_types ft', 'ft.fileTypeId = fd.fileTypeId');
		$this->db->join('user_section s', 's.sectionId = fd.sectionId');
		$this->db->join('file_uploaded fu', 'fu.fileDetailId = fd.fileId');
		$this->db->join('general_file_category fc', 'fc.generalCategoryId = fd.generalCategoryId', 'LEFT');
		$this->db->join('user_profiles up', 'up.upro_uacc_fk = fd.createdBy');
		$this->db->order_by('createdDate','desc');
        $result = $this->db->get('file_detail fd');
        
        if($result->num_rows() > 0) {            
            $data = $result->result_array();            
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_scaning function ----*/
	
	//File Audit code will come here	
	// get scan unit
	function get_scan_unit($db_where = null, $db_limit = null, $db_order = null, $db_count = null, $fileType = null) {
		
		if($db_count==null){
		//$this->db->select('fd.*, ft.fileType,u.fileName,us.sectionName,g.generalCategoryName,CONCAT (up.upro_first_name, " " ,up.upro_last_name) as created_by_name');
		$this->db->select('fd.*, ft.fileType,us.sectionName,g.generalCategoryName,CONCAT (up.upro_first_name, " " ,up.upro_last_name) as created_by_name');
        }
		else{
		$this->db->select('COUNT(fd.fileId) as fdCount');
		}
		$this->db->from('file_detail fd');
        
        if ($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }
		
		if ($db_limit) {
            $this->db->limit($db_limit['limit'], $db_limit['startPageRecord']);
        }
        
       if($db_order) {
            foreach($db_order as $get_order) {
                $this->db->order_by($get_order['title'], $get_order['order_by']);
            } 
        }
		
		$this->db->join('file_types ft', 'ft.fileTypeId = fd.fileTypeId');
		$this->db->join('user_section us', 'us.sectionId = fd.sectionId');
		
		//$this->db->join('file_uploaded u', 'u.fileDetailId = fd.fileId');
		
		$this->db->join('general_file_category g', 'g.generalCategoryId = fd.generalCategoryId','LEFT');
		$this->db->join('user_profiles up', 'up.upro_uacc_fk = fd.createdBy');
		if($fileType == '1'){
			//$this->db->group_by('fd.employeeCNIC');
			//$this->db->limit($db_limit['limit'], $db_limit['startPageRecord']);
			//$query =  $this->db->get_compiled_select();
			//$query = '('.$query.')';
			
			
			
			//$this->db->query("Select * from ".$query." as temp_table Group By employeeCNIC");
			//$this->db->group_start();
			//$this->db->from("($query)",false);
			//$this->db->group_end();
			$this->db->group_by('employeeCNIC');
			//$completeq =  $this->db->get();
			
			
			//$this->db->group_by('fd.employeeCNIC');
		}
		//print_r($completeq); exit;
		$this->db->order_by('fd.createdDate','DESC');
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
				
				if($fileType == '1') {
					$count = count($result);
				}
				else {
					foreach ($result as $key => $get_result) {
						$count = $get_result['fdCount'];
					}
				}
					
				return $count;
			}
            
        } else {
            return 0;
        }
		
		
	}
	/*
    |------------------------------------------------
    | start: general category Name function
    |------------------------------------------------
    |
    | This function will get all general category file names
    |
    */
	function get_general_category_name(){
		$this->db->select('g.generalCategoryId,g.generalCategoryName,ft.fileType');
		$this->db->JOIN("file_types ft", 'ft.fileTypeId = g.fileTypeId', "INNER");
		$query = $this->db->get('general_file_category g');
		
		 foreach ($query->result_array() as $row) {
			 $data[$row['generalCategoryId']] = $row;
		 }
		 return $data;
	}
    /*End function of get general category name*/
	/*Start check duplicated general category names*/
	public function check_duplicates_general_category_name() {
        $this->db->select('*');
        $this->db->where('generalCategoryName', $this->input->post("general_category_name"));
        $this->db->where('fileTypeId', $this->input->post("file_type_id"));
        $result = $this->db->get('general_file_category');
        if ($result->num_rows() > 0) {
            return TRUE;
        } else
            return FALSE;
    }
	/*End of check duplicat general category names*/
	/*Start General Category Name*/
	public function add_general_category_name() {
	
		$user_id = $this->flexi_auth->get_user_id();
		
    	$formData = $this->input->post();
		
		$data= array(
           'generalCategoryName'	=> $formData['general_category_name'],
           'fileTypeId'				=> $formData['file_type_id'],
           'createdDate'      		=> date('Y-m-d H:i:s'),
           'createdBy'      		=> $user_id,
		   'isExtraCategory' 	=> (isset($formData['isExtraCategory'])) ? $formData['isExtraCategory'] : '0',
		);
		
		$this->db->trans_begin();
        $this->db->set($data);
        $this->db->insert('general_file_category');
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
	/*End of Add General Category Name*/
	/*Start of Edit General Category Name*/
	function edit_general_category_name()
	{
		$formData = $this->input->post();
		
		$data= array(
           'generalCategoryName' => $formData['general_category_name'] ,
		   'isExtraCategory' 	=> (isset($formData['isExtraCategory'])) ? $formData['isExtraCategory'] : '0',
        );
		
		$this->db->trans_begin();
        $this->db->where('generalCategoryId', $formData["generalCategoryId"]);
		$this->db->set($data);
        $this->db->update('general_file_category');
        $this->db->trans_complete();

        //trans_complete

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $b = $this->db->trans_commit();
			return true;
        }
	}
	/*End of Edit General Category Name*/
	/*Start of Get General Category By id*/
	function get_general_category_name_by_id($id)
	{
		$this->db->select('*');
		$this->db->where('generalCategoryId',$id);
        $query = $this->db->get('general_file_category');
		return $query->row_array();
	}
	/*End of Get General Category By Id*/
	/*Delete General File Category Start here*/
	function delete_general_file_category($id)
	{
		$this->db->trans_begin();
        $this->db->where('generalCategoryId', $id); 
        $this->db->delete('general_file_category');
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
	/*Delete General File Category Ends here */
	/*Manage File Count starts Here*/
	function add_manage_section_count($count_acton = null){
	
		//echo '<pre>'; print_r($this->input->post()); die();
		
		//echo $total_files_scanned['total_file_scanned']; die();
		$form_data = $this->input->post();
		$user_id = $this->flexi_auth->get_user_id();
		
		
		foreach($form_data['file_type_id'] as $key => $get_file_type_id) {
			
			//$total_files_scanned = $this->get_total_files_scanned($this->input->post('section_id_post_session'), $form_data['file_type_id'][$key]);
			//echo '<pre>'; print_r($total_files_scanned);
			//echo $count_acton;
			//continue;
			
			if($count_acton) {
				$file_count = $form_data['total_file_count'][$key];
			}
			else {
				//($total_files_scanned && $form_data['file_count'][$key] == $form_data['file_count_old'][$key]) ? $form_data['file_count'][$key] : $total_files_scanned['total_file_scanned']+$form_data['file_count'][$key]
				$file_count = $form_data['file_count_old'][$key]+$form_data['file_count'][$key];
			}
			
			$data = array(
					'sectionId' => $form_data['section_id_post_session'],
					'fileTypeId' => $get_file_type_id,
					'startDate' => ($form_data['start_date'][$key]) ? date("Y-m-d",strtotime($form_data['start_date'][$key])) : '',
					'endDate' => ($form_data['end_date'][$key]) ? date("Y-m-d",strtotime($form_data['end_date'][$key])) : '',
					'totalFileCount' => $file_count,
					//'enteredBy' => $user_id
				);
				
			$this->db->trans_begin();
			
			if($form_data['manage_section_count'][$key]) { 
			
				//echo $file_count;
				//echo '<br />';
				//continue;
			
				if(($form_data['file_count'][$key] == $form_data['file_count_old'][$key]) || ($count_acton && $form_data['total_file_count'][$key] == $form_data['file_count_old'][$key]))
					continue;
				
				
				//echo $file_count;
				//echo '<br />';
				//continue;
				
				$data['syncFile'] = '0';
				
				$this->db->where('manageSectionCountId', $form_data['manage_section_count'][$key]);
				$this->db->set($data);
				$this->db->update('manage_section_count');
				
				$data['action'] = 'update';
			}
			else {
				if(empty($form_data['file_count'][$key]))
					continue;
				
				$query = $this->db->query("SELECT UUID() as uuid");
		
				if(!$query->num_rows() > 0)
					return false;
				
				$data['manageSectionCountId']	= $query->result_array()[0]['uuid'];
				$data['enteredDate'] 			= date('Y-m-d H:i:s');
				$data['enteredBy'] 				= $user_id;
			
				$this->db->set($data);
				$this->db->insert('manage_section_count');
				//$id_id = $this->db->insert_id();
				
				$data['action'] = 'add';
				
			}
			
			$this->db->trans_complete();
				
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				
				if($form_data['file_count'][$key] != $form_data['file_count_old'][$key]) {
				
					if($count_acton) {
						$data['fileCount'] = $form_data['total_file_count'][$key];
						$data['action'] = 'correct';
					}
					else {
						//($total_files_scanned && $form_data['file_count'][$key] == $form_data['file_count_old'][$key]) ? $form_data['file_count'][$key] : $total_files_scanned['total_file_scanned']+$form_data['file_count'][$key]
						$data['fileCount'] = $form_data['file_count'][$key];
					}
				
				
					//$data['fileCount'] = $form_data['file_count'][$key];
					$this->manage_section_count_history($data);
				}
				
				//return $file_id;
			}
		}
		
		//die();
	}
	/*Manage File Count Ends here*/
	/*Get Section Wise Reporting Starts Here*/
	function get_sectionwise_reporting($db_where = NULL){
		$this->db->select('msc.*,count(fd.fileTypeId) as fileScan');
		
        //$this->db->JOIN("user_section us", 'us.sectionId = msc.sectionId', "INNER");
        //$this->db->JOIN("file_types ft", 'ft.fileTypeId = msc.fileTypeId', "INNER");
        /*$this->db->JOIN("user_accounts ua", 'ua.uacc_id = mfc.sectionOfficerId', "INNER");*/
		//$this->db->JOIN("file_detail fd", 'fd.sectionId = msc.sectionId', "INNER");
		$this->db->JOIN("file_detail fd", '`fd`.`sectionId` = `msc`.`sectionId` AND fd.fileTypeId = msc.fileTypeId', "LEFT");
				
		if ($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
					if($key == "msc.sectionId"){
						$this->db->where_in($key, explode(',', $columnVal));
					}
					else{
						$this->db->where($key, $columnVal);
					}
                }
            }
        }
		
		$this->db->group_by('msc.fileTypeId, msc.sectionId');
		$this->db->order_by('msc.sectionId, fd.fileTypeId');
		
		$query = $this->db->get('manage_section_count msc');
		
		 foreach ($query->result_array() as $row) {
			 $data[$row['manageSectionCountId']] = $row;
		 }
		 if($data){
			return $data;
			
		 }
		 else{
			 return FALSE;
		 }
	}
	/*Get Section Wise Reporting Ends Here*/
	/*Get User Wise Reporting Starts Here*/
	function get_userwise_reporting($db_where = NULL){
		$this->db->select('us.sectionId,us.sectionName,fd.fileTypeId,COUNT(fd.fileTypeId) AS fileScan,up.upro_first_name,up.upro_last_name,ua.uacc_username');
		
        //$this->db->JOIN("user_section us", 'us.sectionId = msc.sectionId', "INNER");
        //$this->db->JOIN("file_types ft", 'ft.fileTypeId = msc.fileTypeId', "INNER");
		$this->db->JOIN("file_detail fd", '`fd`.`sectionId` = `us`.`sectionId`', "LEFT");
		$this->db->JOIN("user_accounts ua", 'ua.uacc_id = fd.createdBy', "INNER");
		$this->db->JOIN("user_profiles up", 'up.upro_uacc_fk = ua.uacc_id', "INNER");
		
		
		
		if ($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
					if($key == "us.sectionId"){
						$this->db->where_in($key, explode(',', $columnVal));
					}
					/*if($key == "fd.createdDate"){
						$this->db->where($key, $columnVal);
					}*/
					else{
						$this->db->where($key, $columnVal);
					}
                }
            }
        }
		
		$this->db->group_by('fd.fileTypeId, fd.sectionId, fd.createdBy');
		$this->db->order_by('fd.sectionId,fd.fileTypeId');
		
		$query = $this->db->get('user_section us');
		
		 foreach ($query->result_array() as $row) {
			 $data[] = $row;
		 }
		 if($data){
			return $data;
			
		 }
		 else{
			 return FALSE;
		 }
	}
	/*Get User Wise Reporting Ends Here*/
	/*Get Total Number of Files to be scan start here*/
	function get_total_number_of_files_to_be_scan($section_id) {
	
		//echo $section_id; die();
	
		$this->db->select('SUM(totalFileCount) as total_file_to_be_scan');
		
		//if($section_id)
			//$this->db->where("sectionId", $section_id);
		
		if($section_id)
			$this->db->where_in('sectionId', explode(',', $section_id));
		
		$query = $this->db->get('manage_section_count');
		
		foreach ($query->result_array() as $row) {
			$data = $row;
		}
		 
		return $data;
	}
	/*Get Total Number of files to be scan ends here*/
	
	/*Total Files Scanned till date start here*/
	function get_total_files_scanned($section_id, $file_type_id = null){
		$this->db->select('count(sectionId) as total_file_scanned');
		
		if($section_id)
			$this->db->where_in('sectionId', explode(',', $section_id));
		
		if($file_type_id)
			$this->db->where('fileTypeId', $file_type_id);
	
		$query = $this->db->get('file_detail');
		
		 foreach ($query->result_array() as $row) {
			 $data = $row;
		 }
		 return $data;
	}
	/*Total File Scanned till date ends here */
	/*Total files scanned today starts here*/
	function get_today_scanning($section_id){
		$date = date("Y-m-d");
		$this->db->select('count(sectionId) as total_file_scanned_today');
		$this->db->where("DATE(`createdDate`)", $date);
		
		if($section_id)
			$this->db->where_in('sectionId', explode(',', $section_id));
		
		$query = $this->db->get('file_detail');
		
		 foreach ($query->result_array() as $row) {
			 $data = $row;
		 }
		 return $data;
	}
	/*Total files scanned today ends here*/
	
	/*Get Count of every Section  Starts here*/
	function get_all_manage_count_data($db_where = NULL){
		$this->db->select('*');
		if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }
		$query = $this->db->get('manage_section_count');
		
		 foreach ($query->result_array() as $row) {
			$data[$row['fileTypeId']] = $row;
			 
			$total_files_scanned = $this->get_total_files_scanned($row['sectionId'], $row['fileTypeId']);
			$data[$row['fileTypeId']]['entered_count'] = $total_files_scanned['total_file_scanned'];
		 }
		 return $data;
	}
	/*Get Count of every Section Ends here*/
	
	/*Get File Type Start here*/
	function get_file_types($db_where = null){
		$this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }

        $result = $this->db->get('file_types f');
        foreach ($result->result_array() as $row) {
				$data= $row['fileTypeId'];
		}            
       return $data;

	}
	/*Get File Type Ends here*/
	
	
	function manage_section_count_history($data) {
	
		//echo '<pre>'; print_r($data); //die();
        
        $data = array(
                'sectionId'      	=> $data['sectionId'],
                'fileTypeId'		=> $data['fileTypeId'],
                'startDate'    		=> $data['startDate'],
                'endDate'    		=> $data['endDate'],
                'totalFileCount'    => $data['fileCount'],
                'action'    		=> $data['action'],
                'actionPerformedBy'	=> $data['enteredBy'],
                'PerformedOn'		=> date('Y-m-d H:i:s')
        );
		
        $this->db->trans_begin();
        
        
		$this->db->set($data);
		$this->db->insert('manage_section_count_history');
		$manage_section_id = $this->db->insert_id();
		
        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
			return $manage_section_id;
        }
	
	}
	
	/*------------------- start: Entered by --------------*/
	function get_entered_by(){
		$this->db->select('ua.uacc_id,CONCAT (up.upro_first_name, " " ,up.upro_last_name) as entered_by_name');
		$this->db->join('user_profiles up', 'up.upro_uacc_fk = ua.uacc_id');
		       
        $result = $this->db->get('user_accounts ua');
		foreach ($result->result_array() as $row) {
				// echo "<pre>"; print_r($row); exit;
				$data[$row['uacc_id']]= $row;
		}
		return $data;
		
	}
	/*------------------- end: entered by ----------------*/
}