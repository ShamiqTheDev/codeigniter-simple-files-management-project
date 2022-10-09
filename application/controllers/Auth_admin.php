<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth_admin extends CI_Controller {
    
    public $uri_privileged;
 
    function __construct() 
    {
        parent::__construct();
 		
		// To load the CI benchmark and memory usage profiler - set 1==1.
		if (1==2) 
		{
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
		if (! $this->flexi_auth->is_logged_in_via_password() || ! $this->flexi_auth->is_admin()) 
		{
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
		$this->data['uacc_group_fk'] = $user_data->uacc_group_fk;
		
		$this->data['session_section'] = $session_section = get_session_section();
		
		if(count($session_section == 1)) {
			//$section = get_session_section();
			$this->data["user_section"] = $session_section[$user_data->uacc_section_fk]['sectionName'];
		}
		else {
			$this->data["user_section"] = '';
		}
		
		//get uri segment for active menu
		$this->data['uri_3'] = ($this->uri->segment(3) && (!(is_numeric($this->uri->segment(3)))))?"/".$this->uri->segment(3):"";
		$this->data['uri_2'] = $this->uri->segment(2);
		$this->data['uri_1'] = $this->uri->segment(1);

		$this->data['sub_menu'] = $this->data['uri_1'].'/'.$this->data['uri_2'].'/'.$this->data['uri_3'];
		
		$this->data['menu'] = $this->data['uri_1'];

		
		
		// Get User Privilege 
		$this->load->model('admin/menu_model');
		$check_slash = substr($this->data['sub_menu'], -1);
		$check_slash = ($check_slash == "/")?$this->data['sub_menu']:$this->data['sub_menu']."/";
		$check_slash = str_replace("//","/",$check_slash);
		
		
		$this->uri_privileged = $this->menu_model->get_privilege_name($check_slash);
		$this->data['menu_title'] = $this->uri_privileged;

		// load general model
        $this->load->model('admin/general_model');
		$this->data['menu_sections'] = $this->general_model->get_menu_section_data();
		$this->data['receipt_inbox'] = $this->general_model->get_receipt_inbox_count($this->data['sub_menu']);
		$this->data['file_inbox'] = $this->general_model->get_file_inbox_count($this->data['sub_menu']);
		
		// Get Dynamic Menus
		$this->data['get_menu'] = $this->menu_model->get_menu();
		//echo "<pre>"; print_r($this->data['get_menu']); exit();		
		
		// Error Reporting On index.php not showing the errors thats why placed here, path E:\xammp\ecommerce\index.php
		error_reporting(-1);
		ini_set('display_errors', 1);
		//echo "<pre>"; print_r($this->session->userdata()); exit();
		
	}

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// flexi auth demo
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * Many of the functions within this controller load a custom model called 'auth_admin_model' that has been created for the purposes of this demo.
	 * The 'auth_admin_model' file is not part of the flexi auth library, it is included to demonstrate how some of the functions of flexi auth can be used.
	 *
	 * These demos show working examples of how to implement some (most) of the functions available from the flexi auth library.
	 * This particular controller 'auth_admin', is used by logged in admins to manage users and user groups.
	 * 
	 * All demos are to be used as exactly that, a demonstation of what the library can do.
	 * In a few cases, some of the examples may not be considered as 'Best practice' at implementing some features in a live environment.
	*/

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Quick Help Guide
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
	/**
	 * db_column() function
	 * Columns defined using the 'db_column()' functions are referencing native table columns from the auth libraries 'user_accounts' table.
	 * Using the 'db_column()' function ensures if the column names are changed via the auth config file, then no further references to those table columns 
	 * within the CI installation should need to be updated, as the function will auto reference the config files updated column name.
	 * Native library column names can be defined without using this function, however, you must then ensure that all references to those column names are 
	 * updated throughout the site if later changed.
	 */

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Dashboard
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

	/**
	 * index
	 * Forwards to the admin dashboard.
	 */ 
	function index()
    {
		$this->dashboard();
	}
 
 	/**
 	 * dashboard (Admin)
 	 * The public account dashboard page that acts as the landing page for newly logged in public users.
 	 * The dashboard provides links to some examples of the features available from the flexi auth library.  
 	 */
    function dashboard()
    {
		        
		// unshift crumb
		$this->breadcrumbs->unshift('Dashboard', base_url().'auth_admin');
        
		$this->data['message'] = $this->session->flashdata('message');
		
		$this->load->model('admin/scaning_model');
		$this->load->model('admin/general_model');
		$this->load->model('admin/dashboard_model');
		
		
		$this->data['total_number_of_files_to_be_scan'] = $this->scaning_model->get_total_number_of_files_to_be_scan($this->session->userdata('section'));
		//Files has been scanned
		$this->data['total_files_scanned'] = $this->scaning_model->get_total_files_scanned($this->session->userdata('section'));
		//Today Scanning
		$this->data['today_scanning'] = $this->scaning_model->get_today_scanning($this->session->userdata('section'));
		
		
		$scanned_year = $this->dashboard_model->get_stats('YEAR(createdDate)',Null,'file_detail');
		//echo "<pre>";print_r($scanned_year);exit;
		$inbox_emails = $this->general_model->get_sent_email_alerts();
		$today_receipt_scanned = $this->general_model->get_receipt_scanned_count();
		
		$this->data["scanned_year"] = $scanned_year;
		$this->data["inbox_emails"] = $inbox_emails;
		$this->data["today_receipt_scanned"] = $today_receipt_scanned;
		//echo "<pre>";print_r($today_receipt_scanned);exit;
		
		$this->data['page_title'] = 'Dashboard';
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/dashboard/dashboard_view', $this->data);
		
	}

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// User Accounts
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
 	/**
 	 * manage_user_accounts
 	 * View and manage a table of all users.
 	 * This example allows accounts to be suspended or deleted via checkoxes within the page.
 	 * The example also includes a search tool to lookup users via either their email, first name or last name. 
 	 */
    function manage_user_accounts()
    {
		$this->load->model('auth_admin_model');

		// Check user has privileges to view user accounts, else display a message to notify the user they do not have valid privileges.
		/*if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view user accounts.</p>');
			redirect('auth_admin');
		}*/
                
                // unshift crumb
                $this->breadcrumbs->unshift('Manage User Accounts', base_url().'auth_admin/manage_user_accounts');

		// If 'Admin Search User' form has been submitted, this example will lookup the users email address and first and last name.
		if ($this->input->post('search_users') && $this->input->post('search_query')) 
		{
			// Convert uri ' ' to '-' spacing to prevent '20%'.
			// Note: Native php functions like urlencode() could be used, but by default, CodeIgniter disallows '+' characters.
			$search_query = str_replace(' ','-',$this->input->post('search_query'));
		
			// Assign search to query string.
			redirect('auth_admin/manage_user_accounts/search/'.$search_query.'/page/');
		}
		// If 'Manage User Accounts' form has been submitted and user has privileges to update user accounts, then update the account details.
		else if ($this->input->post('update_users') && $this->flexi_auth->is_privileged($this->uri_privileged)) 
		{
			$this->auth_admin_model->update_user_accounts();
		}


                
		// Get user account data for all users. 
		// If a search has been performed, then filter the returned users.
		$this->auth_admin_model->get_user_accounts();
		
		// Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

		$this->data['page_title'] = 'Manage User Accounts';
		
		$btn_array["Update"]["action"] = "auth_admin/update_user_account/";
		$btn_array["Delete"]["action"] = "auth_admin/delete_user_account/";
		$user_privileges = $this->menu_model->get_privilege_name($btn_array);
		$this->data['update_user'] = $user_privileges["Update"]["title"];
		$this->data['delete_user'] = $user_privileges["Delete"]["title"];
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/setting/user_acccounts_view', $this->data);
                
				
    }
	
 	/**
 	 * update_user_account
 	 * Update the account details of a specific user.
 	 */
	function update_user_account($user_id)
	{
		// Check user has privileges to update user accounts, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to update user accounts.</p>');
			redirect('auth_admin');		
		}
                
		// active menu
		$this->data['sub_menu'] = $this->data['uri_1'].'/manage_user_accounts/';
		
		// start: add breadcrumbs
		$this->breadcrumbs->push('Update User Account', base_url().'auth_admin/update_user_account');
		
		// unshift crumb
		$this->breadcrumbs->unshift('Manage User Accounts', base_url().'auth_admin/manage_user_accounts');

		// If 'Update User Account' form has been submitted, update the users account details.
		if ($this->input->post('update_users_account')) 
		{
			$this->load->model('auth_admin_model');
			$this->auth_admin_model->update_user_account($user_id);
		}
		
		// Get users current data.
		$sql_where = array($this->flexi_auth->db_column('user_acc', 'id') => $user_id);
		$this->data['user'] = $this->flexi_auth->get_users_row_array(FALSE, $sql_where);
	
		// Select only active records
		$sql_select = array();
		$sql_where  = array();
		$sql_where[$this->flexi_auth->db_column('user_group', 'active')] = 1;
		$this->data['user_groups'] = $this->flexi_auth->get_groups_array($sql_select,$sql_where);
            
		// Get user groups.
		$this->data['groups'] = $this->flexi_auth->get_groups_array($sql_select, $sql_where);
		
		// Get section and designation.
		$this->data['sections'] = $this->general_model->get_section();
		$this->data['designation'] = $this->general_model->get_designation();
		$this->data['user_job_groups'] = $this->general_model->get_user_job_group();

		// Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

		$this->data['page_title'] = 'Update User Account';
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/setting/user_account_update_view', $this->data);
	}

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// User Groups
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
 	/**
 	 * manage_user_groups
 	 * View and manage a table of all user groups.
 	 * This example allows user groups to be deleted via checkoxes within the page.
 	 */
    function manage_user_groups()
    {
		// Check user has privileges to view user groups, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view user groups.</p>');
			redirect('auth_admin');		
		}
                
                // unshift crumb
                $this->breadcrumbs->unshift('Manage User Groups', base_url().'auth_admin/manage_user_groups');

		// If 'Manage User Group' form has been submitted and user has privileges, delete user groups.
		if ($this->input->post('delete_group') && $this->flexi_auth->is_privileged($this->uri_privileged)) 
		{
			$this->load->model('auth_admin_model');
			$this->auth_admin_model->manage_user_groups();
		}

		// Define the group data columns to use on the view page. 
		// Note: The columns defined using the 'db_column()' functions are native table columns to the auth library. 
		// Read more on 'db_column()' functions in the quick help section near the top of this controller. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_group', 'id'),
			$this->flexi_auth->db_column('user_group', 'name'),
			$this->flexi_auth->db_column('user_group', 'description'),
			$this->flexi_auth->db_column('user_group', 'admin')
		);
		$sql_where = array();
		// master_admin_group_id is defined in flexi_auth config file
		if ($this->flexi_auth->get_user_group_id() != $this->auth->auth_settings['master_admin_group_id'])
		{
			$sql_where[$this->flexi_auth->db_column('user_group', 'id').' !='] = $this->auth->auth_settings['master_admin_group_id'];
		}
                // Select only active records
		$sql_where[$this->flexi_auth->db_column('user_group', 'active')] = 1;
		$this->data['user_groups'] = $this->flexi_auth->get_groups_array($sql_select,$sql_where);
				
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];	
                
                $btn_array["Delete"]["action"] = "delete_user_group/";
                $user_privileges = $this->menu_model->get_privilege_name($btn_array);
                $this->data['delete_user_group'] = $user_privileges["Delete"]["title"];

                $this->data['page_title'] = 'Manage User Groups';
                
                if($this->config->item('flexi_theme')) {
                    $this->load->view('demo/admin_examples/user_groups_view', $this->data);
                }
                else {
                    $this->load->view('admin/includes/header', $this->data);
                    $this->load->view('admin/setting/user_groups_view', $this->data);
                }	
    }
	
 	/**
 	 * insert_user_group
 	 * Insert a new user group.
 	 */
	function insert_user_group()
	{
		// Check user has privileges to insert user groups, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to insert new user groups.</p>');
			redirect('auth_admin/manage_user_groups');		
		}
                
                // start: add breadcrumbs
                $this->breadcrumbs->push('Insert User Group', base_url().'auth_admin/insert_user_group');
                
                // unshift crumb
                $this->breadcrumbs->unshift('Manage User Groups', base_url().'auth_admin/manage_user_groups');

		// If 'Add User Group' form has been submitted, insert the new user group.
		if ($this->input->post('insert_user_group')) 
		{
			$this->load->model('auth_admin_model');
			$this->auth_admin_model->insert_user_group();
		}
		
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

                $this->data['page_title'] = 'Insert User Group';
                
                if($this->config->item('flexi_theme')) {
                    $this->load->view('demo/admin_examples/user_group_insert_view', $this->data);
                }
                else {
                    $this->load->view('admin/includes/header', $this->data);
                    $this->load->view('admin/setting/user_group_insert_view', $this->data);
                }
	}
	
 	/**
 	 * update_user_group
 	 * Update the details of a specific user group.
 	 */
	function update_user_group($group_id)
	{
		// Check user has privileges to update user groups, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to update user groups.</p>');
			redirect('auth_admin/manage_user_groups');		
		}
                
                // active menu
                $this->data['sub_menu'] = $this->data['uri_1'].'/manage_user_groups/';
                
                // start: add breadcrumbs
                $this->breadcrumbs->push('Update User Group', base_url().'auth_admin/update_user_group');
                
                // unshift crumb
                $this->breadcrumbs->unshift('Manage User Groups', base_url().'auth_admin/manage_user_groups');

		// If 'Update User Group' form has been submitted, update the user group details.
		if ($this->input->post('update_user_group')) 
		{
			$this->load->model('auth_admin_model');
			$this->auth_admin_model->update_user_group($group_id);
		}

		// Get user groups current data.
		$sql_where = array($this->flexi_auth->db_column('user_group', 'id') => $group_id);
		$this->data['group'] = $this->flexi_auth->get_groups_row_array(FALSE, $sql_where);
		
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

                $this->data['page_title'] = 'User Group Update';
                
                if($this->config->item('flexi_theme')) {
                    $this->load->view('demo/admin_examples/user_group_update_view', $this->data);
                }
                else {
                    $this->load->view('admin/includes/header', $this->data);
                    $this->load->view('admin/setting/user_group_update_view', $this->data);
                }
	}

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Privileges
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
 	
	
 	/**
 	 * update_user_privileges
 	 * Update the access privileges of a specific user.
 	 */
    function update_user_privileges($user_id)
    {
		// Check user has privileges to update user privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to update user privileges.</p>');
			redirect('auth_admin/manage_user_accounts');		
		}
                
                // active menu
                $this->data['sub_menu'] = $this->data['uri_1'].'/manage_user_accounts/';
                
                // start: add breadcrumbs
                $this->breadcrumbs->push('Update User Privilege', base_url().'auth_admin/update_user_privileges');
                
                // unshift crumb
                $this->breadcrumbs->unshift('Manage User Accounts', base_url().'auth_admin/manage_user_accounts');

		// If 'Update User Privilege' form has been submitted, update the user privileges.
		if ($this->input->post('update_user_privilege')) 
		{
			$this->load->model('auth_admin_model');
			$this->auth_admin_model->update_user_privileges($user_id);
		}

		// Get users profile data.
		$sql_select = array(
			'upro_uacc_fk', 
			'upro_first_name', 
			'upro_last_name',
			$this->flexi_auth->db_column('user_acc', 'group_id'),
			$this->flexi_auth->db_column('user_group', 'name')
        );
		$sql_where = array($this->flexi_auth->db_column('user_acc', 'id') => $user_id);
		$this->data['user'] = $this->flexi_auth->get_users_row_array($sql_select, $sql_where);		

		// Get all privilege data. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_privileges', 'id'),
			$this->flexi_auth->db_column('user_privileges', 'name'),
                        $this->flexi_auth->db_column('user_privileges', 'parent')
		);
                $sql_where = array($this->flexi_auth->db_column('user_privileges', 'active') => 1,$this->flexi_auth->db_column('user_privileges', 'delete') => 0);
		$privileges = $this->flexi_auth->get_privileges_array($sql_select,$sql_where);
		$formated_menu = array();
                foreach ($privileges as $key => $value) {
                    if ($value[$this->flexi_auth->db_column('user_privileges', 'parent')] == 0) {
                        $formated_menu[$value[$this->flexi_auth->db_column('user_privileges', 'id')]][$this->flexi_auth->db_column('user_privileges', 'id')] = $value[$this->flexi_auth->db_column('user_privileges', 'id')];
                        $formated_menu[$value[$this->flexi_auth->db_column('user_privileges', 'id')]][$this->flexi_auth->db_column('user_privileges', 'name')] = $value[$this->flexi_auth->db_column('user_privileges', 'name')];
                        $formated_menu[$value[$this->flexi_auth->db_column('user_privileges', 'id')]][$this->flexi_auth->db_column('user_privileges', 'parent')] = $value[$this->flexi_auth->db_column('user_privileges', 'parent')];
                    } else {
                        if($this->menu_model->check_parent_delete($value[$this->flexi_auth->db_column('user_privileges', 'parent')])){
							if($this->menu_model->check_parent_active($value[$this->flexi_auth->db_column('user_privileges', 'parent')])){
								$formated_menu[$value[$this->flexi_auth->db_column('user_privileges', 'parent')]]['child_menu'][$value[$this->flexi_auth->db_column('user_privileges', 'id')]][$this->flexi_auth->db_column('user_privileges', 'id')] = $value[$this->flexi_auth->db_column('user_privileges', 'id')];
								$formated_menu[$value[$this->flexi_auth->db_column('user_privileges', 'parent')]]['child_menu'][$value[$this->flexi_auth->db_column('user_privileges', 'id')]][$this->flexi_auth->db_column('user_privileges', 'name')] = $value[$this->flexi_auth->db_column('user_privileges', 'name')];
								$formated_menu[$value[$this->flexi_auth->db_column('user_privileges', 'parent')]]['child_menu'][$value[$this->flexi_auth->db_column('user_privileges', 'id')]][$this->flexi_auth->db_column('user_privileges', 'parent')] = $value[$this->flexi_auth->db_column('user_privileges', 'parent')];
							}
                        }
                    }
                }
                $this->data['privileges'] = $formated_menu;
                
		// Get user groups current privilege data.
		$sql_select = array($this->flexi_auth->db_column('user_privilege_groups', 'privilege_id'));
		$sql_where = array($this->flexi_auth->db_column('user_privilege_groups', 'group_id') => $this->data['user'][$this->flexi_auth->db_column('user_acc', 'group_id')]);
		$group_privileges = $this->flexi_auth->get_user_group_privileges_array($sql_select, $sql_where);
                
        $this->data['group_privileges'] = array();
        foreach($group_privileges as $privilege)
        {
            $this->data['group_privileges'][] = $privilege[$this->flexi_auth->db_column('user_privilege_groups', 'privilege_id')];
        }
                
		// Get users current privilege data.
		$sql_select = array($this->flexi_auth->db_column('user_privilege_users', 'privilege_id'));
		$sql_where = array($this->flexi_auth->db_column('user_privilege_users', 'id') => $user_id);
		$user_privileges = $this->flexi_auth->get_user_privileges_array($sql_select, $sql_where);
	
		// For the purposes of the example demo view, create an array of ids for all the users assigned privileges.
		// The array can then be used within the view to check whether the user has a specific privilege, this data allows us to then format form input values accordingly. 
		$this->data['user_privileges'] = array();
		foreach($user_privileges as $privilege)
		{
			$this->data['user_privileges'][] = $privilege[$this->flexi_auth->db_column('user_privilege_users', 'privilege_id')];
		}
                
                //echo '<pre>'; print_r($user_privileges); die();
	
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

        // For demo purposes of demonstrate whether the current defined user privilege source is getting privilege data from either individual user 
        // privileges or user group privileges, load the settings array containing the current privilege sources. 
		$this->data['privilege_sources'] = $this->auth->auth_settings['privilege_sources'];
                
                $this->data['page_title'] = 'User Privileges Update';
                
                if($this->config->item('flexi_theme')) {
                    $this->load->view('demo/admin_examples/user_privileges_update_view', $this->data);
                }
                else {
                    $this->load->view('admin/includes/header', $this->data);
                    $this->load->view('admin/setting/user_privileges_update_view', $this->data);
                }	
    }
    
 	/**
 	 * update_group_privileges 
 	 * Update the access privileges of a specific user group.
 	 */
    function update_group_privileges($group_id)
    {
		// Check user has privileges to update group privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to update group privileges.</p>');
			redirect('auth_admin/manage_user_accounts');		
		}
                
                // active menu
                $this->data['sub_menu'] = $this->data['uri_1'].'/manage_user_groups/';
                
                // start: add breadcrumbs
                $this->breadcrumbs->push('Update Group Privileges', base_url().'auth_admin/update_group_privileges');
                
                // unshift crumb
                $this->breadcrumbs->unshift('Manage User Groups', base_url().'auth_admin/manage_user_groups');

		// If 'Update Group Privilege' form has been submitted, update the privileges of the user group.
		if ($this->input->post('update_group_privilege')) 
		{
			$this->load->model('auth_admin_model');
			$this->auth_admin_model->update_group_privileges($group_id);
		}
		
		// Get data for the current user group.
		$sql_where = array($this->flexi_auth->db_column('user_group', 'id') => $group_id);
		$this->data['group'] = $this->flexi_auth->get_groups_row_array(FALSE, $sql_where);
                
                        // Get all privilege data. 
                $sql_select = array(
                    $this->flexi_auth->db_column('user_privileges', 'id'),
                    $this->flexi_auth->db_column('user_privileges', 'name'),
                    $this->flexi_auth->db_column('user_privileges', 'parent')
                );
                $sql_where = array($this->flexi_auth->db_column('user_privileges', 'active') => 1, $this->flexi_auth->db_column('user_privileges', 'delete') => 0);
                $privileges = $this->flexi_auth->get_privileges_array($sql_select, $sql_where);
                $formated_menu = array();
                foreach ($privileges as $key => $value) {
                    if ($value[$this->flexi_auth->db_column('user_privileges', 'parent')] == 0) {
                        $formated_menu[$value[$this->flexi_auth->db_column('user_privileges', 'id')]][$this->flexi_auth->db_column('user_privileges', 'id')] = $value[$this->flexi_auth->db_column('user_privileges', 'id')];
                        $formated_menu[$value[$this->flexi_auth->db_column('user_privileges', 'id')]][$this->flexi_auth->db_column('user_privileges', 'name')] = $value[$this->flexi_auth->db_column('user_privileges', 'name')];
                        $formated_menu[$value[$this->flexi_auth->db_column('user_privileges', 'id')]][$this->flexi_auth->db_column('user_privileges', 'parent')] = $value[$this->flexi_auth->db_column('user_privileges', 'parent')];
                    } else {
                        if($this->menu_model->check_parent_delete($value[$this->flexi_auth->db_column('user_privileges', 'parent')])){
							if($this->menu_model->check_parent_active($value[$this->flexi_auth->db_column('user_privileges', 'parent')])){
								$formated_menu[$value[$this->flexi_auth->db_column('user_privileges', 'parent')]]['child_menu'][$value[$this->flexi_auth->db_column('user_privileges', 'id')]][$this->flexi_auth->db_column('user_privileges', 'id')] = $value[$this->flexi_auth->db_column('user_privileges', 'id')];
								$formated_menu[$value[$this->flexi_auth->db_column('user_privileges', 'parent')]]['child_menu'][$value[$this->flexi_auth->db_column('user_privileges', 'id')]][$this->flexi_auth->db_column('user_privileges', 'name')] = $value[$this->flexi_auth->db_column('user_privileges', 'name')];
								$formated_menu[$value[$this->flexi_auth->db_column('user_privileges', 'parent')]]['child_menu'][$value[$this->flexi_auth->db_column('user_privileges', 'id')]][$this->flexi_auth->db_column('user_privileges', 'parent')] = $value[$this->flexi_auth->db_column('user_privileges', 'parent')];
							}
						}
                    }
                }
                $this->data['privileges'] = $formated_menu;

                // Get data for the current privilege group.
		$sql_select = array($this->flexi_auth->db_column('user_privilege_groups', 'privilege_id'));
		$sql_where = array($this->flexi_auth->db_column('user_privilege_groups', 'group_id') => $group_id);
		$group_privileges = $this->flexi_auth->get_user_group_privileges_array($sql_select, $sql_where);
                
		// For the purposes of the example demo view, create an array of ids for all the privileges that have been assigned to a privilege group.
		// The array can then be used within the view to check whether the group has a specific privilege, this data allows us to then format form input values accordingly. 
		$this->data['group_privileges'] = array();
		foreach($group_privileges as $privilege)
		{
			$this->data['group_privileges'][] = $privilege[$this->flexi_auth->db_column('user_privilege_groups', 'privilege_id')];
		}
	
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

                // For demo purposes of demonstrate whether the current defined user privilege source is getting privilege data from either individual user 
                // privileges or user group privileges, load the settings array containing the current privilege sources. 
                $this->data['privilege_sources'] = $this->auth->auth_settings['privilege_sources'];

                $this->data['page_title'] = 'User Group Privileges';
                
                if($this->config->item('flexi_theme')) {
                    $this->load->view('demo/admin_examples/user_group_privileges_update_view', $this->data);
                }
                else {
                    $this->load->view('admin/includes/header', $this->data);
                    $this->load->view('admin/setting/user_group_privileges_update_view', $this->data);
                }		
    }

	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// User Activity
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
 	/**
 	 * list_user_status
 	 * Display a list of active or inactive user accounts. 
 	 * The active status of an account is based on whether the user has verified their account after registering - typically via email activation. 
 	 * This demo example simply shows a table of users that have, and have not activated their accounts.
 	 */
	function list_user_status($status = FALSE)
	{
		// Check user has privileges to view user accounts, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view user accounts.</p>');
			redirect('auth_admin');		
		}
                
                // unshift crumb
                if($status == 'active')
                    $this->breadcrumbs->unshift('List Active Users', base_url().'auth_admin/list_user_status/active');
                else
                    $this->breadcrumbs->unshift('List Inactive Users', base_url().'auth_admin/list_user_status/inactive');
                

		// The view associated with this controller method is used by multiple methods, therefore set a page title.
		$this->data['page_title'] = ($status == 'inactive') ? 'Inactive Users' : 'Active Users';
		$this->data['status'] = ($status == 'inactive') ? 'inactive_users' : 'active_users'; // Indicate page function.
		
		// Get an array of all active/inactive user accounts, using the sql select and where statements defined below.
		// Note: The columns defined using the 'db_column()' functions are native table columns to the auth library. 
		// Read more on 'db_column()' functions in the quick help section near the top of this controller. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_acc', 'id'),
			$this->flexi_auth->db_column('user_acc', 'email'),
			$this->flexi_auth->db_column('user_acc', 'active'),
			$this->flexi_auth->db_column('user_group', 'name'),
			// The following columns are located in the demo example 'demo_user_profiles' table, which is not required by the library.
			'upro_first_name', 
			'upro_last_name'
		);
		$sql_where[$this->flexi_auth->db_column('user_acc', 'active')] = ($status == 'inactive') ? 0 : 1;
		if (! $this->flexi_auth->in_group('Master Admin'))
		{
			// For this example, prevent any 'Master Admin' users being listed to non master admins.
			$sql_where[$this->flexi_auth->db_column('user_group', 'id').' !='] = 2;
		}
		$this->data['users'] = $this->flexi_auth->get_users_array($sql_select, $sql_where);
			
                $this->data['page_title'] = 'Users';
                
                if($this->config->item('flexi_theme')) {
                    $this->load->view('demo/admin_examples/users_view', $this->data);
                }
                else {
                    $this->load->view('admin/includes/header', $this->data);
                    $this->load->view('admin/setting/users_view', $this->data);
                }
	}

 	/**
 	 * delete_unactivated_users
 	 * Display a list of all user accounts that have not been activated within a define time period. 
 	 * This demo example allows the option to then delete all of the unactivated accounts.
 	 */
	function delete_unactivated_users()
	{
		// Check user has privileges to view user accounts, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view user accounts.</p>');
			redirect('auth_admin');		
		}
                
                // unshift crumb
                $this->breadcrumbs->unshift('Delete Unactivated Users', base_url().'auth_admin/delete_unactivated_users');

		// Filter accounts old than set number of days.
		$inactive_days = 28;
	
		// If 'Delete Unactivated Users' form has been submitted and user has privileges to delete users.
		if ($this->input->post('delete_unactivated') && $this->flexi_auth->is_privileged($this->uri_privileged))
		{
			$this->load->model('auth_admin_model');
			$this->auth_admin_model->delete_users($inactive_days);
		}

		// Get an array of all user accounts that have not been activated within the defined limit ($inactive_days), using the sql select and where statements defined below.
		// Note: The columns defined using the 'db_column()' functions are native table columns to the auth library. 
		// Read more on 'db_column()' functions in the quick help section near the top of this controller. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_acc', 'id'),
			$this->flexi_auth->db_column('user_acc', 'email'),
			$this->flexi_auth->db_column('user_acc', 'active'),
			$this->flexi_auth->db_column('user_group', 'name'),
			// The following columns are located in the demo example 'demo_user_profiles' table, which is not required by the library.
			'upro_first_name',
			'upro_last_name'
		);
		$this->data['users'] = $this->flexi_auth->get_unactivated_users_array($inactive_days, $sql_select);
				
                $this->data['page_title'] = 'Users';
                
                if($this->config->item('flexi_theme')) {
                    $this->load->view('demo/admin_examples/users_unactivated_view', $this->data);
                }
                else {
                    $this->load->view('admin/includes/header', $this->data);
                    $this->load->view('admin/setting/users_unactivated_view', $this->data);
                }
	}
	
 	/**
 	 * failed_login_users
 	 * Display a list of all user accounts that have too many failed login attempts since the users last successful login. 
	 * The purpose of this example is to highlight user accounts that have either struggled to login, or that may be the subject of a brute force hacking attempt.
 	 */
	function failed_login_users()
	{
		// Check user has privileges to view user accounts, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to view this page.</p>');
			redirect('auth_admin');		
		}
                
                // unshift crumb
                $this->breadcrumbs->unshift('Failed Login Users', base_url().'auth_admin/failed_login_users');

		// The view associated with this controller method is used by other methods, therefore set a page title.
		$this->data['page_title'] = 'Failed Login Users';
		$this->data['status'] = 'failed_login_users'; // Indicate page function.
		
		// Get an array of all user accounts that have more than 3 failed login attempts since their last successfuly login.
		// Note: The columns defined using the 'db_column()' functions are native table columns to the auth library. 
		// Read more on 'db_column()' functions in the quick help section near the top of this controller. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_acc', 'id'),
			$this->flexi_auth->db_column('user_acc', 'email'),
			$this->flexi_auth->db_column('user_acc', 'failed_logins'),
			$this->flexi_auth->db_column('user_acc', 'active'),
			$this->flexi_auth->db_column('user_group', 'name'),
			// The following columns are located in the demo example 'demo_user_profiles' table, which is not required by the library.
			'upro_first_name',
			'upro_last_name'
		);
		$sql_where[$this->flexi_auth->db_column('user_acc', 'failed_logins').' >='] = 3; // Get users with 3 or more failed login attempts.
		if (! $this->flexi_auth->in_group('Master Admin'))
		{
			// For this example, prevent any 'Master Admin' users being listed to non master admins.
			$sql_where[$this->flexi_auth->db_column('user_group', 'id').' !='] = 2;
		}
		$this->data['users'] = $this->flexi_auth->get_users_array($sql_select, $sql_where);
		
                $this->data['page_title'] = 'Users';
                
                if($this->config->item('flexi_theme')) {
                    $this->load->view('demo/admin_examples/users_view', $this->data);
                }
                else {
                    $this->load->view('admin/includes/header', $this->data);
                    $this->load->view('admin/setting/users_view', $this->data);
                }
	}
        
        
        ###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// User Accounts
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	
        /**
	 * register_account
	 * User registration page used by all new users wishing to create an account.
	 * Note: This page is only accessible to users who are not currently logged in, else they will be redirected.
	 */ 
	function register_account() {
            
		// Check user has privileges to add user accounts, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have privileges to add this page.</p>');
				redirect('auth_admin');		
		}
		
		// active menu
		$this->data['sub_menu'] = $this->data['uri_1'].'/manage_user_accounts/';
		
		// start: add breadcrumbs
		$this->breadcrumbs->push('Add User Account', base_url().'auth_admin/register_account');

		// unshift crumb
		$this->breadcrumbs->unshift('Manage User Accounts', base_url().'auth_admin/manage_user_accounts');

		// If 'Registration' form has been submitted, attempt to register their details as a new account.
		if ($this->input->post('register_user')) {
		
			//echo '<pre>'; print_r($this->input->post()); die();
			
			if($this->input->post('register_username') == $this->input->post('register_password')) {
				$this->data['message'] = '<p class="error_msg">Username and password are same</p>';
			}
			else {
				$this->db->cache_delete_all(); //clear db query caching folder
				$this->load->model('auth_model');
				$this->auth_model->register_account();
			}
			
		}
   
		// Select only active records
		$sql_select = array();
		$sql_where  = array();
		$sql_where[$this->flexi_auth->db_column('user_group', 'active')] = 1;
		$this->data['user_groups'] = $this->flexi_auth->get_groups_array($sql_select,$sql_where);
		
		// Get user groups.
		$this->data['groups'] = $this->flexi_auth->get_groups_array($sql_select, $sql_where);
		
		// load general model
        $this->load->model('admin/general_model');
		
		// Get section and designation.
		//$this->data['cities'] = $this->general_model->get_cities();
		//$this->data['location'] = $this->general_model->get_location();
		$this->data['section'] = $this->general_model->get_section();
		$this->data['designation'] = $this->general_model->get_designation();
		$this->data['user_job_groups'] = $this->general_model->get_user_job_group();
		
		// Get any status message that may have been set.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];

		$this->data['page_title'] = 'User Add';

		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/setting/users_account_insert_view', $this->data);
		
	}
	
	//check_username exist
	function check_username() {
		//echo '<pre>'; print_r($this->input->post('username'));
		$username = $this->input->post('username');
		$this->load->model('auth_model');
		$data = $this->auth_model->check_username($username);
		echo $data;
	}
	//check_username exist end
	
	/**
 	 * change_password
 	 * update the users password, by submitting the current and new password.
 	 * This example requires that the length of the password must be between 8 and 20 characters, containing only alpha-numerics plus the following 
 	 * characters: periods (.), commas (,), hyphens (-), underscores (_) and spaces ( ). These customisable validation settings are defined via the auth config file.
 	 */
	function change_password($user_id = NULL)
	{
            
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to update user privileges.</p>');
			redirect('auth_admin/manage_user_accounts');		
		}
            
                // unshift crumb
                $this->breadcrumbs->unshift('User Profile', base_url().'auth_public/change_password');
				
				
		// Get users current data.
		$sql_where = array($this->flexi_auth->db_column('user_acc', 'id') => $user_id);
		$this->data['user'] = $this->flexi_auth->get_users_row_array(FALSE, $sql_where);
            
		// If 'Update Password' form has been submitted, validate and then update the users password.
		if ($this->input->post('change_password'))
		{						

			if($this->data['user']['uacc_username'] == $this->input->post('new_password')) {
				$this->data['message'] = '<p class="error_msg">Username and password are same</p>';
			}
			else {
				$this->load->model('auth_model');
				$this->auth_model->change_password();
			}
		}
                
                
                
				
		// Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		
                $this->data['page_title'] = 'Change User Password';
                $this->data['userId'] = $user_id;
                if($this->config->item('flexi_theme')) {
                    $this->load->view('demo/public_examples/password_update_view', $this->data);
                }
                else {
                    $this->load->view('admin/includes/header', $this->data);
                    $this->load->view('admin/setting/password_update_view', $this->data);
                }
	}
        
        
        
        /**
 	 * Manage Menu
 	 */
	function manage_menu()
    {
		// Check user has privileges to view user privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to view user privileges.</p>');
			redirect('auth_admin');		
		}
                
                // unshift crumb
                $this->breadcrumbs->unshift('Manage Menus', base_url().'auth_admin/manage_menu');
                
                $this->data['add_link'] = "auth_admin/add_menu/";
                $this->data['update_link'] = "auth_admin/update_menu/";
                $this->data['delete_link'] = "delete_menu/";
                
                $btn_array["Add"]["action"] = $this->data['add_link'];
                $btn_array["Update"]["action"] = $this->data['update_link'];
                $btn_array["Delete"]["action"] = $this->data['delete_link'];
                
                $user_privileges = $this->menu_model->get_privilege_name($btn_array);
                $this->data['add_menu'] = $user_privileges["Add"]["title"];
                $this->data['update_menu'] = $user_privileges["Update"]["title"];
                $this->data['delete_menu'] = $user_privileges["Delete"]["title"];
                
                $this->data['add'] = (isset($user_privileges["Add"]["title"]))?$user_privileges["Add"]["title"]:"";
                $this->data['update'] = (isset($user_privileges["Add"]["title"]))?$user_privileges["Update"]["title"]:"";
                $this->data['delete'] = (isset($user_privileges["Add"]["title"]))?$user_privileges["Delete"]["title"]:"";
		
		// If 'Manage Privilege' form has been submitted and the user has privileges to delete privileges.
		if ($this->input->post('delete_item') && $this->flexi_auth->is_privileged($this->data['delete_menu'])) 
		{
			
                        if($this->menu_model->delete_menu()){
                            $this->session->set_flashdata('message', '<p class="status_msg">Menu Deleted Successfully.</p>');
                            redirect('auth_admin/manage_menu/');
                        }else{
                            $this->session->set_flashdata('message', '<p class="error_msg">Menu Failed to Delete, Please try again.</p>');
                            redirect('auth_admin/manage_menu/');
                        }
		}

		// Define the privilege data columns to use on the view page. 
		// Note: The columns defined using the 'db_column()' functions are native table columns to the auth library. 
		// Read more on 'db_column()' functions in the quick help section near the top of this controller. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_privileges', 'id'),
			$this->flexi_auth->db_column('user_privileges', 'name'),
			$this->flexi_auth->db_column('user_privileges', 'description')
		);
		//$this->data['privileges'] = $this->flexi_auth->get_privileges_array($sql_select);
		
                
            
                $hide_privilege  = FALSE;
				$show_disabled_parent_childs = TRUE;
                
                $this->data['get_menus'] = $this->menu_model->get_menu($hide_privilege,$show_disabled_parent_childs);
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

                $this->data['page_title'] = 'Manage Menu';
                
                if($this->config->item('flexi_theme')) {
                    $this->load->view('demo/admin_examples/privileges_view', $this->data);
                }
                else {
                    $this->load->view('admin/includes/header', $this->data);
                    $this->load->view('admin/setting/menu_view', $this->data);
                }
	}
        
        
        /**
 	 * add_menu
 	 * Add a new Menu.
 	 */
	function add_menu()
	{
		// Check user has privileges to insert user privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to insert new user privileges.</p>');
			redirect('auth_admin/');		
		}
                
                // active menu
                $this->data['sub_menu'] = $this->data['uri_1'].'/manage_menu/';
                
                // start: add breadcrumbs
                $this->breadcrumbs->push('Add Menu', base_url().'auth_admin/add_menu');
                
                // unshift crumb
                $this->breadcrumbs->unshift('Manage Menu', base_url().'auth_admin/manage_menu');

		// If 'Add Privilege' form has been submitted, insert the new privilege.
		if ($this->input->post('insert_menu')) 
		{
                        if($this->menu_model->add_menu()){
                            $this->session->set_flashdata('message', '<p class="status_msg">Menu Added Successfully.</p>');
                            redirect('auth_admin/manage_menu/');
                        }else{
                            $this->session->set_flashdata('message', '<p class="error_msg">Menu Failed to Add, Please try again.</p>');
                            redirect('auth_admin/manage_menu/');
                        }
                        
		}
		
                $this->data['get_parent_menu'] = $this->menu_model->get_parent_menu();
                
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

                $this->data['page_title'] = 'Add Menu';
                
                if($this->config->item('flexi_theme')) {
                    $this->load->view('demo/admin_examples/privilege_insert_view', $this->data);
                }
                else {
                    $this->load->view('admin/includes/header', $this->data);
                    $this->load->view('admin/setting/menu_add_view', $this->data);
                }
	}
        
        
        /**
 	 * update_menu
 	 * Update Menu
 	 */
	function update_menu($menu_id)
	{
		// Check user has privileges to update user privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to update user privileges.</p>');
			redirect('auth_admin/');		
		}
                
                // active menu
                $this->data['sub_menu'] = $this->data['uri_1'].'/manage_menu/';
                
                // start: add breadcrumbs
                $this->breadcrumbs->push('Update Menu', base_url().'auth_admin/update_menu');
                
                // unshift crumb
                $this->breadcrumbs->unshift('Manage Menu', base_url().'auth_admin/manage_menu');

		// If 'Update Privilege' form has been submitted, update the privilege details.
		if ($this->input->post('update_menu')) 
		{
			if($this->menu_model->update_menu()){
                            $this->session->set_flashdata('message', '<p class="status_msg">Menu Updated Successfully.</p>');
                            redirect('auth_admin/update_menu/'.$menu_id);
                        }else{
                            $this->session->set_flashdata('message', '<p class="error_msg">Menu Failed to Update, Please try again.</p>');
                            redirect('auth_admin/update_menu/'.$menu_id);
                        }
		}
		
		// Get privileges current data.
                
                // Get Data
                $this->data['get_parent_menu'] = $this->menu_model->get_parent_menu();
		$this->data['get_current_menu'] = $this->menu_model->get_current_menu($menu_id);
                $this->data['get_current_menu'] = $this->data['get_current_menu'][0];
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

                $this->data['page_title'] = 'Update Menu';
                
                if($this->config->item('flexi_theme')) {
                    $this->load->view('demo/admin_examples/privilege_update_view', $this->data);
                }
                else {
                    $this->load->view('admin/includes/header', $this->data);
                    $this->load->view('admin/setting/menu_update_view', $this->data);
                }
	}
        
        
        ###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	
	// Public Account Management
	###++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++###	

 	/**
 	 * update_account
 	 * Manage and update the account details of a logged in public user.
 	 */
	function update_account()
	{
                // unshift crumb
                $this->breadcrumbs->unshift('User Profile', base_url().'auth_public/update_account');
            
		// If 'Update Account' form has been submitted, update the user account details.
		if ($this->input->post('update_account')) 
		{
			$this->load->model('auth_model');
			$this->auth_model->update_account();
		}
		
		// Get users current data.
		// This example does so via 'get_user_by_identity()', however, 'get_users()' using any other unqiue identifying column and value could also be used.
		$this->data['user'] = $this->flexi_auth->get_user_by_identity_row_array();

		// load general model
        $this->load->model('admin/general_model');
		
		// Get designation.
		$this->data['sections'] = $this->general_model->get_section();
		
		// Set any returned status/error messages.
		$this->data['message'] = (! isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

		$this->data['page_title'] = 'Update User Account';
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/setting/account_update_view', $this->data);
	}
        
        
        
        
        
        /**
 	 * Manage Privilege
 	 */
	function manage_privilege()
    {
		// Check user has privileges to view user privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to view user privileges.</p>');
			redirect('auth_admin');		
		}
                
                // unshift crumb
                $this->breadcrumbs->unshift('Manage Privleges', base_url().'auth_admin/manage_privilege');
                
                $btn_array["Add"]["action"] = "auth_admin/add_privilege/";
                $btn_array["Update"]["action"] = "auth_admin/update_privilege/";
                $btn_array["Delete"]["action"] = "delete_privilege/";
                
                $this->data['add_link'] = "auth_admin/add_privilege/";
                $this->data['update_link'] = "auth_admin/update_privilege/";
                $this->data['delete_link'] = "delete_privilege/";
                
                $user_privileges = $this->menu_model->get_privilege_name($btn_array);
                $this->data['add'] = $user_privileges["Add"]["title"];
                $this->data['update'] = $user_privileges["Update"]["title"];
                $this->data['delete'] = $user_privileges["Delete"]["title"];
		
		// If 'Manage Privilege' form has been submitted and the user has privileges to delete privileges.
		if ($this->input->post('delete_item') && $this->flexi_auth->is_privileged($this->data['delete'])) 
		{
			
                        if($this->menu_model->delete_menu()){
                            $this->session->set_flashdata('message', '<p class="status_msg">Menu Deleted Successfully.</p>');
                            redirect('auth_admin/manage_privilege/');
                        }else{
                            $this->session->set_flashdata('message', '<p class="error_msg">Menu Failed to Delete, Please try again.</p>');
                            redirect('auth_admin/manage_privilege/');
                        }
		}

		// Define the privilege data columns to use on the view page. 
		// Note: The columns defined using the 'db_column()' functions are native table columns to the auth library. 
		// Read more on 'db_column()' functions in the quick help section near the top of this controller. 
		$sql_select = array(
			$this->flexi_auth->db_column('user_privileges', 'id'),
			$this->flexi_auth->db_column('user_privileges', 'name'),
			$this->flexi_auth->db_column('user_privileges', 'description')
		);
		//$this->data['privileges'] = $this->flexi_auth->get_privileges_array($sql_select);
		
                
            
                $hide_privilege  = FALSE;
                $this->data['get_menus'] = $this->menu_model->get_menu($hide_privilege); 
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

                $this->data['page_title'] = 'Manage Privilege';
                
                if($this->config->item('flexi_theme')) {
                    $this->load->view('demo/admin_examples/privileges_view', $this->data);
                }
                else {
                    $this->load->view('admin/includes/header', $this->data);
                    $this->load->view('admin/setting/menu_view', $this->data);
                }
	}
        
        
        
        /**
 	 * add_privilege
 	 * Add a new Privilege.
 	 */
	function add_privilege()
	{
		// Check user has privileges to insert user privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to insert new user privileges.</p>');
			redirect('auth_admin/');		
		}
                
                // active menu
                $this->data['sub_menu'] = $this->data['uri_1'].'/manage_privilege/';
                
                // start: add breadcrumbs
                $this->breadcrumbs->push('Add Privilege', base_url().'auth_admin/add_privilege');
                
                // unshift crumb
                $this->breadcrumbs->unshift('Manage Privilege', base_url().'auth_admin/manage_privilege');

		// If 'Add Privilege' form has been submitted, insert the new privilege.
		if ($this->input->post('insert_privilege')) 
		{
                        if($this->menu_model->add_menu()){
                            $this->session->set_flashdata('message', '<p class="status_msg">Privilege Added Successfully.</p>');
                            redirect('auth_admin/manage_privilege/');
                        }else{
                            $this->session->set_flashdata('message', '<p class="error_msg">Privilege Failed to Add, Please try again.</p>');
                            redirect('auth_admin/manage_privilege/');
                        }
                        
		}
		
                $this->data['get_parent_menu'] = $this->menu_model->get_parent_menu();
                
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

                $this->data['page_title'] = 'Add Privilege';
                
                if($this->config->item('flexi_theme')) {
                    $this->load->view('demo/admin_examples/privilege_insert_view', $this->data);
                }
                else {
                    $this->load->view('admin/includes/header', $this->data);
                    $this->load->view('admin/setting/menu_add_view', $this->data);
                }
	}
        
        
        
        /**
 	 * update_menu
 	 * Update Menu
 	 */
	function update_privilege($menu_id)
	{
		// Check user has privileges to update user privileges, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged))
		{
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to update user privileges.</p>');
			redirect('auth_admin/');		
		}
                
                // active menu
                $this->data['sub_menu'] = $this->data['uri_1'].'/manage_privilege/';
                
                // start: add breadcrumbs
                $this->breadcrumbs->push('Update Privilege', base_url().'auth_admin/update_privilege');
                
                // unshift crumb
                $this->breadcrumbs->unshift('Manage Privilege', base_url().'auth_admin/manage_privilege');

		// If 'Update Privilege' form has been submitted, update the privilege details.
		if ($this->input->post('update_privilege')) 
		{
			if($this->menu_model->update_menu()){
                            $this->session->set_flashdata('message', '<p class="status_msg">Privilege Updated Successfully.</p>');
                            redirect('auth_admin/update_privilege/'.$menu_id);
                        }else{
                            $this->session->set_flashdata('message', '<p class="error_msg">Privilege Failed to Update, Please try again.</p>');
                            redirect('auth_admin/update_privilege/'.$menu_id);
                        }
		}
		
		// Get privileges current data.
                
                // Get Data
                $this->data['get_parent_menu'] = $this->menu_model->get_parent_menu();
		$this->data['get_current_menu'] = $this->menu_model->get_current_menu($menu_id);
                $this->data['get_current_menu'] = $this->data['get_current_menu'][0];
		// Set any returned status/error messages.
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];		

                $this->data['page_title'] = 'Update Privilege';
                
                if($this->config->item('flexi_theme')) {
                    $this->load->view('demo/admin_examples/privilege_update_view', $this->data);
                }
                else {
                    $this->load->view('admin/includes/header', $this->data);
                    $this->load->view('admin/setting/menu_update_view', $this->data);
                }
	}

}

/* End of file auth_admin.php */
/* Location: ./application/controllers/auth_admin.php */