<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Scheme extends CI_Controller {
    
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
        $this->load->model('admin/scheme_model');
        
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
        //section menu work ends
		
    }
	
	
    function add() {
			
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to add scheme.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
		
		$random_submit_num =  rand(1000,100000);
		
		$this->data['child_document_type'] = array();
		$errors_file = 0;
		
		if ($this->input->post() && ($this->session->userdata('random_submit_num') == $this->input->post('random_submit_num'))) {
		
			//echo '<pre>'; print_r($this->input->post()); die();
		
			$post = $this->input->post();
			
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('adp_year', 'ADP Year', 'required');
			$this->form_validation->set_rules('adp_number', 'ADP Number', 'required');
			$this->form_validation->set_rules('scheme_name', 'Scheme Name', 'required');
			$this->form_validation->set_rules('scheme_date', 'Scheme Date', 'required');
			$this->form_validation->set_rules('document_type_id', 'Document Type', 'required');

			$upload_dir = $this->config->item('fileUploadPathScheme');
			
			if ($this->form_validation->run() && (isset($post['file_uploaded_id']) && file_exists($upload_dir.'temp_files/'.$post['file_uploaded_name'][0]))) {
			
				if ($this->scheme_model->scheme()) {
					$this->session->set_flashdata('message', '<p class="status_msg">Scheme file added successfully.</p>');
					//die('TEST');
					$this->session->set_userdata('random_submit_num', $random_submit_num);
					redirect('/admin/scheme/add/');
				}	
				
			}else{
				$errors_file = 1;
				$this->session->set_flashdata('message', '<p class="error_msg">Error: Please Upload File</p>');
				//echo validation_errors();
				//exit;
			}
			
			
			if($this->input->post('adp_number')){
				$db_where = array('sc.parentAdpNumber' => $this->input->post('parent_adp_number'), 'fu.moduleType' => 'scheme');
				$this->data['related_file'] = $this->scheme_model->get_scheme($db_where);
			}
			
			//echo '<pre>'; print_r($this->data['related_file']); die();
			
			$db_where = array('dt.documentTypeParentId' => $this->input->post('document_type_id'));
			$this->data['child_document_type'] = $this->general_model->get_document_type('scheme_document_type', $db_where);
				
        }
		
		// get document type
		$db_where = array('dt.documentTypeParentId' => '0');
		$this->data['document_type'] = $this->general_model->get_document_type('scheme_document_type', $db_where);
		
		
		// get document type child
		//$db_where = array('dt.documentTypeParentId' => '0');
		//$this->data['document_type_child'] = $this->general_model->get_document_type('scheme_document_type', $db_where);
		
		// unshift crumb
		$this->breadcrumbs->unshift('Add Scheme', base_url().'scheme');
		
        $this->data['page_title'] = 'Add Scheme';
        $this->data['random_submit_num'] = $random_submit_num;
		
		if($errors_file == 0){
			$generate_uuid = $this->scheme_model->generate_uuid();
			$this->session->set_userdata('uuid', $generate_uuid);
		}
		else{
			$generate_uuid = $this->session->userdata('uuid');
		}
		$this->data['uuid'] = $generate_uuid;
		
		
		$this->session->set_userdata('random_submit_num', $random_submit_num);
		
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/scheme/scheme', $this->data);
		
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

                if ($this->scheme_model->scaning($file_id)) {
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
		$this->data['scaning'] = $this->scheme_model->get_scaning(array('fd.fileId' => $file_id));
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
		$this->data['total_number_of_files_to_be_scan'] = $this->scheme_model->get_total_number_of_files_to_be_scan();
		//Files has been scanned
		$this->data['total_files_scanned'] = $this->scheme_model->get_total_files_scanned();
		//Today Scanning
		$this->data['today_scanning'] = $this->scheme_model->get_today_scanning();
		
		// unshift crumb
		$this->breadcrumbs->unshift('Bulk Scaning', base_url().'Scaning');
		
        $this->data['page_title'] = 'Bulk Scaning';
		
		
		
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/scaning/scaning', $this->data);
		
    }
	
	
	function scheme_detail($scheme_id = null,$request_type) {
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to scheme document.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
		
		// unshift crumb
		$this->breadcrumbs->unshift('Scheme Document', base_url().'admin/scheme/scheme_detail/');
		if($request_type == "services"){
			$this->breadcrumbs->unshift('Scheme Listing', base_url().'admin/scheme/scheme_listing/');
		}
		if($request_type == "operator"){
			$this->breadcrumbs->unshift('Document Uploaded Schemes', base_url().'admin/scheme/docs_uploaded_schemes/');
		}
	
		if(!$scheme_id) {
			redirect('/admin/scheme/scheme_listing/');
		}
	
		// get scheme data
		//$db_select = 'fd.employeeCNIC';
		$this->data['scheme'] = $this->scheme_model->get_scheme(array('sc.parentAdpNumber' => $scheme_id));
		
		if(!empty($this->data['scheme'][0])){
			$this->data['scheme'] = $this->data['scheme'][0];	
			if(!empty($this->data['scheme']['parentAdpNumber'])){
				$db_where = array('sc.parentAdpNumber' => $this->data['scheme']['parentAdpNumber'], 'sc.schemeId !=' => $scheme_id);
				$this->data['related_file'] = $this->scheme_model->get_scheme($db_where);
			}
		}		
		else{
			/***** start: record limit and record start form *****/
			$dto['limit'] = 20;
			$dto['start'] = 0;
			/***** end: record limit and record start form *****/
			
			/***** start: get data order by *****/
			$dto["order"] = 'desc';
			list($adp_number,$adp_start_year,$adp_end_year) = explode('-',$scheme_id);
			
			$dto["adp_year"] = $adp_start_year."-".$adp_end_year;
			$dto["adp_number"] = $adp_number;
		
			$this->load->library('RestService');
	
			// Set Values for password encryption library 
			$this->load->library('PasswordEncryption');
			$this->passwordencryption->setValues($this->config->item("scheme_password"), $this->config->item("inputKey"), $this->config->item("blockSize"));
			
			$sourceLocation = $this->config->item("getOngoingScheme");
			//$headers = array("user" => $this->config->item("scheme_user"), "pass" => $this->passwordencryption->encrypt());
			
			$headers = array('Content-Type:application/json', 'Authorization: '.$this->config->item("scheme_authorization"));
			
			//$headers = array();
			
			$sendData = $dto;
			$response = json_decode($this->restservice->put_scheme_request($sourceLocation, $headers, $sendData));
			//$dataCount = count($dataRecord);
			//$this->data['related_file'] = $dataRecord;
			$scheme_data["schemeName"] = $response->schemeDTOs[0]->schemeAdpName;
			// check scheme has parent adp number if not then show same schme_id
			if($response->schemeDTOs[0]->parentAdpNumber){
				$scheme_data["parentAdpNumber"] = $response->schemeDTOs[0]->parentAdpNumber .'-'. $response->schemeDTOs[0]->parentAdpYear;
			}
			else{
				$scheme_data["parentAdpNumber"] = $scheme_id;
			}
			$this->data["scheme"] = $scheme_data;
			
			//echo '<pre>'; print_r($response); die();
		}
		//echo "<pre>"; print_r($this->data['related_file']);exit;
		
        $this->data['page_title'] = 'Scheme Document';
		
		//echo '<pre>'; print_r($this->data); die();
		//die();
		
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/scheme/scheme_detail_view', $this->data);
		
	}
	
	function scheme_detail_view($scheme_id = null) {
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to scheme detail.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
	
		if(!$scheme_id) {
			redirect('/admin/scheme/scheme_listing/');
		}
	
		// get scheme data
		//$db_select = 'fd.employeeCNIC';
		$this->data['scheme'] = $this->scheme_model->get_scheme(array('sc.schemeId' => $scheme_id));
		$this->data['scheme'] = $this->data['scheme'][0];
		
		if(!empty($this->data['scheme']['parentAdpNumber'])){
			$db_where = array('sc.parentAdpNumber' => $this->data['scheme']['parentAdpNumber'], 'sc.schemeId !=' => $scheme_id);
			$this->data['related_file'] = $this->scheme_model->get_scheme($db_where);
		}
		
			
		// unshift crumb
		$this->breadcrumbs->unshift('Scheme Detail', base_url().'admin/scheme/scheme_detail_view/');
		$this->breadcrumbs->unshift('Scheme Document', base_url().'admin/scheme/scheme_detail/'.$this->data['scheme']['parentAdpNumber']);
		
		
        $this->data['page_title'] = 'Scheme Detail';
		
		//echo '<pre>'; print_r($this->data); die();
		//die();
		
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/scheme/scheme_detail', $this->data);
		
	}
	
	/*
    |------------------------------------------------
    | start: file_upload function
    |------------------------------------------------
    |
    | load multi file upload library and get product images
    |
   */
    function file_upload($action, $scheme_id = null) {
        
        $options = array( 'scheme_id' => $scheme_id, 'action' => $action, 'module_type' => 'scheme');      
        $this->load->library("CustomUploadHandler", $options);
        
    }
    /*---- end: file_upload function ----*/
	
	
	/*
	|------------------------------------------------
    | start: ajax_get_project_details function
    |------------------------------------------------
    |
    | get project name by adp number and add year
    |
   */
    function ajax_get_scheme_details() {
	
		$post_data = $this->input->post();
		
		$data['response_data'] = null;
		
		if($scheme = $this->scheme_model->get_scheme(array('sc.adpYear' => $post_data['adp_year'], 'sc.adpNumber' => $post_data['adp_number']))) {
			$scheme = $scheme[0];
			$data['response_data']['scheme_name'] = $scheme['schemeName'];
			$data['response_data']['parent_adp_number'] = $scheme['parentAdpNumber'];
			$adp_number = $scheme['adpNumber'];
		}		
		else {
			$this->load->library('RestService');
		
			// Set Values for password encryption library 
			$this->load->library('PasswordEncryption');
			$this->passwordencryption->setValues($this->config->item("scheme_password"), $this->config->item("inputKey"), $this->config->item("blockSize"));
			
			$sourceLocation = $this->config->item("getScheme");
			//$headers = array("user" => $this->config->item("scheme_user"), "pass" => $this->passwordencryption->encrypt());
			
			$headers = array('Content-Type:application/json', 'Authorization: '.$this->config->item("scheme_authorization"));
			
			//$headers = array();
						
			$dto['adp_year'] = $post_data['adp_year'];
			$dto['adp_number'] = $post_data['adp_number'];
			
			$sendData = $dto;
			$response = json_decode($this->restservice->put_scheme_request($sourceLocation, $headers, $sendData));
			
			//echo '<pre>'; print_r($response); die();
			
			if($response->status) {
				
				$data['response_data']['scheme_name'] = $response->scheme_name;
				
				if($response->parent_adp_year != 'null' && $response->parent_adp_number != 'null')
					$data['response_data']['parent_adp_number'] = $response->parent_adp_number.'-'.$response->parent_adp_year;
				else
					$data['response_data']['parent_adp_number'] = $post_data['adp_number'].'-'.$post_data['adp_year'];
				
				
				$data['response_data']['data_exists'] = mt_rand();
				
				$this->session->set_userdata(array('data_exists'  => $data['response_data']['data_exists']));
				
				$adp_number = $post_data['adp_number'];
			}
		}
		
		//$post_data = $this->input->post();
		$data['scheme_detail'] = array();
		
		
		//$datatest = $this->scheme_model->get_scheme(array('sc.parentAdpNumber11' => $data['response_data']['parent_adp_number']));
		
		//print_r($datatest);
		//die('TEST');
		
		//if($scheme_detail = $this->scheme_model->get_scheme(array('sc.adpNumber' => $adp_number)) && isset($adp_number)) {
		if($data['response_data'] && $scheme_detail = $this->scheme_model->get_scheme(array('sc.parentAdpNumber' => $data['response_data']['parent_adp_number']))) {
			$data['scheme_detail'] = $scheme_detail;
		}
		
		//die();
		
		echo json_encode($data);
		        
    }
    /*---- end: ajax_get_project_details function ----*/
	
	
	
	/*
	|------------------------------------------------
    | start: ajax_get_uploaded_file function
    |------------------------------------------------
    |
    | get uploaded file by employee cnic
    |
   */
    function ajax_get_uploaded_file() {
		
		$post_data = $this->input->post();
		$data['scheme_detail'] = null;
		
		if($scheme_detail = $this->scheme_model->get_scheme(array('sc.adpNumber' => $post_data['adp_number']))) {
			$data['scheme_detail'] = $scheme_detail;
		}
		
		echo json_encode($data);
        
    }
    /*---- end: ajax_get_uploaded_file function ----*/
	
	
	
	/*
    |------------------------------------------------
    | start: Scan Listing
    |------------------------------------------------
    |
    | load multi file upload library and get product images
    |
   */
   function scheme_listing() {
   
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to all schemes listing.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
   
		// unshift crumb
		//$this->breadcrumbs->unshift(str_replace('_',' ',$file_type_name), base_url() . 'admin/scaning/scan_listing/$secion_id/$file_type_name');
		$this->breadcrumbs->unshift('List All Schemes', base_url() . 'admin/scheme/scheme_listing/');
		
		//$this->data['show_field'] = array('CNIC', 'Employee Name', 'Category Name');
		
		$this->data['default_listing'] = false;
		$this->data['action_btn'] = json_encode(array('view'));
		
        $this->data['page_title'] = 'All Schemes Listing';
		
		$this->data['section_id'] = $secion_id;
		$this->data['file_type_name'] = $file_type_name;
		// get file type and general category
		//$db_where = array('f.fileType' => str_replace('_',' ',$file_type_name));
		//$this->data['file_type_index'] = $this->scheme_model->get_file_types($db_where);
		
		/*if($this->data['file_type_index'] == '1') {
			$this->data['show_field'] = array('CNIC', 'Employee Name');
			$this->data['show_table_th'] = array('Employee CNIC', 'Employee Name', 'File Type', 'Section', 'Old File Number','Entered By','Entered Date','Action');
			$this->data['dt_column'] = array('employeeCNIC','employeeName','fileType','sectionName','oldFileNumber','created_by_name','createdDate','view_button');
		}
		else if($this->data['file_type_index'] == '2') {
			$this->data['show_field'] = array('CNIC', 'Employee Name');
			$this->data['show_table_th'] = array('Employee CNIC', 'Employee Name', 'File Type', 'Section', 'Old File Number','Entered By','Entered Date','Action');
			$this->data['dt_column'] = array('employeeCNIC','employeeName','fileType','sectionName','oldFileNumber','created_by_name','createdDate','view_button');
		}
		else {*/
			//$this->data['show_field'] = array('Category Name', 'Subject');
			$this->data['show_table_th'] = array('ADP Year', 'ADP No', 'Parent ADP No', 'Scheme Name', 'Document Count' ,'Action');
			$this->data['dt_column'] = array('adpYear', 'adpNo', 'parentAdpNumber', 'schemeAdpName', 'documentCount' ,'view_button');
		//}
		
		$this->data['request_type'] = 'services';
		$this->data['dt_column'] = json_encode($this->data['dt_column']);
		
		$db_where = array();
		$db_select = "sc.parentAdpNumber, count(*) as documentCount";
		$db_group = array("sc.parentAdpNumber");
		$db_order = null;
		$db_index_array = 1;
		$scheme_data = $this->scheme_model->get_scheme($db_where, $db_select, $db_group, $db_order, $db_index_array);
		$this->data['scheme_data'] = json_encode($scheme_data);
		$this->data['scheme_data_count'] = ($scheme_data["totalCount"]);
		
		//echo "<pre>"; print_r($this->data['file_type_index']);exit;
		$this->data['general_category'] = $this->general_model->get_general_category_name();
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/scheme/scheme_listing', $this->data);
	}
	/*---- end: scan listing function ----*/

	/*
    |------------------------------------------------
    | start: Document Uploaded Schemes Listing
    |------------------------------------------------
    |
    |
   */
   function docs_uploaded_schemes() {
   
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to docs uploaded schemes listing.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
   
		// unshift crumb
		//$this->breadcrumbs->unshift(str_replace('_',' ',$file_type_name), base_url() . 'admin/scaning/scan_listing/$secion_id/$file_type_name');
		$this->breadcrumbs->unshift('Document Uploaded Schemes', base_url() . 'admin/scheme/docs_uploaded_schemes/');
		
		//$this->data['show_field'] = array('CNIC', 'Employee Name', 'Category Name');
		
		$this->data['default_listing'] = false;
		$this->data['action_btn'] = json_encode(array('view'));
		
        $this->data['page_title'] = 'Document Uploaded Schemes';
		
		$this->data['section_id'] = $secion_id;
		$this->data['file_type_name'] = $file_type_name;
		// get file type and general category
		//$db_where = array('f.fileType' => str_replace('_',' ',$file_type_name));
		//$this->data['file_type_index'] = $this->scheme_model->get_file_types($db_where);
		
		/*if($this->data['file_type_index'] == '1') {
			$this->data['show_field'] = array('CNIC', 'Employee Name');
			$this->data['show_table_th'] = array('Employee CNIC', 'Employee Name', 'File Type', 'Section', 'Old File Number','Entered By','Entered Date','Action');
			$this->data['dt_column'] = array('employeeCNIC','employeeName','fileType','sectionName','oldFileNumber','created_by_name','createdDate','view_button');
		}
		else if($this->data['file_type_index'] == '2') {
			$this->data['show_field'] = array('CNIC', 'Employee Name');
			$this->data['show_table_th'] = array('Employee CNIC', 'Employee Name', 'File Type', 'Section', 'Old File Number','Entered By','Entered Date','Action');
			$this->data['dt_column'] = array('employeeCNIC','employeeName','fileType','sectionName','oldFileNumber','created_by_name','createdDate','view_button');
		}
		else {*/
			//$this->data['show_field'] = array('Category Name', 'Subject');
			$this->data['show_table_th'] = array('ADP Year', 'ADP No', 'Parent ADP No', 'Scheme Name', 'Document Date', 'Last Upload Date' , 'Action');
			$this->data['dt_column'] = array('adpYear', 'adpNumber', 'parentAdpNumber', 'schemeName', 'schemeDate', 'lastuploadeddocument' ,'view_button');
		//}
		
		$this->data['request_type'] = 'operator';
		$this->data['dt_column'] = json_encode($this->data['dt_column']);
		
		$this->data['scheme_data'] = json_encode(array());
		
		//echo "<pre>"; print_r($this->data['file_type_index']);exit;
		$this->data['general_category'] = $this->general_model->get_general_category_name();
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/scheme/scheme_listing', $this->data);
	}
	/*---- end: docs_uploaded_schemes function ----*/



	
	/*
    |------------------------------------------------
    | start: Get Server Side Scan Listing
    |------------------------------------------------
    |
    | Load server side scan listing
    |
   */
	function get_scheme_listing() {
        
		//echo '<pre>'; print_r($this->input->post()); die();
		
		$action_btn_array = json_decode($this->input->post('action_btn'));
        
        // database column for searching
        //$db_column = array('ft.fileTypeId', 'fd.oldFileNumber');
        $db_column = array('sc.adpYear');
                
        
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
        $request_type = $this->input->post('request_type');
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
				if(preg_match('/scd/', $key)) {

                    $search_key = substr($key, 3);
					
					$search_key = explode('_', $search_key);
					$new_search_key = '';
					foreach($search_key as $get_search_key) {
						$new_search_key = $new_search_key.ucfirst($get_search_key);
					}
                    
                    if($search_val!="")
                        $db_where['sc.'.$new_search_key]  = $search_val;

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
        
		//echo "<pre>";print_r($db_order);exit;
		$dataRecord = $this->scheme_model->get_scheme_list($db_where, $db_limit, $db_order);
        //echo '<pre>'; print_r($dataRecord); die();
		$dataCount = $this->scheme_model->get_scheme_list($db_where, null, null);
        $dataCount = count($dataCount);
		/*if($dataCount==NULL || $dataCount<=0){
			 $dataCount=0;
		}*/
		//echo '<pre>'; print_r($dataCount); die();
		//die();
       
        
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
						$data[$i][] = '<a href="'.base_url().'admin/scheme/scheme_detail/'.$value['parentAdpNumber'].'/'.$request_type.'/" class="btn btn-xs btn-primary text-center" title=""><i class="fa fa-eye"></i></a>'; //Link to view the property detail <i class="fa fa-eye"></i>
						
					}
					/**/
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
					if($get_dt_column == 'schemeDate') {
						$data[$i][] = ($value[$get_dt_column] == '0000-00-00') ? '' : date('d-m-Y', strtotime($value[$get_dt_column]));
					}
					else if($get_dt_column == 'parentAdpNumber') {
						$parent_adp_number = explode('-', $value[$get_dt_column]);
						$data[$i][] = $parent_adp_number[1].'-'.$parent_adp_number[2].' ('.$parent_adp_number[0].')';
					}
					else if($get_dt_column == 'schemeName') {
						$data[$i][] = '<p style="width:300px;">'.$value[$get_dt_column].'</p>'; //Link to view the property detail
						
					}
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
	
	
	function get_scheme_listing_service(){
		
		/***** start: record limit and record start form *****/
        if($this->input->post('length') != '-1') {
            $dto['limit'] = $this->input->post('length');
            $dto['start'] = $this->input->post('start');
        }
		/***** end: record limit and record start form *****/
        
        /***** start: get data order by *****/
        //$dto["order"] = $this->input->post('order');
        $dto["order"] = "desc";
		$request_type = $this->input->post('request_type');
		
		if($this->input->post('adp_year')){
			$dto["adp_year"] = $this->input->post('adp_year');
			 
		}
		if($this->input->post('adp_number')){
			$dto["adp_number"] = $this->input->post('adp_number');
			//$db_where["sc.adpNumber"] = $dto["adp_number"];
		}		
		
		//echo "<pre>"; print_r($dto);exit;
		/*
		$db_where = array();
		$db_select = "sc.parentAdpNumber, count(*) as documentCount";
		$db_group = array("sc.parentAdpNumber");
		$db_order = null;
		$db_index_array = 1;
		$scheme_data = $this->scheme_model->get_scheme($db_where, $db_select, $db_group, $db_order, $db_index_array);
		*/
		$scheme_data = (array) json_decode($this->input->post('scheme_data'));
		
		//echo "<pre>"; print_r($scheme_data); exit;
		
		
		$this->load->library('RestService');
		
		// Set Values for password encryption library 
		$this->load->library('PasswordEncryption');
		$this->passwordencryption->setValues($this->config->item("scheme_password"), $this->config->item("inputKey"), $this->config->item("blockSize"));
		
		$sourceLocation = $this->config->item("getOngoingScheme");
		//$headers = array("user" => $this->config->item("scheme_user"), "pass" => $this->passwordencryption->encrypt());
		
		$headers = array('Content-Type:application/json', 'Authorization: '.$this->config->item("scheme_authorization"));
		
		//$headers = array();
		
		$sendData = $dto;
		$dataRecord = json_decode($this->restservice->put_scheme_request($sourceLocation, $headers, $sendData));
		$dataCount = $dataRecord->totalRecords;
		//echo '<pre>'; print_r($dataRecord); die();
		
		//if($dataCount > 0) {
			$dt_column = json_decode($this->input->post('dt_column'));
		
			$data = array();
			$i = 0;
			
			if($dataRecord) {
				
				foreach((array)$dataRecord->schemeDTOs as $key => $value) {
					
					foreach($dt_column as $get_dt_column) {
						$parent_adp_number = ($value->parentAdpNumber) ? $value->parentAdpNumber : $value->adpDetailDTO->adpNo;
						$parent_adp_year = ($value->parentAdpYear) ? $value->parentAdpYear : $this->input->post('adp_year');
						$adp_year = $this->input->post('adp_year');
						$adp_number = $value->adpDetailDTO->adpNo;
						$scheme_name = $value->schemeAdpName;
						
						$parent_adp_number_year = $parent_adp_number ."-". $parent_adp_year;
							
						
						if($get_dt_column == 'view_button') {
							$data[$i][] = '<a href="'.base_url().'admin/scheme/scheme_detail/'.$parent_adp_number_year.'/'.$request_type.'/" class="btn btn-xs btn-primary text-center" title=""><i class="fa fa-eye"></i></a>'; //Link to view the property detail <i class="fa fa-eye"></i>
							
						}
						
						if($get_dt_column == 'adpYear'){
							$data[$i][] = $adp_year;
						}
						
						if($get_dt_column == 'adpNo'){
							$data[$i][] = $adp_number;
						}
						
						
						if($get_dt_column == 'parentAdpNumber') {
							//$parent_adp_number = explode('-', $value[$get_dt_column]);
							$data[$i][] = $parent_adp_year.' ('.$parent_adp_number.')';
						}
						if($get_dt_column == 'schemeAdpName') {
							$data[$i][] = '<p style="width:600px;">'.$scheme_name.'</p>'; //Link to view the property detail
							
						}
						
						if($get_dt_column == 'documentCount'){
							$data[$i][] = ($scheme_data[$parent_adp_number_year]) ? $scheme_data[$parent_adp_number_year] : 0 ;
						}
						
						/*else{
							$data[$i][] = $value[$get_dt_column];
						}*/
					}
					
					$i++;
				}
			}
			
			$this->data['datatable']['draw']            = $this->input->post('draw');
			$this->data['datatable']['recordsTotal']    = $dataCount;
			$this->data['datatable']['recordsFiltered'] = $dataCount;
			$this->data['datatable']['data']            = $data;
			//$this->data['totalCount']					= $scheme_data["totalCount"];
			
			echo json_encode($this->data['datatable']);
		//}	
	}

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
				$this->data['sectionwise_reporting'] = $this->scheme_model->get_sectionwise_reporting($db_where);
			}
			
			//$this->data['general_category_names'] = $this->scheme_model->get_general_category_name();
			
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

	
	/*
	|------------------------------------------------
    | start: raw_upload_files function
    |------------------------------------------------
    |
    | this function show all raw files
    |
   */
	function get_document_type_child_ajax() {
		
		$document_type_id = $this->input->post('document_type_id');
                
        $db_where = array('dt.documentTypeParentId' => $document_type_id);
        if(!$document_type_child = $this->general_model->get_document_type('scheme_document_type', $db_where))
            $document_type_child = array();
        
		
        echo json_encode($document_type_child);
		
	}
	/*---- end: raw_upload_files function ----*/
	
	/*
	|------------------------------------------------
    | start: ajax_verify_scheme_details function
    |------------------------------------------------
    |
    | this function verify scheme
    |
   */
	function ajax_verify_scheme_details() {
	
		$db_where = array();
	
		/***** start: top search data by equal to *****/
        if($this->input->post('post_data')) {
            foreach($this->input->post('post_data') as $key => $search_val) {
				if(preg_match('/vcd/', $key)) {

                    $search_key = substr($key, 3);
					
					$search_key = explode('_', $search_key);
					$new_search_key = '';
					foreach($search_key as $get_search_key) {
						$new_search_key = $new_search_key.ucfirst($get_search_key);
					}
                    
                    if($search_val!="")
                        $db_where['sc.'.$new_search_key]  = $search_val;

                }
            }
        }
        // end: top search data by equal to
		
		if($this->scheme_model->get_scheme($db_where))
			$data['verify_record'] = true;
		else
			$data['verify_record'] = false;
		
		echo json_encode($data);
		//echo '<pre>'; print_r($db_where); die();
	
	}
	/*---- end: ajax_verify_scheme_details function ----*/
	
	
	/*
	|------------------------------------------------
    | start: delete_scheme function
    |------------------------------------------------
    |
    | This function delete scheme
    |
   */
	function delete_scheme($file_upload_id = null, $scheme_id = null, $parent_adp_number) {
	
		// Check user has privileges to delete raw upladed files, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged('Delete Scheme'))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to delete scheme.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
	
		$this->scheme_model->delete_scheme($file_upload_id, $scheme_id);
		redirect('admin/scheme/scheme_detail/'.$parent_adp_number);
	
	}
	/*---- end: delete_scheme function ----*/
 	
}