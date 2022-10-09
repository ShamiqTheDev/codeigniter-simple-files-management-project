<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Scaning extends CI_Controller {
    
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
		
		//$this->data["user_section"] = isset($user_data->sectionName) ? "(".$user_data->sectionName.")" : "";

        // load scaning model
        $this->load->model('admin/scaning_model');
        
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
		
		//print_r($this->data['uri_3']);exit;


        $this->uri_privileged = $this->menu_model->get_privilege_name($check_slash);
        $this->data['menu_title'] = $this->uri_privileged;

// Get Dynamic Menus
        $this->data['get_menu'] = $this->menu_model->get_menu();
		//echo "<pre>";print_r($this->data["get_menu"]);exit;
		
		// load general model
		// NOTE: Don't remove following code
		//section menu work
        $this->load->model('admin/general_model');
		
		$this->data['menu_sections'] = $this->general_model->get_menu_section_data();
		$this->data['receipt_inbox'] = $this->general_model->get_receipt_inbox_count($this->data['sub_menu']);
		$this->data['file_inbox'] = $this->general_model->get_file_inbox_count($this->data['sub_menu']);
		// following code only for scan_listing page
		if($this->data["uri_3"] == "scan_listing"){
			$this->data['section_selected'] = $this->uri->segment(4);
			$this->data['fileType_selected'] = str_replace("_"," ",$this->uri->segment(5));
		}
        //section menu work ends
		
    }
	
	
    function add() {
			
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to add scan.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
		
		$random_submit_num =  rand(1000,100000);
		
		if ($this->input->post() && ($this->session->userdata('random_submit_num') == $this->input->post('random_submit_num'))) {
		
			//echo $this->session->userdata('random_submit_num');
		
			//echo '<pre>'; print_r($this->input->post()); die();
		
			$post = $this->input->post();
			/*if(!isset($post['file_uploaded_id'])) {
				$this->session->set_flashdata('message', '<p class="status_msg">Please upload file.</p>');
				redirect('/admin/scaning/add/');
			}*/
		
			//if(($post['data_exists'] == $this->session->userdata('data_exists') && ($post['file_type_id'] == 1 || $post['file_type_id'] == 2)) || $post['file_type_id'] == 3) {
				$this->load->library('form_validation');
				
				$this->form_validation->set_rules('file_type_id', 'File Type', 'required');
				if($this->input->post('file_type_id') == '1' || $this->input->post('file_type_id') == '2') {
					$this->form_validation->set_rules('employee_name', 'Employee Name', 'required');
					$this->form_validation->set_rules('employee_cnic', 'Employee CNIC', 'required');
				}
				else {
					$this->form_validation->set_rules('subject', 'Subject', 'required');
					$this->form_validation->set_rules('general_category_id', 'Category Name', 'required');
				}
				
				$this->form_validation->set_rules('section_id', 'Section', 'required');
				//$this->form_validation->set_rules('assigned_date', 'Assigned Date', 'required');
				//$this->form_validation->set_rules('file_uploaded_id', 'File Uploaded', 'required');

				$upload_dir = $this->config->item('fileUploadPath');
				
				//$upload_dir.'temp_files/'.$post['file_uploaded_name'][0];
				
				if ($this->form_validation->run() && (isset($post['file_uploaded_id']) && file_exists($upload_dir.'temp_files/'.$post['file_uploaded_name'][0]))) {
						
					//$directoryFile = $this->directory_file_upload();

					if ($this->scaning_model->scaning()) {
						$this->session->set_flashdata('message', '<p class="status_msg">File added successfully.</p>');
						//die('TEST');
						$this->session->set_userdata('random_submit_num', $random_submit_num);
						redirect('/admin/scaning/add/');
					}
					
					
				}else{
					$this->session->set_flashdata('message', '<p class="error_msg">Error: Please Upload File</p>');
					//echo validation_errors();
					//exit;
				}
				
				if($this->input->post('employee_cnic')){
					$db_where = array('fd.employeeCNIC' => $this->input->post('employee_cnic'));
					$this->data['related_file'] = $this->scaning_model->get_scaning($db_where);
				}
				//$this->data['related_file'] = $this->scaning_model->get_scaning(array('fd.employeeCNIC' => $this->input->post('employee_cnic')))
			//}
			
        }
		//else {
			//$this->session->set_userdata('random_submit_num', $random_submit_num);
		//}
		
		/*if(!$this->session->userdata('section')) {
			// Select user data to be displayed.
			$sql_select = array(
				$this->flexi_auth->db_column('user_acc', 'id'),
				'upro_first_name',
				'upro_last_name'
			);
			
			if($this->auth->database_config['custom_join']) {
				foreach($this->auth->database_config['custom_join'] as $get_custom_join) {
					foreach($get_custom_join['custom_columns'] as $get_custom_column) {
						$sql_select[] = $get_custom_join['table'].'.'.$get_custom_column;
					}
				}
			}
			
			$this->flexi_auth->sql_select($sql_select);
							
			// Get Only Active Users
			$sql_where[$this->flexi_auth->db_column('user_acc', 'active').'='] = 1;
			$sql_where[$this->flexi_auth->db_column('user_acc', 'group_id').'='] = 4;
					
			$this->flexi_auth->sql_where($sql_where);

			$this->data['file_received_from'] = $this->flexi_auth->get_users_array();
		}*/
		
		// get file type and general category
		$this->data['file_types'] = $this->general_model->get_file_types();
		$this->data['general_category'] = $this->general_model->get_general_category_name();
		
		//echo "<pre>"; print_r($this->data['general_category']); exit;
		
		//Total Files to be scanned
		$this->data['total_number_of_files_to_be_scan'] = $this->scaning_model->get_total_number_of_files_to_be_scan($this->session->userdata('section'));
		
		//echo "<pre>"; print_r($this->data); die();
		
		//Files has been scanned
		$this->data['total_files_scanned'] = $this->scaning_model->get_total_files_scanned($this->session->userdata('section'));
		//Today Scanning
		$this->data['today_scanning'] = $this->scaning_model->get_today_scanning($this->session->userdata('section'));
		//echo "<pre>"; print_r($this->data['today_scanning']); exit;
		
		// unshift crumb
		$this->breadcrumbs->unshift('Bulk Scan', base_url().'Scaning');
		
        $this->data['page_title'] = 'Bulk Scan';
        $this->data['random_submit_num'] = $random_submit_num;
		
		$this->session->set_userdata('random_submit_num', $random_submit_num);
		
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/scaning/scaning', $this->data);
		
    }
	
	
	function edit($file_id) {
	
		//echo '<pre>'; print_r($_SERVER); die();
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to update scan.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
		
		
		if ($this->input->post()) {
		
			$this->load->library('form_validation');
			
            $this->form_validation->set_rules('file_type_id', 'File Type', 'required');
			if($this->input->post('file_type_id') == '1' || $this->input->post('file_type_id') == '2') {
				$this->form_validation->set_rules('employee_name', 'Employee Name', 'required');
				$this->form_validation->set_rules('employee_cnic', 'Employee CNIC', 'required');
			}
			
			if($this->input->post('file_type_id') == '3') {
				$this->form_validation->set_rules('subject', 'Subject', 'required');
			}
			
			$this->form_validation->set_rules('assigned_by', 'Assigned By', 'required');
			$this->form_validation->set_rules('assigned_date', 'Assigned Date', 'required');

            if ($this->form_validation->run()) {
                
                //$directoryFile = $this->directory_file_upload();

                if ($this->scaning_model->scaning($file_id)) {
					$this->session->set_flashdata('message', '<p class="status_msg">Scanning File updated successfully.</p>');
					//die();
					redirect('/admin/scaning/edit/'.$file_id);
                }
            }else{
                //echo validation_errors();
                //exit;
            }
        }
		
		// get scaning data
		$this->data['scaning'] = $this->scaning_model->get_scaning(array('fd.fileId' => $file_id));
		$this->data['scaning'] = $this->data['scaning'][0];
		
		//echo '<pre>'; print_r($this->data['scaning']); die();
		
		// get file type and general category
		$this->data['file_types'] = $this->general_model->get_file_types();
		$this->data['general_category'] = $this->general_model->get_general_category_name();
		
		
		// Select user data to be displayed.
		$sql_select = array(
			$this->flexi_auth->db_column('user_acc', 'id'),
			'upro_first_name',
			'upro_last_name'
		);
		
		if($this->auth->database_config['custom_join']) {
			foreach($this->auth->database_config['custom_join'] as $get_custom_join) {
				foreach($get_custom_join['custom_columns'] as $get_custom_column) {
					$sql_select[] = $get_custom_join['table'].'.'.$get_custom_column;
				}
			}
		}
		
		$this->flexi_auth->sql_select($sql_select);
		                
		// Get Only Active Users
		$sql_where[$this->flexi_auth->db_column('user_acc', 'active').'='] = 1;
		$sql_where[$this->flexi_auth->db_column('user_acc', 'group_id').'='] = 4;
				
		$this->flexi_auth->sql_where($sql_where);

		$this->data['file_received_from'] = $this->flexi_auth->get_users_array();
		
		//Total Files to be scanned
		$this->data['total_number_of_files_to_be_scan'] = $this->scaning_model->get_total_number_of_files_to_be_scan();
		//Files has been scanned
		$this->data['total_files_scanned'] = $this->scaning_model->get_total_files_scanned();
		//Today Scanning
		$this->data['today_scanning'] = $this->scaning_model->get_today_scanning();
		
		// unshift crumb
		$this->breadcrumbs->unshift('Bulk Scaning', base_url().'Scaning');
		
        $this->data['page_title'] = 'Bulk Scaning';
		
		
		
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/scaning/scaning', $this->data);
		
    }
	
	
	function scaning_detail($file_id = null, $file_type_id = null) {
	
		//die();
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to scan detail.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
	
		if(!$file_id) {
			redirect('/admin/scaning/scan_listing/');
		}
	
		// get scaning data
		//$db_select = 'fd.employeeCNIC';
		if($file_type_id!=null && $file_type_id=='1'){
			$this->data['file_detail'] = $this->scaning_model->get_scaning(array('fd.employeeCNIC' => $file_id,'ft.fileTypeId' =>'1'));
		}else{
			$this->data['file_detail'] = $this->scaning_model->get_scaning(array('fd.fileId' => $file_id));
		}
		$this->data['file_detail'] = $this->data['file_detail'][0];
		if(!empty($this->data['file_detail']['employeeCNIC'])){
			if($file_type_id!=null && $file_type_id=='1'){
				$db_where = array('fd.employeeCNIC' => $this->data['file_detail']['employeeCNIC']);
			}
			else{
				$db_where = array('fd.employeeCNIC' => $this->data['file_detail']['employeeCNIC'], 'fd.fileId !=' => $file_id);
			}
			$this->data['related_file'] = $this->scaning_model->get_scaning($db_where);
		}
	
		//echo '<pre>'; print_r($this->data['file_detail']); die();

		// unshift crumb
		//$this->breadcrumbs->unshift('Scan Detail', base_url().'Scaning');
		$this->breadcrumbs->unshift('Scanning Detail', base_url().'admin/scaning/scaning_detail/');
		$this->breadcrumbs->unshift('Scanning Listing', base_url().'admin/scaning/user_scan_listing/');
		
        $this->data['page_title'] = 'Scan Detail';
		if($file_type_id!=null && $file_type_id =='1'){
			$this->data['file_type_id'] = $file_type_id;
		}
		//echo '<pre>'; print_r($this->data); die();
		//die();
		
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/scaning/scaning_detail', $this->data);
		
	}
	
	/*Scanning detail view*/
	function scaning_detail_view($file_id = null, $file_type_id = null) {
	
		//die();
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to scan detail.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
	
		if(!$file_id) {
			redirect('/admin/scaning/scan_listing/');
		}
	
		// get scaning data
		//$db_select = 'fd.employeeCNIC';
		$this->data['file_detail'] = $this->scaning_model->get_scaning(array('fd.fileId' => $file_id));
		$this->data['file_detail'] = $this->data['file_detail'][0];
		if(!empty($this->data['file_detail']['employeeCNIC'])){
			$db_where = array('fd.employeeCNIC' => $this->data['file_detail']['employeeCNIC'], 'fd.fileId !=' => $file_id);
			$this->data['related_file'] = $this->scaning_model->get_scaning($db_where);
		}
	
		
			
		// unshift crumb
		//$this->breadcrumbs->unshift('Scan Detail', base_url().'Scaning');
		$this->breadcrumbs->unshift('Scanning Detail', base_url().'admin/scaning/scaning_detail_view/');
		$this->breadcrumbs->unshift('Scanning Listing', base_url().'admin/scaning/scaning_detail/'.$this->data['file_detail']['employeeCNIC'].'/'.$file_type_id);
		
		
        $this->data['page_title'] = 'Scan Detail';
		//echo '<pre>'; print_r($this->data); die();
		//die();
		
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/scaning/scaning_detail_view', $this->data);
		
	}
	/*end of scanning detail view*/
	
	
	/*
    |------------------------------------------------
    | start: file_upload function
    |------------------------------------------------
    |
    | load multi file upload library and get product images
    |
   */
    function file_upload($action, $file_detail_id = null, $file_type_id = null) {
        
        $options = array( 'file_detail_id' => $file_detail_id, 'action' => $action, 'file_type_id' => $file_type_id, 'module_type' => 'scaning');      
        $this->load->library("CustomUploadHandler", $options);
        
    }
    /*---- end: file_upload function ----*/
	
	
	/*
	|------------------------------------------------
    | start: ajax_get_file_details function
    |------------------------------------------------
    |
    | get file details by employee cnic
    |
   */
    function ajax_get_file_details() {
		
			$this->load->library('RestService');
			
			// Set Values for password encryption library 
			$this->load->library('PasswordEncryption');
			$this->passwordencryption->setValues($this->config->item("hrmis_password"), $this->config->item("inputKey"), $this->config->item("blockSize"));
			
			$sourceLocation = $this->config->item("getEmployee");
			$headers = array("user" => $this->config->item("hrmis_user"), "pass" => $this->passwordencryption->encrypt());
			
			$dto['emp_cnic'] = $this->input->post('employee_cnic');
			$sendData = $dto;
			$response = $this->restservice->put_employee_request($sourceLocation, $headers, $sendData);
					
			$data['response_data'] = null;
			
			if($response->data) {
				
				$gender_value = '';
				
				if($response->data[0]->gender == 'M') {
					$gender_value = 's/o';
				}
				
				if($response->data[0]->gender == 'F') {
					$gender_value = 'd/o';
				}
				
				$data['response_data']['emp_name_father'] = ucwords(strtolower($response->data[0]->emp_name)).' '.$gender_value.' '.ucwords(strtolower($response->data[0]->emp_father_name));
				$data['response_data']['emp_name'] = ucwords(strtolower($response->data[0]->emp_name));
				
				$data['response_data']['data_exists'] = mt_rand();
				
				$this->session->set_userdata(array('data_exists'  => $data['response_data']['data_exists']));
			}
			
			echo json_encode($data);
		        
    }
    /*---- end: ajax_get_file_details function ----*/
	
	
	
	
	
	/*
	|------------------------------------------------
    | start: ajax_get_uploaded_file function
    |------------------------------------------------
    |
    | get uploaded file by employee cnic
    |
   */
    function ajax_get_uploaded_file() {
		
		$data['file_detail'] = null;
		
		if($file_detail = $this->scaning_model->get_scaning(array('fd.employeeCNIC' => $this->input->post('employee_cnic')))) {
			$data['file_detail'] = $file_detail;
		}
		
		echo json_encode($data);
        
    }
    /*---- end: ajax_get_uploaded_file function ----*/
	
	
	
	
	
	/*
	|------------------------------------------------
    | start: user_scan_listing
    |------------------------------------------------
    |
    | load multi file upload library and get product images
    |
   */
	function user_scan_listing() {
   
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to List All Files</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
   
		// unshift crumb
		$this->breadcrumbs->unshift('Listing', base_url() . 'admin/scaning/user_scan_listing/');
		$this->data['show_field'] = array('CNIC', 'Employee Name', 'Category Name');
		
		$this->data['default_listing'] = false;
		$this->data['action_btn'] = json_encode(array('view', 'edit'));
		
		$this->data['file_types'] = $this->general_model->get_file_types();
		$this->data['appName'] = $this->general_model->get_enum_value('file_detail', 'appName');
		$this->data['entered_by'] = 	$this->scaning_model->get_entered_by();
		$this->data['show_field'] = array('Section', 'File Type', 'Entered By', 'Old File Number'/*, 'CNIC', 'Employee Name', 'Category Name', 'Entered By', 'Subject', 'App Name'*/);
		
		if(!empty($this->input->post())){
			$db_where = array('f.fileTypeId' => $this->input->post('ft_file_type_id'));
			$this->data['file_type_index'] = $this->scaning_model->get_file_types($db_where);
			$db_where_gfc= array('gfc.fileTypeId' => $this->input->post('ft_file_type_id'));
			//echo "<pre>"; print_r($this->data['file_type_index']);exit;
		}
		$this->data['general_category'] = $this->general_model->get_general_category_name($db_where_gfc);
		//echo "<pre>"; print_r($this->data['general_category']);exit;
		if(!empty($this->data['file_type_index'])){
			if($this->data['file_type_index'] == '1') {
				//$this->data['show_field'] = array('CNIC', 'Employee Name');
				$this->data['show_table_th'] = array('Employee CNIC', 'Employee Name', 'File Type', 'Category Name', 'Section', 'Old File Number','Entered By', 'App Name' ,'Entered Date','Action');
				$this->data['dt_column'] = array('employeeCNIC','employeeName','fileType','generalCategoryName','sectionName','oldFileNumber','created_by_name', 'appName','createdDate','view_button');
				
				$this->data['db_column'] = array('employeeCNIC','employeeName','fileType','generalCategoryName','sectionName','oldFileNumber','created_by_name', 'appName','createdDate');
			}
			else if($this->data['file_type_index'] == '2') {
				//$this->data['show_field'] = array('CNIC', 'Employee Name');
				$this->data['show_table_th'] = array('Employee CNIC', 'Employee Name', 'File Type', 'Section', 'Old File Number','Entered By','Entered Date','Action');
				$this->data['dt_column'] = array('employeeCNIC','employeeName','fileType','sectionName','oldFileNumber','created_by_name','createdDate','view_button');
				
				$this->data['db_column'] = array('employeeCNIC','employeeName','fileType','sectionName','oldFileNumber','created_by_name','createdDate');
			}
			else {
				//$this->data['show_field'] = array('Category Name', 'Subject');
				$this->data['show_table_th'] = array('Subject', 'File Type', 'Category Name', 'Section', 'Old File Number','Entered By', 'App Name','Entered Date','Action');
				$this->data['dt_column'] = array('subject', 'fileType','generalCategoryName','sectionName','oldFileNumber','created_by_name', 'appName','createdDate','view_button');
				
				$this->data['db_column'] = array('subject', 'fileType','generalCategoryName','sectionName','oldFileNumber','created_by_name', 'appName','createdDate','view_button');
			}
		}
		$this->data['dt_column'] = json_encode($this->data['dt_column']);
		$this->data['db_column'] = json_encode($this->data['db_column']);
		$this->data['general_category_all'] = $this->general_model->get_general_category_name();
		$this->data['page_title'] = 'List all Files';
		
		//$this->data['section_id'] = $secion_id;
		//$this->data['file_type_name'] = $file_type_name;
		// get file type and general category
		//$db_where = array('f.fileType' => str_replace('_',' ',$file_type_name));
		
		
		//$this->data['file_types'] = array();
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/scaning/scan_listing', $this->data);
	}
	/*---- end: user_scan_listing function ----*/
	
	
	
	/*
    |------------------------------------------------
    | start: Scan Listing
    |------------------------------------------------
    |
    | load multi file upload library and get product images
    |
   */
   function scan_listing($secion_id = NULL, $file_type_name = NULL) {
   
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        /*if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to scan listing.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }*/
   
		// unshift crumb
		//$this->breadcrumbs->unshift(str_replace('_',' ',$file_type_name), base_url() . 'admin/scaning/scan_listing/$secion_id/$file_type_name');
		$this->breadcrumbs->unshift('Section', base_url() . 'admin/scaning/scan_listing/');
		$this->data['show_field'] = array('CNIC', 'Employee Name', 'Category Name');
		
		$this->data['default_listing'] = true;
		$this->data['action_btn'] = json_encode(array('view', 'edit'));
		
        $this->data['page_title'] = 'Scan Listing';
		
		$this->data['section_id'] = $secion_id;
		$this->data['file_type_name'] = $file_type_name;
		// get file type and general category
		$db_where = array('f.fileType' => str_replace('_',' ',$file_type_name));
		$this->data['file_type_index'] = $this->scaning_model->get_file_types($db_where);
		
		if($this->data['file_type_index'] == '1') {
			$this->data['show_field'] = array('CNIC', 'Employee Name');
			$this->data['show_table_th'] = array('Employee CNIC', 'Employee Name', 'Category Name', 'Old File Number','Entered By','Entered Date','Action');
			$this->data['dt_column'] = array('employeeCNIC','employeeName', 'generalCategoryName','oldFileNumber','created_by_name','createdDate','view_button');
			
			$this->data['db_column'] = array('employeeCNIC','employeeName', 'generalCategoryName','oldFileNumber','created_by_name','createdDate');
		}
		else if($this->data['file_type_index'] == '2') {
			$this->data['show_field'] = array('CNIC', 'Employee Name');
			$this->data['show_table_th'] = array('Employee CNIC', 'Employee Name' , 'Old File Number','Entered By','Entered Date','Action');
			$this->data['dt_column'] = array('employeeCNIC','employeeName','oldFileNumber','created_by_name','createdDate','view_button');
			
			$this->data['db_column'] = array('employeeCNIC','employeeName','oldFileNumber','created_by_name','createdDate');
		}
		else {
			$this->data['show_field'] = array('Category Name', 'Subject');
			$this->data['show_table_th'] = array('Subject', 'Category Name', 'Old File Number','Entered By','Entered Date','Action');
			$this->data['dt_column'] = array('subject', 'generalCategoryName','oldFileNumber','created_by_name','createdDate','view_button');
			
			$this->data['db_column'] = array('subject', 'generalCategoryName','oldFileNumber','created_by_name','createdDate');
		}
		$this->data['general_category_all'] = $this->general_model->get_general_category_name();
		$this->data['dt_column'] = json_encode($this->data['dt_column']);
		$this->data['db_column'] = json_encode($this->data['db_column']);
		
		//echo "<pre>"; print_r($this->data['file_type_index']);exit;
		$this->data['general_category'] = $this->general_model->get_general_category_name();
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/scaning/scan_listing', $this->data);
	}
	/*---- end: scan listing function ----*/
	function search_category_name(){
		$file_type_id = $this->input->post('file_type_id');
		$db_where = array('gfc.fileTypeId' => $file_type_id);
		$data['general_category'] = $this->general_model->get_general_category_name($db_where);
		//echo "<pre>"; print_r($this->data['general_category']); exit;
		echo json_encode($data);
	}
	/*
    |------------------------------------------------
    | start: Get Server Side Scan Listing
    |------------------------------------------------
    |
    | Load server side scan listing
    |
   */
	function get_scan_listing() {
        
		//echo '<pre>'; print_r($this->input->post()); die();
		
		$action_btn_array = json_decode($this->input->post('action_btn'));
        
        // database column for searching
        //$db_column = array('ft.fileTypeId', 'fd.oldFileNumber');
        //$db_column = array('fd.createdDate');
		$db_column = json_decode($this->input->post('db_column'));
                
        
        $db_where		= array();
        $db_limit       = array();
        $db_order       = array();
		$db_count		= "Counter";
		
        /***** start: record limit and record start form *****/
        if($this->input->post('length') != '-1') {
            $db_limit['limit'] = $this->input->post('length');
            $db_limit['startPageRecord'] = $this->input->post('start');
        }
		/***** end: record limit and record start form *****/
        
        /***** start: get data order by *****/
        $order = $this->input->post('order');
		//echo "<pre>";print_r($order);exit;
        if($order) {
            foreach($order as $key => $get_order) {
                //$db_order[$key]['title']    = $db_column[$get_order['column']-1];
				$db_order[$key]['title']    = $db_column[$get_order['column']];
                $db_order[$key]['order_by'] = $get_order['dir'];
            }            
        }
        // end: get data order by
		
		
        //echo "<pre>"; print_r($this->input->post('top_search_like')); exit;
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
                        $db_where['fd.'.$new_search_key . ' LIKE']  = '%' . $search_val . '%';

                }
				
				/*if(preg_match('/cb/', $key)) {

                    $search_key = substr($key, 3);
					
					$search_key = explode('_', $search_key);
					$new_search_key = '';
					foreach($search_key as $get_search_key) {
						$new_search_key = $new_search_key.ucfirst($get_search_key);
					}
                    
                    if($search_val!="")
                        $db_where['fd.'.$new_search_key]  = $search_val;

                }*/
            }
        }
        // end: top search data by like
		
		
		/***** start: top search data by equal to *****/
        if($this->input->post('top_search')) {
            foreach($this->input->post('top_search') as $key => $search_val) {
				if(preg_match('/fd/', $key)) {

                    $search_key = substr($key, 3);
					
					$search_key = explode('_', $search_key);
					$new_search_key = '';
					foreach($search_key as $get_search_key) {
						$new_search_key = $new_search_key.ucfirst($get_search_key);
					}
                    
                    if($search_val!="")
                        $db_where['fd.'.$new_search_key]  = $search_val;

                }
            }
        }
        // end: top search data by equal to
		
		
		
		
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
                        $db_where['fd.'.$new_search_key . ' LIKE']  = '%' . $search_val . '%';

                }
            }
        }
        // end: top search data by like
		
		
		
        /***** start: search data by like (datatable) *****/
        $search = $this->input->post('search');
        
        if($search['value'] != '') {
            foreach($db_column as $value) {
				if(!empty($value)) {
					$db_where[$value . ' LIKE']   = '%' . $search['value'] . '%';
				}
            }
        }
        // end: search data by like (datatable)
        $fileType = $this->input->post('file_type_ids');
		//echo "<pre>";print_r($fileTypes);exit;
		$dataRecord = $this->scaning_model->get_scan_unit($db_where, $db_limit, $db_order, null,$fileType);
        //echo '<pre>'; print_r($dataRecord); die();
        $dataCount = $this->scaning_model->get_scan_unit($db_where, null, null, $db_count, $fileType);
		//echo $dataCount; die();
        
		/*$show_fields = array(
			"1" => array('employeeCNIC','employeeName','fileType','sectionName','oldFileNumber'),
			"2" => array('employeeCNIC','employeeName','fileType','sectionName','oldFileNumber'),
			"3" => array('subject', 'fileType','generalCategoryName','sectionName','oldFileNumber')
		);
		
		
		$file_type_id = $this->input->post('fd_file_typeId');
		
		// if user enter extra file type then dynamically create index for it. To avoid warning
		if(!isset($show_fields[$file_type_id])){
			$show_fields[$file_type_id] = array('subject', 'fileType','generalCategoryName','sectionName','oldFileNumber');
		}*/
		
        $dt_column = json_decode($this->input->post('dt_column'));
		
        $data = array();
        $i = 0;
        
        if($dataRecord) {
			
            foreach($dataRecord as $key => $value) {
				
                foreach($dt_column as $get_dt_column) {
					if($get_dt_column == 'view_button') {
						if($value['fileTypeId']=='1'){
							$data[$i][] = '<a href="'.base_url().'admin/scaning/scaning_detail/'.$value['employeeCNIC'].'/'.$value['fileTypeId'].'" class="btn btn-xs btn-primary text-center" title=""><i class="fa fa-eye"></i></a>'; //Link to view the property detail <i class="fa fa-eye"></i>
						}else{
							$data[$i][] = '<a href="'.base_url().'admin/scaning/scaning_detail/'.$value['fileId'].'/'.$value['fileTypeId'].'" class="btn btn-xs btn-primary text-center" title=""><i class="fa fa-eye"></i></a>'; //Link to view the property detail <i class="fa fa-eye"></i>
						}
					}
					else if($get_dt_column == 'fileType') {
						$data[$i][] = '<p style="width:100px;">'.$value['fileType'].'</p>'; //Link to view the property detail
						
					}
					/*else if($get_dt_column == 'oldFileNumber') {
						$data[$i][] = '<a href="'.base_url().'admin/scaning/scaning_detail/'.$value['fileId'].'" title="" target="_blank">'.$value[$get_dt_column].'<div></a>'; //Link to view the property detail
						
					}
					else if($get_dt_column == 'fileType') {
						$data[$i][] = '<a href="'.base_url().'admin/scaning/scaning_detail/'.$value['fileId'].'" title="" target="_blank">'.$value[$get_dt_column].'<div></a>'; //Link to view the property detail
						
					}
					else if($get_dt_column == 'sectionName') {
						$data[$i][] = '<a href="'.base_url().'admin/scaning/scaning_detail/'.$value['fileId'].'" title="" target="_blank">'.$value[$get_dt_column].'<div></a>'; //Link to view the property detail
						
					}
					else if($get_dt_column == 'generalCategoryName') {
						$data[$i][] = '<a href="'.base_url().'admin/scaning/scaning_detail/'.$value['fileId'].'" title="" target="_blank">'.$value[$get_dt_column].'<div></a>'; //Link to view the property detail
						
					}
					else if($get_dt_column == 'subject') {
						$data[$i][] = '<a href="'.base_url().'admin/scaning/scaning_detail/'.$value['fileId'].'" title="" target="_blank">'.$value[$get_dt_column].'<div></a>'; //Link to view the property detail
						
					}
					else if($get_dt_column == 'fileName') {
						$data[$i][] = '<a href="'.base_url().'admin/scaning/scaning_detail/'.$value['fileId'].'" title="" target="_blank">'.current(explode('.', $value[$get_dt_column])).'<div></a>'; //Link to view the property detail
						
					}
					else if($get_dt_column == 'createdDate') {
						$data[$i][] = '<a href="'.base_url().'admin/scaning/scaning_detail/'.$value['fileId'].'" title="" target="_blank">'.$value[$get_dt_column].'<div></a>'; //Link to view the property detail
						
					}*/
					else{
                        $data[$i][] = $value[$get_dt_column];
					}
                }
				
                $i++;
            }
        }
        
        $this->data['datatable']['draw']            = $this->input->post('draw');
        $this->data['datatable']['recordsTotal']    = $dataCount;
        $this->data['datatable']['recordsFiltered'] = $dataCount;
        $this->data['datatable']['data']            = $data;
        
        echo json_encode($this->data['datatable']);
        
    }
	

	/*---- end: server side scan listing function ----*/

	/*End of General File Name*/
	/*
    |------------------------------------------------
    | start: Report of Manage File Count
    |------------------------------------------------
    |
    | Reporting
    |
   */
   function section_wise_report() {
	   				
			// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
			if (! $this->flexi_auth->is_privileged($this->uri_privileged))
			{
					$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to report.</p>');
					if($this->flexi_auth->is_admin())
						redirect('auth_admin');
					else
						redirect('auth_public');
			}
			
			// unshift crumb
			$this->breadcrumbs->unshift('Section wise Report', base_url() . 'admin/scaning/section_wise_report/');
			
			$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
			$this->data['page_title'] = 'Section wise Report';
			
			$this->data['file_types'] = $this->general_model->get_file_types();
			$this->data['sections'] = $this->general_model->get_section();
			if($this->input->post()){
				$db_where = array('msc.fileTypeId' => $this->input->post('ft_file_type_id'),
								   'msc.sectionId' => $this->input->post('section_id'));
				$this->data['sectionwise_reporting'] = $this->scaning_model->get_sectionwise_reporting($db_where);
			}
			
			//$this->data['general_category_names'] = $this->scaning_model->get_general_category_name();
			
			$this->load->view('admin/includes/header', $this->data);
			$this->load->view('admin/setting/view_sectionwise_reporting', $this->data);
			
   }
	/*End of Reporting here */ 
	
	
	/*
	|------------------------------------------------
    | start: raw_upload_files function
    |------------------------------------------------
    |
    | this function show all raw files
    |
   */
	function raw_upload_files() {
        
        // Check user has privileges to delete raw upladed files, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to delete raw upladed files.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
        
        // active menu
        $this->data['menu'] = 'auth_admin';
        
        // unshift crumb
        $this->breadcrumbs->unshift('Raw Uploaded Files', base_url().'auth_admin/raw_upload_files');
        
        $this->data['page_title'] = 'Raw Uploaded Files';
        
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/scaning/raw_upload_files', $this->data);
        
    }
	/*---- end: raw_upload_files function ----*/

    
 	
}