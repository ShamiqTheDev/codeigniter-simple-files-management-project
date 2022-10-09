<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class General extends CI_Controller {
    
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

        $this->data['user_designation'] = $user_data->ugrp_name;
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
		
		$this->load->model('admin/scaning_model');
		
		// load general model
        $this->load->model('admin/general_model');
		$this->data['menu_sections'] = $this->general_model->get_menu_section_data();
		$this->data['receipt_inbox'] = $this->general_model->get_receipt_inbox_count($this->data['sub_menu']);
		$this->data['file_inbox'] = $this->general_model->get_file_inbox_count($this->data['sub_menu']);
		
        // Get Dynamic Menus
        $this->data['get_menu'] = $this->menu_model->get_menu();
                
    }
	
	/*
    |------------------------------------------------
    | start: view_section function
    |------------------------------------------------
    |
    | Load view of section
    |
   */
	function view_section() {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to section listing.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
		}

		// unshift crumb
		$this->breadcrumbs->unshift('Manage Sections', base_url() . 'admin/general/view_section/');
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'Section';
		
		$this->data['section'] = $this->general_model->get_section();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/view_section', $this->data);
			
	}
	/*---- end: view_section function ----*/
	
	
 
	/*
    |------------------------------------------------
    | start: section function
    |------------------------------------------------
    |
    | Load section
    |
   */
	function section($section_id = NULL) {
	
		if($section_id) {
			$action = 'update';
		}
		else {
			$action = 'add';
		}
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to '.$action.' section.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
			
		$this->breadcrumbs->unshift(ucfirst($action).' Section', base_url() . 'admin/general/section/');
		$this->breadcrumbs->unshift('Manage Sections', base_url() . 'admin/general/view_section');
	
		if ($this->input->post()) {
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('section_name', 'Section Name', 'required');
			
			if ($this->form_validation->run()) {
			
				if($action != 'update' && $this->general_model->get_section(array('sectionName' => $this->input->post('section_name')))) // check duplicates section
				{
					$this->session->set_flashdata('message', '<p class="error_msg">Section Name Already Exists</p>');
					redirect('admin/general/section');
				}
				else{
					if($this->general_model->section($section_id)) {
						$this->session->set_flashdata('message', '<p class="status_msg">Section Name '.ucfirst($action).' Successfully</p>');
						redirect('admin/general/view_section');
					}
				}
			}
			else {
				//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				//redirect('admin/general/section');
				//exit;
			}
		}
		
		if($section_id) {
			$db_where = array('sectionId' => $section_id);
			$this->data['section'] = $this->general_model->get_section($db_where);
			$this->data['section'] = $this->data['section'][$section_id];
		}
		
		$this->data['page_title'] = $this->data['page_name'] = ucfirst($action).' Section';
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['action'] = $action;
		
		$this->load->view('admin/includes/header', $this->data);
		
		$this->load->view('admin/general/section', $this->data);
	}
	/*---- end: section function ----*/
	
	
	
	
	/*
    |------------------------------------------------
    | start: Manage General Category
    |------------------------------------------------
    |
    | Load view of General File Names
    |
   */
	function manage_general_category() {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to manage general category.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
	
		// unshift crumb
		$this->breadcrumbs->unshift('Manage General Category', base_url() . 'admin/general/manage_general_category/');
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'Manage General Category';
		
		$this->data['general_category_names'] = $this->scaning_model->get_general_category_name();
		//echo "<pre>";print_r($this->data['general_category_names']);exit;
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/view_general_category_names', $this->data);
			
	}
 /*End of General File Name*/
 
 /*Start of Add General File Category*/
	function add_general_file_category($id = NULL)
	{
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to add file category.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
	
		$this->breadcrumbs->unshift('Add General File Category', base_url() . 'admin/general/add_general_file_category/');
		$this->breadcrumbs->unshift('Manage General Category', base_url() . 'admin/general/manage_general_category');
	
		if ($this->input->post()) {
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('general_category_name', 'General Category Name', 'required');
			$this->form_validation->set_rules('file_type_id', 'File Type', 'required');
			
			if ($this->form_validation->run()) {
				if($this->scaning_model->check_duplicates_general_category_name())
				{
					$this->session->set_flashdata('message', '<p class="error_msg">General Category Name Already Exists</p>');
					redirect('admin/general/add_general_file_category');
				}
				else{
					if($this->scaning_model->add_general_category_name()) {
						$this->session->set_flashdata('message', '<p class="status_msg">General Category Name Added Successfully</p>');
						redirect('admin/general/manage_general_category');
					}
					}
			}
			else {
				$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				redirect('admin/general/add_general_file_category');
				//exit;
			}
		}
		$this->data['file_types'] = $this->general_model->get_file_types();
		$this->data['page_title'] = $this->data['page_name'] = 'Add General File Category';
		
		$this->data['message'] = $this->session->flashdata('message');
		
		$this->load->view('admin/includes/header', $this->data);
		
		$this->load->view('admin/general/add_general_category_names', $this->data);
	}
	/*End of Add general File category */
	
	/*Start of Edit general File Category*/
	function edit_general_file_category($id = NULL)
	{
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to update file category.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
	
		if($id == NULL)
		redirect('admin/general/manage_general_category');
		
		
		// unshift crumb
		$this->breadcrumbs->unshift('Edit General File Category', base_url() . 'admin/general/edit_general_file_category/');
		$this->breadcrumbs->unshift('Manage General Category', base_url() . 'admin/general/manage_general_category');
		
		$this->data['generalCategoryName'] = $this->scaning_model->get_general_category_name_by_id($id);
		// echo "<pre>"; print_r($this->data['propertyData']);exit;
		
		if ($this->input->post()) {
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('general_category_name', 'General Category Name', 'required');
			
			if ($this->form_validation->run()) {
				if($this->scaning_model->edit_general_category_name()) {
					$this->session->set_flashdata('message', '<p class="status_msg">General Category Name Edited Successfully</p>');
					redirect('admin/general/manage_general_category');
				}
			}
			else {
				$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				redirect('admin/general/manage_general_category');
				//exit;
			}
		}
		$this->data['file_types'] = $this->general_model->get_file_types();
		$this->data['page_title'] = $this->data['page_name'] = ($id ? 'Edit General Category Name' : 'Add General Category Name');
		
		$this->data['message'] = $this->session->flashdata('message');
		
		$this->load->view('admin/includes/header', $this->data);
		
		$this->load->view('admin/general/add_general_category_names', $this->data);
	}
	/*End of edit general file category*/
	
	/*Delete General Category name*/
	function delete_general_file_category($id)
	{	
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to delete file category.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
	
		$this->scaning_model->delete_general_file_category($id);
		$this->session->set_flashdata('message', '<p class="status_msg">General File Category deleted successfully.</p>');
		redirect('admin/general/manage_general_category');
	}
	
	/*
    |------------------------------------------------
    | start: Manage Section Count
    |------------------------------------------------
    |
    | Manage section count 
    |
   */
	function manage_section_count() {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to manage section count.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
	
		// unshift crumb
		$this->breadcrumbs->unshift('Manage Section Count', base_url() . 'admin/general/manage_section_count/');
		
		$uacc_section_fk = FALSE;
		if(count($this->data['session_section']) == 1)
			$uacc_section_fk = $this->data['uacc_section_fk'];
		
		$section_id = $this->input->post('section_id') ? $this->input->post('section_id') : $uacc_section_fk;
		
		$this->data["section_id_post_session"] = $section_id;	
		
		$this->data['sections'] = $this->general_model->get_section();
		$this->data['file_types'] = $this->general_model->get_file_types();
		
		if($this->input->post() && ($section_id != 0 && ($this->input->post('section_id_post_session') == $this->data["section_id_post_session"]))){
			//echo "<pre>";print_r($this->input->post());exit;
			
			$this->load->library('form_validation');
			
			
			//$this->form_validation->set_rules('start_date[]', 'Start Date', 'required');
			//$this->form_validation->set_rules('end_date[]', 'End Date', 'required');
			
			/*foreach() {
				
			}*/
			
			//$this->form_validation->set_rules('file_count[]', 'File Count', 'required');
			
			//if ($this->form_validation->run()) {
			//if(TRUE) {
			
				//if() {
					//die();
					/*if($this->input->post('manage_section_count[0]')){
						$this->session->set_flashdata('message', '<p class="status_msg">Successfully Updated Section wise File Count</p>');
						redirect('admin/general/manage_section_count');
					}
					else{*/
						$this->scaning_model->add_manage_section_count();
						//$this->session->set_flashdata('message', '<p class="status_msg">Successfully Added Section wise File Count</p>');
						$this->data['message'] = '<p class="status_msg">Successfully Added Section wise File Count</p>';
						//redirect('admin/general/manage_section_count');
					//}
				//}
			//}
		}
		
		
		if($section_id != 0){
			$db_where = array('sectionId' => $section_id);
			$this->data['manage_count_table_data'] = $this->scaning_model->get_all_manage_count_data($db_where);
			//echo "<pre>";print_r($this->data['manage_count_table_data']);exit;
		}
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		
		//echo $this->data['message']; die();
		
		$this->data["section_name"] = $this->data["user_section"];
		$this->data['page_title'] = 'Manage Section Count';
		
		//$this->data['general_category_names'] = $this->scaning_model->get_general_category_name();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/manage_files_count', $this->data);
		
	}
	/*
    |------------------------------------------------
    | start: view_file type function
    |------------------------------------------------
    |
    | Load view of Listing of File Types
    |
   */
	function view_file_types() {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to section listing.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
		}

		// unshift crumb
		$this->breadcrumbs->unshift('Manage File Types', base_url() . 'admin/general/view_file_types/');
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'Manage File Types';
		
		$this->data['file_types'] = $this->general_model->get_file_types();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/view_file_types', $this->data);
			
	}
	/*---- end: view_file_types function ----*/
	/*
    |------------------------------------------------
    | start: file type function
    |------------------------------------------------
    |
    | Load view of add file types
    |
   */
	function file_type($file_type_id = NULL) {
	
		if($file_type_id) {
			$action = 'update';
		}
		else {
			$action = 'add';
		}
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to '.$action.' section.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
			
		$this->breadcrumbs->unshift(ucfirst($action).' Add File Type', base_url() . 'admin/general/file_type/');
		$this->breadcrumbs->unshift('Manage File Types', base_url() . 'admin/general/view_file_types');
	
		if ($this->input->post()) {
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('file_type_name', 'File Type', 'required');
			
			if ($this->form_validation->run()) {
			
				if($action != "update" && $this->general_model->get_file_types(array('fileType' => $this->input->post('file_type_name')))) // check duplicates section
				{
					$this->session->set_flashdata('message', '<p class="error_msg">File Type Already Exists</p>');
					redirect('admin/general/view_file_types');
				}
				else{
					if($this->general_model->file_type($file_type_id)) {
						$this->session->set_flashdata('message', '<p class="status_msg">File Type '.ucfirst($action).' Successfully</p>');
						redirect('admin/general/view_file_types');
					}
				}
			}
			else {
				//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				//redirect('admin/general/section');
				//exit;
			}
		}
		
		if($file_type_id) {
			$db_where = array('fileTypeId' => $file_type_id);
			$this->data['file_type'] = $this->general_model->get_file_types($db_where);
			$this->data['file_type'] = $this->data['file_type'][$file_type_id];
		}
		
		$this->data['page_title'] = $this->data['page_name'] = ucfirst($action).' File Type';
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['action'] = $action;
		
		$this->load->view('admin/includes/header', $this->data);
		
		$this->load->view('admin/general/file_type', $this->data);
	}
	/*---- end: section function ----*/
	
	
	function set_manage_section_count($section_id = null) {
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to Correct count.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
	
		// unshift crumb
		$this->breadcrumbs->unshift('Correct Count', base_url() . 'admin/general/set_manage_section_count/'.$section_id);
		$this->breadcrumbs->unshift('Manage Section Count', base_url() . 'admin/general/manage_section_count/');
		
		$this->data['sections'] = $this->general_model->get_section(array('sectionId' => $section_id));
		$this->data['file_types'] = $this->general_model->get_file_types();
		
		if($this->input->post()) {
		
			//echo '<pre>'; print_r($this->input->post()); die();
		
			$this->scaning_model->add_manage_section_count('set_manage_count');
			//$this->session->set_flashdata('message', '<p class="status_msg">Successfully Added Section wise File Count</p>');
			$this->data['message'] = '<p class="status_msg">Successfully Set Section wise File Count</p>';
			//redirect('admin/general/manage_section_count');
		}
		
		$this->data["section_name"] = $this->data['sections'][$section_id]['sectionName'];
		$this->data["section_id_post_session"] = $section_id;
		$this->data["set_manage_count"] = true;
		
		$db_where = array('sectionId' => $section_id);
		$this->data['manage_count_table_data'] = $this->scaning_model->get_all_manage_count_data($db_where);
		
		//echo '<pre>'; print_r($this->data['sections']); die();
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		
		$this->data['page_title'] = 'Correct Count';
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/manage_files_count', $this->data);
	
	}
	
	
	
	/*
    |------------------------------------------------
    | start: add and edit locations function
    |------------------------------------------------
    |
    | Load view of designation
    |
   */
	function locations($location_id = NULL) {
	
		if($location_id) {
			$action = 'update';
		}
		else {
			$action = 'add';
		}
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to '.$action.' section.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
			
		$this->breadcrumbs->unshift(ucfirst($action).' Location', base_url() . 'admin/general/locations/');
		$this->breadcrumbs->unshift('Manage Locations', base_url() . 'admin/general/view_manage_locations');
	
		if ($this->input->post()) {
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('location_name', 'Location Name', 'required');
			$this->form_validation->set_rules('city_id', 'City Id', 'required');
			
			if ($this->form_validation->run()) {
			
				if($action != 'update' && $this->general_model->get_locations(array('locationName' => $this->input->post('location_name')))) // check duplicates section
				{
					$this->session->set_flashdata('message', '<p class="error_msg">Location Name Already Exists</p>');
					redirect('admin/general/locations');
				}
				else{
					if($this->general_model->locations($location_id)) {
						$this->session->set_flashdata('message', '<p class="status_msg">Location Name '.ucfirst($action).' Successfully</p>');
						redirect('admin/general/view_manage_locations');
					}
				}
			}
			else {
				//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				//redirect('admin/general/section');
				//exit;
			}
		}
		
		if($location_id) {
			$db_where = array('locationId' => $location_id);
			$this->data['locations'] = $this->general_model->get_locations($db_where);
			$this->data['locations'] = $this->data['locations'][$location_id];
		}
		$this->data['cities'] = $this->general_model->get_city();
		$this->data['page_title'] = $this->data['page_name'] = ucfirst($action).' Location';
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['action'] = $action;
		
		$this->load->view('admin/includes/header', $this->data);
		
		$this->load->view('admin/general/locations', $this->data);
	}
	/*---- end: add & edit locations function ----*/
	
	
	/*Screen for Manage Locations*/
	function view_manage_locations() {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to manage general category.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
	
		// unshift crumb
		$this->breadcrumbs->unshift('Manage Locations', base_url() . 'admin/general/view_manage_locations/');
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'Manage Locations';
		
		$this->data['manage_locations'] = $this->general_model->get_locations();
		//echo "<pre>";print_r($this->data['general_category_names']);exit;
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/view_manage_locations', $this->data);
			
	}
	/*End of Manage Cities */
	
	
	/*
    |------------------------------------------------
    | start: classified function
    |------------------------------------------------
    |
    | Add and Update Classified
    |
   */
	function classified($classified_id = NULL) {
	
		if($classified_id) {
			$action = 'update';
		}
		else {
			$action = 'add';
		}
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to '.$action.' classified.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
			
		$this->breadcrumbs->unshift(ucfirst($action).' Classified', base_url() . 'admin/general/classified/');
		$this->breadcrumbs->unshift('Classified', base_url() . 'admin/general/listing_classified');
	
		if ($this->input->post()) {
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('classified_name', 'Classified', 'required');
			
			if ($this->form_validation->run()) {
			
				if($this->general_model->get_classified(array('classifiedName' => $this->input->post('classified_name')))) // check duplicates classified
				{
					$this->session->set_flashdata('message', '<p class="error_msg">Classified Already Exists</p>');
					redirect('admin/general/classified');
				}
				else{
					if($this->general_model->classified($classified_id)) {
						$this->session->set_flashdata('message', '<p class="status_msg">Classified '.ucfirst($action).' Successfully</p>');
						redirect('admin/general/listing_classified');
					}
				}
			}
			else {
				//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				//redirect('admin/general/section');
				//exit;
			}
		}
		
		if($classified_id) {
			$db_where = array('classifiedId' => $classified_id);
			$this->data['classified'] = $this->general_model->get_classified($db_where);
			$this->data['classified'] = $this->data['classified'][$classified_id];
		}
		
		$this->data['page_title'] = $this->data['page_name'] = ucfirst($action).' Classified';
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['action'] = $action;
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/classified', $this->data);
	}
	/*---- end: classified function ----*/
	
	
	/*
    |------------------------------------------------
    | start: listing_classified function
    |------------------------------------------------
    |
    | Listing classified
    |
   */
	function listing_classified() {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to classified listing.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
				redirect('auth_public');
		}

		// unshift crumb
		$this->breadcrumbs->unshift('Classified', base_url() . 'admin/general/listing_classified/');
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'Classified';
		
		$this->data['classified'] = $this->general_model->get_classified();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/listing_classified', $this->data);
			
	}
	/*---- end: listing_classified function ----*/
	
	
	/*
    |------------------------------------------------
    | start: delivery_mode function
    |------------------------------------------------
    |
    | Add and Update delivery mode
    |
   */
	function delivery_mode($delivery_mode_id = NULL) {
	
		if($delivery_mode_id) {
			$action = 'update';
		}
		else {
			$action = 'add';
		}
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to '.$action.' delivery mode.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
			
		$this->breadcrumbs->unshift(ucfirst($action).' Delivery Mode', base_url() . 'admin/general/delivery_mode/');
		$this->breadcrumbs->unshift('Delivery Mode', base_url() . 'admin/general/listing_delivery_mode');
	
		if ($this->input->post()) {
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('delivery_mode', 'Delivery Mode', 'required');
			
			if ($this->form_validation->run()) {
			
				if($this->general_model->get_delivery_mode(array('deliveryMode' => $this->input->post('delivery_mode')))) // check duplicates delivery mode
				{
					$this->session->set_flashdata('message', '<p class="error_msg">Delivery Mode Already Exists</p>');
					redirect('admin/general/delivery_mode');
				}
				else{
					if($this->general_model->delivery_mode($delivery_mode_id)) {
						$this->session->set_flashdata('message', '<p class="status_msg">Delivery mode '.ucfirst($action).' Successfully</p>');
						redirect('admin/general/listing_delivery_mode');
					}
				}
			}
			else {
				//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				//redirect('admin/general/section');
				//exit;
			}
		}
		
		if($delivery_mode_id) {
			$db_where = array('deliveryModeId' => $delivery_mode_id);
			$this->data['delivery_mode'] = $this->general_model->get_delivery_mode($db_where);
			$this->data['delivery_mode'] = $this->data['delivery_mode'][$delivery_mode_id];
		}
		
		$this->data['page_title'] = $this->data['page_name'] = ucfirst($action).' Delivery Mode';
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['action'] = $action;
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/delivery_mode', $this->data);
	}
	/*---- end: delivery_mode function ----*/
	
	
	/*
    |------------------------------------------------
    | start: listing_delivery_mode function
    |------------------------------------------------
    |
    | Listing delivery mode
    |
   */
	function listing_delivery_mode() {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to delivery mode listing.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
		}

		// unshift crumb
		$this->breadcrumbs->unshift('Delivery Mode', base_url() . 'admin/general/listing_delivery_mode/');
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'Delivery Mode';
		
		$this->data['delivery_mode'] = $this->general_model->get_delivery_mode();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/listing_delivery_mode', $this->data);
			
	}
	/*---- end: listing_delivery_mode function ----*/
	
	
	/*
    |------------------------------------------------
    | start: document_type function
    |------------------------------------------------
    |
    | Add and Update document type
    |
   */
	function document_type($document_type_id = NULL) {
	
		if($document_type_id) {
			$action = 'update';
		}
		else {
			$action = 'add';
		}
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to '.$action.' document type.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
			
		$this->breadcrumbs->unshift(ucfirst($action).' Document Type', base_url() . 'admin/general/document_type/');
		$this->breadcrumbs->unshift('Document Type', base_url() . 'admin/general/listing_document_type');
	
		if ($this->input->post()) {
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('document_type', 'Document Type', 'required');
			
			if ($this->form_validation->run()) {
			
				if($this->general_model->get_document_type('document_type', array('documentType' => $this->input->post('document_type')))) // check duplicates document type
				{
					$this->session->set_flashdata('message', '<p class="error_msg">Document Type Already Exists</p>');
					redirect('admin/general/document_type');
				}
				else{
					if($this->general_model->document_type('document_type', $document_type_id)) {
						$this->session->set_flashdata('message', '<p class="status_msg">Document Type '.ucfirst($action).' Successfully</p>');
						redirect('admin/general/listing_document_type');
					}
				}
			}
			else {
				//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				//redirect('admin/general/section');
				//exit;
			}
		}
		
		if($document_type_id) {
			$db_where = array('documentTypeId' => $document_type_id);
			$this->data['document_type'] = $this->general_model->get_document_type('document_type', $db_where);
			$this->data['document_type'] = $this->data['document_type'][$document_type_id];
		}
		
		$this->data['page_title'] = $this->data['page_name'] = ucfirst($action).' Document Type';
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['action'] = $action;
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/document_type', $this->data);
	}
	/*---- end: document_type function ----*/
	
	
	/*
    |------------------------------------------------
    | start: listing_document_type function
    |------------------------------------------------
    |
    | Listing document type
    |
   */
	function listing_document_type() {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to document type listing.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
		}

		// unshift crumb
		$this->breadcrumbs->unshift('Document Type', base_url() . 'admin/general/listing_document_type/');
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'Document Type';
		
		$this->data['document_type'] = $this->general_model->get_document_type('document_type');
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/listing_document_type', $this->data);
			
	}
	/*---- end: listing_document_type function ----*/
	
	
	/*
    |------------------------------------------------
    | start: ministry function
    |------------------------------------------------
    |
    | Add and Update ministry
    |
   */
	function ministry($ministry_id = NULL) {
	
		if($ministry_id) {
			$action = 'update';
		}
		else {
			$action = 'add';
		}
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to '.$action.' ministry.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
			
		$this->breadcrumbs->unshift(ucfirst($action).' Ministry', base_url() . 'admin/general/ministry/');
		$this->breadcrumbs->unshift('Ministry', base_url() . 'admin/general/listing_ministry');
	
		if ($this->input->post()) {
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('ministry_name', 'Ministry', 'required');
			
			if ($this->form_validation->run()) {
			
				if($this->general_model->get_ministry(array('ministryName' => $this->input->post('ministry_name')))) // check duplicates ministry
				{
					$this->session->set_flashdata('message', '<p class="error_msg">Ministry Already Exists</p>');
					redirect('admin/general/ministry/'.$ministry_id);
				}
				else{
					if($this->general_model->ministry($ministry_id)) {
						$this->session->set_flashdata('message', '<p class="status_msg">Ministry '.ucfirst($action).' Successfully</p>');
						redirect('admin/general/listing_ministry');
					}
				}
			}
			else {
				//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				//redirect('admin/general/section');
				//exit;
			}
		}
		
		if($ministry_id) {
			$db_where = array('ministryId' => $ministry_id);
			$this->data['ministry'] = $this->general_model->get_ministry($db_where);
			$this->data['ministry'] = $this->data['ministry'][$ministry_id];
		}
		
		$this->data['page_title'] = $this->data['page_name'] = ucfirst($action).' Ministry';
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['action'] = $action;
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/ministry', $this->data);
	}
	/*---- end: ministry function ----*/
	
	
	/*
    |------------------------------------------------
    | start: listing_ministry function
    |------------------------------------------------
    |
    | Listing ministry
    |
   */
	function listing_ministry() {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to ministry listing.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
		}

		// unshift crumb
		$this->breadcrumbs->unshift('Ministry', base_url() . 'admin/general/listing_ministry/');
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'Ministry';
		
		$this->data['ministry'] = $this->general_model->get_ministry();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/listing_ministry', $this->data);
			
	}
	/*---- end: listing_ministry function ----*/
	
	
	/*
    |------------------------------------------------
    | start: department function
    |------------------------------------------------
    |
    | Add and Update department
    |
   */
	function department($department_id = NULL) {
	
		if($department_id) {
			$action = 'update';
		}
		else {
			$action = 'add';
		}
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to '.$action.' department.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
			
		$this->breadcrumbs->unshift(ucfirst($action).' Department', base_url() . 'admin/general/department/');
		$this->breadcrumbs->unshift('Department', base_url() . 'admin/general/listing_department');
	
		if ($this->input->post()) {
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('ministry_id', 'Ministry', 'required');
			$this->form_validation->set_rules('department_name', 'Department', 'required');
			
			if ($this->form_validation->run()) {
			
				if($this->general_model->get_department(array('departmentName' => $this->input->post('department_name'), 'd.ministryId' => $this->input->post('ministry_id')))) // check duplicates department
				{
					$this->session->set_flashdata('message', '<p class="error_msg">Department Already Exists</p>');
					redirect('admin/general/department/'.$department_id);
				}
				else{
					if($this->general_model->department($department_id)) {
						$this->session->set_flashdata('message', '<p class="status_msg">Department '.ucfirst($action).' Successfully</p>');
						redirect('admin/general/listing_department');
					}
				}
			}
			else {
				//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				//redirect('admin/general/section');
				//exit;
			}
		}
		
		if($department_id) {
			$db_where = array('departmentId' => $department_id);
			$this->data['department'] = $this->general_model->get_department($db_where);
			$this->data['department'] = $this->data['department'][$department_id];
		}
		
		$this->data['ministry'] = $this->general_model->get_ministry();
		
		$this->data['page_title'] = $this->data['page_name'] = ucfirst($action).' Department';
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['action'] = $action;
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/department', $this->data);
	}
	/*---- end: department function ----*/
	
	
	/*
    |------------------------------------------------
    | start: listing_department function
    |------------------------------------------------
    |
    | Listing department
    |
   */
	function listing_department() {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to department listing.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
		}

		// unshift crumb
		$this->breadcrumbs->unshift('Department', base_url() . 'admin/general/listing_department/');
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'Department';
		
		$this->data['department'] = $this->general_model->get_department();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/listing_department', $this->data);
			
	}
	/*---- end: listing_department function ----*/
	
	
	/*
    |------------------------------------------------
    | start: state function
    |------------------------------------------------
    |
    | Add and Update state
    |
   */
	function state($state_id = NULL) {
	
		if($state_id) {
			$action = 'update';
		}
		else {
			$action = 'add';
		}
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to '.$action.' state.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
			
		$this->breadcrumbs->unshift(ucfirst($action).' State', base_url() . 'admin/general/state/');
		$this->breadcrumbs->unshift('State', base_url() . 'admin/general/listing_state');
	
		if ($this->input->post()) {
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('country_id', 'Country', 'required');
			$this->form_validation->set_rules('state_name', 'State', 'required');
			
			if ($this->form_validation->run()) {
			
				if($this->general_model->get_state(array('stateName' => $this->input->post('state_name') , 's.countryId' => $this->input->post('country_id')))) // check duplicates state
				{
					$this->session->set_flashdata('message', '<p class="error_msg">State Already Exists</p>');
					redirect('admin/general/state/'.$state_id);
				}
				else{
					if($this->general_model->state($state_id)) {
						$this->session->set_flashdata('message', '<p class="status_msg">State '.ucfirst($action).' Successfully</p>');
						redirect('admin/general/listing_state');
					}
				}
			}
			else {
				//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				//redirect('admin/general/section');
				//exit;
			}
		}
		
		if($state_id) {
			$db_where = array('stateId' => $state_id);
			$this->data['state'] = $this->general_model->get_state($db_where);
			$this->data['state'] = $this->data['state'][$state_id];
		}
		
		$this->data['country'] = $this->general_model->get_country();
		
		$this->data['page_title'] = $this->data['page_name'] = ucfirst($action).' State';
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['action'] = $action;
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/state', $this->data);
	}
	/*---- end: state function ----*/
	
	
	/*
    |------------------------------------------------
    | start: listing_state function
    |------------------------------------------------
    |
    | Listing state
    |
   */
	function listing_state() {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to state listing.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
		}

		// unshift crumb
		$this->breadcrumbs->unshift('State', base_url() . 'admin/general/listing_state/');
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'State';
		
		$this->data['state'] = $this->general_model->get_state();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/listing_state', $this->data);
			
	}
	/*---- end: listing_state function ----*/
	
	
	/*
    |------------------------------------------------
    | start: category function
    |------------------------------------------------
    |
    | Add and Update category
    |
   */
	function category($category_id = NULL) {
	
		if($category_id) {
			$action = 'update';
		}
		else {
			$action = 'add';
		}
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to '.$action.' category.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
			
		$this->breadcrumbs->unshift(ucfirst($action).' Category', base_url() . 'admin/general/category/');
		$this->breadcrumbs->unshift('Category', base_url() . 'admin/general/listing_category');
	
		if ($this->input->post()) {
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('category_name', 'Category', 'required');
			//$this->form_validation->set_rules('category_color', 'Category', 'required');
			
			if ($this->form_validation->run()) {
			
				if($this->general_model->get_category(array('categoryName' => $this->input->post('category_name')))) // check duplicates category
				{
					$this->session->set_flashdata('message', '<p class="error_msg">Category Already Exists</p>');
					redirect('admin/general/category/'.$category_id);
					
				}
				else if($this->general_model->get_category(array('categoryColor' => $this->input->post('category_color')))){
						$this->session->set_flashdata('message', '<p class="error_msg">Color Should be Unique</p>');
						redirect('admin/general/category/'.$category_id);
				}
				else{
					if($this->general_model->category($category_id)) {
						$this->session->set_flashdata('message', '<p class="status_msg">Category '.ucfirst($action).' Successfully</p>');
						redirect('admin/general/listing_category');
					}
				}
			}
			else {
				//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				//redirect('admin/general/section');
				//exit;
			}
		}
		
		if($category_id) {
			$db_where = array('categoryId' => $category_id);
			$this->data['category'] = $this->general_model->get_category($db_where);
			$this->data['category'] = $this->data['category'][$category_id];
		}
		
		$this->data['page_title'] = $this->data['page_name'] = ucfirst($action).' Category';
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['action'] = $action;
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/category', $this->data);
	}
	/*---- end: category function ----*/
	
	
	/*
    |------------------------------------------------
    | start: listing_category function
    |------------------------------------------------
    |
    | Listing category
    |
   */
	function listing_category() {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to category listing.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
		}

		// unshift crumb
		$this->breadcrumbs->unshift('Category', base_url() . 'admin/general/listing_category/');
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'Category';
		
		$this->data['category'] = $this->general_model->get_category();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/listing_category', $this->data);
			
	}
	/*---- end: listing_category function ----*/
	
	
	/*
    |------------------------------------------------
    | start: country function
    |------------------------------------------------
    |
    | Add and Update country
    |
   */
	function country($country_id = NULL) {
	
		if($country_id) {
			$action = 'update';
		}
		else {
			$action = 'add';
		}
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to '.$action.' country.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
			
		$this->breadcrumbs->unshift(ucfirst($action).' Country', base_url() . 'admin/general/country/');
		$this->breadcrumbs->unshift('Country', base_url() . 'admin/general/listing_country');
	
		if ($this->input->post()) {
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('country_name', 'Country', 'required');
			
			if ($this->form_validation->run()) {
			
				if($this->general_model->get_country(array('countryName' => $this->input->post('country_name')))) // check duplicates country
				{
					$this->session->set_flashdata('message', '<p class="error_msg">Country Already Exists</p>');
					redirect('admin/general/country/'.$country_id);
				}
				else{
					if($this->general_model->country($country_id)) {
						$this->session->set_flashdata('message', '<p class="status_msg">Country '.ucfirst($action).' Successfully</p>');
						redirect('admin/general/listing_country');
					}
				}
			}
			else {
				//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				//redirect('admin/general/section');
				//exit;
			}
		}
		
		if($country_id) {
			$db_where = array('countryId' => $country_id);
			$this->data['country'] = $this->general_model->get_country($db_where);
			$this->data['country'] = $this->data['country'][$country_id];
		}
		
		$this->data['page_title'] = $this->data['page_name'] = ucfirst($action).' Country';
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['action'] = $action;
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/country', $this->data);
	}
	/*---- end: country function ----*/
	
	
	/*
    |------------------------------------------------
    | start: listing_country function
    |------------------------------------------------
    |
    | Listing country
    |
   */
	function listing_country() {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to country listing.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
		}

		// unshift crumb
		$this->breadcrumbs->unshift('Country', base_url() . 'admin/general/listing_country/');
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'Country';
		
		$this->data['country'] = $this->general_model->get_country();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/listing_country', $this->data);
			
	}
	/*---- end: listing_country function ----*/
	
	
	/*
    |------------------------------------------------
    | start: city function
    |------------------------------------------------
    |
    | Add and Update city
    |
	*/
	function city($city_id = NULL) {
	
		if($city_id) {
			$action = 'update';
		}
		else {
			$action = 'add';
		}
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to '.$action.' city.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
			
		$this->breadcrumbs->unshift(ucfirst($action).' City', base_url() . 'admin/general/city/');
		$this->breadcrumbs->unshift('City', base_url() . 'admin/general/listing_city');
	
		if ($this->input->post()) {
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('state_id', 'State', 'required');
			$this->form_validation->set_rules('city_name', 'City Name', 'required');
			//$this->form_validation->set_rules('city_code', 'City Code', 'required');
			
			if ($this->form_validation->run()) {
			
				if($this->general_model->get_city(array('cityName' => $this->input->post('city_name')))) // check duplicates city
				{
					$this->session->set_flashdata('message', '<p class="error_msg">City Name Already Exists</p>');
					redirect('admin/general/city/'.$city_id);
				}
				else{
					if($this->general_model->city($city_id)) {
						$this->session->set_flashdata('message', '<p class="status_msg">City Name '.ucfirst($action).' Successfully</p>');
						redirect('admin/general/listing_city');
					}
				}
			}
			else {
				//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				//redirect('admin/general/section');
				//exit;
			}
		}
		
		if($city_id) {
			$db_where = array('cityId' => $city_id);
			$this->data['city'] = $this->general_model->get_city($db_where);
			$this->data['city'] = $this->data['city'][$city_id];
		}
		
		$this->data['state'] = $this->general_model->get_state();
		
		$this->data['page_title'] = $this->data['page_name'] = ucfirst($action).' City';
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['action'] = $action;
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/city', $this->data);
	}
	/*---- end: city function ----*/
	
	
	/*
    |------------------------------------------------
    | start: listing_city function
    |------------------------------------------------
    |
    | Listing city
    |
	*/
	function listing_city() {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to city listing.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
	
		// unshift crumb
		$this->breadcrumbs->unshift('City', base_url() . 'admin/general/listing_city/');
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'City';
		
		$this->data['city'] = $this->general_model->get_city();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/listing_city', $this->data);
			
	}
	/*---- end: listing_city function ----*/
	
	
	
	
	
	
	
	/*
    |------------------------------------------------
    | start: sub_category function
    |------------------------------------------------
    |
    | Add and Update city
    |
	*/
	function sub_category($sub_category_id = NULL) {
	
		if($sub_category_id) {
			$action = 'update';
		}
		else {
			$action = 'add';
		}
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to '.$action.' sub category.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
			
		$this->breadcrumbs->unshift(ucfirst($action).' Sub Category', base_url() . 'admin/general/sub_category/');
		$this->breadcrumbs->unshift('Sub Category', base_url() . 'admin/general/listing_sub_category');
	
		if ($this->input->post()) {
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('category_id', 'Category', 'required');
			$this->form_validation->set_rules('sub_category_name', 'Sub Category', 'required');
			
			if ($this->form_validation->run()) {
			
				if($this->general_model->get_sub_category(array('subCategoryName' => $this->input->post('sub_category_name')))) // check duplicates sub category
				{
					$this->session->set_flashdata('message', '<p class="error_msg">Sub Category Already Exists</p>');
					redirect('admin/general/sub_category/'.$sub_category_id);
				}
				else{
					if($this->general_model->sub_category($sub_category_id)) {
						$this->session->set_flashdata('message', '<p class="status_msg">Sub Category '.ucfirst($action).' Successfully</p>');
						redirect('admin/general/listing_sub_category');
					}
				}
			}
			else {
				//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				//redirect('admin/general/section');
				//exit;
			}
		}
		
		if($sub_category_id) {
			$db_where = array('subCategoryId' => $sub_category_id);
			$this->data['sub_category'] = $this->general_model->get_sub_category($db_where);
			$this->data['sub_category'] = $this->data['sub_category'][$sub_category_id];
		}
		
		$this->data['category'] = $this->general_model->get_category();
		
		$this->data['page_title'] = $this->data['page_name'] = ucfirst($action).' Sub Category';
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['action'] = $action;
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/sub_category', $this->data);
	}
	/*---- end: sub_category function ----*/
	
	
	/*
    |------------------------------------------------
    | start: listing_sub_category function
    |------------------------------------------------
    |
    | Listing sub category
    |
	*/
	function listing_sub_category() {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to sub category listing.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
	
		// unshift crumb
		$this->breadcrumbs->unshift('Sub Category', base_url() . 'admin/general/listing_sub_category/');
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'Sub Category';
		
		$this->data['sub_category'] = $this->general_model->get_sub_category();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/listing_sub_category', $this->data);
			
	}
	/*---- end: listing_sub_category function ----*/
	
	
	
	
	
	
	/*
    |------------------------------------------------
    | start: send_action function
    |------------------------------------------------
    |
    | Add and Update send action
    |
	*/
	function send_action($send_action_id = NULL) {
	
		if($send_action_id) {
			$action = 'update';
		}
		else {
			$action = 'add';
		}
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to '.$action.' send action.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
			
		$this->breadcrumbs->unshift(ucfirst($action).' Send Action', base_url() . 'admin/general/send_action/');
		$this->breadcrumbs->unshift('Send Action', base_url() . 'admin/general/listing_send_action');
	
		if ($this->input->post()) {
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('send_action', 'Send Action', 'required');
			
			if ($this->form_validation->run()) {
			
				if($this->general_model->get_send_action(array('sendAction' => $this->input->post('send_action')))) // check duplicates send action
				{
					$this->session->set_flashdata('message', '<p class="error_msg">Send Action Already Exists</p>');
					redirect('admin/general/send_action/'.$send_action_id);
				}
				else{
					if($this->general_model->send_action($send_action_id)) {
						$this->session->set_flashdata('message', '<p class="status_msg">Send Action '.ucfirst($action).' Successfully</p>');
						redirect('admin/general/listing_send_action');
					}
				}
			}
			else {
				//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				//redirect('admin/general/section');
				//exit;
			}
		}
		
		if($send_action_id) {
			$db_where = array('sendActionId' => $send_action_id);
			$this->data['send_action'] = $this->general_model->get_send_action($db_where);
			$this->data['send_action'] = $this->data['send_action'][$send_action_id];
		}
		
		$this->data['page_title'] = $this->data['page_name'] = ucfirst($action).' Send Action';
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['action'] = $action;
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/send_action', $this->data);
	}
	/*---- end: send_action function ----*/
	
	
	/*
    |------------------------------------------------
    | start: listing_send_action function
    |------------------------------------------------
    |
    | Listing send action
    |
	*/
	function listing_send_action() {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to send action listing.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
	
		// unshift crumb
		$this->breadcrumbs->unshift('Send Action', base_url() . 'admin/general/listing_send_action/');
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'Send Action';
		
		$this->data['send_action'] = $this->general_model->get_send_action();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/listing_send_action', $this->data);
			
	}
	/*---- end: listing_send_action function ----*/
	
	
	
	
	
	/*
    |------------------------------------------------
    | start: send_priority function
    |------------------------------------------------
    |
    | Add and Update send priority
    |
	*/
	function send_priority($send_priority_id = NULL) {
	
		if($send_priority_id) {
			$action = 'update';
		}
		else {
			$action = 'add';
		}
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to '.$action.' send priority.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
			
		$this->breadcrumbs->unshift(ucfirst($action).' Send Priority', base_url() . 'admin/general/send_priority/');
		$this->breadcrumbs->unshift('Send Priority', base_url() . 'admin/general/listing_send_priority');
	
		if ($this->input->post()) {
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('send_priority', 'Send Priority', 'required');
			
			if ($this->form_validation->run()) {
			
				if($this->general_model->get_send_priority(array('sendPriority' => $this->input->post('send_priority')))) // check duplicates send priority
				{
					$this->session->set_flashdata('message', '<p class="error_msg">Send Priority Already Exists</p>');
					redirect('admin/general/send_priority/'.$send_priority_id);
				}
				else if($this->general_model->get_send_priority(array('send_priority_color' => $this->input->post('send_priority_color'))))
				{
					$this->session->set_flashdata('message', '<p class="error_msg">Priority Color Should be Unique</p>');
					redirect('admin/general/send_priority/'.$send_priority_id);
				}
				else{
					if($this->general_model->send_priority($send_priority_id)) {
						$this->session->set_flashdata('message', '<p class="status_msg">Send Priority '.ucfirst($action).' Successfully</p>');
						redirect('admin/general/listing_send_priority');
					}
				}
			}
			else {
				//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				//redirect('admin/general/section');
				//exit;
			}
		}
		
		if($send_priority_id) {
			$db_where = array('sendPriorityId' => $send_priority_id);
			$this->data['send_priority'] = $this->general_model->get_send_priority($db_where);
			$this->data['send_priority'] = $this->data['send_priority'][$send_priority_id];
		}
		
		$this->data['page_title'] = $this->data['page_name'] = ucfirst($action).' Send Priority';
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['action'] = $action;
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/send_priority', $this->data);
	}
	/*---- end: send_priority function ----*/
	
	
	/*
    |------------------------------------------------
    | start: listing_send_priority function
    |------------------------------------------------
    |
    | Listing send priority
    |
	*/
	function listing_send_priority() {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to send priority listing.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
	
		// unshift crumb
		$this->breadcrumbs->unshift('Send Priority', base_url() . 'admin/general/listing_send_priority/');
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'Send Priority';
		
		$this->data['send_priority'] = $this->general_model->get_send_priority();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/listing_send_priority', $this->data);
			
	}
	/*---- end: listing_send_priority function ----*/
	
	
	
	
	
	
	/*
    |------------------------------------------------
    | start: designation function
    |------------------------------------------------
    |
    | Add and Update designation
    |
	*/
	function designation($designation_id = NULL) {
	
		if($designation_id) {
			$action = 'update';
		}
		else {
			$action = 'add';
		}
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to '.$action.' designation.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
			
		$this->breadcrumbs->unshift(ucfirst($action).' Designation', base_url() . 'admin/general/designation/');
		$this->breadcrumbs->unshift('Designation', base_url() . 'admin/general/listing_designation');
	
		if ($this->input->post()) {
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('designation_name', 'Designation', 'required');
			
			if ($this->form_validation->run()) {
			
				if($this->general_model->get_designation(array('designationName' => $this->input->post('designation_name')))) // check duplicates designation name
				{
					$this->session->set_flashdata('message', '<p class="error_msg">Designation Already Exists</p>');
					redirect('admin/general/designation/'.$designation_id);
				}
				else{
					if($this->general_model->designation($designation_id)) {
						$this->session->set_flashdata('message', '<p class="status_msg">Designation '.ucfirst($action).' Successfully</p>');
						redirect('admin/general/listing_designation');
					}
				}
			}
			else {
				//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				//redirect('admin/general/section');
				//exit;
			}
		}
		
		if($designation_id) {
			$db_where = array('designationId' => $designation_id);
			$this->data['designation'] = $this->general_model->get_designation($db_where);
			$this->data['designation'] = $this->data['designation'][$designation_id];
		}
		
		$this->data['page_title'] = $this->data['page_name'] = ucfirst($action).' Designation';
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['action'] = $action;
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/designation', $this->data);
	}
	/*---- end: designation function ----*/
	
	
	/*
    |------------------------------------------------
    | start: listing_designation function
    |------------------------------------------------
    |
    | Listing designation
    |
	*/
	function listing_designation() {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to designation listing.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
	
		// unshift crumb
		$this->breadcrumbs->unshift('Designation', base_url() . 'admin/general/listing_designation/');
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'Designation';
		
		$this->data['designation'] = $this->general_model->get_designation();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/listing_designation', $this->data);
			
	}
	/*---- end: listing_designation function ----*/
	
	
	
	
	/*function test() {
	
		$j = 100;
		
		for($i = 2; $i <= $j; $i++) {
			for($k = 2; $k <= $i; $k++) {
				if($i%$k == 0)
					break;
			}
			
			if($i == $k)
				echo $k.'<br />';
		}
	}*/
	
	/*
    |------------------------------------------------
    | start: scheme_document_type function
    |------------------------------------------------
    |
    | Add and Update document type
    |
   */
	function scheme_document_type($document_type_id = NULL) {
	
		if($document_type_id) {
			$action = 'update';
		}
		else {
			$action = 'add';
		}
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to '.$action.' scheme document type.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
			
		$this->breadcrumbs->unshift(ucfirst($action).' Scheme Document Type', base_url() . 'admin/general/scheme_document_type/');
		$this->breadcrumbs->unshift('Scheme Document Type', base_url() . 'admin/general/listing_scheme_document_type');
	
		if ($this->input->post()) {
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('document_type', 'Document Type', 'required');
			
			if ($this->form_validation->run()) {
			
				$db_where = array('dt.documentType' => $this->input->post('document_type'));
				
				if($this->input->post('have_parent'))
					$db_where['dt.documentTypeParentId'] = $this->input->post('document_type_parent_id');
				else
					$db_where['dt.documentTypeParentId'] = '0';
			
				if($this->general_model->get_document_type('scheme_document_type', $db_where)) // check duplicates document type
				{
					$this->session->set_flashdata('message', '<p class="error_msg">Document Type Already Exists</p>');
					redirect('admin/general/scheme_document_type/'.$document_type_id);
				}
				else{
					if($this->general_model->document_type('scheme_document_type', $document_type_id)) {
						$this->session->set_flashdata('message', '<p class="status_msg">Document Type '.ucfirst($action).' Successfully</p>');
						redirect('admin/general/listing_scheme_document_type');
					}
				}
			}
			else {
				//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				//redirect('admin/general/section');
				//exit;
			}
		}
		
		if($document_type_id) {
			$db_where = array('dt.documentTypeId' => $document_type_id);
			$this->data['document_type_data'] = $this->general_model->get_document_type('scheme_document_type', $db_where);
			$this->data['document_type_data'] = $this->data['document_type_data'][$document_type_id];
			
			//unset()
		}
		
		// get document type
		$db_where = array('dt.documentTypeParentId' => '0');
		$this->data['document_type_parent'] = $this->general_model->get_document_type('scheme_document_type', $db_where);
		
		if($document_type_id)
			unset($this->data['document_type_parent'][$document_type_id]);
		
		$this->data['page_title'] = $this->data['page_name'] = ucfirst($action).' Document Type';
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['action'] = $action;
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/scheme_document_type', $this->data);
	}
	/*---- end: scheme_document_type function ----*/
	
	
	/*
    |------------------------------------------------
    | start: listing_scheme_document_type function
    |------------------------------------------------
    |
    | Listing document type
    |
   */
	function listing_scheme_document_type() {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to scheme document type listing.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
		}

		// unshift crumb
		$this->breadcrumbs->unshift('Scheme Document Type', base_url() . 'admin/general/listing_scheme_document_type/');
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'Document Type';
		
		$this->data['document_type'] = $this->general_model->get_document_type('scheme_document_type');
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/listing_scheme_document_type', $this->data);
			
	}
	/*---- end: listing_scheme_document_type function ----*/
	
	
	/*
    |------------------------------------------------
    | start: listing_user_job_group function
    |------------------------------------------------
    |
    | Listing User Job Group
    |
   */
	function listing_user_job_group() {
	
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to department listing.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
		}

		// unshift crumb
		$this->breadcrumbs->unshift('User Job Group', base_url() . 'admin/general/listing_user_job_group/');
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'User Job Group';
		
		$this->data['user_job_groups'] = $this->general_model->get_user_job_group();
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/listing_user_job_group', $this->data);
			
	}
	/*---- end: listing_user_job_group function ----*/
	
	
	/*
    |------------------------------------------------
    | start: user_job_group function
    |------------------------------------------------
    |
    | Add and Update department
    |
   */
	function user_job_group($user_job_group_id = NULL) {
	
		if($user_job_group_id) {
			$action = 'update';
		}
		else {
			$action = 'add';
		}
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to '.$action.' user job group.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
			
		$this->breadcrumbs->unshift(ucfirst($action).' User Job Group', base_url() . 'admin/general/user_job_group/');
		$this->breadcrumbs->unshift('User Job Group', base_url() . 'admin/general/listing_user_job_group');
	
		if ($this->input->post()) {
			
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('user_job_group_name', 'User Job Group Name', 'required');
			
			if ($this->form_validation->run()) {
			
				if($this->general_model->get_user_job_group(array('userJobGroupName' => $this->input->post('user_job_group_name')))) // check duplicates department
				{
					$this->session->set_flashdata('message', '<p class="error_msg">User Job Group Already Exists</p>');
					redirect('admin/general/user_job_group/'.$user_job_group_id);
				}
				else{
					if($this->general_model->user_job_group($user_job_group_id)) {
						$this->session->set_flashdata('message', '<p class="status_msg">User Job Group '.ucfirst($action).' Successfully</p>');
						redirect('admin/general/listing_user_job_group');
					}
				}
			}
			else {
				//$this->session->set_flashdata('message', '<p class="error_msg">Please Enter All Fields</p>');
				//redirect('admin/general/section');
				//exit;
			}
		}
		
		if($user_job_group_id) {
			$db_where = array('userJobGroupId' => $user_job_group_id);
			$this->data['user_job_group'] = $this->general_model->get_user_job_group($db_where);
			$this->data['user_job_group'] = $this->data['user_job_group'][$user_job_group_id];
		}
		
		$this->data['page_title'] = $this->data['page_name'] = ucfirst($action).' User Job Group';
		
		$this->data['message'] = $this->session->flashdata('message');
		$this->data['action'] = $action;
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/general/user_job_group', $this->data);
	}
	/*---- end: user_job_group function ----*/
}