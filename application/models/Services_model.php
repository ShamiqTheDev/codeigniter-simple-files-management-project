<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Services_model extends CI_Model {


	public function verify_password($identity, $verifyPassword)
	{
		
		if (empty($identity) || empty($verifyPassword))
		{
			return FALSE;
		}
				
		$sqlSelect = array(
			$this->auth->tbl_col_user_account['password'],
			$this->auth->tbl_col_user_account['salt']
		);
		
		$query = $this->db->select($sqlSelect)
			->where('user_accounts.uacc_username', $identity)
			->limit(1)
			->get($this->auth->tbl_user_account);
				 
	    $result = $query->row();

	    if ($query->num_rows() !== 1)
	    {
			return FALSE;
	    }
		
				
		$databasePassword = $result->{$this->auth->database_config['user_acc']['columns']['password']};
		$databaseSalt = $result->{$this->auth->database_config['user_acc']['columns']['salt']};
		$staticSalt = $this->auth->auth_security['static_salt'];
		
		require_once(APPPATH.'libraries/phpass/PasswordHash.php');				
		$hashToken = new PasswordHash(8, FALSE);
		
		return $hashToken->CheckPassword($databaseSalt . $verifyPassword . $staticSalt, $databasePassword);
		
	}
	
	
	/*
    |------------------------------------------------
    | start: add_scanning_details function
    |------------------------------------------------
    |
    | This function add scanning data on cloud 
    |
	*/
    function sync_scanning_details($scanningData) {
	
		// verify record already exist
		$dbWhere 	= array('fd.fileId' => $scanningData->fileId);
		$dbLimit 	= null;
		$dbSelect	= 'fd.fileId';
		
		if($this->get_scanning($dbWhere, $dbLimit, $dbSelect)) {
			$insertData['fileId'] 	= $scanningData->fileId;
			$insertData['syncFile']	= 1;
			
			return $insertData;
		}
		
		$data = array(
				'fileId'      		=> $scanningData->fileId,
				'fileTypeId'      	=> $scanningData->fileTypeId,
				//'cityId'      		=> $scanningData['cityId'],
				//'locationId'      	=> $scanningData['locationId'],
				'sectionId'    		=> $scanningData->sectionId,
				'employeeName'    	=> $scanningData->employeeName,
				'employeeCNIC'    	=> $scanningData->employeeCNIC,
				'oldFileNumber'    	=> $scanningData->oldFileNumber,
				'seniorityNo'    	=> $scanningData->seniorityNo,
				'subject'    		=> $scanningData->subject,
				'createdBy'			=> $scanningData->createdBy,
				'generalCategoryId'	=> $scanningData->generalCategoryId,
				'createdDate'		=> $scanningData->createdDate,
				'updatedBy'			=> $scanningData->updatedBy,
				'updatedDate'		=> $scanningData->updatedDate,
				'syncFile'			=> 1,
		);
		
		$this->db->trans_begin();
	
		$this->db->set($data);
		$this->db->insert('file_detail');
		
		
		$this->db->trans_complete();
		
		//echo $this->db->trans_status();exit;
		if($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			$this->sync_upload_file($scanningData);
			$this->sync_file_audit($scanningData);
			
			$insertData['fileId'] 	= $scanningData->fileId;
			$insertData['syncFile']	= 1;
		
			return $insertData;
		}
        
    }
    /*---- end: add_scanning_details function ----*/
	
	
	/*
    |------------------------------------------------
    | start: add_upload_file function
    |------------------------------------------------
    |
    | This function add upload file data on cloud
    |
	*/
    function sync_upload_file($scanningData) {
		
		$data = array(
				'fileDetailId'      => $scanningData->fileDetailId,
				'OriginalFileName'	=> $scanningData->OriginalFileName,
				'fileName'      	=> $scanningData->fileName,
				'fileSize'    		=> $scanningData->fileSize,
				'fileType'    		=> $scanningData->fileType,
				'filePath'    		=> $scanningData->filePath,
				'fileUploadedDate'	=> $scanningData->fileUploadedDate,
				'fileUploadedBy'    => $scanningData->fileUploadedBy,
				'isDeleted'    		=> $scanningData->isDeleted,
				'DeletedBy'			=> $scanningData->DeletedBy,
		);	
		
		
		$this->db->trans_begin();
	
		$this->db->set($data);
		$this->db->insert('file_uploaded');
		
		$this->db->trans_complete();
	
		if($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
        
    }
    /*---- end: add_upload_file function ----*/
	
	
	/*
    |------------------------------------------------
    | start: add_file_audit function
    |------------------------------------------------
    |
    | This function add upload file data on cloud
    |
	*/
    function sync_file_audit($scanningData) {
		
		$data = array(
				'fileAction'    	=> $scanningData->fileAction,
				'fileMsg'      		=> $scanningData->fileMsg,
				'actionPerformedBy'	=> $scanningData->actionPerformedBy,
				'performedOn'      	=> $scanningData->performedOn,
		);		
		
		$this->db->trans_begin();
	
		$this->db->set($data);
		$this->db->insert('file_audit');
		
		$this->db->trans_complete();
	
		if($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
        
    }
    /*---- end: add_file_audit function ----*/
	
	
	/*
    |------------------------------------------------
    | start: update_scanning function
    |------------------------------------------------
    |
    | This function update scanning local data
    |
	*/
    function update_scanning($response) {
		
		$data = array(
				'syncFile' => $response->syncFile,
		);	
		
		
		$this->db->trans_begin();
	
		$this->db->where('fileId', $response->fileId);
		$this->db->set($data);
		$this->db->update('file_detail');
		
		$this->db->trans_complete();
	
		if($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return FALSE;
		} else {
			$this->db->trans_commit();
			return TRUE;
		}
        
    }
    /*---- end: update_scanning function ----*/
	
	
	/*
    |------------------------------------------------
    | start: get_scanning function
    |------------------------------------------------
    |
    | This function get all scaning data and get column wise
    |
    */
    function get_scanning($dbWhere = null, $dbLimit = null, $dbSelect = null) {
        
		if($dbSelect) {
			$this->db->select($dbSelect);
		}
		else {
			$this->db->select('fd.*, fu.fileName, fu.fileSize, fu.filePath');
			//$this->db->select('fd.*, ft.fileType, s.sectionName, fu.fileName, fc.generalCategoryName, fu.fileSize, fu.filePath');
		}
		
        
        
        if($dbWhere) {
            foreach ($dbWhere as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }
		
		//$this->db->join('file_types ft', 'ft.fileTypeId = fd.fileTypeId');
		//$this->db->join('user_section s', 's.sectionId = fd.sectionId');
		$this->db->join('file_uploaded fu', 'fu.fileDetailId = fd.fileId');
		//$this->db->join('general_file_category fc', 'fc.generalCategoryId = fd.generalCategoryId', 'LEFT');
		$this->db->join('file_audit fa', "fa.fileMsg LIKE CONCAT('%', fd.fileId, '%')");
		
		if($dbLimit)
			$this->db->limit($dbLimit);
		
		$this->db->order_by('fd.createdDate');

        $result = $this->db->get('file_detail fd');
        if(!empty($result)){
			if($result->num_rows() > 0) {            
				$data = $result->result_array();            
				return $data;
			}
			else
				return FALSE;
		}
		else
			return FALSE;
        
    }
    /*---- end: get_scanning function ----*/
	
	
	/*
	|------------------------------------------------
    | start: get_section_count function
    |------------------------------------------------
    |
    | This function get all scaning data and get column wise
    |
    */
    function get_section_count($dbWhere = null) {
        
		$this->db->select('*');
		
        if($dbWhere) {
            foreach ($dbWhere as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }
		
		$this->db->order_by('msc.enteredDate');
		
        $result = $this->db->get('manage_section_count msc');
        if(!empty($result)){
			if($result->num_rows() > 0) {            
				$data = $result->result_array();            
				return $data;
			}
			else
				return FALSE;
        }
		else
			return FALSE;
    }
    /*---- end: get_section_count function ----*/
	
	
	/*
    |------------------------------------------------
    | start: sync_section_count function
    |------------------------------------------------
    |
    | This function get all scaning data and get column wise
    |
    */
	function sync_section_count($sectionCountData){
			
		$insertData = array();
		
		foreach($sectionCountData as $key => $getSectionCountData) {
		
			// verify record already exist
			$dbWhere = array('msc.manageSectionCountId' => $getSectionCountData->manageSectionCountId);
		
			$data = array(
					'sectionId' 			=> $getSectionCountData->sectionId,
					'fileTypeId'			=> $getSectionCountData->fileTypeId,
					'startDate'				=> $getSectionCountData->startDate,
					'endDate'				=> $getSectionCountData->endDate,
					'totalFileCount'		=> $getSectionCountData->totalFileCount,
					'enteredBy'				=> $getSectionCountData->enteredBy,
					'enteredDate'			=> $getSectionCountData->enteredDate,
					'syncFile'				=> 1,
			);
			
			$this->db->trans_begin();
			
			if($this->get_section_count($dbWhere)) {
				$this->db->where('manageSectionCountId', $getSectionCountData->manageSectionCountId);
				$this->db->set($data);
				$this->db->update('manage_section_count');
			}
			else {
				$data['manageSectionCountId'] = $getSectionCountData->manageSectionCountId;
				$this->db->set($data);
				$this->db->insert('manage_section_count');
			}
			
			$this->db->trans_complete();
			//trans_complete

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$b = $this->db->trans_commit();
				$insertData[$key]['manageSectionCountId']	= $getSectionCountData->manageSectionCountId;
				$insertData[$key]['syncFile']				= 1;
				//return $id;
			}
		
		}
		
		return $insertData;
		
	}
	/*---- end: sync_section_count function ----*/
	
	
	/*
    |------------------------------------------------
    | start: update_section_count function
    |------------------------------------------------
    |
    | This function update scanning local data
    |
	*/
    function update_section_count($response) {
		
		foreach($response as $getSectionCount) {
		
			$data = array(
					'syncFile' => $getSectionCount->syncFile,
			);	
			
			
			$this->db->trans_begin();
		
			$this->db->where('manageSectionCountId', $getSectionCount->manageSectionCountId);
			$this->db->set($data);
			$this->db->update('manage_section_count');
			
			$this->db->trans_complete();
		
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				//return TRUE;
			}
		
		}
        
    }
    /*---- end: update_section_count function ----*/
	
	
	/* ##############################################################################################
	############################################################################################## */
		
	/*
	|------------------------------------------------
    | start: get_section_count_history function
    |------------------------------------------------
    |
    | This function get all scaning data and get column wise
    |
    */
    function get_section_count_history($dbWhere = null) {
        
		$this->db->select('*');
		
        if($dbWhere) {
            foreach ($dbWhere as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }
		
		$this->db->order_by('msch.PerformedOn');
		
        $result = $this->db->get('manage_section_count_history msch');
        if(!empty($result)){
			if($result->num_rows() > 0) {            
				$data = $result->result_array();            
				return $data;
			}
			else
				return FALSE;
        }
		else	
			return FALSE;
    }
    /*---- end: get_section_count_history function ----*/
	
	
	/*
    |------------------------------------------------
    | start: sync_section_count_history function
    |------------------------------------------------
    |
    | This function get all scaning data and get column wise
    |
    */
	function sync_section_count_history($sectionCountData){
	
		//echo '<pre>'; print_r($sectionCountData); die();
		//echo json_encode($sectionCountData); die();
		
		$insertData = array();
		
		foreach($sectionCountData as $key => $getSectionCountData) {
				
			$data = array(
					'sectionId' 		=> $getSectionCountData->sectionId,
					'fileTypeId'		=> $getSectionCountData->fileTypeId,
					'startDate'			=> $getSectionCountData->startDate,
					'endDate'			=> $getSectionCountData->endDate,
					'totalFileCount'	=> $getSectionCountData->totalFileCount,
					'action'			=> $getSectionCountData->action,
					'actionPerformedBy'	=> $getSectionCountData->actionPerformedBy,
					'PerformedOn'		=> $getSectionCountData->PerformedOn,
					'syncFile'			=> 1,
			);
			
			$this->db->trans_begin();			
			
			$this->db->set($data);
			$this->db->insert('manage_section_count_history');
			
			$this->db->trans_complete();
			//trans_complete

			if ($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$b = $this->db->trans_commit();
				$insertData[$key]['sectionCountHistoryId']	= $getSectionCountData->sectionCountHistoryId;
				$insertData[$key]['syncFile']				= 1;
				//return $id;
			}
		
		}
		
		return $insertData;
		
	}
	/*---- end: sync_section_count_history function ----*/
	
	
	/*
    |------------------------------------------------
    | start: update_section_count_history function
    |------------------------------------------------
    |
    | This function update scanning local data
    |
	*/
    function update_section_count_history($response) {
		
		foreach($response as $getSectionCount) {
		
			$data = array(
					'syncFile' => $getSectionCount->syncFile,
			);	
			
			
			$this->db->trans_begin();
		
			$this->db->where('sectionCountHistoryId', $getSectionCount->sectionCountHistoryId);
			$this->db->set($data);
			$this->db->update('manage_section_count_history');
			
			$this->db->trans_complete();
		
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				//return TRUE;
			}
		
		}
        
    }
    /*---- end: update_section_count_history function ----*/
	
	/* ==============================================================================================
	============================================================================================== */
	
	
	/*function get_scanning_temp($dbWhere = null, $dbLimit = null, $dbSelect = null) {
        
		if($dbSelect) {
			$this->db->select($dbSelect);
		}
		else {
			$this->db->select('fd.*, fu.fileName, fu.fileSize, fu.filePath');
			//$this->db->select('fd.*, ft.fileType, s.sectionName, fu.fileName, fc.generalCategoryName, fu.fileSize, fu.filePath');
		}
		
        
        
        if($dbWhere) {
            foreach ($dbWhere as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }
		
		//$this->db->join('file_types ft', 'ft.fileTypeId = fd.fileTypeId');
		//$this->db->join('user_section s', 's.sectionId = fd.sectionId');
		$this->db->join('file_uploaded_temp fu', 'fu.fileDetailId = fd.fileId');
		//$this->db->join('general_file_category fc', 'fc.generalCategoryId = fd.generalCategoryId', 'LEFT');
		$this->db->join('file_audit_temp fa', "fa.fileMsg LIKE CONCAT('%', fd.fileId, '%')");

        $result = $this->db->get('file_detail_temp fd');
		
        if(!empty($result)){
		
			if($result->num_rows() > 0){
				$data = $result->result_array();            
				return $data;	
			}
			else{
				return false;
			}
        }
        else
            return FALSE;
        
    }
	
	function get_section_count_temp($dbWhere = null) {
        
		$this->db->select('*');
		
        if($dbWhere) {
            foreach ($dbWhere as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }
		
        $result = $this->db->get('manage_section_count_temp msc');
        if(!empty($result)){
			if($result->num_rows() > 0) {            
				$data = $result->result_array();            
				return $data;
			}
			else
				return FALSE;
		}
		else
			return FALSE;
        
    }*/
	
	
	
	/*
    |------------------------------------------------
    | start: get_scheme function
    |------------------------------------------------
    |
    | This function get all scaning data and get column wise
    |
    */
    function get_scheme($data_post = null) {
        
		$this->db->select('sc.parentAdpNumber, sc.adpYear, sc.adpNumber, sc.schemeName, sc.schemeDate, dt.documentType, cdt.documentType as documentTypeChild, sc.miscellaneousType, fu.fileName, fu.fileSize, fu.filePath');
		
		if(isset($data_post["adp_number"]) && isset($data_post["adp_year"])){
			$where = "((parentAdpNumber = '".$data_post['adp_number'].'-'.$data_post['adp_year']."') OR (adpNumber = '".$data_post['adp_number']."' AND adpYear = '".$data_post['adp_year']."'))";
			
			if(isset($data_post['document_type']) && $data_post['document_type'])
				$where .= " AND dt.documentType = '".$data_post['document_type']."'";
		}
		
		if(isset($data_post['doc_id']) && $data_post['doc_id']){ // for bar code schemes
			$where = "schemeId = '".$data_post['doc_id']."'";
		}
		
		$this->db->where($where);
		$this->db->where("sc.isDeleted",'0');
		
		$this->db->join('scheme_document_type dt', 'dt.documentTypeId = sc.documentTypeId');
		$this->db->join('file_uploaded fu', 'fu.fileDetailId = sc.schemeId');
		$this->db->join('scheme_document_type cdt', 'cdt.documentTypeId = sc.documentTypeChildId', 'LEFT');

        $result = $this->db->get('scheme sc');
        
        if($result->num_rows() > 0) {            
            $data = $result->result_array();            
            return $data;
        }
        else
            return null;
        
    }
    /*---- end: get_scheme function ----*/
	
	
	
	/*
    |------------------------------------------------
    | start: get_table_details function
    |------------------------------------------------
    |
    | This function get all scaning data and get column wise
    |
    */
    function get_table_details($db_data = null,  $db_join = null) {
	
		$table_prefix_name = array(
								'File Type' 	=> array('table' => 'file_types', 'primary_key' => 'fileTypeId'),
								'File Category'	=> array('table' => 'general_file_category', 'primary_key' => 'generalCategoryId'),
								'Section' 		=> array('table' => 'user_section', 'primary_key' => 'sectionId'),
								'File Detail' 	=> array('table' => 'file_detail', 'primary_key' => 'fileId'),
								'File Uploaded'	=> array('table' => 'file_uploaded', 'primary_key' => 'fileUploadedId'),
							);
							
		if(!isset($table_prefix_name[$db_data['db_table']]['table']))
			return null;
		
		$this->db->select($table_prefix_name[$db_data['db_table']]['table'].'.*');
		
		if($db_join) {		
			foreach($db_join as $get_db_join) {
				
				if($get_db_join['join_db_select'])
					$this->db->select($get_db_join['join_db_select']);
				
				$this->db->join($get_db_join['table_name'], $get_db_join['primary_key'].' = '.$table_prefix_name[$db_data['db_table']]['table'].'.'.$get_db_join['foreign_key'], $get_db_join['join_type']);
			}
		}
		
		if(isset($db_data['db_where']) && $db_data['db_where']) {
            foreach ($db_data['db_where'] as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }

        $result = $this->db->get($table_prefix_name[$db_data['db_table']]['table']);
        
        if($result->num_rows() > 0) {
			
			$primary_key = $table_prefix_name[$db_data['db_table']]['primary_key'];
			
			foreach($result->result_array() as $row){
				$data[$row[$primary_key]] = $row;
			}
			
            return $data;
        }
        else
            return null;
        
    }
    /*---- end: get_table_details function ----*/
	
	
	/*
	|------------------------------------------------
    | start: scanning_file_uploaded function
    |------------------------------------------------
    |
    | This function update media
    |
   */
    function scanning_file_uploaded() {
		
		$post_data = $this->input->post();
		
		// get file type by id
		$file_type_db = array('db_table' => 'File Type', 'db_where' => array('fileTypeId' => $post_data['file_type_id']));
		$file_type = $this->get_table_details($file_type_db);
		$file_type = $file_type[$post_data['file_type_id']];
		
		// get general file category by id
		$general_file_category_db = array('db_table' => 'File Category', 'db_where' => array('generalCategoryId' => $post_data['general_category_id']));
		$general_file_category = $this->get_table_details($general_file_category_db);
		$general_file_category = $general_file_category[$post_data['general_category_id']];		
	
		// START: create directory if not exist
		$upload_dir = $this->config->item('fileUploadPath');
		$upload_temp_dir = $upload_dir.'temp_files/';
		
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
		// END: create directory if not exist
		
		// load upload library
		$this->load->library('upload');
		
		$upload_config = array(
							'upload_path'   => $upload_temp_dir,
							'allowed_types' => $this->config->item('allowedFileType'),
							'max_size'      => $this->config->item('allowedFileSize'),
						);
						
		$this->upload->initialize($upload_config);
		
		//echo json_encode($post_data); die();
		
		$employee = json_decode($post_data['employee']);
		
		$file_detail_count = 0;
		
		foreach($employee as $get_employee) {
		
			// get file detail by employee cnic
			$file_detail_db = array('db_table' => 'File Detail', 'db_where' => array('employeeCNIC' => $get_employee->employee_cnic));
			$file_detail = $this->get_table_details($file_detail_db);
		
			//echo json_encode($get_employee); die();
		
			// START: create UUID
			$query = $this->db->query("SELECT UUID() as uuid");
		
			if(!$query->num_rows() > 0)
				return false;
		
			$file_id = $query->result_array()[0]['uuid'];
			// END: create UUID
			
			//echo json_encode($post_data['file_type_id']); die();
			
			$data = array(
					'fileId'      		=> $file_id,
					'fileTypeId'      	=> $post_data['file_type_id'],
					'appName'      		=> $post_data['app_name'],
					'generalCategoryId'	=> $post_data['general_category_id'],
					'employeeName'    	=> trim($get_employee->employee_name),
					'employeeCNIC'    	=> $get_employee->employee_cnic,
					'oldFileNumber'    	=> ($file_detail) ? current($file_detail['oldFileNumber']) : '0',
					'notificationNo'	=> $post_data['notification_no'],
					//'seniorityNo'    	=> $post_data['seniority_no'],
					'subject'    		=> $post_data['subject'],
					'sectionId'    		=> $post_data['section_id'],
					'createdDate'		=> date('Y-m-d H:i:s'),
					//'createdBy'			=> $this->flexi_auth->get_user_id()
			);
			
			//echo json_encode($data); die();
			
			$this->db->trans_begin();
			
			$this->db->set($data);
			$this->db->insert('file_detail');
			$file_id = $data['fileId'];
			
			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				//return $file_id;
				
				foreach($_FILES as $field_name => $file) {
					
					if(!$this->upload->do_upload($field_name)) {
						$upload_data = array('error' => $this->upload->display_errors());
					}
					else {
						
						$upload_data = $this->upload->data();
						
						$file_name = $upload_data['file_name'];
						
						$infoFile = new SplFileInfo($file_name);
						$extension = $infoFile->getExtension();
						
						//$new_file_name = $get_employee->employee_name.'_'.time().'_'.$file_id.'.'.$extension;
						$new_file_name = clean($file_type['fileType']).'_'.clean($general_file_category['generalCategoryName']).'_'.$get_employee->employee_cnic.'_'.mt_rand(10000,99999).$extension;
						
						copy($upload_temp_dir.$file_name, $upload_dir_path.$file_name);
						rename($upload_dir_path.$file_name, $upload_dir_path.$new_file_name);
						unlink($upload_temp_dir.$file_name);
						
						// START: insert files details in db
						$data = array(
								'fileDetailId'		=> $file_id,
								'moduleType'		=> 'scaning',
								'OriginalFileName'	=> $file_name,
								'fileName'			=> $new_file_name,
								'fileSize'          => $upload_data['file_size'],
								'fileType'          => $upload_data['file_type'],
								'filePath'          => $upload_dir_path.$new_file_name,
								'fileUploadedDate'	=> date('Y-m-d H:i:s'),
								//'fileUploadedBy'	=> $user_id
						); 
					
						$this->db->trans_begin();
						
						$this->db->set($data);
						$this->db->insert('file_uploaded');
						$file_uploaded_id = $this->db->insert_id();
						
						$this->db->trans_complete();
						
						if($this->db->trans_status() === FALSE) {
							$this->db->trans_rollback();
							return FALSE;
						} else {
							$b = $this->db->trans_commit();
							//return $data;
						}
						// END: insert files details in db
						
						//$return_data[] = $file_id;
						
					}
				}
				
				//return true;
				
			}
			
			$file_detail_count++;
		}
		
		return $file_detail_count;
		
		//return $return_data;
		
	}
	/*---- end: scanning_file_uploaded function ----*/
	
	
	/*
    |------------------------------------------------
    | start: get_file_details function
    |------------------------------------------------
    |
    | This function get all scaning data and get column wise
    |
    */
    function get_file_details($db_data = null) {

		$this->db->select('*');
		
		if($db_data['db_where']) {
            foreach ($db_data['db_where'] as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }
		
		$this->db->join('file_types ft', 'ft.fileTypeId = fd.fileTypeId');
		$this->db->join('user_section s', 's.sectionId = fd.sectionId');
		//$this->db->join('file_uploaded fu', 'fu.fileDetailId = fd.fileId');
		$this->db->join('general_file_category fc', 'fc.generalCategoryId = fd.generalCategoryId', 'LEFT');

        $result = $this->db->get('file_detail fd');
        
        if($result->num_rows() > 0) {
			
			foreach($result->result_array() as $row){
			
				$data[$row['fileId']] = $row;
			
				// get file type by id
				$file_uploaded_db = array('db_table' => 'File Uploaded', 'db_where' => array('fileDetailId' => $row['fileId']));
				$db_join = array(
								array(
									'table_name' 		=> 'user_profiles up',
									'primary_key' 		=> 'up.upro_uacc_fk',
									'foreign_key'		=> 'fileUploadedBy',
									'join_type'			=> 'INNER',
									'join_db_select'	=> 'up.upro_first_name, up.upro_last_name'
								)
							);
				
				$data[$row['fileId']]['file_uploaded'] = $this->get_table_details($file_uploaded_db, $db_join);
				
			}
			
            return $data;
        }
        else
            return null;
        
    }
    /*---- end: get_file_details function ----*/
    
    
    /*
    |------------------------------------------------
    | start: get_emp_efiles function
    |------------------------------------------------
    |
    | This function get all scaning data and get column wise against CNIC
    |
    */
    function get_emp_efiles($db_data = null) {

        $this->db->select('*');

        if ($db_data->db_where) {
            foreach ($db_data->db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }
        
        $this->db->join('file_types ft', 'ft.fileTypeId = fd.fileTypeId');
        $this->db->join('user_section s', 's.sectionId = fd.sectionId');
        //$this->db->join('file_uploaded fu', 'fu.fileDetailId = fd.fileId');
        $this->db->join('general_file_category fc', 'fc.generalCategoryId = fd.generalCategoryId', 'LEFT');

        $result = $this->db->get('file_detail fd');

        if ($result->num_rows() > 0) {

            foreach ($result->result_array() as $row) {

                $data[$row['fileId']] = $row;

                // get file type by id
                $file_uploaded_db = array('db_table' => 'File Uploaded', 'db_where' => array('fileDetailId' => $row['fileId']));
                $db_join = array(
                    array(
                        'table_name' => 'user_profiles up',
                        'primary_key' => 'up.upro_uacc_fk',
                        'foreign_key' => 'fileUploadedBy',
                        'join_type' => 'INNER',
                        'join_db_select' => 'up.upro_first_name, up.upro_last_name'
                    )
                );

                $data[$row['fileId']]['file_uploaded'] = $this->get_table_details($file_uploaded_db, $db_join);
            }

            return $data;
        } else
            return null;
    }
    
    /*---- end: get_file_details function ----*/

}
