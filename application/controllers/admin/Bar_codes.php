<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
	
	class bar_codes extends CI_Controller {
		
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
			$this->load->model('admin/bar_code_model');
			
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
			
			$this->data['show_send_button'] = array('created', 'sent', 'inbox', 'roll back');
			$this->data['show_send_back_button'] = array('inbox');
			//section menu work
			$this->data['menu_sections'] = $this->general_model->get_menu_section_data();
			$this->data['receipt_inbox'] = $this->general_model->get_receipt_inbox_count($this->data['sub_menu']);
			//section menu work ends
			
		}
		
		
		
		
		/*
		|------------------------------------------------
		| start: bar_code_listing function
		|------------------------------------------------
		|
		| This function show created listing
		|
		*/
		function bar_code_listing() {
			
			// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
			if (! $this->flexi_auth->is_privileged($this->uri_privileged))
			{
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to bar code listing.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
			}
		
			// unshift crumb
			$this->breadcrumbs->unshift('Bar Code Listing', base_url() . 'admin/bar_code/bar_code_listing/');
			
			$this->data['action_btn'] = json_encode(array());
			
			$this->data['dt_datatable'] = array(
												array(
													'th_table' => 'Generated Date',
													'dt_column' => 'generated_date',
													'db_column' => 'generated_date',
													'td_orderable' => 'true',
													'td_width' => '20%'
												),
												array(
													'th_table' => 'Document Number',
													'dt_column' => 'document_number',
													'db_column' => 'document_number',
													'td_orderable' => 'true',
													'td_width' => '20%'
												),
												array(
													'th_table' => 'Personnel Id',
													'dt_column' => 'personnel_id',
													'db_column' => 'personnel_id',
													'td_orderable' => 'true',
													'td_width' => '20%'
												),
												array(
													'th_table' => 'CNIC',
													'dt_column' => 'cnic',
													'db_column' => 'cnic',
													'td_orderable' => 'true',
													'td_width' => '20%'
												),
												array(
													'th_table' => 'Document Type',
													'dt_column' => 'document_type',
													'db_column' => 'document_type',
													'td_orderable' => 'true',
													'td_width' => '20%'
												)
											);
											
			$this->data['datatable_setting'] = array(
													'processing' 	=> 'true',
													'searching' 	=> 'false',
													'autoWidth' 	=> 'false',
													'lengthChange'	=> 'false',
													'order'			=> array('column' => '0', 'value' => 'desc'),
													'pageLength'	=> '20'
												);
			
			$this->data['page_title'] = 'Bar Code Listing';
			
			$this->load->view('admin/includes/header', $this->data);
			$this->load->view('admin/bar_code/bar_code_listing', $this->data);
			
		}
		/*---- end: bar_code_listing function ----*/
		
		
		
		/*
		|------------------------------------------------
		| start: get_listing function
		|------------------------------------------------
		|
		| This function get receipt listing
		|
		*/
		function get_listing() {
        
			//echo '<pre>'; print_r($this->input->post()); die();
			
			$dt_datatable = json_decode($this->input->post('dt_datatable'));
			$action_btn_array = json_decode($this->input->post('action_btn'));
			
			$db_where		= array();
			$db_or_where	= array();
			$db_where_in	= array();
			$db_limit       = array();
			$db_order       = array();
			$db_select		= "COUNT(gb.generated_date) as count, 'count' AS generated_date";
			
			
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
					$db_order[$key]['title']    = $dt_datatable[$get_order['column']]->db_column;
					$db_order[$key]['order_by'] = $get_order['dir'];
				}            
			}
			// end: get data order by
			
			
			/***** start: top search data by equal to *****/
			if($this->input->post('top_search')) {
				foreach($this->input->post('top_search') as $key => $search_val) {
					if(preg_match('/gb/', $key)) {

						$search_key = substr($key, 3);
						
						/*$search_key = explode('_', $search_key);
						$new_search_key = '';
						foreach($search_key as $get_search_key) {
							$new_search_key = $new_search_key.ucfirst($get_search_key);
						}*/
						
						if($search_val!="")
							$db_where['gb.'.$search_key]  = $search_val;

					}
				}
			}
			// end: top search data by equal to
			
			//echo '<pre>'; print_r($db_where); die();
			
			
			/***** start: top search data by like *****/
			if($this->input->post('top_search_like')) {
				foreach($this->input->post('top_search_like') as $key => $search_val) {
					if(preg_match('/gb/', $key)) {

						$search_key = substr($key, 3);
						
						/*$search_key = explode('_', $search_key);
						$new_search_key = '';
						foreach($search_key as $get_search_key) {
							$new_search_key = $new_search_key.ucfirst($get_search_key);
						}*/
						
						if($search_val!="")
							$db_or_where['gb.'.$search_key . ' LIKE']  = '%' . $search_val . '%';

					}
				}
			}
			// end: top search data by like
			
			
			
			/***** start: search data by like (datatable) *****/
			$search = $this->input->post('search');
			
			if($search['value'] != '') {
				foreach($dt_datatable as $get_dt_datatable) {
					if(!empty($get_dt_datatable->db_column)) {
						$db_or_where[$get_dt_datatable->db_column . ' LIKE']   = '%' . $search['value'] . '%';
					}
				}
			}
			// end: search data by like (datatable)
			
			
			$dataRecord = $this->bar_code_model->get_bar_code($db_where, $db_or_where, $db_where_in, $db_limit, $db_order, null);
			$dataCount = $this->bar_code_model->get_bar_code($db_where, $db_or_where, $db_where_in, null, null, $db_select);
			$dataCount = $dataCount['count']['count'];
						
			
			$data = array();
			$i = 0;
			
			if($dataRecord) {
				
				foreach($dataRecord as $key => $value) {	
					foreach($dt_datatable as $get_dt_datatable) {
						
						if($get_dt_datatable->dt_column == 'viewButton') {
							
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
						else{
							$data[$i][] = $value[$get_dt_datatable->dt_column];
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
		
		
	}	