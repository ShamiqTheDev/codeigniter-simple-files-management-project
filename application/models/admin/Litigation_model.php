<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Litigation_model extends CI_Model {
		
		/*
		|------------------------------------------------
		| start: create function
		|------------------------------------------------
		|
		| This function add/create file details
		|
		*/
		function create_case($case_id) {
			$user_id = $this->flexi_auth->get_user_id();
			
			$post = $this->input->post();
			$data = array(
				'court'			=> $post['court'],
				'caseNo'		=> $post['caseNo'],
				'ground'		=> $post['ground'],
				'relatedTo'		=> $post['relatedTo'],
				'memo' 			=> $post['memo'],
				'caseDate'		=> $post['caseDate'],
				// 'caseStatus'	=> $post['caseStatus'],
			);
			$update_request = false;
			$this->db->trans_begin();
			if(empty($case_id)) {
				$query = $this->db->query("SELECT UUID() as uuid");
				if(!$query->num_rows() > 0)
					return false;
				
				$data['caseId']	= $query->result_array()[0]['uuid'];
				$data['createdBy'] = $user_id;
				
				$this->db->set($data);
				$this->db->insert('cases');
				$case_id = $data['caseId'];
			}
			else {
				$update_request = true;
				$data['updatedAt'] = date('Y-m-d H:i:s');
				$data['updatedBy'] = $user_id;
				$this->db->where('caseId', $case_id);
				$this->db->set($data);
				$query = $this->db->update('cases');
			}
			
			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
			} else {
				$this->db->trans_commit();
			}

			if (!empty($case_id)) {
				$member_ids = $this->create_members();
				if (!$update_request) {
					ee('here in model-> if (!$update_request) {');
					$this->create_case_petitioner($case_id,$member_ids[0]);
					$this->create_case_responder($case_id,$member_ids[1]);
				}
				$case_hearing_date_id = $this->set_case_hearing_date($case_id,$update_request);
			} // endif case id

			return $case_id;
		}
		/*---- end: create function ----*/


		/*
		|------------------------------------------------
		| start: create_members function
		|------------------------------------------------
		|
		| This function create members
		|
		*/
		function create_members($id='')
		{
			$user_id = $this->flexi_auth->get_user_id();
			$post = $this->input->post();
			$members = $post['members'];
			$member_ids = [];
			for ($i=0; $i < 2; $i++) { // 1 for petitioner and 2nd is for responder
				$member = $members[$i];
				$data = array(
					'memberFirstName' => $member['memberFirstName'],
					'memberLastName' => $member['memberLastName'],
					'designationId' => $member['designationId'],
				);

				$this->db->trans_begin();
				$this->db->set($data);
				
				if (!empty($member['memberId'])) {
					$this->db->where('memberId',$member['memberId']);
					$this->db->update('members');
					$member_id = $member['memberId'];
				}else{
					$this->db->insert('members');
					$member_id = $this->db->insert_id();
				}
				
				$this->db->trans_complete();
				if($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
				} else {
					$this->db->trans_commit();
					$member_ids[] = $member_id;
				}
				
			}
			return $member_ids;
		}
		/*---- end: create_members function ----*/


		/*
		|------------------------------------------------
		| start: create_case_petitioner function
		|------------------------------------------------
		|
		| This function create case petitioner
		|
		*/
		function create_case_petitioner($case_id,$member_id)
		{
			$user_id = $this->flexi_auth->get_user_id();

			$this->db->trans_begin();
			$data = array(
				'caseId' => $case_id,
				'memberId' => $member_id,
				'type' => 1,//petitioner
				'createdBy' => $user_id,
			);

			$this->db->set($data);
			$this->db->insert('case_members');
			$id = $this->db->insert_id();

			$this->db->trans_complete();

			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				return $id;
			}
		}
		/*---- end: create_case_petitioner function ----*/


		/*
		|------------------------------------------------
		| start: create_case_responder function
		|------------------------------------------------
		|
		| This function create case responder
		|
		*/
		function create_case_responder($case_id,$member_id)
		{
			$user_id = $this->flexi_auth->get_user_id();
			$this->db->trans_begin();
			$data = array(
				'caseId' => $case_id,
				'memberId' => $member_id,
				'type' => 2,//responder
				'createdBy' => $user_id,
			);
			
			$this->db->set($data);
			$this->db->insert('case_members');
			$id = $this->db->insert_id();

			$this->db->trans_complete();

			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				return $id;
			}
		}
		/*---- end: create_case_responder function ----*/

		/*
		|------------------------------------------------
		| start: set_case_hearing_date function
		|------------------------------------------------
		|
		| This function set case hearing date
		|
		*/
		function set_case_hearing_date($case_id,$update_request)
		{
			$user_id = $this->flexi_auth->get_user_id();
			$post = $this->input->post();

			if ($update_request) {
				$this->db->trans_begin();

				$data = array('status' => 0);
				$this->db->set($data);
				$this->db->where('caseId',$case_id);
				$this->db->update('case_hearing_dates');

				$this->db->trans_complete();

				if($this->db->trans_status() === FALSE) {
					$this->db->trans_rollback();
				} else {
					$this->db->trans_commit();
				}

			}
			$this->db->trans_begin();
			$data = array(
				'caseId' => $case_id,
				'hearingDate' => $post['hearingDate'],
				'status' => 1,// 1 for current hearing date
				// 'judiciaryRemarks' => $post['judiciaryRemarks'], //will be added on frontend
				// 'remarks' => $post['remarks'], //will be added on frontend
				'createdBy' => $user_id,
			);
			
			$this->db->set($data);
			$this->db->insert('case_hearing_dates');
			$id = $this->db->insert_id();

			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				return $id;
			}
		}
		/*---- end: set_case_hearing_date function ----*/


		function create_case_docs($case_id,$post)
		{
			$user_id = $this->flexi_auth->get_user_id();
			$this->db->trans_begin();

			$query = $this->db->query("SELECT UUID() as uuid");
			if(!$query->num_rows() > 0)
				return false;
				
			$case_docs_Id = $query->result_array()[0]['uuid'];
			$data = array(
				'caseDocId' => $case_docs_Id,
				'caseId' => $case_id,
				'docNo' => $post['docNo'],
				'docName' => $post['docName'],
				'docType' => $post['docType'],

				'createdBy' => $user_id,
			);
			
			$this->db->set($data);
			$this->db->insert('case_docs');
			$id = $case_docs_Id;

			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				return $id;
			}
		}


		/*
		|------------------------------------------------
		| start: upload_attachment function
		|------------------------------------------------
		|
		| This function add and update file uploaded data
		|
		*/
		function upload_attachment($case_id, $case_docs_id, $file_data) {
	
			$user_id = $this->flexi_auth->get_user_id();
			
			$upload_dir = $this->config->item('litigationPath');
			
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
			$upload_temp_dir = $upload_dir;
			
			$infoFile = new SplFileInfo($file_data['file_name']);
			$extension = $infoFile->getExtension();
			
			$new_file_name = $case_docs_id.'.'.$extension;
			
			copy($upload_temp_dir.$file_data['file_name'], $upload_dir_path.$file_data['file_name']);
			rename($upload_dir_path.$file_data['file_name'], $upload_dir_path.$new_file_name);
			unlink($upload_temp_dir.$file_data['file_name']);
		
			$data = array(
					'fileDetailId'		=> $case_docs_id,
					'moduleType'		=> 'litigation',
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
		| start: get_cases function
		|------------------------------------------------
		|
		| This function get all files and get file by id and other cloumn
		|
		*/
		function get_cases($db_where = null, $db_or_where = null, $db_where_in = null, $db_limit = null, $db_order = null, $db_select = null, $view_type = null) {

			if($db_select) {
				$this->db->select($db_select);
			}
			else{
				$this->db->select("c.caseId,c.caseNo, c.ground, c.relatedTo, c.memo,c.caseDate , chd.hearingDate");
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

			$this->db->join("case_members cm", "c.caseId = cm.caseId", "LEFT");
			$this->db->join("case_hearing_dates chd", "c.caseId = chd.caseId", "LEFT");
			$this->db->where('chd.status',1);
			$result = $this->db->get('cases c');

			
			if($result->num_rows() > 0) { 
				foreach($result->result_array() as $row){
					$data[$row["caseId"]] = $row;
				} 
				return $data;
			}
			else {
				return FALSE;
			}
		}
		/*---- end: get_cases function ----*/

		/*
		|------------------------------------------------
		| start: get_case_details function
		|------------------------------------------------
		|
		| This function get case details
		|
		*/
		function get_case_details($db_where = null, $db_or_where = null, $db_where_in = null, $db_order = null, $db_select = null, $view_type = null) {

			if($db_select) {
				$this->db->select($db_select);
			}
			else{
				$this->db->select("c.*, chd.hearingDate");
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
			
			// if($db_limit) {
				$this->db->limit(1);
				// }
			
			if($db_order) {
				foreach($db_order as $get_order) {
					$this->db->order_by($get_order['title'], $get_order['order_by']);
				} 
			}

			$this->db->join("case_members cm", "c.caseId = cm.caseId", "LEFT");
			$this->db->join("case_hearing_dates chd", "c.caseId = chd.caseId", "LEFT");
			$this->db->where('chd.status',1);
			$result = $this->db->get('cases c');

			// $this->db->join("members pet", "cm.memberId = pet.memberId AND cm.type = 1", "LEFT");
			// $this->db->join("members resp", "cm.memberId = resp.memberId AND cm.type = 2", "LEFT");
			
			if($result->num_rows() > 0) {
				$res = $result->result_array(); 
				$data['case'] = $res[0];
				$case_id = $data['case']['caseId'];
				$data['petitioner'] = $this->get_members($case_id,1);
				$data['respondent'] = $this->get_members($case_id,2);
				// dd($data);
				return $data;
			}
			else
				return FALSE;
			
		}
		/*---- end: get_case_details function ----*/

		function get_members($case_id=null, $type=null)
		{
			$this->db->select('m.memberId,m.memberFirstName,m.memberLastName,m.designationId,cm.type');
			$this->db->where('cm.caseId',$case_id);
			$this->db->where('cm.type',$type);
			$this->db->join('case_members cm','m.memberId = cm.memberId','LEFT');
			$result = $this->db->get('members m');
			$data = $result->result_array();
			return $data[0];
		}

		function get_case_docs($case_id=null, $type=null)
		{
			$this->db->select('cd.*, fu.moduleType, fu.moduleType, fu.OriginalFileName, fu.fileName,fu.filePath');
			$this->db->where('cd.caseId',$case_id);
			// $this->db->where('fu.moduleType','litigation');
			if (!empty($type)) {
				$this->db->where('cd.docType',$type);
			}
			$this->db->join('file_uploaded fu','cd.caseDocId = fu.fileDetailId','LEFT');
			$result = $this->db->get('case_docs cd');
			$data = $result->result_array();
			return $data;
		}
		
	}	