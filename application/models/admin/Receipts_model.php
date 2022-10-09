<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Receipts_model extends CI_Model {
		
		/*
		|------------------------------------------------
		| start: browse_diarise function
		|------------------------------------------------
		|
		| This function add and update browse & diarise
		|
		*/
		function browse_diarise($receipt_id = null, $receipt_file = null) {
			
			$user_id = $this->flexi_auth->get_user_id();
			
			$post_data = $this->input->post();
			//echo "<pre>"; print_r($post_data); exit;
			$data = array(
				'classifiedId'      => $post_data['classified_id'],
				'deliveryModeId'	=> $post_data['delivery_mode_id'],
				'documentTypeId'    => $post_data['document_type_id'],
				'categoryId'    	=> $post_data['category_id'],
				//'subCategoryId'    	=> $post_data['sub_category_id'],
				'senderType'		=> $post_data['sender_type'],
				'language'    		=> $post_data['language'],
				//'modeNumber'    	=> $post_data['mode_number'],
				'letterRefNo'    	=> $post_data['letter_ref_no'],
				//'fileNumber'		=> !empty($post_data['file_number']) ? $post_data['file_number'] : '0',
				'receivedDate'    	=> ($post_data['received_date']) ? date('Y-m-d', strtotime($post_data['received_date'])) : '0000-00-00',
				'letterDate'		=> ($post_data['letter_date']) ? date('Y-m-d', strtotime($post_data['letter_date'])) : '0000-00-00',
				//'vip'				=> $post_data['vip'],
				//'vipName'			=> $post_data['vip_name'],
				//'dealingHands'		=> $post_data['dealing_hands'],
				'subject'			=> $post_data['subject'],
				//'enclosures'		=> $post_data['enclosures'],
				//'receiptStatus'		=> (isset($post_data['generate']) && $post_data['generate'] == 'generate') ? 'created' : 'sent',
				'receiptStatus'		=> 'created',
				'rAndIDiaryNo'		=> $post_data['r_and_i_diary_no'],
				'diaryDate'			=> date('Y-m-d', strtotime($post_data['diary_date'])),
			);
			
			$this->db->trans_begin();
			
			if(!$receipt_id) {
				
				$query = $this->db->query("SELECT UUID() as uuid");
				
				//echo '<pre>'; print_r($query->result_array()); die();
				
				if(!$query->num_rows() > 0)
					return false;
				
				$data['receiptDetailId']	= $query->result_array()[0]['uuid'];
				$data['receiptNo']			= $this->generate_receipt_no();
				$data['createdBy'] 			= $user_id;
				$data['receiptExistanceId'] = $user_id;
				$data['createdDate']		= date('Y-m-d H:i:s');
				
				$this->db->set($data);
				$this->db->insert('receipt_detail');
				$receipt_id = $data['receiptDetailId'];
			}
			else {
				$this->db->where('receiptDetailId', $receipt_id);
				$this->db->set($data);
				$this->db->update('receipt_detail');
			}
			
			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				
				$this->contact_detail($receipt_id, $post_data);
				
				//if($post_data['receipt_file_hidden']==""){ echo 'test if';
					$this->file_uploaded($receipt_id, $post_data);
				/*}
				else if($post_data['receipt_file_hidden']=="new_file_uploaded"){ echo 'test else if';
					$this->file_uploaded($receipt_id, $post_data);
				}*/
				
				//die();
				return $receipt_id;
			}
			
		}
		/*---- end: browse_diarise function ----*/
		
		
		/*
		|------------------------------------------------
		| start: contact_detail function
		|------------------------------------------------
		|
		| This function add and update contact detail
		|
		*/
		function contact_detail($receipt_id, $post_data) {
			
			$user_id = $this->flexi_auth->get_user_id();
			
			$data = array(
				'ministryId'		=> $post_data['ministry_id'],
				'departmentId'    	=> $post_data['department_id'],
				//'countryId'    		=> $post_data['country_id'],
				'stateId'    		=> $post_data['state_id'],
				//'cityId'			=> $post_data['city_id'],
				'contactName'    	=> $post_data['contact_name'],
				'designation'		=> $post_data['designation'],
				//'organization'    	=> $post_data['organization'],
				'addressOne'    	=> $post_data['address_one'],
				//'addressTwo'    	=> $post_data['address_two'],
				//'pinCode'    		=> $post_data['pin_code'],
				'contactMobile'		=> $post_data['contact_mobile'],
				//'contactLandline'	=> $post_data['contact_landline'],
				//'contactFax'		=> $post_data['contact_fax'],
				//'contactEmail'		=> $post_data['contact_email'],
			);
			
			$this->db->trans_begin();
			
			if(!$post_data['contactDetailId']) {
				
				$data['receiptDetailId']	= $receipt_id;
				$data['createdBy'] 			= $user_id;
				$data['createdDate'] 		= date('Y-m-d H:i:s');
				
				$this->db->set($data);
				$this->db->insert('contact_detail');
				$contact_id = $this->db->insert_id();
			}
			else {
				$this->db->where('contactDetailId', $post_data['contactDetailId']);
				$this->db->set($data);
				$this->db->update('contact_detail');
				
				$contact_id = $post_data['contactDetailId'];
			}
			
			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				return $contact_id;
			}
			
		}
		/*---- end: contact_detail function ----*/
		
		
		/*
		|------------------------------------------------
		| start: get_enum_value function
		|------------------------------------------------
		|
		| This function get enum value
		|
		*/
		function get_enum_value($table_name, $field_name) {
        
			$query = $this->db->query("SHOW COLUMNS FROM `{$table_name}` LIKE '{$field_name}'");

			if(!$query->num_rows()) return array();
			
			preg_match_all('~\'([^\']*)\'~', $query->row('Type'), $matches);

			return $matches[1];
			
		}
		/*---- end: get_enum_value function ----*/
		
		
		/*
		|------------------------------------------------
		| start: generate_receipt_no function
		|------------------------------------------------
		|
		| This function generate receipt no
		|
		*/
		function generate_receipt_no() {
		
			return mt_rand(1000000000,9999999999);
			
		}
		/*---- end: generate_receipt_no function ----*/
		
		
		/*
	|------------------------------------------------
    | start: update_media function
    |------------------------------------------------
    |
    | This function update media
    |
   */
    function file_uploaded($receipt_id, $post_data) {
	
		$user_id = $this->flexi_auth->get_user_id();
		
		$upload_dir = $this->config->item('receiptUploadPath');
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
		
			if($post_data['file_uploaded_id'][$key])
				continue;
		
			$infoFile = new SplFileInfo($get_file_name);
			$extension = $infoFile->getExtension();
			
			$new_file_name = $receipt_id.'-page'. $key .'.'.$extension;
			
			copy($upload_temp_dir.$get_file_name, $upload_dir_path.$get_file_name);
			rename($upload_dir_path.$get_file_name, $upload_dir_path.$new_file_name);
			unlink($upload_temp_dir.$get_file_name);
		
			$data = array(
					'fileDetailId'		=> $receipt_id,
					'moduleType'		=> 'receipt',
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
		
		
		}
		
	}
	/*---- end: update_media function ----*/
		
		
		/*
		|------------------------------------------------
		| start: get_receipt function
		|------------------------------------------------
		|
		| This function get all receipt and get receipt by id and other cloumn
		|
		*/
		function get_receipt($db_where = null, $db_or_where = null, $db_where_in = null, $db_limit = null, $db_order = null, $db_select = null, $view_type = null, $db_where_attachment = null) {
			
			if($db_select) {
				$this->db->select($db_select);
			}
			else if($view_type == 'created'){
				$this->db->select('*,rd.createdDate as receiptCreatedDate');
			}
			else if($view_type == 'inbox' || $view_type == 'sent' || $view_type == 'roll back' || $view_type == 'receipt_tracking') {
				$this->db->select('*,CONCAT(up.upro_first_name, " ", up.upro_last_name) as full_name,rs.createdDate as sentOn, rs.createdBy as receiptSentBy,rd.createdDate as receiptCreatedDate, rd.rAndIDiaryNo as rAndIDiaryNo');
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

			$this->db->join("contact_detail cd", "cd.receiptDetailId = rd.receiptDetailId");
			//$this->db->join("file_uploaded fu", "fu.fileDetailId = rd.receiptDetailId", 'LEFT');
			$this->db->join("category c", "c.categoryId = rd.categoryId");
			$this->db->join("delivery_mode dm", "dm.deliveryModeId = rd.deliveryModeId");
			$this->db->join("document_type dt", "dt.documentTypeId = rd.documentTypeId");
			//$this->db->join("file f", "f.fileNumber = rd.fileNumber",'LEFT');
			//echo "<pre>"; print_r($view_type); exit;
			if($view_type == 'inbox' || $view_type == 'roll back') {
				$this->db->join("sent rs", "rs.fkDetailId = rd.receiptDetailId"); 
				$this->db->join("send_priority sp", "sp.sendPriorityId = rs.sendPriorityId", 'LEFT');
				$this->db->join("user_accounts ua", "ua.uacc_id = rs.createdBy");
				$this->db->join("user_profiles up", "up.upro_uacc_fk = ua.uacc_id");
				$this->db->join("designation d", "ua.uacc_designation_fk = d.designationId", 'LEFT');
				$this->db->order_by('rs.createdDate','DESC');
			}
			else if($view_type == 'sent'){
				$this->db->join("sent rs", "rs.fkDetailId = rd.receiptDetailId"); 
				$this->db->join("send_priority sp", "sp.sendPriorityId = rs.sendPriorityId", 'LEFT');
				$this->db->join("user_accounts ua", "ua.uacc_id = rs.sentTo");
				$this->db->join("user_profiles up", "up.upro_uacc_fk = ua.uacc_id");
				$this->db->join("designation d", "ua.uacc_designation_fk = d.designationId",'LEFT');
				$this->db->order_by('rs.createdDate','DESC');
			}
			else if($view_type == 'receipt_tracking'){
				//echo "<pre>";print_r($view_type);exit;
				$this->db->join("sent rs", "rs.fkDetailId = rd.receiptDetailId");
				$this->db->join("user_accounts ua", "ua.uacc_id = rd.receiptExistanceId",'LEFT');
				$this->db->join("user_profiles up", "up.upro_uacc_fk = ua.uacc_id",'LEFT');
				$this->db->join("designation d", "ua.uacc_designation_fk = d.designationId",'LEFT');
				$this->db->join("file f", "f.fileId = rd.receiptDetailId",'LEFT');
				//$this->db->group_by('rd.receiptNo');
				$this->db->order_by('rs.createdDate','DESC');
			}
			
			if($view_type == 'created'){
				$this->db->order_by('rd.createdDate','DESC');
			}
			
			$result = $this->db->get('receipt_detail rd');
			
			//echo "<pre>";print_r($result);exit;

			if($result->num_rows() > 0) { 
				foreach($result->result_array() as $row){
					if($view_type == 'inbox' || $view_type == 'sent' || $view_type == 'roll back') {
						//echo "<pre>";print_r('if mein araha hai');exit;
						$data[$row["sentId"]] = $row;
					}
					else { //die('test');
						//echo "<pre>";print_r('else mein araha hai');exit;
						$data[$row["receiptDetailId"]] = $row;
						//echo '<pre>'; print_r($db_where_attachment); die();
						if($db_where_attachment) { //echo '<pre>'; print_r($db_where_attachment); die();
							$db_where_attachment['fileDetailId'] = $row["receiptDetailId"];
							$data[$row["receiptDetailId"]]['attachment'] = $this->get_receipt_files($db_where_attachment);
						}
					}
					
				}
				//echo "<pre>";print_r($data);exit;        
				return $data;

			}
			else
				return FALSE;
			
		}
		/*---- end: get_receipt function ----*/
		
		
		/*
		|------------------------------------------------
		| start: sent_receipt function
		|------------------------------------------------
		|
		| This function send receipt
		|
		*/
		function sent_receipt() {
		
		
			$user_id = $this->flexi_auth->get_user_id();
			
			$post_data = $this->input->post();
			
			//echo "<pre>";print_r($post_data);exit;	
			//$post_data['sent_to'] = explode(',', $post_data['sent_to']);
			//$post_data['sent_cc'] = explode(',', $post_data['sent_cc']);
			
			//echo '<pre>'; print_r($this->input->post());exit;
			//echo '<pre>'; print_r($post_data); die('zia');
			
			foreach($post_data['receipt_id'] as $key => $get_fk_detail_id) {
			
				$data = array(
					'fkDetailId'		=> $get_fk_detail_id,
					'sentModule'		=> 'receipt',
					'sendActionId'		=> $post_data['send_action_id'],
					'sendPriorityId'    => $post_data['send_priority_id'],
					'sentTo'    		=> $post_data['sent_to'],
					'sentToUserGroup'   => $post_data['user_job_group_id'][$post_data['sent_to']],
					'sentByUserGroup'	=> $this->session->userdata('user_job_group'),
					//'sentCc'    		=> $post_data['sent_cc'],
					'dueDate'			=> date('Y-m-d', strtotime($post_data['due_date'])),
					'forwardDate'		=> $post_data['forward_date'] != '' ? date('Y-m-d', strtotime($post_data['forward_date'])) : date('Y-m-d'),
					'remarks'    		=> $post_data['remarks'],
					'sentStatus' 		=> 'sent',
					'sentBackStatus' 	=> 'sent',
					'isRead' 			=> ($post_data['send_type']=='inbox') ? '1': '0',
					//'emailVersion'      => 'latest',
					'createdBy'			=> $user_id,
					'createdDate'   	=> date('Y-m-d H:i:s'),
				);			
				//echo '<pre>'; print_r($data); die();
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
					

					
					$this->receipt_status_update($get_fk_detail_id, 'sent', $post_data['sent_to']);
					$this->sent_receipt_update($get_fk_detail_id, $post_data['send_id_old']);
					//$this->file_uploaded($receipt_id, $post_data, $receipt_file);
					
					if(isset($post_data['roll_back_close']) && $post_data['roll_back_close']) {
						if($post_data['send_type'] == 'roll back'){
							$sent_data = array('sentStatus' => 'closed');
							$this->roll_back_close($post_data['roll_back_close'], $sent_data);
						}
						else if($post_data['send_type'] == 'inbox') {
							$sent_data = array('isRead' => '1');
							$this->roll_back_close($post_data['roll_back_close'], $sent_data);
						}
					}
					
					//return $receipt_id;
				}
			}
			
		}
		/*---- end: sent_receipt function ----*/
		
		
		function roll_back_close($roll_back_close, $sent_data) {
			
			//$data = array('sentStatus' => 'closed');				
			$data = $sent_data;				
			
			$this->db->trans_begin();
						
			$this->db->where('sentId', $roll_back_close);
			$this->db->set($data);
			$this->db->update('sent');
				
			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				return $roll_back_close;
			}
			
		}
		
		/*
		|------------------------------------------------
		| start: sent_back_receipt function
		|------------------------------------------------
		|
		| This function send back receipt
		|
		*/
		function sent_back_receipt() {
		
			$user_id = $this->flexi_auth->get_user_id();
			
			$post_data = $this->input->post();
			foreach($post_data['receipt_id'] as $key => $get_fk_detail_id) {
			
				$data = array(
					'fkDetailId'		=> $get_fk_detail_id,
					'sentModule'		=> 'receipt',
					'sendActionId'		=> $post_data['send_action_id'],
					'sendPriorityId'    => $post_data['send_priority_id'],
					'sentTo'    		=> $post_data['receipt_sent_to'],
					//'sentCc'    	=> $post_data['sent_cc'],
					'dueDate'			=> date('Y-m-d', strtotime($post_data['due_date'])),
					'remarks'    		=> $post_data['remarks'],
					'sentStatus' 		=> 'sent',
					'sentBackStatus' 	=> 'sent back',
					'createdBy'			=> $user_id,
					'createdDate'   	=> date('Y-m-d H:i:s'),
				);			
				//$update_data = array( 'emailVersion'      => 'old' );
				$this->db->trans_begin();
				$this->db->set($data);
				$this->db->insert('sent');
				
				/*if($post_data['receipt_sent_back_id']!="") {
					$this->db->where('sentId', $post_data['receipt_sent_back_id']);
					$this->db->set($data);
					$this->db->update('sent');
					//$receipt_id = $data['fkDetailId'];
				}*/
				
				$this->db->trans_complete();
				
				if($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					return FALSE;
				} else {
					$this->db->trans_commit();
					$this->receipt_status_update($get_fk_detail_id, 'sent', $post_data['receipt_sent_to']);
					$this->sent_receipt_update($get_fk_detail_id, $post_data['send_id_old']);
					//$this->file_uploaded($receipt_id, $post_data, $receipt_file);
					
					//return $receipt_id;
				}
			}
			
		}
		/*---- end: sent_back_receipt function ----*/
		
		/*
		|------------------------------------------------
		| start: receipt_status_update function
		|------------------------------------------------
		|
		| This function update receipt status
		|
		*/
		function receipt_status_update($receipt_id, $status, $existance) {
		

			$data = array(
					'receiptStatus'	=> $status,
					'receiptExistanceId' => $existance
				);				
			$this->db->trans_begin();
			
			
			$this->db->where('receiptDetailId', $receipt_id);
			$this->db->set($data);
			$this->db->update('receipt_detail');
				
			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				return $receipt_id;
			}
		
		}
		/*---- end: receipt_status_update function ----*/
		function sent_receipt_update($receipt_id, $sendId){
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
		| start: put_in_file function
		|------------------------------------------------
		|
		| This function update attached file id in receipt
		|
		*/
		function put_in_file($receipt_id, $file_no) {
		
			$user_id = $this->flexi_auth->get_user_id();
		
			$data = array(
					'fileNumber'	=> $file_no,
					'attachedBy'	=> $user_id,
					'attachedDate'	=> date('Y-m-d H:i:s')
				);			
				
			$this->db->trans_begin();
			
			
			$this->db->where('receiptDetailId', $receipt_id);
			$this->db->set($data);
			$this->db->update('receipt_detail');
				
			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				return $receipt_id;
			}
		
		}
		/*---- end: put_in_file function ----*/
		
		
		/*
		|------------------------------------------------
		| start: get_sections function
		|------------------------------------------------
		|
		| This function get all sections
		|
		*/
		function get_file_movements($receipt_id){
			$this->db->select('rs.* ,CONCAT(up_sentby.upro_first_name, " ", up_sentby.upro_last_name) as by_full_name , CONCAT(up_sentto.upro_first_name, " ", up_sentto.upro_last_name) as to_full_name, sa.sendAction');
			$this->db->join("user_profiles up_sentby", "rs.createdBy = up_sentby.upro_uacc_fk");
			$this->db->join("user_profiles up_sentto", "rs.sentTo = up_sentto.upro_uacc_fk");
			$this->db->join("send_action sa", "sa.sendActionId = rs.sendActionId");
			$this->db->where('rs.fkDetailId', $receipt_id);
			$this->db->where('rs.sentStatus !=', 'closed');
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
		| start: receipt_roll_back function
		|------------------------------------------------
		|
		| This function roll back receipt and status update
		|
		*/
		function receipt_roll_back($receipt_id) {
		
			$user_id = $this->flexi_auth->get_user_id();
			
			$data = array(
					'sentStatus'	=> 'roll back'
				);			
				
			$this->db->trans_begin();
			
			
			$this->db->where('fkDetailId', $receipt_id);
			$this->db->where('isRead', '0');
			$this->db->where('createdBy', $user_id);
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
		/*---- end: receipt_roll_back function ----*/
		
		/*
		|------------------------------------------------
		| start: close receipt function
		|------------------------------------------------
		|
		| This function use for close receipt
		|
		*/
		function close_receipt($receipt_id = null) {
			foreach($this->input->post('receipt_id') as $key => $get_receipt_id) {
				$data = array(
						'receiptStatus'	=> 'cancel',
						'receiptRemarks'=> $this->input->post('remarks'),
				);
				$this->db->trans_begin();
				$this->db->where('receiptDetailId', $get_receipt_id);
				$this->db->set($data);
				$this->db->update('receipt_detail');
				
				$this->db->trans_complete();
				if($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
					return FALSE;
				} else {
					$this->db->trans_commit();
					return true;
				}
			}
		}
		/*---- end: close receipt function ----*/
		function get_sent_receipt_id($receipt_id = null){
			$this->db->select('rs.sentId');
			if($receipt_id){
			$this->db->where('rs.fkDetailId', $receipt_id);
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
			if($result->num_rows() > 0) {            
				$data = $result->result_array();            
				return $data[0];
			}
			else
				return FALSE;
		}
		/*-------- end: sent receipt id ------------*/
		
		
		function get_receipt_files($db_where = null) {
		
			$this->db->select('fileUploadedId, filePath');
			
			if($db_where) {
				$this->db->group_start();
				foreach ($db_where as $key => $columnVal) {
					if ($columnVal != "") {
						$this->db->where($key, $columnVal);
					}
				}
				$this->db->group_end();
			}
			
			$result = $this->db->get('file_uploaded');
			
			if($result->num_rows() > 0) {
				foreach($result->result_array() as $row){
					$data[$row['fileUploadedId']] = $row;
				}
				
				return $data;
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
				$this->db->where('fkDetailId', $this->input->post('receiptDetailId'));
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
		function isRead($receipt_id = null){
		//echo "<pre>"; print_r($file_id); exit;
			$data = array(
					'isRead'		=> '1',
				);	
			$this->db->trans_begin();
			$this->db->where('fkDetailId', $receipt_id);
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
		
	}	
	