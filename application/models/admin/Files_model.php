<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Files_model extends CI_Model {
		
		/*
		|------------------------------------------------
		| start: create function
		|------------------------------------------------
		|
		| This function add/create file details
		|
		*/
		function create($file_id) {
			
			$user_id = $this->flexi_auth->get_user_id();
			
			$post_data = $this->input->post();
			
			//echo "<pre>";print_r($post_data);exit;

			$data = array(
				//'fileNumber'		=> $this->generate_file_no(),
				'description'		=> $post_data['description'],
				'generalCategoryId'	=> $post_data['general_category_id'],
				'fileTypeId'		=> $post_data['file_type_id'],
				'employeeName'    	=> trim($post_data['employee_name']),
                'employeeCNIC'    	=> $post_data['employee_cnic'],
				'categoryId'    	=> $post_data['category_id'],
				'subCategoryId'    	=> $post_data['sub_category_id'],
				'classifiedId' 		=> $post_data['classified_id'],
				'remarks' 			=> $post_data['remarks'],
				'oldFileNumber'    	=> $post_data['old_file_number'],
				'fileStatus' 		=> 'created',
				'past_reference' 	=> $post_data['past_reference'],
				'future_reference' 	=> $post_data['future_reference'],
			);
			
			$this->db->trans_begin();
			
			if(!$file_id) {
				$query = $this->db->query("SELECT UUID() as uuid");
				
				//echo '<pre>'; print_r($query->result_array()); die();
				
				if(!$query->num_rows() > 0)
					return false;
				
				$data['fileId']			= $query->result_array()[0]['uuid'];
				$data['fileNumber']		= $this->generate_file_no();
				$data['createdBy'] 		= $user_id;
				$data['fileExistenceId'] = $user_id;
				$data['createdDate']	= date('Y-m-d H:i:s');
				


				$this->db->set($data);
				$this->db->insert('file');
				$file_id = $data['fileId'];
			}
			else {
				$this->db->where('fileId', $file_id);
				$this->db->set($data);
				$this->db->update('file');
			}
			
			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				return $file_id;
			}
			
		}
		/*---- end: create function ----*/
		
		
		/*
		|------------------------------------------------
		| start: generate_file_no function
		|------------------------------------------------
		|
		| This function generate file no
		|
		*/
		function generate_file_no() {
		
			return mt_rand();
			
		}
		/*---- end: generate_file_no function ----*/
		
		
		/*
		|------------------------------------------------
		| start: get_file function
		|------------------------------------------------
		|
		| This function get all files and get file by id and other cloumn
		|
		*/
		function get_file($db_where = null, $db_or_where = null, $db_where_in = null, $db_limit = null, $db_order = null, $db_select = null, $view_type = null) {
			
			//echo "<pre>";print_r($view_type);exit;

			if($db_select) {
				$this->db->select($db_select);
			}
			else if($view_type == 'created'){
				//$this->db->select('fd.*,c.categoryName,ns.*');
				$this->db->select('fd.*,ca.categoryName, gfc.generalCategoryName as generalCategoryName');
			}
			else if($view_type == 'inbox' || $view_type == 'sent') {
				$this->db->select('fd.*,CONCAT(up.upro_first_name, " ", up.upro_last_name) as full_name,fs.createdDate as sentOn, fs.dueDate as dueDate, ft.fileType as fileType, gfc.generalCategoryName as generalCategoryName, sp.sendPriorityColor as sendPriorityColor, fs.forwardDate as forward_date, fs.isHardCopyReceived as isHardCopyReceived');

			}
			else if ($view_type == 'file_tracking'){
				$this->db->select('fd.*,CONCAT(up.upro_first_name, " ", up.upro_last_name) as full_name,fs.createdDate as sentOn, fs.dueDate as dueDate, ft.fileType as fileType, gfc.generalCategoryName as generalCategoryName, fs.forwardDate as forward_date');				
			}
			else{
				$this->db->select('fd.*,c.categoryName,fd.createdBy');
			}

			if($db_where) {
				$this->db->group_start();
				foreach ($db_where as $key => $columnVal) {
					if ($columnVal != "") {
						$this->db->where($key, trim($columnVal));
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

			$this->db->join("category c", "c.categoryId = fd.categoryId", "LEFT");
			$this->db->join("sub_category sc", "sc.subCategoryId = fd.subCategoryId", "LEFT");
			//$this->db->join("file_types ft", "ft.fileTypeId = fd.fileTypeId", "LEFT");
			//$this->db->join("general_file_category gc", "gc.generalCategoryId = fd.generalCategoryId", "LEFT");
			//$this->db->join("note_sheet ns", "ns.fileId = fd.fileId");
			
			if($view_type == 'created'){
				$this->db->join("general_file_category gfc", "gfc.generalCategoryId = fd.generalCategoryId" ,'LEFT'); 
				$this->db->join("category ca", "ca.categoryId = fd.categoryId", "LEFT");
				$this->db->join("sent fs", "fs.fkDetailId = fd.fileId", "LEFT");
				//$this->db->order_by('fs.createdDate', 'desc');
			}
			if($view_type == 'inbox' || $view_type == 'sent' || $view_type == 'roll back') {
				//$this->db->join("sent fs", "fs.fkDetailId = fd.fileId"); 
				$this->db->join("sent fs", "fs.fkDetailId = fd.fileId", 'LEFT');
				$this->db->join("send_priority sp", "sp.sendPriorityId = fs.sendPriorityId", 'LEFT');
				$this->db->join("general_file_category gfc", "gfc.generalCategoryId = fd.generalCategoryId" ,'LEFT');
				$this->db->join("file_types ft", "ft.fileTypeId = fd.fileTypeId",'LEFT');
				if($view_type == 'inbox')
				{
					$this->db->join("user_accounts ua", "ua.uacc_id = fs.createdBy");
				}
				else if($view_type == 'sent')
				{
					$this->db->join("user_accounts ua", "ua.uacc_id = fs.sentTo");
				}
				$this->db->join("user_profiles up", "up.upro_uacc_fk = ua.uacc_id");
				$this->db->join("designation d", "ua.uacc_designation_fk = d.designationId",'LEFT');
				$this->db->order_by('fs.createdDate', 'desc');
			}
			if($view_type == 'file_tracking'){
				$this->db->join("sent fs", "fs.fkDetailId = fd.fileId" ,'LEFT'); 
				$this->db->join("user_accounts ua", "ua.uacc_id = fd.fileExistenceId",'LEFT');
				$this->db->join("user_profiles up", "up.upro_uacc_fk = ua.uacc_id",'LEFT');
				$this->db->join("designation d", "ua.uacc_designation_fk = d.designationId",'LEFT');
				$this->db->join("file_types ft", "ft.fileTypeId = fd.fileTypeId",'LEFT');
				$this->db->join("general_file_category gfc", "gfc.generalCategoryId = fd.generalCategoryId" ,'LEFT');

				$this->db->order_by('fs.createdDate', 'desc');
			}
			
			if($view_type == 'inbox'){
				$this->db->order_by('fd.createdDate', 'desc');
			}
			
			$result = $this->db->get('file fd');

			
			if($result->num_rows() > 0) { 
				foreach($result->result_array() as $row){
					//if($view_type == 'inbox' || $view_type == 'sent' || $view_type == 'roll back') {
					//if($view_type == 'inbox' || $view_type == 'roll back') {
					if($view_type == 'roll back') {
						$data[$row["sentId"]] = $row;
					}
					else {
						$data[$row["fileId"]] = $row;
					}
					
				} 
				//echo "<pre>"; print_r($data); exit;
				return $data;
			}
			else
				return FALSE;
			
		}


		function get_file_type_name($file_id) {		
		
			$this->db->select('ft.fileType');
			$this->db->where('fileId', $file_id);
			$this->db->join("file_types ft", "ft.fileTypeId = fd.fileTypeId", "LEFT");
			$result = $this->db->get('file fd');

			if($result->num_rows() > 0) {
				foreach($result->result_array() as $row){
					$data = $row;
					$data = implode($data);
				} 
				return $data;
			}
			else
				return FALSE;
		}


		function get_category_name($file_id) {		
		
			$this->db->select('gfc.generalCategoryName');
			$this->db->where('fileId', $file_id);
			$this->db->join("general_file_category gfc", "gfc.generalCategoryId = fd.generalCategoryId", "LEFT");
			$result = $this->db->get('file fd');

			if($result->num_rows() > 0) {
				foreach($result->result_array() as $row){
					$data = $row;
					$data = implode($data);
				} 
				return $data;
			}
			else
				return FALSE;
		}

		/*---- end: get_file function ----*/
		
		/*
		|------------------------------------------------
		| start: note_sheet function
		|------------------------------------------------
		|
		| This function add and update note sheet
		|
		*/
		function note_sheet($file_id, $note_type, $note_sheet_id) {
		
			$user_id = $this->flexi_auth->get_user_id();
			
			$post_data = $this->input->post();
			
			$data = array(
				'fileId'     			=> $file_id,
				'noteSheetType'     	=> $note_type,
				'noteSheetContent'		=> $post_data['note_sheet_content'],
				'noteSheetCharCount'	=> strlen($post_data['note_sheet_content']),
				//'confirmNote'    	=> $post_data['confirmNote'],
				//'noteSheetVersion'  => ($post_data['note_sheet_version']) ? $post_data['note_sheet_version']+0.1 : 1.0,
				//'lastUpdate'    	=> date('Y-m-d H:i:s')
			);
			
			$this->db->trans_begin();
			
			if(!$note_sheet_id) {
			
				$query = $this->db->query("SELECT UUID() as uuid");
				
				if(!$query->num_rows() > 0)
					return false;
				
				$data['noteSheetId']		= $query->result_array()[0]['uuid'];
				$data['noteSheetStatus']	= 'created';
				$data['createdBy'] 			= $user_id;
				$data['createdDate']		= date('Y-m-d H:i:s');
				
				$this->db->set($data);
				$this->db->insert('note_sheet');
				$note_sheet_id = $data['noteSheetId'];
			}
			else {
			
				if(trim($post_data['note_sheet_content']) == trim($post_data['note_sheet_content_update']))
					return $note_sheet_id;
				
				$this->db->where('noteSheetId', $note_sheet_id);
				$this->db->set($data);
				$this->db->update('note_sheet');
			}
			
			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				
				$this->note_sheet_history($note_sheet_id, $post_data['note_sheet_version'], $user_id);
				
				return $note_sheet_id;
			}
		
		}
		/*---- end: note_sheet function ----*/
		
		
		/*
		|------------------------------------------------
		| start: note_sheet_history function
		|------------------------------------------------
		|
		| This function add and update note sheet
		|
		*/
		function note_sheet_history($note_sheet_id, $note_sheet_version, $user_id) {
			
			$data = array(
				'noteSheetId'		=> $note_sheet_id,
				'noteSheetVersion'	=> ($note_sheet_version) ? $note_sheet_version+0.1 : 1.0,
				'updateBy' 			=> $user_id,
				'updateDate'		=> date('Y-m-d H:i:s')
			);
			
			$this->db->trans_begin();
				
			$this->db->set($data);
			$this->db->insert('note_sheet_history');
			$note_sheet_history_id = $this->db->insert_id();
						
			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				return $note_sheet_history_id;
			}
		
		}
		/*---- end: note_sheet_history function ----*/
		
		
		/*
		|------------------------------------------------
		| start: get_note_sheet function
		|------------------------------------------------
		|
		| This function get all note sheet and get note sheet by id and other cloumn
		|
		*/
		function get_note_sheet($db_where = null, $db_where_in = null) {
			
			//echo "<pre>";print_r($db_where);exit;
			$this->db->select('ns.*, up.upro_first_name, up.upro_last_name, d.designationName');
			
			if($db_where) {
				$this->db->group_start();
				foreach ($db_where as $key => $columnVal) {
					if ($columnVal != "") {
						$this->db->where($key, $columnVal);
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
			
			$this->db->where('ns.isDeleted', '0');
			
			$this->db->order_by('createdDate', 'ASC');
			
			$this->db->join("user_accounts ua", "ua.uacc_id = ns.createdBy");
			$this->db->join("user_profiles up", "up.upro_uacc_fk = ua.uacc_id");
			$this->db->join("designation d", "d.designationId = ua.uacc_designation_fk", 'LEFT');
			
			$result = $this->db->get('note_sheet ns');
			//echo "<pre>"; print_r($db_where); exit;
			if($result->num_rows() > 0) { 
				foreach($result->result_array() as $row){
					$data[$row["noteSheetId"]] = $row;
					
					$db_where = array('nsh.noteSheetId' => $row["noteSheetId"]);
					$data[$row["noteSheetId"]]['history'] = $this->get_note_sheet_history($db_where);
					
					$db_where = array('fu.fileDetailId' => $row["noteSheetId"], 'fu.isDeleted' => '0');
					$data[$row["noteSheetId"]]['files'] = $this->get_upload_attachment($db_where);
					
					if($row['noteSheetType'] == 'green')
						$data['green_note_active'] = true;
					
				}           
				//echo "<pre>"; print_r($data); exit;
				return $data;
			}
			else
				return FALSE;
			
		}
		/*---- end: get_note_sheet function ----*/
		
		
		/*
		|------------------------------------------------
		| start: get_note_sheet_history function
		|------------------------------------------------
		|
		| This function get all note sheet history and get note sheet history by id and other cloumn
		|
		*/
		function get_note_sheet_history($db_where = null) {
			
			$this->db->select('nsh.*, d.designationName, up.upro_first_name, up.upro_last_name');
			
			if($db_where) {
				$this->db->group_start();
				foreach ($db_where as $key => $columnVal) {
					if ($columnVal != "") {
						$this->db->where($key, $columnVal);
					}
				}
				$this->db->group_end();
			}

			$this->db->order_by('updateDate', 'DESC');
			
			$this->db->join("user_accounts ua", "ua.uacc_id = nsh.updateBy");
			$this->db->join("user_profiles up", "up.upro_uacc_fk = ua.uacc_id");
			$this->db->join("designation d", "ua.uacc_designation_fk = d.designationId",'LEFT');
			
			$result = $this->db->get('note_sheet_history nsh');
			
			if($result->num_rows() > 0) { 
				foreach($result->result_array() as $row){
					$data[$row["noteSheetHistoryId"]] = $row;
					
				}           
				return $data;
			}
			else
				return FALSE;
			
		}
		/*---- end: get_note_sheet_history function ----*/
		
		
		/*
		|------------------------------------------------
		| start: get_upload_attachment function
		|------------------------------------------------
		|
		| This function get all upload attachment and get upload attachment by id and other cloumn
		|
		*/
		function get_upload_attachment($db_where = null) {
			
			$this->db->select('*');
			
			if($db_where) {
				$this->db->group_start();
				foreach ($db_where as $key => $columnVal) {
					if ($columnVal != "") {
						$this->db->where($key, $columnVal);
					}
				}
				$this->db->group_end();
			}

			$this->db->order_by('fileUploadedDate', 'DESC');
			
			$result = $this->db->get('file_uploaded fu');
			
			if($result->num_rows() > 0) { 
				foreach($result->result_array() as $row){
					$data[$row["fileUploadedId"]] = $row;
					
				}           
				return $data;
			}
			else
				return FALSE;
			
		}
		/*---- end: get_upload_attachment function ----*/
		
		
		/*
		|------------------------------------------------
		| start: note_sheet_confirm function
		|------------------------------------------------
		|
		| This function update note sheet(confirm yellow to green note)
		|
		*/
		function note_sheet_confirm($note_type, $note_sheet_id) {
			
			$data = array(
				'noteSheetType'	=> $note_type,
				'confirmNote'   => 1
			);
			
			$this->db->trans_begin();
			
			$this->db->where('noteSheetId', $note_sheet_id);
			$this->db->set($data);
			$this->db->update('note_sheet');
			
			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();				
				return $note_sheet_id;
			}
		
		}
		/*---- end: note_sheet_confirm function ----*/
		
		
		/*
		|------------------------------------------------
		| start: upload_attachment function
		|------------------------------------------------
		|
		| This function add and update file uploaded data
		|
		*/
		function upload_attachment($note_sheet_id, $file_data) {
	
			$user_id = $this->flexi_auth->get_user_id();
			
			$upload_dir = $this->config->item('fileUploadPath');
			
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
			
			$infoFile = new SplFileInfo($file_data['file_name']);
			$extension = $infoFile->getExtension();
			
			$new_file_name = $note_sheet_id.'.'.$extension;
			
			copy($upload_temp_dir.$file_data['file_name'], $upload_dir_path.$file_data['file_name']);
			rename($upload_dir_path.$file_data['file_name'], $upload_dir_path.$new_file_name);
			unlink($upload_temp_dir.$file_data['file_name']);
		
			$data = array(
					'fileDetailId'		=> $note_sheet_id,
					'moduleType'		=> 'note_sheet',
					'OriginalFileName'	=> $file_data['file_name'],
					'fileName'			=> $new_file_name,
					'fileSize'          => $file_data['file_size'],
					'fileType'          => $file_data['file_type'],
					'filePath'          => $upload_dir_path.$new_file_name,
			); 
		
			$this->db->trans_begin();
		
			if($post_data['file_uploaded_id']) {
				$this->db->where('fileUploadedId', $post_data['file_uploaded_id']);
				$this->db->set($data);
				$this->db->update('file_uploaded');
				$file_uploaded_id = $post_data['file_uploaded_id'];
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
				return $file_uploaded_id;
			}
		}
		/*---- end: upload_attachment function ----*/
		
		
		/*
		|------------------------------------------------
		| start: sent_file function
		|------------------------------------------------
		|
		| This function send file
		|
		*/
		function sent_file() {
		
			$user_id = $this->flexi_auth->get_user_id();
			
			$post_data = $this->input->post();

			//echo "<pre>";print_r($post_data);exit;
			
			//$post_data['sent_to'] = explode(',', $post_data['sent_to']);
			//$post_data['sent_cc'] = explode(',', $post_data['sent_cc']);
			
			//echo '<pre>'; print_r($this->input->post());
			//echo '<pre>'; print_r($post_data); die('zia');
			
			foreach($post_data['file_id'] as $key => $get_fk_detail_id) {
			
				$data = array(
					'fkDetailId'		=> $get_fk_detail_id,
					'sentModule'		=> 'file',
					'sendActionId'		=> $post_data['send_action_id'],
					'sendPriorityId'    => $post_data['send_priority_id'],
					'sentTo'    		=> $post_data['sent_to'],
					'sentToUserGroup'   => $post_data['user_job_group_id'][$post_data['sent_to']],
					'sentByUserGroup'	=> $this->session->userdata('user_job_group'),
					'dueDate'			=> date('Y-m-d', strtotime($post_data['due_date'])),
					'remarks'    		=> $post_data['remarks'],
					'sentStatus' 		=> 'sent',
					'sentBackStatus' 	=> 'sent',
					//'receiptSentBackStatus' => 'sent',
					//'emailVersion'      => 'latest',
					'isRead' 			=> ($post_data['send_type']=='inbox') ? '1': '0',
					'createdBy'			=> $user_id,
					'createdDate'   	=> date('Y-m-d H:i:s'),
					'forwardDate'   	=> $post_data['forward_date'],
				);			
				//echo '<pre>'; print_r($data); die('zia');
				$this->db->trans_begin();
				
				//if(!$receipt_id) {
					
					//$query = $this->db->query("SELECT UUID() as uuid");
					
					//echo '<pre>'; print_r($query->result_array()); die();
					
					//if(!$query->num_rows() > 0)
						//return false;
					
					//$data['fkDetailId']	= $query->result_array()[0]['uuid'];
					//$data['receiptNo']			= $this->generate_receipt_no();
					//$data['createdBy'] 			= $user_id;
					//$data['createdDate'] 		= date('Y-m-d H:i:s');
					
					$this->db->set($data);
					$this->db->insert('sent');
					//$receipt_id = $data['fkDetailId'];
				/*}
				else {
					$this->db->where('fkDetailId', $receipt_id);
					$this->db->set($data);
					$this->db->update('receipt_detail');
				}*/
				
				$this->db->trans_complete();
				
				if($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					return FALSE;
				} else {
					$this->db->trans_commit();
					$this->file_status_update($get_fk_detail_id, 'sent',$post_data['sent_to']);
					$this->note_sheet_status_update($get_fk_detail_id, 'sent');
					
					$this->sent_notesheet_update($get_fk_detail_id, $post_data['send_id_old']);
					
					
					
					
					//$this->receipt_status_update($get_fk_detail_id, 'sent');
					//$this->file_uploaded($receipt_id, $post_data, $receipt_file);
					
					//return $receipt_id;
				}
			}
			
		}
		/*---- end: sent_file function ----*/
		function sent_notesheet_update($receipt_id, $sendId){
			$data = array(
					'isResponded'	=> '1',
				);	
			$this->db->trans_begin();
			
			
			$this->db->where('sentId', $sendId);
			$this->db->set($data);
			$this->db->update('sent');
				
			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				return $receipt_id;
			}
		}
		/*
		|------------------------------------------------
		| start: isRead function
		|------------------------------------------------
		|
		| This function isRead
		|
		*/
		function isRead($file_id = null){
		//echo "<pre>"; print_r($file_id); exit;
			$data = array(
					'isRead'		=> '1',
				);	
			$this->db->trans_begin();
			$this->db->where('fkDetailId', $file_id);
			$this->db->set($data);
			$this->db->update('sent');
			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} 
			else {
				$this->db->trans_commit();
				return True;
			}
		}
		/*End isRead function*/
		
		/*
		|------------------------------------------------
		| start: file_status_update function
		|------------------------------------------------
		|
		| This function update file status
		|
		*/
		function file_status_update($file_id, $status, $existance) {
		
			$data = array(
					'fileStatus'	=> $status,
					'fileExistenceId' => $existance
				);			
				
			$this->db->trans_begin();
			
			
			$this->db->where('fileId', $file_id);
			$this->db->set($data);
			$this->db->update('file');
				
			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				return $receipt_id;
			}
		
		}
		/*---- end: file_status_update function ----*/
		
		
		/*
		|------------------------------------------------
		| start: note_sheet_status_update function
		|------------------------------------------------
		|
		| This function update note sheet status
		|
		*/
		function note_sheet_status_update($file_id, $status) {
		
			$user_id = $this->flexi_auth->get_user_id();
		
			$data = array(
					'noteSheetStatus' => $status
				);			
				
			$this->db->trans_begin();
			
			$this->db->where('fileId', $file_id);
			$this->db->where('createdBy', $user_id);
			$this->db->set($data);
			$this->db->update('note_sheet');
				
			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				return $receipt_id;
			}
		
		}
		/*---- end: note_sheet_status_update function ----*/
		
		
		/*
		|------------------------------------------------
		| start: get_sections function
		|------------------------------------------------
		|
		| This function get all sections
		|
		*/
		function get_file_movements($file_id){
		
			$this->db->select('rs.* ,CONCAT(up_sentby.upro_first_name, " ", up_sentby.upro_last_name) as by_full_name , CONCAT(up_sentto.upro_first_name, " ", up_sentto.upro_last_name) as to_full_name, sa.sendAction');
			$this->db->join("user_profiles up_sentby", "rs.createdBy = up_sentby.upro_uacc_fk");
			$this->db->join("user_profiles up_sentto", "rs.sentTo = up_sentto.upro_uacc_fk");
			$this->db->join("send_action sa", "sa.sendActionId = rs.sendActionId", 'LEFT');
			$this->db->where('rs.fkDetailId', $file_id);
			$this->db->order_by('rs.sentId', 'DESC');
			
			$result = $this->db->get('sent rs');
			
			if($result->num_rows() > 0) {            
				$data = $result->result_array();            
				return $data;
			}
			else
				return FALSE;
			
		}
		/*---- end: get_sections function ----*/


		
		

		
		/*
		|------------------------------------------------
		| start: delete_note_sheet_file function
		|------------------------------------------------
		|
		| This function delete note sheet file
		|
		*/
		function delete_note_sheet_file($note_sheet_file_id) {
		
			$user_id = $this->flexi_auth->get_user_id();
		
			$data = array('isDeleted' => '1', 'DeletedBy' => $user_id);
			$this->db->where('fileUploadedId', $note_sheet_file_id);
			$this->db->set($data);
			$this->db->update('file_uploaded');
		
		}
		/*---- end: delete_note_sheet_file function ----*/
		
		
		/*
		|------------------------------------------------
		| start: note_sheet_discard function
		|------------------------------------------------
		|
		| This function discard note sheet
		|
		*/
		function note_sheet_discard($note_sheet_id) {
		
			$user_id = $this->flexi_auth->get_user_id();
		
			$data = array('isDeleted' => '1', 'deletedBy' => $user_id);
			$this->db->where('noteSheetId', $note_sheet_id);
			$this->db->set($data);
			$this->db->update('note_sheet');
		
		}
		/*---- end: note_sheet_discard function ----*/
		
		
		/*
		|------------------------------------------------
		| start: verify_user function
		|------------------------------------------------
		|
		| This function verfiy user
		|
		*/
		public function verify_user() {
		
			$user_data = $this->flexi_auth->get_user_by_id($this->flexi_auth->get_user_id())->row();
			$identity = $user_data->uacc_email;
			$verify_password = $this->input->post('login_password');
			
			if (empty($identity) || empty($verify_password))
			{
				return FALSE;
			}
					
			$sql_select = array(
				$this->auth->tbl_col_user_account['password'],
				$this->auth->tbl_col_user_account['salt']
			);
			
			$query = $this->db->select($sql_select)
				->where($this->auth->primary_identity_col, $identity)
				->limit(1)
				->get($this->auth->tbl_user_account);
					 
			$result = $query->row();

			if ($query->num_rows() !== 1)
			{
				return FALSE;
			}
					
			$database_password = $result->{$this->auth->database_config['user_acc']['columns']['password']};
			$database_salt = $result->{$this->auth->database_config['user_acc']['columns']['salt']};
			$static_salt = $this->auth->auth_security['static_salt'];
			
			require_once(APPPATH.'libraries/phpass/PasswordHash.php');				
			$hash_token = new PasswordHash(8, FALSE);
						
			return $hash_token->CheckPassword($database_salt . $verify_password . $static_salt, $database_password);
		}
		/*---- end: verify_user function ----*/
		function get_sent_file_id($file_id = null, $db_where_in = null){
			$this->db->select('rs.sentId');
			if($file_id)
			{
			$this->db->where('rs.fkDetailId', $file_id);
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
			$this->db->order_by('rs.sentId','DESC');
			$result = $this->db->get('sent rs');
			//echo "<pre>"; print_r($file_id);exit;
			if($result->num_rows() > 0) {            
				$data = $result->result_array();            
				return $data[0];
				//echo "<pre>"; print_r($data);exit;
			}
			else
				return FALSE;
		}
		/*
		|------------------------------------------------
		| start: hard copy received function
		|------------------------------------------------
		|
		| This function use for hard copy received
		|
		*/
		function hard_copy_received() {
				$data = array(
						'isHardCopyReceived' => $this->input->post('hardCopyReceived'),
				);
				$this->db->trans_begin();
				$this->db->where('fkDetailId', $this->input->post('file_id'));
				$this->db->set($data);
				$this->db->update('sent');
				
				$this->db->trans_complete();
				if($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					return FALSE;
				} else {
					$this->db->trans_commit();
					return true;
				}
		}
		/*---- end: hard copy received function ----*/
		
		
		function update_file_pob($file_id, $attach_receipt_id) {
			if($this->input->post('send_type') == ''){
				$data = array('pob_id' => $attach_receipt_id);
				
				$this->db->trans_begin();
							
				$this->db->where('fileId', $file_id);
				$this->db->set($data);
				$this->db->update('file');
				
				$this->db->trans_complete();
				
				if($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					return FALSE;
				} else {
					return $receip_id;
				}
			}
		}
		
	}	