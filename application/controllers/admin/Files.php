<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Files extends CI_Controller {
    
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
        
        //get uri segment for active menu
        $this->data['uri_3'] = $this->uri->segment(3);
        $this->data['uri_2'] = $this->uri->segment(2);
        $this->data['uri_1'] = $this->uri->segment(1);

        $this->data['sub_menu'] = $this->data['uri_1'].'/'.$this->data['uri_2'].'/'.$this->data['uri_3'];
        $this->data['menu'] = $this->data['uri_2'];
        
        
        // Get User Privilege 
        $this->load->model('admin/menu_model');
        $check_slash = substr($this->data['sub_menu'], -1);
        $check_slash = ($check_slash == "/")?$this->data['sub_menu']:$this->data['sub_menu']."/";
        $check_slash = str_replace("//","/",$check_slash);


        $this->uri_privileged = $this->menu_model->get_privilege_name($check_slash);
        $this->data['menu_title'] = $this->uri_privileged;
		
		// load files model
		$this->load->model('admin/files_model');
		
		// load general model
        $this->load->model('admin/general_model');
		$this->data['menu_sections'] = $this->general_model->get_menu_section_data();
		$this->data['receipt_inbox'] = $this->general_model->get_receipt_inbox_count($this->data['sub_menu']);
		$this->data['file_inbox'] = $this->general_model->get_file_inbox_count($this->data['sub_menu']);
		// Get Dynamic Menus
        $this->data['get_menu'] = $this->menu_model->get_menu();
		
		$this->data['show_send_button'] = array('created', 'inbox', 'roll back');
		$this->data['show_send_back_button'] = array('inbox');
		$this->data['file_inbox'] = $this->general_model->get_file_inbox_count($this->data['sub_menu']);
		//echo "<pre>";print_r($this->data['file_inbox']);exit;
		
                
    }
	
	/*
    |------------------------------------------------
    | start: create function
    |------------------------------------------------
    |
    | Load view of create file
    |
	*/
	function create($file_id = null) {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to this section.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
		}

		if($file_id) {
			$action = 'update';
			$db_where = array("fd.fileId" => $file_id);
			$this->data['file_data'] = $this->files_model->get_file($db_where);
			$this->data['file_id'] = $file_id;
			//echo "<pre>"; print_r($this->data['file_data'][$file_id]['fileTypeId']); exit;
			
		}
		else {
			$action = 'add';
		}
		//echo "<pre>"; print_r($this->data['file_data']); exit;
		// unshift crumb
		$this->breadcrumbs->unshift('Create File', base_url() . 'admin/files/create/');
		$this->breadcrumbs->unshift('Files', '#');
		
		//echo "<pre>";print_r($this->input->post());exit;

		$random_number = rand(1000,100000);


		 if($this->input->post() && $this->input->post('submit_check_session') == $this->session->userdata('submit_check_session')) {
			
			$post_data = $this->input->post();
			//echo '<pre>'; print_r($post_data); die();
			
			$this->load->library('form_validation');
				
			$this->form_validation->set_rules('file_type_id', 'File Type', 'required');

			if($this->input->post('file_type_id') == '1' || $this->input->post('file_type_id') == '2') {
			//die('here');
				$this->form_validation->set_rules('employee_name', 'Employee Name', 'required');
				$this->form_validation->set_rules('employee_cnic', 'Employee CNIC', 'required');
			}
							
			if($this->form_validation->run()) {
			//die('here-again');
				if($file_id = $this->files_model->create($file_id)) {
					//$this->session->set_flashdata('message', '<p class="status_msg">File #:'.$fileId .' Created Successfully</p>');
					$this->session->set_flashdata('message', '<p class="status_msg">File has been '.$action.' Successfully</p>');
					$this->session->set_userdata('submit_check_session',$random_number);    
        			$this->data['submit_check_session'] = $random_number ;
					redirect('admin/files/note_sheet/'.$file_id);
				}
				else{
				}
			}
			else{
			
			}
		}


		$this->session->set_userdata('submit_check_session',$random_number);    
        $this->data['submit_check_session'] = $random_number ;
		
		$this->data['file_types'] = $this->general_model->get_file_types();
		$this->data['general_category'] = $this->general_model->get_general_category_name();
		//echo "<pre>";print_r($this->data['general_category']);exit;
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'Create File';
		
		$this->data['section'] = $this->general_model->get_section();
		$this->data['classified'] = $this->general_model->get_classified();
		$this->data['category'] = $this->general_model->get_category();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/file/create', $this->data);
			
	}
	/*---- end: view_section function ----*/
	
	
	/*
    |------------------------------------------------
    | start: correspondence function
    |------------------------------------------------
    |
    | correspondence view function
    |
	*/
	function correspondence($file_id = null) {
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to this section.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
		}
		
		if(!$file_id)
			redirect('admin/files/create/');

		// unshift crumb
		$this->breadcrumbs->unshift('Correspondence', base_url() . 'admin/files/correspondence/');
		$this->breadcrumbs->unshift('Files', '#');
		
		$this->load->model('admin/receipts_model');
		
		/*if ($this->input->post()) {
			
			$post_data = $this->input->post();
			// echo '<pre>'; print_r($post_data); die();
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules('file_no', 'File Number', 'required');
			$this->form_validation->set_rules('description', 'Description', 'required');
			
			if($this->form_validation->run()) {
				if($fileId = $this->files_model->create()){
					$this->session->set_flashdata('message', '<p class="status_msg">File #:'.$fileId .' Created Successfully</p>');
					redirect('admin/files/note_sheet/'.$fileId);
				}
				else{
				}
			}
			else{
			
			}
		}*/
		
		if($this->input->post('attach_receipt_file')) {
			
			$post_data = $this->input->post();
			//echo '<pre>'; print_r($post_data); die();
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules('attach_receipt_file', 'Receipt', 'required');
			
			if($this->form_validation->run()) { //die('test');
				$this->receipts_model->put_in_file($post_data['receipt_id'] , $post_data['file_number']);
				redirect('admin/files/correspondence/'.$file_id);
			}
			else{
			
			}
		}
		
		$db_where = array('ns.fileId' => $file_id);
		$this->data['note_sheet'] = $this->files_model->get_note_sheet($db_where);
		
		$db_where = array('fd.fileId' => $file_id);
		$this->data['files_detail'] = $this->files_model->get_file($db_where);
		$this->data['files_detail'] = $this->data['files_detail'][$file_id];
		
		//echo '<pre>'; print_r($this->data['files_detail']); die();
		
		//if($table_content == 'toc') {
		if(TRUE) {
			
			
			
			
			//$this->load->model('admin/receipts_model');
			$db_where = array('rd.fileNumber' => $this->data['files_detail']['fileNumber']);
			$this->data['receipt_detail'] = $this->receipts_model->get_receipt($db_where);
			
			//echo '<pre>'; print_r($this->data['receipt_detail']); die();
		}
		
		$this->data['file_id'] = $file_id;
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'Correspondence';
		
		//$this->data['section'] = $this->general_model->get_section();
		//$this->data['classified'] = $this->general_model->get_classified();
		//$this->data['category'] = $this->general_model->get_category();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/file/correspondence', $this->data);
	
	}
	/*---- end: correspondence function ----*/
	
	
	/*
    |------------------------------------------------
    | start: note_sheet function
    |------------------------------------------------
    |
    | note sheet add green and yellow note
    |
	*/
	//function note_sheet($file_id = null, $note_type = null, $note_sheet_id = null, $table_content = null) {
	function note_sheet($file_id = null, $note_type = null, $note_sheet_id = null) {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to this section.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
		}
		
		/*if($note_type == 'green') {
			$this->data['note_color'] = '#d4f8ca';
			$this->data['note_sheet_color'] = $note_type;
		}
		else {
			$this->data['note_color'] = '#fbffc5';
			$this->data['note_sheet_color'] = $note_type;
		}*/
		
		$this->data['note_color'] = '#d4f8ca';
		$this->data['note_sheet_color'] = $note_type;
		
		
		$this->load->model('admin/receipts_model');
		
		$db_where = array('fd.fileId' => $file_id);
		$this->data['files_detail'] = $this->files_model->get_file($db_where);
		$this->data['files_detail'] = $files_detail = $this->data['files_detail'][$file_id];
		
		// echo '<pre>'; print_r($this->data['files_detail']); die();
		
		//$db_where = array('fu.fileDetailId' => $file_id);
		//$this->data['attachment_files'] = $this->files_model->get_upload_attachment($db_where);
		

		// unshift crumb
		//$this->breadcrumbs->unshift(ucfirst($note_type).' note', base_url() . 'admin/files/note_sheet/'.$file_id.'/'.$note_type);
		
		if($this->data['files_detail']['fileStatus'] == 'created')
			$this->breadcrumbs->unshift('Correspondence', base_url() . 'admin/files/correspondence/'.$file_id);
		else
			$this->breadcrumbs->unshift('Note Sheet Details', base_url() . 'admin/files/note_sheet_detail/'.$file_id);
	
		$this->breadcrumbs->unshift('Files', '#');
		
		if($this->input->post('note_sheet_content')) {
			
			$post_data = $this->input->post();
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules('note_sheet_content', 'Note Sheet Content', 'required');
			
			if($this->form_validation->run()) { //die('test');
				if($note_sheet_id = $this->files_model->note_sheet($file_id, $note_type, $note_sheet_id)) {
				//if($note_sheet_id = $this->files_model->note_sheet($file_id, $note_type)) {
					$this->session->set_flashdata('message', '<p class="status_msg">'.ucfirst($note_type).' note Created Successfully</p>');
					if($note_type == 'green') {
						redirect('admin/files/note_sheet/'.$file_id.'/'.$note_type.'/'.$note_sheet_id);
					}
					else {
						redirect('admin/files/note_sheet_view/'.$file_id.'/'.$note_type.'/'.$note_sheet_id);
					}
				}
				else{
				}
			}
			else{
			
			}
		}
		
		if($this->input->post('attach_receipt_file')) {
			
			$post_data = $this->input->post();
			//echo '<pre>'; print_r($post_data); die();
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules('attach_receipt_file', 'Receipt', 'required');
			
			if($this->form_validation->run()) { //die('test');
				$this->receipts_model->put_in_file($post_data['receipt_id'] , $post_data['file_number']);
				redirect('admin/files/note_sheet/'.$file_id.'/'.$note_type.'/'.$note_sheet_id.'/'.$table_content);
			}
			else{
			
			}
		}
		
		if($note_sheet_id) {
			$db_where = array("ns.noteSheetId" => $note_sheet_id, "ns.noteSheetType" => $note_type, "ns.confirmNote" => (($note_types == 'green') ? 1 : 0));
			
			if($this->data['note_sheet'] = $this->files_model->get_note_sheet($db_where)) {
				$this->data['note_sheet'] = $this->data['note_sheet'][$note_sheet_id];
				//echo '<pre>'; print_r($this->data['note_sheet']); die();
			}
			else {
				$this->session->set_flashdata('message', '<p class="status_msg">'.ucfirst($note_type).' note not found</p>');
				redirect('admin/files/correspondence/');
			}
		}
		
		
		
		//if($table_content == 'toc') {
		if(TRUE) {
			
			
			//echo '<pre>'; print_r($this->data['files_detail']); die();
			
			//$this->load->model('admin/receipts_model');
			$db_where = array('rd.fileNumber' => $this->data['files_detail']['fileNumber']);
			
			//echo '<pre>'; print_r($db_where); die();
			
			$db_where_attachment = array('moduleType' => 'note_sheet');
			//$this->data['receipt_detail'] = $this->receipts_model->get_receipt($db_where);
			$this->data['receipt_detail'] = $this->receipts_model->get_receipt($db_where, null, null, null, null, null, null, $db_where_attachment);
			
			//if($this->data['receipt_detail'] as $get_receipt) {
				//$receipt_id = $get_receipt[''];
			//}
			
			//echo '<pre>'; print_r($this->data['receipt_detail']); die();
		}
		
		if($files_detail['employeeCNIC']) {
			$this->load->model('admin/scaning_model');
			$this->data['releted_files'] = $this->scaning_model->get_scaning(array('fd.employeeCNIC' => $files_detail['employeeCNIC']));
		}
				
		//echo '<pre>'; print_r($this->data['releted_files']); die();
				
		$this->data['file_id'] = $file_id;
		$this->data['note_type'] = $note_type;
		$this->data['table_content'] = $table_content;
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		//$this->data['page_title'] = ucfirst($note_type).' note';
		$this->data['page_title'] = 'Correspondence';
		
		//echo '<pre>'; print_r($this->data); die();
		
		
		//$this->data['section'] = $this->general_model->get_section();
		//$this->data['classified'] = $this->general_model->get_classified();
		//$this->data['category'] = $this->general_model->get_category();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/file/note_sheet', $this->data);
		
	}
	/*---- end: note_sheet function ----*/
	
	
	/*
    |------------------------------------------------
    | start: note_sheet_view function
    |------------------------------------------------
    |
    | note sheet add green and yellow note
    |
	*/
	function note_sheet_view($file_id, $note_type = null, $note_sheet_id = null) {
	
		//$note_sheet_id = $this->input->post('note_sheet_id');

		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to this section.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
		}
		
		$this->load->model('admin/receipts_model');
		
		if($note_type == 'green') {
			$this->data['note_color'] = '#d4f8ca';
		}
		else {
			$this->data['note_color'] = '#fbffc5';
		}

		// unshift crumb
		$this->breadcrumbs->unshift(ucfirst($note_type).' note', base_url() . 'admin/files/note_sheet/'.$note_type);
		$this->breadcrumbs->unshift('Correspondence', base_url() . 'admin/files/correspondence/'.$file_id);
		$this->breadcrumbs->unshift('Files', '#');
		
		/*if ($this->input->post()) {
			
			$post_data = $this->input->post();
			// echo '<pre>'; print_r($post_data); die();
			
			$this->load->library('form_validation');
			$this->form_validation->set_rules('note_sheet_content', 'Note Sheet Content', 'required');
			
			if($this->form_validation->run()) {
				if($note_sheet_id = $this->files_model->note_sheet($note_type, $note_sheet_id)){
					$this->session->set_flashdata('message', '<p class="status_msg">'.ucfirst($note_type).' note Created Successfully</p>');
					redirect('admin/files/note_sheet_view/'.$note_type.'/'.$note_sheet_id);
				}
				else{
				}
			}
			else{
			
			}
		}*/
		
		/*if($note_sheet_id) {
			$db_where = array("ns.noteSheetId" => $note_sheet_id);
			$this->data['note_sheet'] = $this->files_model->get_note_sheet($db_where);
			$this->data['note_sheet'] = $this->data['note_sheet'][$note_sheet_id];
		}*/
		//isRead Check
		
		
		$db_where = array('fd.fileId' => $file_id);
		$this->data['files_detail'] = $this->files_model->get_file($db_where);
		$this->data['files_detail'] = $this->data['files_detail'][$file_id];
		
		if(TRUE) {
		
			//echo '<pre>'; print_r($this->data['files_detail']); die();
			
			//$this->load->model('admin/receipts_model');
			$db_where = array('rd.fileNumber' => $this->data['files_detail']['fileNumber']);
			$this->data['receipt_detail'] = $this->receipts_model->get_receipt($db_where);
			
			//echo '<pre>'; print_r($this->data['receipt_detail']); die();
		}
		
		if($note_sheet_id && $note_type == 'yellow') {
			$db_where = array("ns.noteSheetId" => $note_sheet_id, "ns.noteSheetType" => $note_type, "ns.confirmNote" => (($note_types == 'green') ? 1 : 0));
			
			if($this->data['note_sheet'] = $this->files_model->get_note_sheet($db_where)) {
				$this->data['note_sheet'] = $this->data['note_sheet'][$note_sheet_id];
			}
			else {
				$this->session->set_flashdata('message', '<p class="status_msg">'.ucfirst($note_type).' note not found</p>');
				redirect('admin/files/correspondence/');
			}		
		}
		else {
			$this->session->set_flashdata('message', '<p class="status_msg">'.ucfirst($note_type).' note not found</p>');
			redirect('admin/files/correspondence/');
		}
		
		$this->data['note_type'] = $note_type;
		$this->data['file_id'] = $file_id;
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = ucfirst($note_type).' note';
		
		//$this->data['section'] = $this->general_model->get_section();
		//$this->data['classified'] = $this->general_model->get_classified();
		//$this->data['category'] = $this->general_model->get_category();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/file/note_sheet_view', $this->data);
	
	}
	/*---- end: note_sheet_view function ----*/
	
	
	/*
    |------------------------------------------------
    | start: note_sheet_confirm function
    |------------------------------------------------
    |
    | note sheet confirm yellow to green note 
    |
	*/
	function note_sheet_confirm($file_id = null, $note_type = null, $note_sheet_id = null) {
	
		$this->files_model->note_sheet_confirm($note_type, $note_sheet_id);
		$this->session->set_flashdata('message', '<p class="status_msg"> Notesheet successful converted to Green note</p>');
		redirect('admin/files/note_sheet/'.$file_id.'/'.$note_type.'/'.$note_sheet_id);
	}
	/*---- end: note_sheet_confirm function ----*/
	/*
    |------------------------------------------------
    | start: note_sheet_confirm function
    |------------------------------------------------
    |
    | note sheet confirm yellow to green note 
    |
	*/
	function upload_attachment($receipt_id = null, $note_sheet_id = null) {
	
		//echo $note_sheet_id; die();
		$post_data = $this->input->post();
		
		//$file_id = 
		//echo '<pre>'; print_r($_FILES); die();
		
		$this->load->library('upload');
		
		$upload_dir = $this->config->item('fileUploadPath');
		
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
		foreach($_FILES['note_sheet_file'] as $key => $val) {
			foreach($val as $inKey => $v) {
				$field_name = $inKey;
				$_FILES[$field_name][$key] = $v; 
			}
		}
		
		

		// Unset the useless one ;)
		unset($_FILES['note_sheet_file']);
		
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
					//$upload_data = array('error' => $this->upload->display_errors());
					$this->session->set_flashdata('message', '<p class="error_msg">'.$this->upload->display_errors().'</p>');
					redirect('admin/files/note_sheet/'.$post_data['file_id'].'/');
				//}
				
			}
			else { //..die('TEST');
				
				/*if($action == 'update' && !empty($dataForm['old_'.$field_name])) {
					unlink('upload/'.$model_type.'/'.$dataForm['old_'.$field_name]);
				}*/

				// otherwise, put the upload datas here.
				// if you want to use database, put insert query in this loop
				$upload_data = $this->upload->data();
				
				$this->files_model->upload_attachment($receipt_id, $upload_data);
				redirect('admin/files/note_sheet/'.$post_data['file_id'].'/');
				
				//$file_name = $upload_data['file_name'];
				//$ext = pathinfo($file_name, PATHINFO_EXTENSION);
				
				//$new_file_name = $field_name.'-'.$dataForm['notification_no'].'.'.$ext;
				//$downloadNo = stripslashes(preg_replace('/[^0-9a-zA-Z_]/',"_",$dataForm['download_no']));
				//$new_file_name = $file_type.'_'.$downloadNo.'_'.strtotime("now").rand(100, 200).'.'.$ext;
				
				//rename('upload/'.$model_type.'/'.$upload_data['file_name'], 'upload/'.$model_type.'/'.$new_file_name);
				
				//$downloadFile[$field_name] = $new_file_name;

			}
			
		}

		//echo '<pre>'; print_r($upload_data); die();
		
		//return $upload_data;
	
	}
	/*---- end: note_sheet_confirm function ----*/
	
	
	/*
    |------------------------------------------------
    | start: get_receipt_attach function
    |------------------------------------------------
    |
    | get receipt data for attach receipt
    |
	*/
	function get_receipt_attach() {
	
		$this->load->view('admin/file/get_receipt_attach', $this->data);
	
	}
	/*---- end: get_receipt_attach function ----*/
	
	
	/*
	|------------------------------------------------
	| start: get_receipt_ajax function
	|------------------------------------------------
	|
	| This function get receipt listing
	|
	*/
	function get_receipt_ajax() {
	
		//echo '<pre>'; print_r($this->input->post()); die();
		
		
		$dt_datatable = array(
								array(
									'dt_column' => 'radioBox',
									'db_column' => '',
								),
								array(
									'dt_column' => 'receiptNo',
									'db_column' => 'receiptNo',
								),
								array(
									'dt_column' => 'subject',
									'db_column' => 'subject',
								)												
							);
										
										
		
				
		$db_where		= array();
		$db_or_where	= array();
		$db_where_in	= array();
		$db_limit       = array();
		$db_order       = array();
		$db_select		= "COUNT(rd.receiptDetailId) as count, 'count' AS receiptDetailId";
		
		
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
				$db_order[$key]['title']    = $dt_datatable[$get_order['column']]['db_column'];
				$db_order[$key]['order_by'] = $get_order['dir'];
			}            
		}
		// end: get data order by
		
		
		/***** start: top search data by equal to *****/
		if($this->input->post('top_search')) {
			foreach($this->input->post('top_search') as $key => $search_val) {
				if(preg_match('/rd/', $key)) {

					$search_key = substr($key, 3);
					
					$search_key = explode('_', $search_key);
					$new_search_key = '';
					foreach($search_key as $get_search_key) {
						$new_search_key = $new_search_key.ucfirst($get_search_key);
					}
					
					if($search_val!="")
						$db_where['rd.'.$new_search_key]  = $search_val;

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
					
					if($search_val!="")
						$db_or_where['rd.'.$new_search_key . ' LIKE']  = '%' . $search_val . '%';

				}
				
				if(preg_match('/rs/', $key)) {

					$search_key = substr($key, 3);
					
					$search_key = explode('_', $search_key);
					$new_search_key = '';
					foreach($search_key as $get_search_key) {
						$new_search_key = $new_search_key.ucfirst($get_search_key);
					}
					
					if($search_val!="")
						$db_or_where['rs.'.$new_search_key . ' LIKE']  = '%' . $search_val . '%';

				}
			}
		}
		// end: top search data by like
		
		
		
		/***** start: search data by like (datatable) *****/
		$search = $this->input->post('search');
		
		if($search['value'] != '') {
			foreach($dt_datatable as $get_dt_datatable) {
				if(!empty($get_dt_datatable['db_column'])) {
					$db_or_where[$get_dt_datatable['db_column'] . ' LIKE']   = '%' . $search['value'] . '%';
				}
			}
		}
		// end: search data by like (datatable)
		
		$this->load->model('admin/receipts_model');
		$dataRecord = $this->receipts_model->get_receipt($db_where, $db_or_where, $db_where_in, $db_limit, $db_order, null);
		//die('Hello');
		$dataCount = $this->receipts_model->get_receipt($db_where, $db_or_where, $db_where_in, null, null, $db_select);
		$dataCount = $dataCount['count']['count'];
		
		$data = array();
		$i = 0;
		
		if($dataRecord) {
			
			foreach($dataRecord as $key => $value) {
				foreach($dt_datatable as $get_dt_datatable) {
					if($get_dt_datatable['dt_column'] == 'viewButton') {
						
						if($action_btn_array) {
							$button_html = '';
							foreach($action_btn_array as $action_btn) {
								$button_url = ($action_btn->url) ? base_url().''.$action_btn->url.'/'.$value['receiptDetailId'] : 'javascript:void(0)';
								$button_html .= '<a href="'.$button_url.'" class="btn btn-xs btn-primary text-center" title="" action-recipt-id="'.$value['receiptDetailId'].'"><i class="'.$action_btn->icon_class.'"></i></a>';
							}
							$data[$i][] .= $button_html;
						}
					
						//$data[$i][] = '<a href="'.base_url().'admin/scaning/scaning_detail/'.$value['fileId'].'" class="btn btn-xs btn-primary text-center" title=""><i class="fa fa-eye"></i></a>'; //Link to view the property detail <i class="fa fa-eye"></i>
						
					}
					/*else if($get_dt_datatable['dt_column'] == 'receiptNo') {
						$data[$i][] = '<a href="'.base_url().'admin/receipts/receipt_view/'.$value['receiptDetailId'].'" class="text-center" title="">'.$value['receiptNo'].'</a><input type="hidden" id="color_by_input" name="color_by_input" value="'.$value['categoryColor'].'" />'; 
					}*/
					/*else if($get_dt_datatable['dt_column'] == 'createdDate') {
						$data[$i][] = date('d-M-Y', strtotime($value[$get_dt_datatable['dt_column']]));
					}*/
					/*else if($get_dt_datatable['dt_column'] == 'subject') {
						if($value['receiptSentBackStatus']=="sent"){
							$data[$i][] = $value['subject'].'<a data-toggle="modal" data-target="#sentremarks_'.$value['receiptSentId'].'" style="cursor: pointer;"><img src="'.base_url().'includes/admin/images/senticon.png" width="15px" height="10px" style="margin-left:2px;" /></a><div id="sentremarks_'.$value['receiptSentId'].'" class="modal fade" role="dialog"> 
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
						else if($value['receiptSentBackStatus']=="sent back"){
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
						else{
							$data[$i][] = $value['subject'];
						}
					}*/
					/*else if($get_dt_datatable['dt_column'] == 'sentOn') {
						$data[$i][] = date('d-M-Y', strtotime($value[$get_dt_datatable['dt_column']]));
					}*/
					/*else if($get_dt_datatable['dt_column'] == 'dueDate') {
						$data[$i][] = date('d-M-Y', strtotime($value[$get_dt_datatable['dt_column']]));
					}*/
					else if($get_dt_datatable['dt_column'] == 'radioBox') {
						$data[$i][] = '<div class="checkbox-table"><label><input type="radio" name="receipt_id" class="grey checked_box" value="'.$value['receiptDetailId'].'"></label></div>';
					}
					/*else if($get_dt_datatable['dt_column'] == 'sendPriorityColor') {
						$data[$i][] = '<span class="p_square" style="background: '.$value['sendPriorityColor'].'" is-read="'.$value['isRead'].'"></span>';
					}*/
					/*else if($get_dt_datatable['dt_column'] == 'full_name') {
						if($value['designationId']!=""){
							$data[$i][] = $value['full_name']." (".$value['designationName'].")" ;
						}
						else{
							$data[$i][] = $value['full_name'];
						}
					}*/
					else{
						$data[$i][] = $value[$get_dt_datatable['dt_column']];
					}
					
				}
				//$data[i][] = $value['categoryColor'];
				$i++;
				//echo '<hr />';echo '<hr />';
			}
		}
		//die();
		//echo '<hr />';
		//echo '<pre>'; print_r($data); die();
		
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
    | start: note_sheet_send function
    |------------------------------------------------
    |
    | send note sheet
    |
	*/
	function note_sheet_send($file_id = null, $note_sheet_id = null) {
	
		if($this->input->post()) {
			//echo '<pre>'; print_r($this->input->post()); die();
		}
		
		$this->data['attach_receipt_id'] = $attach_receipt_id = $this->input->post('attach_receipt_id');
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to send note sheet.</p>');
			if($this->flexi_auth->is_admin())
				redirect('auth_admin');
			else
				redirect('auth_public');
		}
		
		//echo "<pre>";print_r($this->input->post());exit;
		
		// unshift crumb
		$this->breadcrumbs->unshift('Note Sheet Send', base_url() . 'admin/files/note_sheet_send/'.$file_id.'/'.$note_sheet_id);
		//$this->breadcrumbs->unshift('Green note', base_url() . 'admin/files/note_sheet/'.$file_id.'/green/'.$note_sheet_id);
		$this->breadcrumbs->unshift('Correspondence', base_url() . 'admin/files/note_sheet/'.$file_id);
		$this->breadcrumbs->unshift('Files', '#');
		//echo "<pre>"; print_r($this->input->post('view_type')); exit;
		if($file_id == null){
			$file_id = $this->input->post('file_id');
			$send_type = $this->input->post('view_type');
			//$note_sheet_id = $this->input->post('note_sheet_id');
		}
		
		//echo '<pre>'; print_r($this->input->post()); die();
		//echo '<pre>'; print_r($this->input->post()); die();
		//if($this->input->post('login_password')) {
		if($this->input->post('sign_and_send')) {
			//echo '<pre>'; print_r($this->input->post()); die();
			
			//$post_data = $this->input->post();
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('sent_to', 'Sent To', 'required');
			//$this->form_validation->set_rules('login_password', 'Password', 'required');
			
			if($this->form_validation->run()) {
			
				//echo $this->files_model->verify_user(); die();
			
				//if($this->files_model->verify_user()) {
				//if($this->receipts_model->sent_receipt()) {
					//$this->load->model('admin/receipts_model');
					$this->files_model->sent_file();
					$this->files_model->update_file_pob($file_id, $attach_receipt_id);
				
					$this->session->set_flashdata('message', '<p class="status_msg">Note Sheet Sent Successfully</p>');
					//die();
					//echo '<pre>'; print_r($this->input->post()); die();
					
					redirect('admin/files/sent/'/*.$file_id.'/'.$note_sheet_id*/);
				//}
				//}
				
				//$this->data['message'] = 'Your password not verfied';
				
				
				
				
				//echo '<pre>'; print_r($receipt_file); die();
			}
			else {
				//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				//redirect('admin/general/section');
				//exit;
			}
			
		}
		
		/*if($receipt_id == null){
			$receipt_id = $this->input->post('receipt_id');
		}*/
		
		
		$db_where_in = array('fd.fileId' => $file_id);			
		$this->data['file'] = $this->files_model->get_file(array(), array(), $db_where_in);
		//echo '<pre>'; print_r($this->data['file']); die();
		
		//$db_where_in = array('ns.fileId' => $file_id);	
		//$this->data['file'][$file_id]['note_sheet'] = $this->files_model->get_note_sheet(array(), $db_where_in);
		//echo '<pre>'; print_r($this->data['file']); die();
		$db_where_in = array('rs.fkDetailId' => $file_id);
		$this->data['sent_receipt'] = $this->files_model->get_sent_file_id(null,$db_where_in);
		//echo '<pre>'; print_r($this->data['sent_receipt']); die();
		
		//$db_where = array('ns.noteSheetId' => $note_sheet_id);
		
		//$this->data['file'][$file_id]['note_sheet'] = $this->files_model->get_note_sheet($db_where);
		
		//echo '<pre>'; print_r($this->data['file']); die();
		
		$this->data['page_title'] = 'Note Sheet Send';
		
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
		//$sql_where[$this->flexi_auth->db_column('user_acc', 'id').'!='] = $this->flexi_auth->get_user_id();
		$sql_where[$this->flexi_auth->db_column('user_acc', 'user_job_group').'!='] = $this->session->userdata('user_job_group');
		//$sql_where[$this->flexi_auth->db_column('user_acc', 'designation').'!='] = null;
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
		
		$this->data['send_action'] = $this->general_model->get_send_action();
		$this->data['send_priority'] = $this->general_model->get_send_priority();
		$this->data['send_type'] = $send_type;
		//echo '<pre>'; print_r($this->data); die();
	
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/file/note_sheet_send', $this->data);
	
	}
	/*---- end: note_sheet_send function ----*/
	
	
	/*
	|------------------------------------------------
	| start: note_sheet_detail function
	|------------------------------------------------
	|
	| This function show note sheet details
	|
	*/
	function note_sheet_detail($send_inbox_button = null,$file_id = null, $note_sheet_id = null) {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to view file.</p>');
			if($this->flexi_auth->is_admin())
				redirect('auth_admin');
			else
				redirect('auth_public');
		}
		// unshift crumb
		$this->breadcrumbs->unshift('Note Sheet detail', base_url() . 'admin/files/note_sheet_detail/'.$file_id.'/'.$note_sheet_id);
		$this->breadcrumbs->unshift('Files', '#');
		
		$this->data['page_title'] = 'Note Sheet Detail';
		
		if ($this->uri->segment(4) == 'sent'){
			$file_id = $this->uri->segment(5);
		}
		else if ($this->uri->segment(4) == 'inbox'){
			$file_id = $this->uri->segment(5);	
		}
		else{
			$file_id = $this->uri->segment(4);	
		}
		
		$movementInfo = $this->files_model->get_file_movements($file_id);
		
		$this->data['movementInfo'] = $movementInfo;
		$this->data['file_id'] = $file_id;
		$this->data['note_sheet_id'] = $note_sheet_id;
		
		//print_r($movementInfo); exit;
		
		$db_where = array('fd.fileId' => $file_id);
		//echo "<pre>";print_r($file_id);exit;
		$this->data['file'] = $this->files_model->get_file($db_where);
		// if ($this->data['file']){
		// 	echo "<pre>";print_r('get_file mein araha hai');exit;
		// }
		//echo "<pre>";print_r($file_id);exit;

	
		$this->data['file'] = $file = $this->data['file'][$file_id];
		
		//$this->data['fileType'] = $this->data['file']['fileTypeId'];
		//$file_id = $this->data['file']['fileTypeId']; 
		$this->data['file_type_name'] = $this->files_model->get_file_type_name($file_id);
		$this->data['category_name'] = $this->files_model->get_category_name($file_id);
		//echo "<pre>";print_r($this->data['file_type_name']);exit;

		$this->load->model('admin/receipts_model');
		$db_where = array('rd.fileNumber' => $this->data['file']['fileNumber']);
		//echo "<pre>";print_r($db_where);exit;
		// $this->data['receipt_detail'] = $this->receipts_model->get_receipt($db_where);
		// if ($this->data['receipt_detail']){
		// 	echo "<pre>";print_r('get_receipt mein araha hai');exit;
		// }
		//echo "<pre>";print_r($db_where);exit;
		$db_where = array('ns.fileId' => $file_id);	
		$this->data['send_inbox_button'] = $send_inbox_button;
		if($send_inbox_button ==  "inbox"){
			//echo "<pre>";print_r($file_id);exit;
			$this->files_model->isRead($file_id);
		}
		//echo "<pre>";print_r($db_where);exit;
		$this->data['note_sheet'] = $this->files_model->get_note_sheet($db_where);
		
		
		
		if($file['employeeCNIC']) {
			$this->load->model('admin/scaning_model');
			$this->data['releted_files'] = $this->scaning_model->get_scaning(array('fd.employeeCNIC' => $file['employeeCNIC']));
			//echo "<pre>";print_r($this->data['releted_files']);exit;
			// if ($this->data['releted_files']){
			// 	echo "<pre>"; print_r('get_scaning mein araha hai');exit;		
			// }
		}

		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/file/note_sheet_detail', $this->data);
	}
	/*---- end: note_sheet_detail function ----*/
	
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
		$this->breadcrumbs->unshift('Created', base_url() . 'admin/files/created/');
		$this->breadcrumbs->unshift('Files', '#');
		
		$this->data['action_btn'] = json_encode(array());
		
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
												'th_table' => 'File No.',
												'dt_column' => 'fileNumber',
												'db_column' => 'fileNumber',
												'db_order_column' => 'fileNumber',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),
											array(
												'th_table' => 'Department File Number',
												'dt_column' => 'oldFileNumber',
												'db_column' => 'oldFileNumber',
												'db_order_column' => 'oldFileNumber',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),
											array(
												'th_table' => 'Empolyee Name',
												'dt_column' => 'employeeName',
												'db_column' => 'employeeName',
												'db_order_column' => 'employeeName',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),
											
											array(
												'th_table' => 'Subject',
												'dt_column' => 'description',
												'db_column' => 'description',
												'db_order_column' => 'description',
												'td_orderable' => 'true',
												'td_width' => '25%'
											),
											array(
												'th_table' => 'Category',
												'dt_column' => 'generalCategoryName',
												'db_column' => 'generalCategoryName',
												'db_order_column' => 'generalCategoryName',
												'td_orderable' => 'true',
												'td_width' => '10%'
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
												'order'			=> array('column' => '2', 'value' => 'desc'),
												'pageLength'	=> '100'
											);
		
		$db_where = array('sp.sendPriorityShow' => '1');
		$this->data['send_priority'] = $this->general_model->get_send_priority($db_where);
		
		$db_where = array('categoryShow' => '1');
		$this->data['category'] = $this->general_model->get_category($db_where);
		
		$this->data['page_title'] = 'Created';
		$this->data['view_type'] = 'created';
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/file/file_listing', $this->data);
		
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
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to sent listing.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
		}
	
		// unshift crumb
		$this->breadcrumbs->unshift('Sent', base_url() . 'admin/files/sent/');
		$this->breadcrumbs->unshift('Files', '#');
		
		//echo "<pre>";print_r($this->input->post());exit;

		//$this->data['action_btn'] = json_encode(array('view', 'edit'));
		$this->data['action_btn'] = json_encode(
												array(
													array(
														'label' => 'view',
														'url' => '',
														'icon_class' => 'fa clip-undo roll-back-receipt'
													)
												)
											);
		
		$this->data['dt_datatable'] = array(
											// array(
											// 	'th_table' => '',
											// 	'dt_column' => 'checkBox',
											// 	'db_column' => '',
											// 	'db_order_column' => '',
											// 	'td_orderable' => 'false',
											// 	'td_width' => '5%'
											// ),
											array(
												'th_table' => 'Priority',
												'dt_column' => 'sendPriorityColor',
												'db_column' => 'sendPriorityColor',
												'db_order_column' => 'sendPriorityColor',
												'td_orderable' => 'false',
												'td_width' => '5%'
											),
											array(
												'th_table' => 'File No.',
												'dt_column' => 'fileNumber',
												'db_column' => 'fileNumber',
												'db_order_column' => 'fileNumber',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),
											array(
												'th_table' => 'Department File Number',
												'dt_column' => 'oldFileNumber',
												'db_column' => 'oldFileNumber',
												'db_order_column' => 'oldFileNumber',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),
											array(
												'th_table' => 'Empolyee Name',
												'dt_column' => 'employeeName',
												'db_column' => 'employeeName',
												'db_order_column' => 'employeeName',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),
											
											array(
												'th_table' => 'Subject',
												'dt_column' => 'description',
												'db_column' => 'description',
												'db_order_column' => 'description',
												'td_orderable' => 'true',
												'td_width' => '15%'
											),
											array(
												'th_table' => 'Category',
												'dt_column' => 'generalCategoryName',
												'db_column' => 'generalCategoryName',
												//'db_order_column' => 'get_general_category_name',
												'db_order_column' => 'generalCategoryName',
												'td_orderable' => 'true',
												'td_width' => '11%'
											),
											/*array(
												'th_table' => 'Sender',
												'dt_column' => 'contactName',
												'db_column' => 'contactName',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),*/
											array(
												'th_table' => 'Sent to',
												'dt_column' => 'full_name',
												'db_column' => 'CONCAT(up.upro_first_name, up.upro_last_name)',
												'db_order_column' => 'full_name',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),
											array(
												'th_table' => 'Sent On',
												'dt_column' => 'sentOn',
												'db_column' => 'fs.createdDate',
												'db_order_column' => 'fs.createdDate',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),
											array(
												'th_table' => 'Forwarded On',
												'dt_column' => 'forward_date',
												'db_column' => 'fs.forward_date',
												'db_order_column' => 'fs.forward_date',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),
											array(
												'th_table' => 'Due On',
												'dt_column' => 'dueDate',
												'db_column' => 'fs.dueDate',
												'db_order_column' => 'fs.dueDate',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),
											array(
												'th_table' => 'Hard Copy Receive',
												'dt_column' => 'isHardCopyReceived',
												'db_column' => '',
												'db_order_column' => '',
												'td_orderable' => 'false',
												'td_width' => '10%'
											),
											
											/*array(
												'th_table' => 'Action',
												'dt_column' => 'viewButton',
												'db_column' => '',
												'td_orderable' => 'false',
												'td_width' => '10%'
											)	*/											
										);
										
		$this->data['datatable_setting'] = array(
												'processing' 	=> 'true',
												'searching' 	=> 'true',
												'autoWidth' 	=> 'false',
												'lengthChange'	=> 'false',
												'order'			=> array('column' => '2', 'value' => 'desc'),
												'pageLength'	=> '100'
											);
		
		$db_where = array('sp.sendPriorityShow' => '1');
		$this->data['send_priority'] = $this->general_model->get_send_priority($db_where);
		
		$db_where = array('categoryShow' => '1');
		$this->data['category'] = $this->general_model->get_category($db_where);
		
		$this->data['view_type'] = 'sent';
		$this->data['page_title'] = 'Sent';
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/file/file_listing', $this->data);
		
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
		$this->breadcrumbs->unshift('Files', '#');
		
		
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
												'th_table' => 'File No.',
												'dt_column' => 'fileNumber',
												'db_column' => 'fileNumber',
												'db_order_column' => 'fileNumber',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),
											array(
												'th_table' => 'Department File No.',
												'dt_column' => 'oldFileNumber',
												'db_column' => 'oldFileNumber',
												'db_order_column' => 'oldFileNumber',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),
											array(
												'th_table' => 'Employee Name',
												'dt_column' => 'employeeName',
												'db_column' => 'employeeName',
												'db_order_column' => 'employeeName',
												'td_orderable' => 'true',
												'td_width' => '15%'
											),
											array(
												'th_table' => 'Subject',
												'dt_column' => 'description',
												'db_column' => 'description',
												'db_order_column' => 'description',
												'td_orderable' => 'true',
												'td_width' => '25%'
											),
											array(
												'th_table' => 'File Type',
												'dt_column' => 'fileType',
												'db_column' => 'fileType',
												'db_order_column' => 'fileType',
												'td_orderable' => 'true',
												'td_width' => '11%'
											),
											/*array(
												'th_table' => 'Sender',
												'dt_column' => 'contactName',
												'db_column' => 'contactName',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),*/
											array(
												'th_table' => 'Sent by',
												'dt_column' => 'full_name',
												'db_column' => 'CONCAT(up.upro_first_name, up.upro_last_name)',
												'db_order_column' => 'full_name',
												'td_orderable' => 'true',
												'td_width' => '15%'
											),
											array(
												'th_table' => 'Sent On',
												'dt_column' => 'sentOn',
												'db_column' => 'fs.createdDate',
												'db_order_column' => 'fs.createdDate',
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
												'dt_column' => 'hardCopyReceived',
												'db_column' => 'isHardCopyReceived',
												'db_order_column' => 'isHardCopyReceived',
												'td_orderable' => 'false',
												'td_width' => '10%'
											),	
										);
										
		$this->data['datatable_setting'] = array(
												'processing' 	=> 'true',
												'searching' 	=> 'true',
												'autoWidth' 	=> 'false',
												'lengthChange'	=> 'false',
												'order'			=> array('column' => '6', 'value' => 'desc'),
												'pageLength'	=> '100'
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
		$this->load->view('admin/file/file_listing', $this->data);
		
	}
	/*---- end: inbox function ----*/
	
	/*
	|------------------------------------------------
	| start: file tracking function
	|------------------------------------------------
	|
	| This function show file tracking listing
	|
	*/
	function file_tracking() {
		
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
		$this->breadcrumbs->unshift('File Tracking', base_url() . 'admin/receipt/file_tracking/');
		$this->breadcrumbs->unshift('Files', '#');
		
		
		//$this->data['action_btn'] = json_encode(array('view', 'edit'));
		//echo "<pre>";print_r($this->input->post());exit;
		
		$this->data['dt_datatable'] = array(
											array(
												'th_table' => 'File No.',
												'dt_column' => 'fileNumber',
												'db_column' => 'fileNumber',
												'db_order_column' => 'fileNumber',
												'td_orderable' => 'true',
												'td_width' => '8%'
											),
											array(
												'th_table' => 'Department File No.',
												'dt_column' => 'oldFileNumber',
												'db_column' => 'oldFileNumber',
												'db_order_column' => 'oldFileNumber',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),
											array(
												'th_table' => 'File Type',
												'dt_column' => 'fileType',
												'db_column' => 'fileType',
												'db_order_column' => 'fileType',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),
											array(
												'th_table' => 'Employee Name',
												'dt_column' => 'employeeName',
												'db_column' => 'employeeName',
												'db_order_column' => 'employeeName',
												'td_orderable' => 'true',
												'td_width' => '15%'
											),
											array(
												'th_table' => 'Subject',
												'dt_column' => 'description',
												'db_column' => 'description',
												'db_order_column' => 'description',
												'td_orderable' => 'true',
												'td_width' => '20%'
											),
											array(
												'th_table' => 'Category',
												'dt_column' => 'generalCategoryName',
												'db_column' => 'generalCategoryName',
												'db_order_column' => 'generalCategoryName',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),
											/*array(
												'th_table' => 'Sender',
												'dt_column' => 'contactName',
												'db_column' => 'contactName',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),*/
											array(
												'th_table' => 'File Existence',
												'dt_column' => 'full_name',
												'db_column' => 'full_name',
												'db_order_column' => 'full_name',
												'td_orderable' => 'true',
												'td_width' => '12%'
											),
											array(
												'th_table' => 'Last Receiving Date',
												'dt_column' => 'sentOn',
												//'db_column' => 'fs.createdDate',
												//'db_order_column' => 'fs.createdDate',
												'db_column' => 'createdDate',
												'db_order_column' => 'createdDate',
												'td_orderable' => 'true',
												'td_width' => '12%'
											),
											array(
												'th_table' => 'Forward Date',
												'dt_column' => 'forward_date',
												'db_column' => 'fs.forward_date',
												'db_order_column' => 'fs.forward_date',
												'td_orderable' => 'true',
												'td_width' => '12%'
											),
											
											
										);
										
		$this->data['datatable_setting'] = array(
												'processing' 	=> 'true',
												'searching' 	=> 'false',
												'autoWidth' 	=> 'false',
												'lengthChange'	=> 'false',
												'order'			=> array('column' => '4', 'value' => 'desc'),
												'pageLength'	=> '20'
											);
		
		$db_where = array('sp.sendPriorityShow' => '1');
		$this->data['send_priority'] = $this->general_model->get_send_priority($db_where);
		
		$db_where = array('categoryShow' => '1');
		$this->data['category'] = $this->general_model->get_category($db_where);
		$this->data['file_types'] = $this->general_model->get_file_types();
		$this->data['default_listing'] = false;

		//echo "<pre>";print_r($this->input->post());exit;
		if($this->input->post()){
			
			$post = $this->input->post();
			if($post['fd_file_number']!='' || $post['fd_dept_file_number']!='' ||  $post['fd_file_type_id']!='' || $post['fd_description'] || $post['fd_employee_name']){
				$this->data['fileno']=$this->input->post('fd_file_number');
				$this->data['dept_fileno']=$this->input->post('fd_dept_file_number');

				$this->data['filetypeids']=$this->input->post('fd_file_type_id');
				$this->data['description']=$this->input->post('fd_description');
				$this->data['employee_name']=$this->input->post('fd_employee_name');
				//echo "<pre>";print_r($this->data['employee_name']);exit;	
				
				
			}
			else{
				$this->data['error_message'] = 'file';
			}
		}
		
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
		//$sql_where[$this->flexi_auth->db_column('user_acc', 'id').'!='] = $this->flexi_auth->get_user_id();
		//$sql_where[$this->flexi_auth->db_column('user_acc', 'user_job_group').'!='] = $this->session->userdata('user_job_group');
		//$sql_where[$this->flexi_auth->db_column('user_acc', 'designation').'!='] = null;
		$sql_where[$this->flexi_auth->db_column('user_acc', 'designation').'!='] = 0;
				
		$this->flexi_auth->sql_where($sql_where);

		$users = $this->flexi_auth->get_users_array();
		//$this->data['send_user'] = $this->flexi_auth->get_users_array();
		
		
		foreach($users as $key => $get_user) {
			$send_user[$get_user['designationName']][$key] = $get_user;
		}

		$this->data['send_user'] = $send_user;
		//echo '<pre>'; print_r($send_user); die();
		
		$this->data['view_type'] = 'file_tracking';
		$this->data['page_title'] = 'File Tracking';
		//$this->data['current_user'] = $this->flexi_auth->get_user_id();
		//$this->data['current_user'] = $this->flexi_auth->get_user_id();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/file/file_tracking', $this->data);
		
	}
	/*---- end: file tracking function ----*/
	
	/*
	|------------------------------------------------
	| start: get_listing function
	|------------------------------------------------
	|
	| This function get files listing
	|
	*/
	function get_listing() {
	
		//echo '<pre>'; print_r($this->input->post('action_btn')); die();
		
		/*if($this->input->post('roll_back_recipt_id')) {
			//$this->receipt_roll_back($this->input->post('roll_back_recipt_id'));
			$this->receipts_model->receipt_roll_back($this->input->post('roll_back_recipt_id'));
		}*/
		
		//$dt_datatable = json_decode($this->input->post('dt_datatable'));
		$dt_datatable = $this->input->post('dt_datatable');
		$action_btn_array = json_decode($this->input->post('action_btn'));					
		$view_type = $this->input->post('view_type');
		//echo "<pre>";print_r($view_type);exit;
		//die();
		//echo '<pre>'; print_r($dt_datatable); die();
		
		if($view_type == 'created' || $view_type == 'file_tracking' || $view_type == 'inbox' || $view_type == 'sent')
			$count_column_name = 'fileId';
		else
			$count_column_name = 'sentId';
		
		$db_where		= array();
		$db_or_where	= array();
		$db_where_in	= array();
		$db_limit       = array();
		$db_order       = array();
		$db_select		= "COUNT(fd.fileId) as count, 'count' AS ".$count_column_name;
		//echo "<pre>";print_r($db_select);exit;
		
		
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
			//echo "<pre>";print_r($this->input->post('top_search'));exit;
			foreach($this->input->post('top_search') as $key => $search_val) {
				//echo "<pre>";print_r($search_val);exit;
				if(preg_match('/fd/', $key)) {

					$search_key = substr($key, 3);
					
					$search_key = explode('_', $search_key);
					$new_search_key = '';
					///echo "<pre>";print_r($new_search_key);exit;

					foreach($search_key as $get_search_key) {
						$new_search_key = $new_search_key.ucfirst($get_search_key);
					}
					
					//echo "<pre>";print_r($search_val);exit;
					if($search_val!="")
						$db_where['fd.'.$new_search_key]  = $search_val;

				}
				
				if(preg_match('/fs/', $key)) {

					$search_key = substr($key, 3);
					
					$search_key = explode('_', $search_key);
					$new_search_key = '';
					foreach($search_key as $get_search_key) {
						$new_search_key = $new_search_key.ucfirst($get_search_key);
					}
					
					if($search_val!="")
						$db_where['fs.'.$new_search_key]  = $search_val;

				}
			}
		}
		// end: top search data by equal to
		
		//echo '<pre>'; print_r($db_where); die();
		
		
		/***** start: top search data by like *****/
		if($this->input->post('top_search_like')) {
			foreach($this->input->post('top_search_like') as $key => $search_val) {
				if(preg_match('/fd/', $key)) {

					$search_key = substr($key, 3);
					
					$search_key = explode('_', $search_key);
					$new_search_key = '';
					foreach($search_key as $get_search_key) {
						$new_search_key = $new_search_key.ucfirst($get_search_key);
					}
					
					if($search_val!="")
						$db_or_where['fd.'.$new_search_key . ' LIKE']  = '%' . $search_val . '%';

				}
				
				if(preg_match('/fs/', $key)) {

					$search_key = substr($key, 3);
					
					$search_key = explode('_', $search_key);
					$new_search_key = '';
					foreach($search_key as $get_search_key) {
						$new_search_key = $new_search_key.ucfirst($get_search_key);
					}
					if($search_val!="" && $new_search_key!='CreatedDate'){
						$db_or_where['fs.'.$new_search_key . ' LIKE']  = '%' . $search_val . '%';
					}
					else if($search_val!="" && $new_search_key == 'CreatedDate'){
						$db_or_where['fs.'.$new_search_key . ' LIKE'] =  '%' . date('Y-m-d', strtotime($search_val)) . '%';
					}

				}
			}
		}
		// end: top search data by like
		
		
		
		/***** start: search data by like (datatable) *****/
		$search = $this->input->post('search');
		
		if($search['value'] != '') {
			foreach($dt_datatable as $get_dt_datatable) {
				if(!empty($get_dt_datatable['db_column'])) {
					$db_or_where[$get_dt_datatable['db_column'] . ' LIKE']   = '%' . $search['value'] . '%';
				}
			}
		}
		// end: search data by like (datatable)
		
		
		$dataRecord = $this->files_model->get_file($db_where, $db_or_where, $db_where_in, $db_limit, $db_order, null, $view_type);

		//echo '<pre>'; print_r($dataRecord); die();
		
		$dataCount = $this->files_model->get_file($db_where, $db_or_where, $db_where_in, null, null, $db_select, $view_type);
		$dataCount = $dataCount['count']['count'];
					
		
		$data = array();
		$i = 0;
		//echo "<pre>"; print_r($dataRecord);exit;
		if($dataRecord) {
			
			foreach($dataRecord as $key => $value) {	
				foreach($dt_datatable as $get_dt_datatable) {
					
					//echo "<pre>";print_r($get_dt_datatable);exit;
					if($get_dt_datatable['dt_column'] == 'viewButton') {
						
						if($action_btn_array) {
							$button_html = '';
							foreach($action_btn_array as $action_btn) {
								$button_url = ($action_btn->url) ? base_url().''.$action_btn->url.'/'.$value['fileId'] : 'javascript:void(0)';
								$button_html .= '<a href="'.$button_url.'" class="btn btn-xs btn-primary text-center" title="" action-recipt-id="'.$value['fileId'].'"><i class="'.$action_btn->icon_class.'"></i></a>';
							}
							$data[$i][] .= $button_html;
						}
					
						//$data[$i][] = '<a href="'.base_url().'admin/scaning/scaning_detail/'.$value['fileId'].'" class="btn btn-xs btn-primary text-center" title=""><i class="fa fa-eye"></i></a>'; //Link to view the property detail <i class="fa fa-eye"></i>
						
					}
					else if($get_dt_datatable['dt_column'] == 'hardCopyReceived') {
							//$receipt_detail_id_hard = $value['receiptDetailId'];
							if($value['isHardCopyReceived']!=1){
							$data[$i][]='<button type="button" id="hard_copy_'.$value['fileId'].'" onclick=hard_copy_received("'.$value['fileId'].'") class="receipt_submit btn btn-primary top_btn"> Hard Copy Receive </button>';
							}
							else{
							$data[$i][]='<button type="button" id="hard_copy_'.$value['fileId'].'" onclick=hard_copy_received("'.$value['fileId'].'") class="receipt_submit btn btn-success top_btn" disabled> Hard Copy Received </button>';
							}
						}
					else if ($get_dt_datatable['dt_column'] == 'isHardCopyReceived'){
							if($value['isHardCopyReceived']!=1){
								$data[$i][] = "<span class='label label-danger' style='opacity: 1;'> Not Received </span>";
							}else{
								$data[$i][] = "<span class='label label-success' style='opacity: 1;'> Received </span>";
							}
						}
					else if($get_dt_datatable['dt_column'] == 'fileNumber') {
						if($view_type == 'file_tracking'){
							$data[$i][] = '<a href="'.base_url().'admin/files/note_sheet_detail/'.$value['fileId'].'/'.$value['noteSheetId'].'" class="text-center" title="">'.$value[$get_dt_datatable['dt_column']].'</a><input type="hidden" id="color_by_input" name="color_by_input" value="'.$value['categoryColor'].'" />';						
						}
						else{
							if($value['fileStatus']=='created'){
								$data[$i][] = '<a href="'.base_url().'admin/files/note_sheet/'.$value['fileId'].'/" class="text-center" title="">'.$value[$get_dt_datatable['dt_column']].'</a><input type="hidden" id="color_by_input" name="color_by_input" value="'.$value['categoryColor'].'" />'; 
							}
							else{
								if($view_type == 'inbox'){
								$data[$i][] = '<a href="'.base_url().'admin/files/note_sheet_detail/inbox/'.$value['fileId'].'/'.$value['noteSheetId'].'" class="text-center" title="">'.$value[$get_dt_datatable['dt_column']].'</a><input type="hidden" id="color_by_input" name="color_by_input" value="'.$value['categoryColor'].'" />';}
								else if($view_type == 'sent'){
								$data[$i][] = '<a href="'.base_url().'admin/files/note_sheet_detail/sent/'.$value['fileId'].'/'.$value['noteSheetId'].'" class="text-center" title="">'.$value[$get_dt_datatable['dt_column']].'</a><input type="hidden" id="color_by_input" name="color_by_input" value="'.$value['categoryColor'].'" />';
								}
							}
						}
					}
					else if(($get_dt_datatable['dt_column'] == 'createdDate' || $get_dt_datatable['dt_column'] == 'forward_date') && $value[$get_dt_datatable['dt_column']] != NULL) {

                        $data[$i][] = date('d M Y', strtotime($value[$get_dt_datatable['dt_column']]));
                    }
                    else if(($get_dt_datatable['dt_column'] == 'createdDate' || $get_dt_datatable['dt_column'] == 'forward_date') && $value[$get_dt_datatable['dt_column']] == NULL) {
                        $data[$i][] = "<center>----</center>";
                    }
					else if($get_dt_datatable['dt_column'] == 'description') {
						if($value['sentBackStatus']=="sent"){
							$data[$i][] = $value[$get_dt_datatable['dt_column']];
						}
						else if($value['sentBackStatus']=="sent back"){
							$data[$i][] = $value[$get_dt_datatable['dt_column']].'<a data-toggle="modal" data-target="#remarks_'.$value['sentId'].'"><img src="'.base_url().'includes/admin/images/sentback.png" width="17px" height="17px" style="margin-left:2px; cursor: pointer;" /></a><div id="remarks_'.$value['sentId'].'" class="modal fade" role="dialog"> 
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
						else{
							$data[$i][] = $value[$get_dt_datatable['dt_column']];
						}
					}
					else if($get_dt_datatable['dt_column'] == 'sentOn') {
						if($value[$get_dt_datatable['dt_column']] != ''){
							$data[$i][] = date('d-m-Y', strtotime($value[$get_dt_datatable['dt_column']]));
						}else{
							$data[$i][] = '';
						}
					}
					else if($get_dt_datatable['dt_column'] == 'dueDate') {
						$data[$i][] = date('d-m-Y', strtotime($value[$get_dt_datatable['dt_column']]));
					}
					else if($get_dt_datatable['dt_column'] == 'checkBox') {
						$data[$i][] = '<div class="checkbox-table"><label><input type="checkbox" name="file_id[]" class="grey checked_box" value="'.$value['fileId'].'"></label></div><span style="display:none;">'.$value['categoryColor'].'</span>'.'<input type="hidden" name="file_note_sheet_value" value="'.base_url().'admin/files/note_sheet_send/'.$value['fileId'].'/'.$value['noteSheetId'].'">';
						//$data[$i][] = '';
					}
					else if($get_dt_datatable['dt_column'] == 'sendPriorityColor') {
						$data[$i][] = '<span class="p_square" style="background: '.$value['sendPriorityColor'].'" is-read="'.$value['isRead'].'"></span>';
					}
					else if($get_dt_datatable['dt_column'] == 'full_name') {
						if($value['designationId']!=""){
							$data[$i][] = $value['full_name']." (".$value['designationName'].")" ;
						}
						else{
							$data[$i][] = $value['full_name'];
						}
					}
					else{
						//echo "<pre>";print_r('else mein bhi araha hai');exit;
						$data[$i][] = $value[$get_dt_datatable['dt_column']];
						//$data[$i][] = '';
					}	
				}
				//$data[$i][] = $value['categoryColor'];
				$i++;
				
			}
		}
		
		//echo '<pre>'; print_r($data); die();
		
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
	| start: delete_note_sheet_file function
	|------------------------------------------------
	|
	| This function delete note sheet file
	|
	*/
	function delete_note_sheet_file($file_id = null, $note_type = null, $note_sheet_id = null, $note_sheet_file_id = null) {
	
		$this->files_model->delete_note_sheet_file($note_sheet_file_id);
		redirect('admin/files/note_sheet/'.$file_id.'/'.$note_type.'/'.$note_sheet_id);
	
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
	function note_sheet_discard($file_id = null, $note_sheet_id = null, $note_sheet_fun = null) {
		
		$this->files_model->note_sheet_discard($note_sheet_id);
		redirect('admin/files/'.$note_sheet_fun.'/'.$file_id);
		
	}
	/*---- end: note_sheet_discard function ----*/
	function hard_copy_received(){
			if($this->input->post()){
				//echo "<pre>"; print_r($this->input->post()); exit;
				$this->files_model->hard_copy_received();
			}
			echo json_encode('yes');
		}
}