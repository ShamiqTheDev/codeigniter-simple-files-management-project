<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class Receipts extends CI_Controller {
		
		public $uri_privileged;
		
		function __construct() {
			
			parent::__construct();
			
			// To load the CI benchmark and memory usage profiler - set 1==1.
			if (1 == 2) {
				$sections = array(
                'benchmarks' => TRUE, 'memory_usage' => TRUE,
                'config' => FALSE, 'controller_info' => FALSE, 'get' => FALSE, 'post' => FALSE, 'queries' => FALSE,
                'uri_string' => FALSE, 'http_headers' => FALSE, 'session_data' => FALSE
				);
				$this->output->set_profiler_sections($sections);
				$this->output->enable_profiler(TRUE);
			}
			
			// IMPORTANT! This global must be defined BEFORE the flexi auth library is loaded! 
			// It is used as a global that is accessible via both models and both libraries, without it, flexi auth will not work.
			$this->auth = new stdClass;
			
			// Load 'standard' flexi auth library by default.
			$this->load->library('flexi_auth');
			
			// Check user is logged in as an admin.
			// For security, admin users should always sign in via Password rather than 'Remember me'.
			if (!$this->flexi_auth->is_logged_in_via_password()) {
				// Set a custom error message.
				$this->flexi_auth->set_error_message('You must login as an admin to access this area.', TRUE);
				$this->session->set_flashdata('message', $this->flexi_auth->get_messages());
				redirect('auth');
			}
			
			// Note: This is only included to create base urls for purposes of this demo only and are not necessarily considered as 'Best practice'.
			$this->load->vars('base_url', base_url());
			$this->load->vars('includes_dir', base_url() . 'includes/');
			$this->load->vars('current_url', $this->uri->uri_to_assoc(1));
			
			// Define a global variable to store data that is then used by the end view page.
			$this->data = null;
			
			// get user data
			$user_data = $this->flexi_auth->get_user_by_id($this->flexi_auth->get_user_id())->row();
			
			$this->data['designation'] = $user_data->ugrp_name;
			$this->data['user_name'] = $user_data->upro_first_name . ' ' . $user_data->upro_last_name;
			$this->data['uacc_section_fk'] = $user_data->uacc_section_fk;
			
			$this->data['session_section'] = $session_section = get_session_section();
			
			if(count($session_section == 1)) {
				//$section = get_session_section();
				$this->data["user_section"] = $session_section[$user_data->uacc_section_fk]['sectionName'];
			}
			else {
				$this->data["user_section"] = '';
			}
			
			// load receipts model
			$this->load->model('admin/receipts_model');
			
			// load general model
			$this->load->model('admin/general_model');
			
			//get uri segment for active menu
			$this->data['uri_3'] = $this->uri->segment(3);
			$this->data['uri_2'] = $this->uri->segment(2);
			$this->data['uri_1'] = $this->uri->segment(1);
			
			$this->data['sub_menu'] = $this->data['uri_1'].'/'.$this->data['uri_2'].'/'.$this->data['uri_3'];
			$this->data['menu'] = $this->data['uri_2'];
			//die();
			
			// Get User Privilege 
			$this->load->model('admin/menu_model');
			$check_slash = substr($this->data['sub_menu'], -1);
			$check_slash = ($check_slash == "/")?$this->data['sub_menu']:$this->data['sub_menu']."/";
			$check_slash = str_replace("//","/",$check_slash);
			
			
			$this->uri_privileged = $this->menu_model->get_privilege_name($check_slash);
			$this->data['menu_title'] = $this->uri_privileged;
			
			// Get Dynamic Menus
			$this->data['get_menu'] = $this->menu_model->get_menu();
			
			$this->data['show_send_button'] = array('created', 'inbox', 'roll back');
			$this->data['show_putinfile_button'] = array('created', 'sent' , 'inbox', 'roll back');
			$this->data['show_send_back_button'] = array('inbox');
			$this->data['show_close_button'] = array('created');
			//section menu work
			$this->data['menu_sections'] = $this->general_model->get_menu_section_data();
			$this->data['receipt_inbox'] = $this->general_model->get_receipt_inbox_count($this->data['sub_menu']);
			//$this->data['file_inbox'] = $this->general_model->get_file_inbox_count($this->data['sub_menu']);
			//section menu work ends
			
		}
		
		
		/*
		|------------------------------------------------
		| start: browse_diarise function
		|------------------------------------------------
		|
		| This function add receipt data
		|
		*/
		function browse_diarise($receipt_id = null) {
		
			// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
			if (! $this->flexi_auth->is_privileged($this->uri_privileged))
			{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to browse & diaries.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
			}
			
			$random_submit_num =  rand(1000,100000);

			if($receipt_id) {
				$action = 'update';
				$db_where = array("rd.receiptDetailId" => $receipt_id);
				
				$db_or_where = null;
				$db_where_in = null;
				$db_limit = null;
				$db_order = null;
				$db_select = 'rd.*, cd.*';
				
				$this->data['browse_diaries_data'] = $this->receipts_model->get_receipt($db_where, $db_or_where, $db_where_in, $db_limit, $db_order, $db_select);
				$this->data['receipt_detail_id'] = $receipt_id;
				
				$db_where = array('d.ministryId' => $this->data['browse_diaries_data'][$receipt_id]['ministryId']);
				$this->data['department'] = $this->general_model->get_department($db_where);
				
				//echo '<pre>'; print_r($this->data['browse_diaries_data']); die();
			}
			else {
				$action = 'add';
			}
			
			$this->breadcrumbs->unshift('Browse & Diaries', base_url() . 'admin/receipts/browse_diarise');
			$this->breadcrumbs->unshift('Receipts', '#');
			$upload_dir = $this->config->item('receiptUploadPath');
			if ($this->input->post() && ($this->session->userdata('random_submit_num') == $this->input->post('random_submit_num'))) {
			
				//echo '<pre>'; print_r($this->input->post()); die();
				
				$post_data = $this->input->post();
			
				$this->load->library('form_validation');
				//die('Testing#1');
				$this->form_validation->set_rules('delivery_mode_id', 'Delivery Mode', 'required');
				$this->form_validation->set_rules('document_type_id', 'Document Type', 'required');
				
				
				if($this->input->post('sender_type') == 'Individual') {
					$this->form_validation->set_rules('contact_name', 'Name', 'required');
				}
				
				//$this->form_validation->set_rules('designation', 'Designation', 'required');
				//$this->form_validation->set_rules('address_one', 'Address 1', 'required');
				$this->form_validation->set_rules('category_id', 'Category', 'required');
				$this->form_validation->set_rules('subject', 'Subject', 'required');
				
				if($this->form_validation->run() && file_exists($upload_dir.'temp_files/'.$post['file_uploaded_name'][0])) {
					//die('Testing#1');
					if($receipt_id = $this->receipts_model->browse_diarise($receipt_id)) {
						$this->session->set_flashdata('message', '<p class="status_msg">Browse & Diarise '.$action.' Successfully</p>');
						if(isset($post_data['generate']) && $post_data['generate'] == 'generate') {
							$this->session->set_userdata('random_submit_num', $random_submit_num);
							redirect('admin/receipts/receipt_view/sent/'.$receipt_id);
						}
						else {
							redirect('admin/receipts/receipt_send/'.$receipt_id);
						}
						
					}
				}
				else {
					//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
					//redirect('admin/general/section');
					//exit;
				}
			}
			/*$upload_dir = $this->config->item('fileUploadPath');
				
				//$upload_dir.'temp_files/'.$post['file_uploaded_name'][0];
				
				if ($this->form_validation->run() && (isset($post['file_uploaded_id']) && file_exists($upload_dir.'temp_files/'.$post['file_uploaded_name'][0]))) {
						
					//$directoryFile = $this->directory_file_upload();

					if ($this->receipts_model->browse_diarise()) {
						$this->session->set_flashdata('message', '<p class="status_msg">File added successfully.</p>');
						//die('TEST');
						$this->session->set_userdata('random_submit_num', $random_submit_num);
						redirect('/admin/scaning/add/');
					}
					
					
				}else{
					$this->session->set_flashdata('message', '<p class="error_msg">Error: Please Upload File</p>');
					//echo validation_errors();
					//exit;
				}*/
			$this->data['classified'] = $this->general_model->get_classified();
			$this->data['delivery_mode'] = $this->general_model->get_delivery_mode();
			//echo '<pre>'; print_r($this->data['delivery_mode']); die();
			$this->data['document_type'] = $this->general_model->get_document_type('document_type');
			$this->data['ministry'] = $this->general_model->get_ministry();
			//$this->data['department'] = $this->general_model->get_department();
			$this->data['state'] = $this->general_model->get_state();
			$this->data['category'] = $this->general_model->get_category();
			//$this->data['country'] = $this->general_model->get_country();
			//$this->data['city'] = $this->general_model->get_city();
			//$this->data['sub_category'] = $this->general_model->get_sub_category();
			
			$this->data['sender_type'] = $this->receipts_model->get_enum_value('receipt_detail', 'senderType');
			$this->data['language'] = $this->receipts_model->get_enum_value('receipt_detail', 'language');
			//$this->data['vip'] = $this->receipts_model->get_enum_value('receipt_detail', 'vip');
			//$this->data['vip_name'] = $this->receipts_model->get_enum_value('receipt_detail', 'vipName');
			//$this->data['dealing_hands'] = $this->receipts_model->get_enum_value('receipt_detail', 'dealingHands');
			$this->data['random_submit_num'] = $random_submit_num;
			$this->data['page_title'] = 'Browse & Diaries';
			$this->session->set_userdata('random_submit_num', $random_submit_num);
			
			$this->load->view('admin/includes/header', $this->data);
			$this->load->view('admin/receipt/receipt', $this->data);
		
		}
		/*---- end: browse_diarise function ----*/
		
		/*
		|------------------------------------------------
		| start: edit browse_diarise  function
		|------------------------------------------------
		|
		| This function edit receipt data
		|
		*/
		
		/*---- end: edit browse_diarise function ----*/
		/*
		|------------------------------------------------
		| start: file_upload function
		|------------------------------------------------
		|
		| This function upload file and rename file
		|
		*/
		function file_upload11($action = null) {
				
			$post_data = $this->input->post();
			
			$this->load->library('upload');
			
			$upload_dir = $this->config->item('receiptUploadPath');
			
			/*list($day, $month, $year) = explode('-', date('d-m-Y'));
			
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
			
			$upload_dir_path = $upload_dir.$year.'/'.$month.'/'.$day.'/';*/
			//$upload_dir_path = $upload_dir.'temp_files/';
			
			$upload_config = array(
								'upload_path'   => $upload_dir.'temp_files/',
								'allowed_types' => $this->config->item('allowedFileType'),
								'max_size'      => $this->config->item('allowedFileSize'),
							);
							
			$this->upload->initialize($upload_config);

			
			
			// Change $_FILES to new vars and loop them
			foreach($_FILES['receipt_file'] as $key => $val) {
				foreach($val as $inKey => $v) {
					$field_name = $inKey;
					$_FILES[$field_name][$key] = $v; 
				}
			}
			
			

			// Unset the useless one ;)
			unset($_FILES['receipt_file']);
			
			//echo '<pre>'; print_r($_FILES); die();

			// main action to upload each file
			foreach($_FILES as $field_name => $file) {

				if(!$this->upload->do_upload($field_name)) {
					
					/*if($post_data['receipt_file_hidden']!=""){
						return "same_file_selected"
					}*/
					/*if($action == 'update' && !empty($dataForm['old_'.$field_name])) {
						
						$file_name = $dataForm['old_'.$field_name];
						$ext = pathinfo($file_name, PATHINFO_EXTENSION);
						
						//$new_file_name = $field_name.'-'.$dataForm['notification_no'].'.'.$ext;
						$downloadNo = stripslashes(preg_replace('/[^0-9a-zA-Z_]/',"_",$dataForm['download_no']));
						$new_file_name = $file_type.'_'.$downloadNo.'_'.strtotime("now").rand(1, 100).'.'.$ext;
						
						rename('upload/'.$model_type.'/'.$dataForm['old_'.$field_name], 'upload/'.$model_type.'/'.$new_file_name);

						$downloadFile[$field_name] = $new_file_name;
						
					}
					else {
						$downloadFile[$field_name] = '';
					}*/
					//else{
						$upload_data = array('error' => $this->upload->display_errors());
					//}
					
				}
				else {
					
					/*if($action == 'update' && !empty($dataForm['old_'.$field_name])) {
						unlink('upload/'.$model_type.'/'.$dataForm['old_'.$field_name]);
					}*/

					// otherwise, put the upload datas here.
					// if you want to use database, put insert query in this loop
					$upload_data = $this->upload->data();
					
					//$file_name = $upload_data['file_name'];
					//$ext = pathinfo($file_name, PATHINFO_EXTENSION);
					
					//$new_file_name = $field_name.'-'.$dataForm['notification_no'].'.'.$ext;
					//$downloadNo = stripslashes(preg_replace('/[^0-9a-zA-Z_]/',"_",$dataForm['download_no']));
					//$new_file_name = $file_type.'_'.$downloadNo.'_'.strtotime("now").rand(100, 200).'.'.$ext;
					
					//rename('upload/'.$model_type.'/'.$upload_data['file_name'], 'upload/'.$model_type.'/'.$new_file_name);
					
					//$downloadFile[$field_name] = $new_file_name;

				}
				
			}
			
			return $upload_data;
			
		}
		/*---- end: file_upload function ----*/
		
		
		/*
		|------------------------------------------------
		| start: ajax_get_department function
		|------------------------------------------------
		|
		| This function get department by ministry id
		|
		*/
		function ajax_get_department() {
			$ministry_id = $this->input->post('ministry_id');
			
			$db_where = array('d.ministryId' => $ministry_id);
			
			if(!$ministry = $this->general_model->get_department($db_where))
				$ministry = array();
			
			echo json_encode($ministry);
		}
		/*---- end: ajax_get_department function ----*/
		
		
		/*
		|------------------------------------------------
		| start: receipt_view function
		|------------------------------------------------
		|
		| This function show receipt view
		|
		*/
		function receipt_view($send_inbox_button = null , $receipt_id = null) {
		
			// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
			if (! $this->flexi_auth->is_privileged($this->uri_privileged))
			{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to view receipt.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
			}
			
			if(!$receipt_id)
				redirect('admin/receipts/'.(($send_inbox_button == 'create') ? $send_inbox_button.'d' : $send_inbox_button));
			
			$this->breadcrumbs->unshift('View Receipt', base_url() . 'admin/receipts/receipt_view');
			$this->breadcrumbs->unshift('Receipt', base_url() . '#');
			
			$this->data['page_title'] = 'View Receipt';
			
			$movementInfo = $this->receipts_model->get_file_movements($receipt_id);
			$this->data['movementInfo'] = $movementInfo;
			
			//echo "<pre>"; print_r($movementInfo); exit;
			
			$db_where = array('rd.receiptDetailId' => $receipt_id);
			//$this->data['receipt'] = $this->receipts_model->get_receipt();
			//$this->data['receipt'] = $this->data['receipt'][$receipt_id];
			$db_where_attachment = array('moduleType' => 'receipt');
			//$this->data['receipt_detail'] = $this->receipts_model->get_receipt($db_where);
			$this->data['receipt'] = $this->receipts_model->get_receipt($db_where, null, null, null, null, null, null, $db_where_attachment);
			$this->data['receipt'] = $this->data['receipt'][$receipt_id];
			//echo "<pre>"; print_r($this->data['receipt']); exit;
			$this->data['send_inbox_button'] = $send_inbox_button;
			if($send_inbox_button ==  "inbox"){
				$this->receipts_model->isRead($receipt_id);
			}
			
			//$this->data['file_uploaded'] = $this->receipts_model->get_receipt_files($receipt_id);
			//echo "<pre>"; print_r($this->data['receipt']); exit;
			$this->load->view('admin/includes/header', $this->data);
			$this->load->view('admin/receipt/receipt_view', $this->data);
		
		}
		/*---- end: receipt_view function ----*/
		
		
		/*
		|------------------------------------------------
		| start: receipt_send function
		|------------------------------------------------
		|
		| This function send receipt
		|
		*/
		function receipt_send($receipt_id = null) { //send_user
			
			// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
			if (! $this->flexi_auth->is_privileged($this->uri_privileged))
			{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to send receipt.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
			}
			
			$this->breadcrumbs->unshift('Send Receipt', base_url() . 'admin/receipts/receipt_send');
			$this->breadcrumbs->unshift('Receipt', base_url() . '#');
			
			//echo '<pre>'; print_r($this->input->post()); die();
			//echo '<pre>'; print_r($this->input->post()); die();
			if($this->input->post('sent_receipt')) {
				//echo '<pre>'; print_r($this->input->post()); die();
				
				
				//$post_data = $this->input->post();
			
				$this->load->library('form_validation');
				
				$this->form_validation->set_rules('sent_to', 'Sent To', 'required');
				
				if($this->form_validation->run()) {
				
					
					//if($this->receipts_model->sent_receipt()) {
						$this->receipts_model->sent_receipt();
					
						$this->session->set_flashdata('message', '<p class="status_msg">Receipt Sent Successfully</p>');
						
						//echo '<pre>'; print_r($this->input->post()); die();
						
						redirect('admin/receipts/sent/');
					//}
					
					
					//echo '<pre>'; print_r($receipt_file); die();
				}
				else {
					//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
					//redirect('admin/general/section');
					//exit;
				}

				
			}
			else {
				
				if($receipt_id == null){
					$receipt_id = $this->input->post('receipt_id');
					$send_type = $this->input->post('view_type');
					
					$post_data = $this->input->post();
					$this->data['roll_back_close'] = isset($post_data['roll_back_close']) ? $post_data['roll_back_close'][$receipt_id[0]] : null;
				}
				
				if(!$receipt_id)
					redirect('admin/receipts/created/');
				
				//echo '<pre>'; print_r($receipt_id); die();
				
				$db_where = array();
				$db_or_where = array();
				$db_where_in = array('rd.receiptDetailId' => $receipt_id);
				$db_limit = array();
				$db_order = array();
				$db_order = array();
				$db_where_sent = array('rs.sendId' => $receipt_id);
				$this->data['receipt'] = $this->receipts_model->get_receipt($db_where, $db_or_where, $db_where_in, $db_limit, $db_order);
				//echo '<pre>'; print_r($receipt_id[0]); die();
				$this->data['sent_receipt'] = $this->receipts_model->get_sent_receipt_id($receipt_id[0]);
 				//echo '<pre>'; print_r($this->data['sent_receipt']); die();
			}

		$db_where_in = array('rd.receiptDetailId' => $receipt_id);			
		$this->data['receipt_detail'] = $this->receipts_model->get_receipt(array(), array(), $db_where_in);
		//echo '<pre>'; print_r($this->data['file']); die();
		
		//$db_where_in = array('ns.fileId' => $file_id);	
		//$this->data['file'][$file_id]['note_sheet'] = $this->files_model->get_note_sheet(array(), $db_where_in);
		//echo '<pre>'; print_r($this->data['file']); die();
		$db_where_in = array('rs.fkDetailId' => $receipt_id);
		$this->data['sent_receipt'] = $this->receipts_model->get_sent_receipt_id(null,$db_where_in);
			
			$this->data['page_title'] = 'Send Receipt';
			
			// Select user data to be displayed.
			$sql_select = array(
				$this->flexi_auth->db_column('user_acc', 'id'),
				'designationName',
				'upro_first_name',
				'upro_last_name',
				'uacc_user_job_group_fk'
			);
			
			if($this->auth->database_config['custom_join']['designation']) {
				foreach($this->auth->database_config['custom_join']['designation']['custom_columns'] as $get_custom_column) {
					$sql_select[] = $this->auth->database_config['custom_join']['designation']['table'].'.'.$get_custom_column;
				}
			}
			
			$this->flexi_auth->sql_select($sql_select);
							
			// Get Only Active Users
			$sql_where[$this->flexi_auth->db_column('user_acc', 'active').'='] = 1;
			//$sql_where[$this->flexi_auth->db_column('user_acc', 'uacc_suspend').'!='] = 1;
			//$sql_where[$this->flexi_auth->db_column('user_acc', 'id').'!='] = $this->flexi_auth->get_user_id();
			$sql_where[$this->flexi_auth->db_column('user_acc', 'user_job_group').'!='] = $this->session->userdata('user_job_group');
			$sql_where[$this->flexi_auth->db_column('user_acc', 'designation').'!='] = 0;
					
			$this->flexi_auth->sql_where($sql_where);

			$users = $this->flexi_auth->get_users_array();
			//$this->data['send_user'] = $this->flexi_auth->get_users_array();
			
			
			foreach($users as $key => $get_user) {
				$send_user[$get_user['designationName']][$key] = $get_user;
			}

			$this->data['send_user'] = $send_user;
			//echo '<pre>'; print_r($send_user); die();
			
			//$db_where = array('rd.receiptDetailId' => $receipt_id);
			//$this->data['receipt'] = $this->receipts_model->get_receipt();
			//$this->data['receipt'] = $this->data['receipt'][$receipt_id];
			//echo '<pre>'; print_r($this->input->post('view_type')); die();
			$this->data['send_action'] = $this->general_model->get_send_action();
			$this->data['send_priority'] = $this->general_model->get_send_priority();
			
			$this->data['send_type'] = $send_type;
		
			$this->load->view('admin/includes/header', $this->data);
			$this->load->view('admin/receipt/receipt_send', $this->data);
			
		}
		/*---- end: receipt_send function ----*/
		
		/*
		|------------------------------------------------
		| start: receipt_send_back function
		|------------------------------------------------
		|
		| This function send back receipt
		|
		*/
		function receipt_send_back($receipt_id = null) {
			
			// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
			if (! $this->flexi_auth->is_privileged($this->uri_privileged))
			{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to send receipt.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
			}
			
			$this->breadcrumbs->unshift('Send Back Receipt', base_url() . 'admin/receipts/receipt_send_back');
			
			//echo '<pre>'; print_r($this->input->post()); die();
			//echo '<pre>'; print_r($this->input->post()); die();
			if($this->input->post('sent_receipt')) {
				//echo '<pre>'; print_r($this->input->post()); die();
			
				//$post_data = $this->input->post();
			
				$this->load->library('form_validation');
				
				$this->form_validation->set_rules('receipt_sent_to', 'Sent To', 'required');
				
				if($this->form_validation->run()) {
					//echo '<pre>'; print_r($this->input->post()); die();
					
					//if($this->receipts_model->sent_receipt()) {
						$this->receipts_model->sent_back_receipt();
					
						$this->session->set_flashdata('message', '<p class="status_msg">Receipt Sent Successfully</p>');
						
						//echo '<pre>'; print_r($this->input->post()); die();
						
						redirect('admin/receipts/sent/');
					//}
					
					
					//echo '<pre>'; print_r($receipt_file); die();
				}
				else {
					//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
					//redirect('admin/general/section');
					//exit;
				}
				
			}
			else {
				
				if($receipt_id == null){
					$receipt_id = $this->input->post('receipt_id');
				}
				
				//echo '<pre>'; print_r($receipt_id); die();
				
				//$db_where = array('rs.emailVersion' => "latest");
				$db_or_where = array();
				$db_where_in = array('rd.receiptDetailId' => $receipt_id);
				$db_limit = array('limit'=>'1');
				$db_order = array();
				$db_order = array();
				$db_select= array();
				$view_type= 'inbox';
				$this->data['receipt'] = $this->receipts_model->get_receipt($db_where, $db_or_where, $db_where_in, $db_limit, $db_order, $db_select, $view_type);
				//echo '<pre>'; print_r($this->data['receipt']); die();
				$this->data['sent_receipt'] = $this->receipts_model->get_sent_receipt_id($receipt_id);
				//echo '<pre>'; print_r($this->data['sent_receipt']); die();
				
				foreach($this->data['receipt'] as $get_receipt) {
					$this->data['receiptSendBackId'] = $get_receipt['receiptSentBy'];
				}
				//echo '<pre>'; print_r($this->data['receiptSendBackId']); die();
			}
			
			$this->data['page_title'] = 'Send Back Receipt';
			
			// Select user data to be displayed.
			$sql_select = array(
				$this->flexi_auth->db_column('user_acc', 'id'),
				'designationName',
				'upro_first_name',
				'upro_last_name'
			);
			
			if($this->auth->database_config['custom_join']['designation']) {
				foreach($this->auth->database_config['custom_join']['designation']['custom_columns'] as $get_custom_column) {
					$sql_select[] = $this->auth->database_config['custom_join']['designation']['table'].'.'.$get_custom_column;
				}
			}
			
			$this->flexi_auth->sql_select($sql_select);
							
			// Get Only Active Users
			$sql_where[$this->flexi_auth->db_column('user_acc', 'active').'='] = 1;
			$sql_where[$this->flexi_auth->db_column('user_acc', 'id').'!='] = $this->flexi_auth->get_user_id();
			//$sql_where[$this->flexi_auth->db_column('user_acc', 'designation').'!='] = null;
					
			$this->flexi_auth->sql_where($sql_where);

			$this->data['send_user'] = $this->flexi_auth->get_users_array();
			//echo "<pre>"; print_r($this->data['send_user']); exit;
			//$db_where = array('rd.receiptDetailId' => $receipt_id);
			//$this->data['receipt'] = $this->receipts_model->get_receipt();
			//$this->data['receipt'] = $this->data['receipt'][$receipt_id];
			
			$this->data['send_action'] = $this->general_model->get_send_action();
			$this->data['send_priority'] = $this->general_model->get_send_priority();
			
			$this->load->view('admin/includes/header', $this->data);
			$this->load->view('admin/receipt/receipt_send_back', $this->data);
			
		}
		/*---- end: receipt_send_back function ----*/
		
		
		
		/*
		|------------------------------------------------
		| start: ajax_get_sub_category function
		|------------------------------------------------
		|
		| This function get sub category by category id
		|
		*/
		/*function ajax_get_sub_category() {
			$category_id = $this->input->post('category_id');
			
			$db_where = array('sc.categoryId' => $category_id);
			
			if(!$sub_category = $this->general_model->get_sub_category($db_where))
				$sub_category = array();
			
			echo json_encode($sub_category);
		}*/
		/*---- end: ajax_get_sub_category function ----*/
		
		
		/*
		|------------------------------------------------
		| start: ajax_get_state function
		|------------------------------------------------
		|
		| This function get state by country id
		|
		*/
		function ajax_get_state() {
			$country_id = $this->input->post('country_id');
			
			$db_where = array('s.countryId' => $country_id);
			
			if(!$state = $this->general_model->get_state($db_where))
				$state = array();
			
			echo json_encode($state);
		}
		/*---- end: ajax_get_state function ----*/
		
		
		/*
		|------------------------------------------------
		| start: ajax_get_city function
		|------------------------------------------------
		|
		| This function get city by state id
		|
		*/
		/*function ajax_get_city() {
			$state_id = $this->input->post('state_id');
			
			$db_where = array('c.stateId' => $state_id);
			
			if(!$city = $this->general_model->get_city($db_where))
				$city = array();
			
			echo json_encode($city);
		}*/
		/*---- end: ajax_get_city function ----*/
		
		
		/*
		|------------------------------------------------
		| start: created function
		|------------------------------------------------
		|
		| This function show created listing
		|
		*/
		function created() {
			
			// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
			if (! $this->flexi_auth->is_privileged($this->uri_privileged))
			{
					$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to created listing.</p>');
					if($this->flexi_auth->is_admin())
						redirect('auth_admin');
					else
						redirect('auth_public');
			}
		
			// unshift crumb
			$this->breadcrumbs->unshift('Created', base_url() . 'admin/receipt/created/');
			$this->breadcrumbs->unshift('Receipts', '#');
			
			/*$this->data['search'] = array(
											array(
												'type' => 'text',
												'name' => 'receipt_no',
												'prefix' => 'rd',
												'value'	=> '',
												'serach_type' => 'top_search'
											)
										);*/
			
			//$this->data['top_search_like'] = array();
			
			//$this->data['action_btn'] = json_encode(array('view', 'edit'));
			
			$this->data['dt_datatable'] = array(
												array(
													'th_table' => '',
													'dt_column' => 'checkBox',
													'db_column' => '',
													'db_order_column' => '',
													'td_orderable' => 'false',
													'td_width' => '5%'
												),
												/*array(
													'th_table' => 'Computer No.',
													'dt_column' => '',
													'db_column' => '',
													'td_orderable' => 'true',
													'td_width' => '20%'
												),*/
												array(
													'th_table' => 'Receipt No.',
													'dt_column' => 'receiptNo',
													'db_column' => 'receiptNo',
													'db_order_column' => 'receiptNo',
													'td_orderable' => 'true',
													'td_width' => '10%'
												),
												array(
													'th_table' => 'Subject',
													'dt_column' => 'subject',
													'db_column' => 'subject',
													'db_order_column' => 'subject',
													'td_orderable' => 'true',
													'td_width' => '25%'
												),
												array(
													'th_table' => 'Subject Category',
													'dt_column' => 'categoryName',
													'db_column' => 'categoryName',
													'db_order_column' => 'categoryName',
													'td_orderable' => 'true',
													'td_width' => '10%'
												),
												array(
													'th_table' => 'Created',
													'dt_column' => 'receiptCreatedDate',
													'db_column' => 'rd.createdDate',
													'db_order_column' => 'rd.createdDate',
													'td_orderable' => 'true',
													'td_width' => '10%'
												)
											);
											
			$this->data['datatable_setting'] = array(
													'processing' 	=> 'true',
													'searching' 	=> 'true',
													'autoWidth' 	=> 'false',
													'lengthChange'	=> 'false',
													'order'			=> array('column' => '4', 'value' => 'desc'),
													'pageLength'	=> '20'
												);
			
			$db_where = array('sp.sendPriorityShow' => '1');
			$this->data['send_priority'] = $this->general_model->get_send_priority($db_where);
			
			$db_where = array('categoryShow' => '1');
			$this->data['category'] = $this->general_model->get_category($db_where);
			
			$this->data['page_title'] = 'Created';
			$this->data['view_type'] = 'created';
			
			$this->load->view('admin/includes/header', $this->data);
			$this->load->view('admin/receipt/receipt_listing', $this->data);
			
		}
		
		/*---- end: created function ----*/

		
		/*
		|------------------------------------------------
		| start: sent function
		|------------------------------------------------
		|
		| This function show sent listing
		|
		*/
		function sent() {
			
			// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
			if (! $this->flexi_auth->is_privileged($this->uri_privileged))
			{
					$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to created listing.</p>');
					if($this->flexi_auth->is_admin())
						redirect('auth_admin');
					else
						redirect('auth_public');
			}
		
			// unshift crumb
			$this->breadcrumbs->unshift('Sent', base_url() . 'admin/receipt/sent/');
			$this->breadcrumbs->unshift('Receipts', '#');
			
			//$this->data['action_btn'] = json_encode(array('view', 'edit'));
			$this->data['action_btn'] = json_encode(
													array(
														array(
															'label' => 'roll back',
															'url' => '',
															'icon_class' => 'fa clip-undo roll-back-receipt'
														)
													)
												);
			
			$this->data['dt_datatable'] = array(
												array(
													'th_table' => '',
													'dt_column' => 'checkBox',
													'db_column' => '',
													'db_order_column' => '',
													'td_orderable' => 'false',
													'td_width' => '5%'
												),
												array(
													'th_table' => 'Priority',
													'dt_column' => 'sendPriorityColor',
													'db_column' => '',
													'db_order_column' => '',
													'td_orderable' => 'false',
													'td_width' => '5%'
												),
												array(
													'th_table' => 'Receipt No.',
													'dt_column' => 'receiptNo',
													'db_column' => 'receiptNo',
													'db_order_column' => 'receiptNo',
													'td_orderable' => 'true',
													'td_width' => '8%'
												),
												array(
													'th_table' => 'Subject',
													'dt_column' => 'subject',
													'db_column' => 'subject',
													'db_order_column' => 'subject',
													'td_orderable' => 'true',
													'td_width' => '18%'
												),
												array(
													'th_table' => 'Subject Category',
													'dt_column' => 'categoryName',
													'db_column' => 'categoryName',
													'db_order_column' => 'categoryName',
													'td_orderable' => 'true',
													'td_width' => '10%'
												),
												array(
													'th_table' => 'Sender',
													'dt_column' => 'contactName',
													'db_column' => 'contactName',
													'db_order_column' => 'contactName',
													'td_orderable' => 'true',
													'td_width' => '10%'
												),
												array(
													'th_table' => 'Sent to',
													'dt_column' => 'full_name',
													'db_column' => 'CONCAT(up.upro_first_name, " ", up.upro_last_name)',
													'db_order_column' => 'full_name',
													'td_orderable' => 'true',
													'td_width' => '15%'
												),
												array(
													'th_table' => 'Sent On',
													'dt_column' => 'sentOn',
													'db_column' => 'rs.createdDate',
													'db_order_column' => 'rs.createdDate',
													'td_orderable' => 'true',
													'td_width' => '10%'
												),
												array(
													'th_table' => 'Forwarded On',
													'dt_column' => 'forwardDate',
													'db_column' => 'rs.forwardDate',
													'db_order_column' => 'rs.forwardDate',
													'td_orderable' => 'true',
													'td_width' => '10%'
												),
												array(
													'th_table' => 'Due On',
													'dt_column' => 'dueDate',
													'db_column' => 'dueDate',
													'db_order_column' => 'dueDate',
													'td_orderable' => 'true',
													'td_width' => '10%'
												),
												array(
													'th_table' => 'Hard Copy Received',
													'dt_column' => 'isHardCopyReceived',
													'db_column' => '',
													'db_order_column' => '',
													'td_orderable' => 'false',
													'td_width' => '10%'
												),
												array(
													'th_table' => 'Action',
													'dt_column' => 'viewButton',
													'db_column' => '',
													'db_order_column' => '',
													'td_orderable' => 'false',
													'td_width' => '10%'
												)												
											);
											
			$this->data['datatable_setting'] = array(
													'processing' 	=> 'true',
													'searching' 	=> 'true',
													'autoWidth' 	=> 'false',
													'lengthChange'	=> 'false',
													'order'			=> array('column' => '7', 'value' => 'desc'),
													'pageLength'	=> '20'
												);
			
			$db_where = array('sp.sendPriorityShow' => '1');
			$this->data['send_priority'] = $this->general_model->get_send_priority($db_where);
			
			$db_where = array('categoryShow' => '1');
			$this->data['category'] = $this->general_model->get_category($db_where);
			
			$this->data['view_type'] = 'sent';
			$this->data['page_title'] = 'Sent';
			$this->load->view('admin/includes/header', $this->data);
			$this->load->view('admin/receipt/receipt_listing', $this->data);
			
		}
		/*---- end: sent function ----*/
		
		/*
		|------------------------------------------------
		| start: inbox function
		|------------------------------------------------
		|
		| This function show inbox listing
		|
		*/
		function inbox() {
			
			// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
			if (! $this->flexi_auth->is_privileged($this->uri_privileged))
			{
					$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to created listing.</p>');
					if($this->flexi_auth->is_admin())
						redirect('auth_admin');
					else
						redirect('auth_public');
			}
		
			// unshift crumb
			$this->breadcrumbs->unshift('Inbox', base_url() . 'admin/receipt/inbox/');
			$this->breadcrumbs->unshift('Receipts', '#');
			//$this->data['action_btn'] = json_encode(array('view', 'edit'));
			
			$this->data['dt_datatable'] = array(
												array(
													'th_table' => '',
													'dt_column' => 'checkBox',
													'db_column' => '',
													'db_order_column' => '',
													'td_orderable' => 'false',
													'td_width' => '5%'
												),
												array(
													'th_table' => 'Priority',
													'dt_column' => 'sendPriorityColor',
													'db_column' => '',
													'db_order_column' => '',
													'td_orderable' => 'false',
													'td_width' => '5%'
												),
												array(
													'th_table' => 'Receipt No.',
													'dt_column' => 'receiptNo',
													'db_column' => 'receiptNo',
													'db_order_column' => 'receiptNo',
													'td_orderable' => 'true',
													'td_width' => '8%'
												),
												array(
													'th_table' => 'Subject',
													'dt_column' => 'subject',
													'db_column' => 'subject',
													'db_order_column' => 'subject',
													'td_orderable' => 'true',
													'td_width' => '24%'
												),
												array(
													'th_table' => 'Subject Category',
													'dt_column' => 'categoryName',
													'db_column' => 'categoryName',
													'db_order_column' => 'categoryName',
													'td_orderable' => 'true',
													'td_width' => '11%'
												),
												array(
													'th_table' => 'Sender',
													'dt_column' => 'contactName',
													'db_column' => 'contactName',
													'db_order_column' => 'contactName',
													'td_orderable' => 'true',
													'td_width' => '10%'
												),
												array(
													'th_table' => 'Sent by',
													'dt_column' => 'full_name',
													'db_column' => 'CONCAT(up.upro_first_name, " ", up.upro_last_name)',
													'db_order_column' => 'full_name',
													'td_orderable' => 'true',
													'td_width' => '15%'
												),
												array(
													'th_table' => 'Sent On',
													'dt_column' => 'sentOn',
													'db_column' => 'rs.createdDate',
													'db_order_column' => 'rs.createdDate',
													'td_orderable' => 'true',
													'td_width' => '10%'
												),
												array(
													'th_table' => 'Due On',
													'dt_column' => 'dueDate',
													'db_column' => 'dueDate',
													'db_order_column' => 'dueDate',
													'td_orderable' => 'true',
													'td_width' => '10%'
												),
												array(
													'th_table' => 'Hard Copy Receive',
													'dt_column' => 'hardCopyReceived',
													'db_column' => '',
													'db_order_column' => '',
													'td_orderable' => 'false',
													'td_width' => '10%'
												),
												array(
													'th_table' => 'Action',
													'dt_column' => 'sendBackButton',
													'db_column' => '',
													'db_order_column' => '',
													'td_orderable' => 'false',
													'td_width' => '10%'
												)	
									
											);
											
			$this->data['datatable_setting'] = array(
													'processing' 	=> 'true',
													'searching' 	=> 'true',
													'autoWidth' 	=> 'false',
													'lengthChange'	=> 'false',
													'order'			=> array('column' => '7', 'value' => 'desc'),
													'pageLength'	=> '20'
												);
			
			$db_where = array('sp.sendPriorityShow' => '1');
			$this->data['send_priority'] = $this->general_model->get_send_priority($db_where);
			
			$db_where = array('categoryShow' => '1');
			$this->data['category'] = $this->general_model->get_category($db_where);
			
			$this->data['view_type'] = 'inbox';
			$this->data['page_title'] = 'Inbox';
			//$this->data['current_user'] = $this->flexi_auth->get_user_id();
			//$this->data['current_user'] = $this->flexi_auth->get_user_id();
			
			$this->load->view('admin/includes/header', $this->data);
			$this->load->view('admin/receipt/receipt_listing', $this->data);
			
		}
		/*---- end: inbox function ----*/
		
		/*
		|------------------------------------------------
		| start: roll back function
		|------------------------------------------------
		|
		| This function show roll back listing
		|
		*/
		function roll_back() {
			
			// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
			if (! $this->flexi_auth->is_privileged($this->uri_privileged))
			{
					$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to created listing.</p>');
					if($this->flexi_auth->is_admin())
						redirect('auth_admin');
					else
						redirect('auth_public');
			}
		
			// unshift crumb
			$this->breadcrumbs->unshift('Roll Back', base_url() . 'admin/receipts/roll_back/');
			$this->breadcrumbs->unshift('Receipts', '#');
			
			$this->data['dt_datatable'] = array(
												array(
													'th_table' => '',
													'dt_column' => 'checkBox',
													'db_column' => '',
													'db_order_column' => '',
													'td_orderable' => 'false',
													'td_width' => '5%'
												),
												array(
													'th_table' => 'Priority',
													'dt_column' => 'sendPriorityColor',
													'db_column' => '',
													'db_order_column' => '',
													'td_orderable' => 'false',
													'td_width' => '5%'
												),
												array(
													'th_table' => 'Receipt No.',
													'dt_column' => 'receiptNo',
													'db_column' => 'receiptNo',
													'db_order_column' => 'receiptNo',
													'td_orderable' => 'true',
													'td_width' => '8%'
												),
												array(
													'th_table' => 'Subject',
													'dt_column' => 'subject',
													'db_column' => 'subject',
													'db_order_column' => 'subject',
													'td_orderable' => 'true',
													'td_width' => '24%'
												),
												array(
													'th_table' => 'Subject Category',
													'dt_column' => 'categoryName',
													'db_column' => 'categoryName',
													'db_order_column' => 'categoryName',
													'td_orderable' => 'true',
													'td_width' => '11%'
												),
												array(
													'th_table' => 'Sender',
													'dt_column' => 'contactName',
													'db_column' => 'contactName',
													'db_order_column' => 'contactName',
													'td_orderable' => 'true',
													'td_width' => '10%'
												),
												array(
													'th_table' => 'Sent to',
													'dt_column' => 'full_name',
													'db_column' => 'CONCAT(up.upro_first_name, " ", up.upro_last_name)',
													'db_order_column' => 'full_name',
													'td_orderable' => 'true',
													'td_width' => '15%'
												),
												array(
													'th_table' => 'Sent On',
													'dt_column' => 'sentOn',
													'db_column' => 'rs.createdDate',
													'db_order_column' => 'rs.createdDate',
													'td_orderable' => 'true',
													'td_width' => '10%'
												),
												array(
													'th_table' => 'Due On',
													'dt_column' => 'dueDate',
													'db_column' => 'dueDate',
													'db_order_column' => 'dueDate',
													'td_orderable' => 'true',
													'td_width' => '10%'
												)												
											);
											
			$this->data['datatable_setting'] = array(
													'processing' 	=> 'true',
													'searching' 	=> 'true',
													'autoWidth' 	=> 'false',
													'lengthChange'	=> 'false',
													'order'			=> array('column' => '7', 'value' => 'desc'),
													'pageLength'	=> '20'
												);
			
			$db_where = array('sp.sendPriorityShow' => '1');
			$this->data['send_priority'] = $this->general_model->get_send_priority($db_where);
			
			$db_where = array('categoryShow' => '1');
			$this->data['category'] = $this->general_model->get_category($db_where);
			
			$this->data['view_type'] = 'roll back';
			$this->data['page_title'] = 'Roll Back';
			$this->load->view('admin/includes/header', $this->data);
			$this->load->view('admin/receipt/receipt_listing', $this->data);
			
		}
		/*---- end: roll back function ----*/	
		
		/*
		|------------------------------------------------
		| start: close function
		|------------------------------------------------
		|
		| This function show close receipt listing
		|
		*/
		function close() {
			
			// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
			if (! $this->flexi_auth->is_privileged($this->uri_privileged))
			{
					$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to created listing.</p>');
					if($this->flexi_auth->is_admin())
						redirect('auth_admin');
					else
						redirect('auth_public');
			}
		
			// unshift crumb
			$this->breadcrumbs->unshift('Close', base_url() . 'admin/receipts/close/');
			$this->breadcrumbs->unshift('Receipts', '#');
			
			$this->data['dt_datatable'] = array(
												array(
													'th_table' => '',
													'dt_column' => 'checkBox',
													'db_column' => '',
													'db_order_column' => '',
													'td_orderable' => 'false',
													'td_width' => '5%'
												),
												/*array(
													'th_table' => 'Computer No.',
													'dt_column' => '',
													'db_column' => '',
													'td_orderable' => 'true',
													'td_width' => '20%'
												),*/
												array(
													'th_table' => 'Receipt No.',
													'dt_column' => 'receiptNo',
													'db_column' => 'receiptNo',
													'db_order_column' => 'receiptNo',
													'td_orderable' => 'true',
													'td_width' => '10%'
												),
												array(
													'th_table' => 'Subject',
													'dt_column' => 'subject',
													'db_column' => 'subject',
													'db_order_column' => 'subject',
													'td_orderable' => 'true',
													'td_width' => '25%'
												),
												array(
													'th_table' => 'Subject Category',
													'dt_column' => 'categoryName',
													'db_column' => 'categoryName',
													'db_order_column' => 'categoryName',
													'td_orderable' => 'true',
													'td_width' => '10%'
												),
												array(
													'th_table' => 'Receipt Remarks',
													'dt_column' => 'receiptRemarks',
													'db_column' => 'receiptRemarks',
													'db_order_column' => 'receiptRemarks',
													'td_orderable' => 'true',
													'td_width' => '20%'
												),
												array(
													'th_table' => 'Created',
													'dt_column' => 'createdDate',
													'db_column' => '',
													'db_order_column' => '',
													'td_orderable' => 'false',
													'td_width' => '10%'
												)
											);
												
			$this->data['datatable_setting'] = array(
													'processing' 	=> 'true',
													'searching' 	=> 'true',
													'autoWidth' 	=> 'false',
													'lengthChange'	=> 'false',
													'order'			=> array('column' => '1', 'value' => 'desc'),
													'pageLength'	=> '20'
												);
			
			$db_where = array('sp.sendPriorityShow' => '1');
			$this->data['send_priority'] = $this->general_model->get_send_priority($db_where);
			
			$db_where = array('categoryShow' => '1');
			$this->data['category'] = $this->general_model->get_category($db_where);
			
			$this->data['view_type'] = 'cancel';
			$this->data['page_title'] = 'Close Receipt';
			$this->load->view('admin/includes/header', $this->data);
			$this->load->view('admin/receipt/receipt_listing', $this->data);
			
		}
		/*---- end: close function ----*/
		
		/*
		|------------------------------------------------
		| start: receipt_tracking
		|------------------------------------------------
		|
		| This function show receipt_tracking
		|
		*/
		function receipt_tracking() {
			
			// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
			if (! $this->flexi_auth->is_privileged($this->uri_privileged))
			{
					$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to created listing.</p>');
					if($this->flexi_auth->is_admin())
						redirect('auth_admin');
					else
						redirect('auth_public');
			}
			
			// unshift crumb
			$this->breadcrumbs->unshift('Receipt Tracking', base_url() . 'admin/receipt/receipt_tracking/');
			$this->breadcrumbs->unshift('Receipts','#');
			//$this->data['action_btn'] = json_encode(array('view', 'edit'));
			
			$this->data['dt_datatable'] = array(
												array(
													'th_table' => 'Receipt No.',
													'dt_column' => 'receiptNo',
													'db_column' => 'receiptNo',
													'db_order_column' => 'receiptNo',
													'td_orderable' => 'true',
													'td_width' => '7%'
												),
												array(
													'th_table' => 'File No.',
													'dt_column' => 'linkedFileNumber',
													'db_column' => 'linkedFileNumber',
													'db_order_column' => 'linkedFileNumber',
													'td_orderable' => 'true',
													'td_width' => '7%'
												),
												array(
													'th_table' => 'R & I Diary No',
													'dt_column' => 'rAndIDiaryNo',
													'db_column' => 'rAndIDiaryNo',
													'db_order_column' => 'rAndIDiaryNo',
													'td_orderable' => 'true',
													'td_width' => '10%'
												),
												array(
													'th_table' => 'Subject',
													'dt_column' => 'subject',
													'db_column' => 'subject',
													'db_order_column' => 'subject',
													'td_orderable' => 'true',
													'td_width' => '15%'
												),
												array(
													'th_table' => 'Subject Category',
													'dt_column' => 'categoryName',
													'db_column' => 'categoryName',
													'db_order_column' => 'categoryName',
													'td_orderable' => 'true',
													'td_width' => '10%'
												),
												array(
													'th_table' => 'Sender',
													'dt_column' => 'contactName',
													'db_column' => 'contactName',
													'db_order_column' => 'contactName',
													'td_orderable' => 'true',
													'td_width' => '10%'
												),
												array(
													'th_table' => 'Current Status',
													'dt_column' => 'full_name',
													//'db_column' => 'CONCAT(up.upro_first_name, " ", up.upro_last_name)',
													'db_column' => 'full_name',
													'db_order_column' => 'full_name',
													'td_orderable' => 'true',
													'td_width' => '10%'
												),
												array(
													'th_table' => 'Forward Date',
													'dt_column' => 'forwardDate',
													'db_column' => 'rs.forwardDate',
													'db_order_column' => 'rs.forwardDate',
													'td_orderable' => 'true',
													'td_width' => '8%'
												),
												array(
													'th_table' => 'System Forward Date',
													'dt_column' => 'sentOn',
													'db_column' => 'rs.createdDate',
													'db_order_column' => 'rs.createdDate',
													'td_orderable' => 'true',
													'td_width' => '8%'
												),
												array(
													'th_table' => 'Letter Date',
													'dt_column' => 'letterDate',
													'db_column' => 'letterDate',
													'db_order_column' => 'letterDate',
													'td_orderable' => 'true',
													'td_width' => '8%'
												),
												array(
													'th_table' => 'Receipt Entered Date',
													'dt_column' => 'receiptCreatedDate',
													'db_column' => 'rd.createdDate',
													'db_order_column' => 'rd.createdDate',
													'td_orderable' => 'true',
													'td_width' => '10%'
												),	
											);
											
			$this->data['datatable_setting'] = array(
													'processing' 	=> 'true',
													'searching' 	=> 'false',
													'autoWidth' 	=> 'false',
													'lengthChange'	=> 'false',
													'order'			=> array('column' => '6', 'value' => 'desc'),
													'pageLength'	=> '20'
												);
			
			$db_where = array('sp.sendPriorityShow' => '1');
			$this->data['send_priority'] = $this->general_model->get_send_priority($db_where);
			
			$db_where = array('categoryShow' => '1');
			$this->data['category'] = $this->general_model->get_category($db_where);
			
			$this->data['subject_category'] = $this->general_model->get_category();
			
			$this->data['receipt_status'] = $this->general_model->get_enum_value('receipt_detail','receiptStatus');
			
			$this->data['default_listing'] = false;
			
			$post = $this->input->post();
			//echo "<pre>";print_r($post);exit;

			if(!$post){
				$post['rd_created_date'] = date('d-m-Y');
			}
			
			if($post['rd_receipt_no']!='' || $post['rd_category_id']!='' || $post['cd_contact_name']!='' || $post['rd_letter_date']!='' || $post['rd_subject']!='' || $post['rd_created_date']!=''){
				$this->data['receiptno']=$post['rd_receipt_no'];
				$this->data['categoryid']=$post['rd_category_id'];
				$this->data['contact_name']=$post['cd_contact_name'];
				$this->data['letter_date']=$post['rd_letter_date'];
				$this->data['created_date']=$post['rd_created_date'];
				$this->data['subject']=$post['rd_subject'];

			}
			else{
				$this->data['error_message'] = 'receipt';
			}
			
			if($this->input->post()) {
				$this->data['start_created_date'] = $this->input->post('start_created_date');
				$this->data['end_created_date'] = $this->input->post('end_created_date');
			}
			else {
				$this->data['start_created_date'] = date('d-m-Y');
				$this->data['end_created_date'] = date('d-m-Y');
			}
			
			// Select only active records
			/*$sql_select = array('ugrp_id as id', 'ugrp_name as name', 'CONCAT('.$this->flexi_auth->db_column('user_group', 'id').', "|group") as id_type');
			$sql_where  = array();
			$sql_where[$this->flexi_auth->db_column('user_group', 'active')] = 1;
			$where_not_in_array = array($this->config->item('task_creater_group_id'), $this->config->item('admin_group_id'));
			$this->flexi_auth->sql_where_not_in($this->flexi_auth->db_column('user_group', 'id'), $where_not_in_array);
			$this->data['user_groups'] = $this->flexi_auth->get_groups_array($sql_select,$sql_where);*/
			
			//echo '<pre>'; print_r($this->data['user_groups']); die();
			
			
			// Select user data to be displayed.
			$sql_select = array(
				$this->flexi_auth->db_column('user_acc', 'id'),
				'designationName',
				'upro_first_name',
				'upro_last_name',
				'uacc_user_job_group_fk'
			);
			
			if($this->auth->database_config['custom_join']['designation']) {
				foreach($this->auth->database_config['custom_join']['designation']['custom_columns'] as $get_custom_column) {
					$sql_select[] = $this->auth->database_config['custom_join']['designation']['table'].'.'.$get_custom_column;
				}
			}
			
			$this->flexi_auth->sql_select($sql_select);
							
			// Get Only Active Users
			$sql_where[$this->flexi_auth->db_column('user_acc', 'active').'='] = 1;
			//$sql_where[$this->flexi_auth->db_column('user_acc', 'uacc_suspend').'!='] = 1;
			//$sql_where[$this->flexi_auth->db_column('user_acc', 'id').'!='] = $this->flexi_auth->get_user_id();
			//$sql_where[$this->flexi_auth->db_column('user_acc', 'user_job_group').'!='] = $this->session->userdata('user_job_group');
			$sql_where[$this->flexi_auth->db_column('user_acc', 'designation').'!='] = 0;
					
			$this->flexi_auth->sql_where($sql_where);

			$users = $this->flexi_auth->get_users_array();
			//$this->data['send_user'] = $this->flexi_auth->get_users_array();
			
			
			foreach($users as $key => $get_user) {
				$send_user[$get_user['designationName']][$key] = $get_user;
			}

			$this->data['send_user'] = $send_user;
			//echo '<pre>'; print_r($send_user); die();
				
			$this->data['view_type'] = 'receipt_tracking';
			$this->data['page_title'] = 'Receipt Tracking';
			//$this->data['current_user'] = $this->flexi_auth->get_user_id();
			//$this->data['current_user'] = $this->flexi_auth->get_user_id();
			
			$this->load->view('admin/includes/header', $this->data);
			$this->load->view('admin/receipt/receipt_tracking', $this->data);
			
		}
		/*---- end: inbox function ----*/
		
		/*
		|------------------------------------------------
		| start: get_listing function
		|------------------------------------------------
		|
		| This function get receipt listing
		|
		*/
		function get_listing() {
			
			if($this->input->post('roll_back_recipt_id')) {
				//$this->receipt_roll_back($this->input->post('roll_back_recipt_id'));
				$this->receipts_model->receipt_roll_back($this->input->post('roll_back_recipt_id'));
			}
			
			//$dt_datatable = json_decode($this->input->post('dt_datatable'));
			$dt_datatable = $this->input->post('dt_datatable');
			//echo '<pre>'; print_r($dt_datatable); die();
			$action_btn_array = json_decode($this->input->post('action_btn'));					
			$view_type = $this->input->post('view_type');
			//die();
			//echo '<pre>'; print_r($view_type); die();
			
			if($view_type == 'created' || $view_type == 'cancel' || $view_type == 'receipt_tracking'){
			//if($view_type == 'created' || $view_type == 'cancel'){				
				$count_column_name = 'receiptDetailId';
				//echo "<pre>";print_r($count_column_name);exit;
			}
			else{
				$count_column_name = 'sentId';
				//echo "<pre>";print_r($count_column_name);exit;
			}

			
			$db_where		= array();
			$db_or_where	= array();
			$db_where_in	= array();
			$db_limit       = array();
			$db_order       = array();
			$db_select		= "COUNT(rd.receiptDetailId) as count, 'count' AS ".$count_column_name;
			
			
			/***** start: record limit and record start form *****/
			if($this->input->post('length') != '-1') {
				$db_limit['limit'] = $this->input->post('length');
				$db_limit['startPageRecord'] = $this->input->post('start');
			}
			// end: record limit and record start form
			
			
			/***** start: get data order by *****/
			$order = $this->input->post('order');
			if($order) {
				foreach($order as $key => $get_order) {
					$db_order[$key]['title']    = $dt_datatable[$get_order['column']]['db_order_column'];
					$db_order[$key]['order_by'] = $get_order['dir'];
				}            
			}
			// end: get data order by
			
			
			/***** start: top search data by equal to *****/
			if($this->input->post('top_search')) {
				foreach($this->input->post('top_search') as $key => $search_val) {
					if(preg_match('/rd/', $key)) {
						
						$search_key = substr($key, 3);
						
						if(preg_match('/bet22/', $key))
							$search_key = substr($key, 8);						
						
						$search_key = explode('_', $search_key);
						$new_search_key = '';
						foreach($search_key as $get_search_key) {
							$new_search_key = $new_search_key.ucfirst($get_search_key);
						}
						
						if(preg_match('/bet22/', $key)) {
							
							if($search_val['start'])
								$db_where['rd.'.$new_search_key.' >='] = date('Y-m-d', strtotime($search_val['start'])).' 00:00:00';
							
							if($search_val['end'])
								$db_where['rd.'.$new_search_key.' <='] = date('Y-m-d', strtotime($search_val['end'])).' 23:59:59';
							
						}
						// else if ($view_type == 'receipt_tracking'){
						// 	if($search_val!="")
						// 		$db_where['rs.'.$new_search_key]  = $search_val;
						// }
						else {
							if($search_val!="")
								$db_where['rd.'.$new_search_key]  = $search_val;
						}
						
						
					}
					
					if(preg_match('/rs/', $key)) {

						$search_key = substr($key, 3);
						
						$search_key = explode('_', $search_key);
						$new_search_key = '';
						foreach($search_key as $get_search_key) {
							$new_search_key = $new_search_key.ucfirst($get_search_key);
						}
						
						if($search_val!="")
							$db_where['rs.'.$new_search_key]  = $search_val;

					}
				}
			}
			
			// end: top search data by equal to
			
			//echo '<pre>'; print_r($db_where); die();
			
			
			/***** start: top search data by like *****/
			if($this->input->post('top_search_like')) {
				foreach($this->input->post('top_search_like') as $key => $search_val) {
					if(preg_match('/rd/', $key)) {

						$search_key = substr($key, 3);
						
						$search_key = explode('_', $search_key);
						$new_search_key = '';
						foreach($search_key as $get_search_key) {
							$new_search_key = $new_search_key.ucfirst($get_search_key);
						}
						
						if($search_val!="" && $new_search_key!='LetterDate' && $new_search_key!='CreatedDate'){
							$db_or_where['rd.'.$new_search_key . ' LIKE']  = '%' . $search_val . '%';
							}
						else if($search_val!="" && ($new_search_key == 'LetterDate' || $new_search_key == 'CreatedDate')){
							$db_or_where['rd.'.$new_search_key . ' LIKE'] =  '%' . date('Y-m-d', strtotime($search_val)) . '%';
							}

					}
					
					if(preg_match('/rs/', $key)) {

						$search_key = substr($key, 3);
						
						$search_key = explode('_', $search_key);
						$new_search_key = '';
						foreach($search_key as $get_search_key) {
							$new_search_key = $new_search_key.ucfirst($get_search_key);
						}
						
						if($search_val!="")
							$db_or_where['rs.'.$new_search_key ]  = $search_val;

					}
										
					if(preg_match('/cd/', $key)) {

						$search_key = substr($key, 3);
						
						$search_key = explode('_', $search_key);
						$new_search_key = '';
						foreach($search_key as $get_search_key) {
							$new_search_key = $new_search_key.ucfirst($get_search_key);
						}
						
						if($search_val!="")
							$db_or_where['cd.'.$new_search_key . ' LIKE']  = '%' .$search_val. '%';

					}
				}
			}
			// end: top search data by like
			
			/***** start: search data by like *****/
			$search = $this->input->post('search');
			
			if($search['value'] != '') {
				foreach($dt_datatable as $get_dt_datatable) { 
					if(!empty($get_dt_datatable['db_column'])) {
						$db_or_where[$get_dt_datatable['db_column'] . ' LIKE']   = '%' . $search['value'] . '%';
					}
				}
			}
			// end: search data by like
			
			
			$dataRecord = $this->receipts_model->get_receipt($db_where, $db_or_where, $db_where_in, $db_limit, $db_order, null, $view_type);
			
			//echo '<pre>'; print_r($dataRecord); die();
			
			$dataCount = $this->receipts_model->get_receipt($db_where, $db_or_where, $db_where_in, null, null, $db_select, $view_type);
			$dataCount = $dataCount['count']['count'];
						
			
			$data = array();
			$i = 0;
			
			if($dataRecord) {
				
				foreach($dataRecord as $key => $value) {	
					foreach($dt_datatable as $get_dt_datatable) {
						
						if($get_dt_datatable['dt_column'] == 'viewButton') {
							if($value['isRead']==0) {
								if($action_btn_array) {
									$button_html = '';
									foreach($action_btn_array as $action_btn) {
										$button_url = ($action_btn->url) ? base_url().''.$action_btn->url.'/'.$value['receiptDetailId'] : 'javascript:void(0)';
										$button_html .= '<a href="'.$button_url.'" class="btn btn-xs btn-primary text-center tooltips" title="" action-recipt-id="'.$value['receiptDetailId'].'" data-original-title="'.$action_btn->label.'"><i class="'.$action_btn->icon_class.'"></i></a>';
									}
									$data[$i][] .= $button_html;
								}
							}
							else {
								if($value['sentBackStatus']=='sent back' || $value['isRead']==1) {
									if($action_btn_array) {
										$button_html = '';
										foreach($action_btn_array as $action_btn) {
											$button_url = ($action_btn->url) ? base_url().''.$action_btn->url.'/'.$value['receiptDetailId'] : 'javascript:void(0)';
											$button_html .= '<a href="'.$button_url.'" style="cursor: not-allowed;opacity: 0.5;text-decoration: none;" class="btn btn-xs btn-primary text-center tooltips" title="" action-recipt-id="'.$value['receiptDetailId'].'" data-original-title="'.$action_btn->label.'"><i class="'.$action_btn->icon_class.'"></i></a>';
										}
										$data[$i][] .= $button_html;
									}
								}
							}
						
							//$data[$i][] = '<a href="'.base_url().'admin/scaning/scaning_detail/'.$value['fileId'].'" class="btn btn-xs btn-primary text-center" title=""><i class="fa fa-eye"></i></a>'; //Link to view the property detail <i class="fa fa-eye"></i>
							
						}
						else if($get_dt_datatable['dt_column'] == 'hardCopyReceived') {
							//$receipt_detail_id_hard = $value['receiptDetailId'];
							if($value['isHardCopyReceived']!=1) {
								$data[$i][]='<button type="button" id="hard_copy_'.$value['receiptDetailId'].'" onclick=hard_copy_received("'.$value['receiptDetailId'].'") class="receipt_submit btn btn-primary top_btn"> Hard Copy Receive </button>';
							}
							else {
								$data[$i][]='<button type="button" id="hard_copy_'.$value['receiptDetailId'].'" onclick=hard_copy_received("'.$value['receiptDetailId'].'") class="receipt_submit btn btn-success top_btn" disabled> Hard Copy Received </button>';
							}
						}
						else if ($get_dt_datatable['dt_column'] == 'isHardCopyReceived') {
							if($value['isHardCopyReceived']!=1) {
								$data[$i][] = "<span class='label label-danger' style='opacity: 1;'> Not Received </span>";
							}
							else {
								$data[$i][] = "<span class='label label-success' style='opacity: 1;'> Received </span>";
							}
						}
						else if($get_dt_datatable['dt_column'] == 'sendBackButton') {
							$data[$i][]='<a href="'.base_url().'admin/receipts/receipt_send_back/'.$value['receiptDetailId'].'" class="receipt_submit btn btn-primary top_btn">
											<img src="'.base_url().'includes/admin/images/sentback.png" width="20px" height="20px" style="margin-left:2px; cursor: pointer;" />
										</a>';
						}
						else if($get_dt_datatable['dt_column'] == 'receiptNo') {
							if($view_type == 'sent') {
								$data[$i][] = '<a href="'.base_url().'admin/receipts/receipt_view/sent/'.$value['receiptDetailId'].'" class="text-center" title="">'.$value['receiptNo'].'</a><input type="hidden" id="color_by_input" name="color_by_input" value="'.$value['categoryColor'].'" />'; 
							}
							else if($view_type == 'inbox') {
								$data[$i][] = '<a href="'.base_url().'admin/receipts/receipt_view/inbox/'.$value['receiptDetailId'].'" class="text-center" title="">'.$value['receiptNo'].'</a><input type="hidden" id="color_by_input" name="color_by_input" value="'.$value['categoryColor'].'" />'; 
							}
							else {
								$data[$i][] = '<a href="'.base_url().'admin/receipts/receipt_view/create/'.$value['receiptDetailId'].'" class="text-center" title="">'.$value['receiptNo'].'</a><input type="hidden" id="color_by_input" name="color_by_input" value="'.$value['categoryColor'].'" />'; 
							}
						}
						else if($get_dt_datatable['dt_column'] == 'receiptCreatedDate') {
							$data[$i][] = date('d-m-Y', strtotime($value[$get_dt_datatable['dt_column']]));
						}
						else if($get_dt_datatable['dt_column'] == 'letterDate') {
							
							//echo $value[$get_dt_datatable['dt_column']];
							//echo ' == ';
							
							//echo ($value[$get_dt_datatable['dt_column']] == '0000-00-00') ? '' : date('d-m-Y', strtotime($value[$get_dt_datatable['dt_column']])); die();
							
							$data[$i][] = ($value[$get_dt_datatable['dt_column']] == '0000-00-00') ? '' : date('d-m-Y', strtotime($value[$get_dt_datatable['dt_column']]));
						}
						else if($get_dt_datatable['dt_column'] == 'linkedFileNumber') {
							if($value['linkedFileNumber']!='' && $value['linkedFileNumber']!=0) {
								$data[$i][] = '<center><a href="'.base_url().'admin/files/note_sheet_detail/'.$value['fileId'].'/'.$value['noteSheetId'].'" class="text-center" title="" target="_blank">'.$value[$get_dt_datatable['dt_column']].'</a><input type="hidden" id="color_by_input" name="color_by_input" value="'.$value['categoryColor'].'" /></center>';
							}
							else if($value['linkedFileNumber'] == '' || $value['linkedFileNumber'] == 0) {
								$data[$i][] = '<center>-</center>';
							}
							else {
								$data[$i][] = '<a href="'.base_url().'admin/files/note_sheet_detail/'.$value['fileId'].'/'.$value['noteSheetId'].'" class="text-center" title="" target="_blank">'.$value[$get_dt_datatable['dt_column']].'</a><input type="hidden" id="color_by_input" name="color_by_input" value="'.$value['categoryColor'].'" />'; 
							}
						}
						else if($get_dt_datatable['dt_column'] == 'subject') {
							if($value['sentBackStatus']=="sent") {
								$data[$i][] = $value['subject'];
							}
							else if($value['sentBackStatus']=="sent back" && $view_type != 'receipt_tracking') {
								$data[$i][] = $value['subject'].'<a data-toggle="modal" data-target="#remarks_'.$value['receiptSentId'].'"><img src="'.base_url().'includes/admin/images/sentback.png" width="17px" height="17px" style="margin-left:2px; cursor: pointer;" /></a><div id="remarks_'.$value['receiptSentId'].'" class="modal fade" role="dialog"> 
								  <div class="modal-dialog">
									<!-- Modal content-->
									<div class="modal-content">
									  <div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Remarks</h4>
									  </div>
									  <div class="modal-body">
										<p style="font-size:15px;">'.$value['remarks'].'</p>
									  </div>
									  <div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									  </div>
									</div>

								  </div>
								</div>';
							}
							else {
								$data[$i][] = $value['subject'];
							}
						}
						else if($get_dt_datatable['dt_column'] == 'sentOn' || $get_dt_datatable['dt_column'] == 'dueDate' || $get_dt_datatable['dt_column'] == 'forwardDate') {
							if($value[$get_dt_datatable['dt_column']]!='') {
								$data[$i][] = date('d-m-Y', strtotime($value[$get_dt_datatable['dt_column']]));
							}
							else {
								$data[$i][] = '';
							}
						}	
						// else if($get_dt_datatable['dt_column'] == 'dueDate' || $get_dt_datatable['dt_column'] == 'forwardDate') {
						// 	if($value[$get_dt_datatable['dt_column']]!='') {
						// 		$data[$i][] = date('d-m-Y', strtotime($value[$get_dt_datatable['dt_column']]));
						// 	}
						// 	else {
						// 		$data[$i][] = '';
						// 	}
						// }
						else if($get_dt_datatable['dt_column'] == 'checkBox') {
							 $check_box = '<div class="checkbox-table"><label><input type="checkbox" name="receipt_id[]" class="grey checked_box" value="'.$value['receiptDetailId'].'"></label></div><span style="display:none;">'.$value['categoryColor'].'</span>';
							
							if($view_type == 'roll back' || $view_type == 'inbox') {
								$check_box .= '<input type="hidden" name="roll_back_close['.$value['receiptDetailId'].']" value="'.$value['sentId'].'">';
							}
							
							$data[$i][] = $check_box;
						}
						else if($get_dt_datatable['dt_column'] == 'sendPriorityColor') {
							$data[$i][] = '<span class="p_square" style="background: '.$value['sendPriorityColor'].'" is-read="'.$value['isRead'].'"></span>';
						}
						else if($get_dt_datatable['dt_column'] == 'full_name') {
							if($value['sentStatus'] != 'roll back') {
								if($value['designationId']!="") {
									$data[$i][] = $value['full_name']." (".$value['designationName'].")" ;
								}
								else {
									$data[$i][] = $value['full_name'];
								}								
							}
							else {
								$data[$i][] = '<div style="text-align: center;">-</div>';
							}
						}
						/*else if($get_dt_datatable['dt_column'] == 'full_name') {
							if() {
							}
						}*/
						else {
							$data[$i][] = $value[$get_dt_datatable['dt_column']];
						}
						
					}
					//$data[i][] = $value['categoryColor'];
					$i++;
					
				}
			}
			
			$this->data['datatable']['draw']            = $this->input->post('draw');
			$this->data['datatable']['recordsTotal']    = $dataCount;
			$this->data['datatable']['recordsFiltered'] = $dataCount;
			$this->data['datatable']['data']            = $data;
			
			//echo '<pre>'; print_r($this->data['datatable']); die();
			
			echo json_encode($this->data['datatable']);
			
		}
		/*---- end: get_listing function ----*/
		
		/*
		|------------------------------------------------
		| start: receipt_put_file function
		|------------------------------------------------
		|
		| This function get receipt listing
		|
		*/
		function receipt_put_file($receipt_id = null){
			
			// load files model
			$this->load->model('admin/files_model');
			
			// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
			if (! $this->flexi_auth->is_privileged($this->uri_privileged))
			{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to view receipt.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
			}
			
			if($receipt_id == null)
			{
				$receipt_id = $_POST['receipt_id'][0];
			}
			
			if ($this->input->post('attach') == 'attach') {
				
				//echo '<pre>'; print_r($this->input->post()); die(); 
				
				$receiptId = $this->input->post('receipt_id');
				$fileNo = $this->input->post('file_no');
				
				if(!empty($receiptId) && !empty($fileNo)){
					$this->receipts_model->put_in_file($receiptId , $fileNo);
					redirect('admin/receipts/receipt_put_file/'.$receiptId);
				}
				// echo $receiptId ." <br> " . $fileId; exit;
			}
			
			$db_where		= array('fd.createdBy' => $this->flexi_auth->get_user_id(), 'fd.fileExistenceId' => $this->flexi_auth->get_user_id());
			$db_or_where	= array();
			$db_where_in	= array();
			$db_limit       = array();
			$db_order       = array();
			$db_select		= array();
			
			$filesInfo = $this->files_model->get_file($db_where, $db_or_where, $db_where_in, $db_limit, $db_order, null, $view_type);
			
			$this->data['filesInfo'] = $filesInfo;
			
			$this->breadcrumbs->unshift('Put In File', base_url() . 'admin/receipts/receipt_put_file');
			
			$this->data['page_title'] = 'Put In File';
			
			$db_where = array('rd.receiptDetailId' => $receipt_id);
			$this->data['receipt'] = $this->receipts_model->get_receipt();
			$this->data['receipt'] = $this->data['receipt'][$receipt_id];
			
			$this->load->view('admin/includes/header', $this->data);
			$this->load->view('admin/receipt/receipt_view', $this->data);
		
		}
		/*---- end: receipt_put_file function ----*/
		
		/*
		|------------------------------------------------
		| start: receipt_roll_back function
		|------------------------------------------------
		|
		| This function roll back receipt and status update
		|
		*/
		function receipt_roll_back($receipt_id) {
		
			$this->receipts_model->receipt_roll_back($receipt_id);
			//return true
			//redirect('admin/receipts/sent/');
			
		}
		/*---- end: receipt_roll_back function ----*/
		
		/*
		|------------------------------------------------
		| start: close receipt function
		|------------------------------------------------
		|
		| This function close receipt
		|
		*/
		function close_receipt($receipt_id = null) {
			// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
			if (! $this->flexi_auth->is_privileged($this->uri_privileged))
			{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to send receipt.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
			}
			
			$this->breadcrumbs->unshift('Close Receipt', base_url() . 'admin/receipts/close_receipt');
			
			if($this->input->post('close_receipt')){
				//echo '<pre>'; print_r($this->input->post()); die();
				if($this->receipts_model->close_receipt($receipt_id)){
					$this->session->set_flashdata('message', '<p class="status_msg">Receipt Closed Successfully</p>');
					redirect('admin/receipts/created/');
				}
			}
			else {
				if($receipt_id == null){
					$receipt_id = $this->input->post('receipt_id');
				}
				
				//echo '<pre>'; print_r($receipt_id); die();
				
				$db_where = array();
				$db_or_where = array();
				$db_where_in = array('rd.receiptDetailId' => $receipt_id);
				$db_limit = array();
				$db_order = array();
				$db_order = array();
				$db_select= array();
				//$view_type= 'inbox';
				$this->data['receipt'] = $this->receipts_model->get_receipt($db_where, $db_or_where, $db_where_in, $db_limit, $db_order, $db_select);
				
			}
			
			$this->data['page_title'] = 'Close Receipt';
			$this->load->view('admin/includes/header', $this->data);
			$this->load->view('admin/receipt/close_receipt', $this->data);
		}
		/*---- end: close receipt function ----*/
		
		/*
		|------------------------------------------------
		| start: generate_receiving function
		|------------------------------------------------
		|
		| This function generate receiving
		|
		*/
		function generate_receiving() {
		}
		/*---- end: generate_receiving function ----*/
		
		/*
		|------------------------------------------------
		| start: file_upload function
		|------------------------------------------------
		|
		| load multi file upload library and get product images
		|
	   */
		function file_upload($action, $file_detail_id = null, $file_type_id = null) {
			
			$options = array( 'file_detail_id' => $file_detail_id, 'action' => $action, 'file_type_id' => $file_type_id, 'module_type' => 'receipt');      
			$this->load->library("CustomUploadHandler", $options);
			
		}
		/*---- end: file_upload function ----*/
		function hard_copy_received(){
			if($this->input->post()){
				$this->receipts_model->hard_copy_received();
			}
			echo json_encode('yes');
		}
		
	}	