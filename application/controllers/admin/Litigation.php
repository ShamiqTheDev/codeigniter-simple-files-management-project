<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Litigation extends CI_Controller {
    
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
		
		// load litigation model
		$this->load->model('admin/litigation_model');
		
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
	| start: index function 
	|------------------------------------------------
	|
	| This function show cases listing
	|
	*/
	function index() {
		
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged)) {
				$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to this listing.</p>');
				if($this->flexi_auth->is_admin())
					redirect('auth_admin');
				else
					redirect('auth_public');
		}
	
		// unshift crumb
		$this->breadcrumbs->unshift('Litigation', base_url() . 'admin/litigation/');
		$this->breadcrumbs->unshift('Litigation', '#');
		

		
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
												'th_table' => 'Case No.',
												'dt_column' => 'caseNo',
												'db_column' => 'caseNo',
												'db_order_column' => 'caseNo',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),
											array(
												'th_table' => 'Ground',
												'dt_column' => 'ground',
												'db_column' => 'ground',
												'db_order_column' => 'ground',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),
											array(
												'th_table' => 'Related to',
												'dt_column' => 'relatedTo',
												'db_column' => 'relatedTo',
												'db_order_column' => 'relatedTo',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),
											
											array(
												'th_table' => 'Case Date',
												'dt_column' => 'caseDate',
												'db_column' => 'caseDate',
												'db_order_column' => 'caseDate',
												'td_orderable' => 'true',
												'td_width' => '25%'
											),
											array(
												'th_table' => 'Hearing Date',
												'dt_column' => 'hearingDate',
												'db_column' => 'hearingDate',
												'db_order_column' => 'hearingDate',
												'td_orderable' => 'true',
												'td_width' => '10%'
											),
											array(
												'th_table' => 'Action',
												'dt_column' => 'viewButton',
												'db_column' => '',
												'td_orderable' => 'false',
												'td_width' => '10%'
											),

										);
										
		$this->data['datatable_setting'] = array(
												'processing' 	=> 'true',
												'searching' 	=> 'true',
												'autoWidth' 	=> 'false',
												'lengthChange'	=> 'false',
												'order'			=> array('column' => '2', 'value' => 'desc'),
												'pageLength'	=> '20'
											);
	

		$this->data['action_btn'] = json_encode(
			array(
				'view' => array(
					'url' => 'admin/litigation/view',
					'icon_class' => 'fa fa-eye',
				),
				'edit' => array(
					'url' => 'admin/litigation/edit',
					'icon_class' => 'fa fa-pencil',
				),
			)
		);
		$this->data['page_title'] = 'Litigations';
		// $this->data['view_type'] = 'litigations';
		
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/litigation/cases_listing', $this->data);
		
	}
	/*---- end: index function ----*/


	/*
	|------------------------------------------------
	| start: get_listing function
	|------------------------------------------------
	|
	| This function get files listing
	|
	*/
	function get_listing() {
		$dt_datatable = $this->input->post('dt_datatable');
		$action_btn_array = json_decode($this->input->post('action_btn'));					
		$count_column_name = 'caseId';
		$db_where		= array();
		$db_or_where	= array();
		$db_where_in	= array();
		$db_limit       = array();
		$db_order       = array();
		$db_select		= "COUNT(c.caseId) as count, 'count' AS ".$count_column_name;
		
		
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
				// if(preg_match('/fd/', $key)) {

				// 	$search_key = substr($key, 3);
					
				// 	$search_key = explode('_', $search_key);
				// 	$new_search_key = '';

				// 	foreach($search_key as $get_search_key) {
				// 		$new_search_key = $new_search_key.ucfirst($get_search_key);
				// 	}
					
				// 	if($search_val!="")
				// 		$db_where['fd.'.$new_search_key]  = $search_val;

				// }
				
				// if(preg_match('/fs/', $key)) {

				// 	$search_key = substr($key, 3);
					
				// 	$search_key = explode('_', $search_key);
				// 	$new_search_key = '';
				// 	foreach($search_key as $get_search_key) {
				// 		$new_search_key = $new_search_key.ucfirst($get_search_key);
				// 	}
					
				// 	if($search_val!="")
				// 		$db_where['fs.'.$new_search_key]  = $search_val;

				// }
			}
		}
		// end: top search data by equal to
		
		/***** start: top search data by like *****/
		if($this->input->post('top_search_like')) {
			foreach($this->input->post('top_search_like') as $key => $search_val) {
				// if(preg_match('/fd/', $key)) {

				// 	$search_key = substr($key, 3);
					
				// 	$search_key = explode('_', $search_key);
				// 	$new_search_key = '';
				// 	foreach($search_key as $get_search_key) {
				// 		$new_search_key = $new_search_key.ucfirst($get_search_key);
				// 	}
					
				// 	if($search_val!="")
				// 		$db_or_where['fd.'.$new_search_key . ' LIKE']  = '%' . $search_val . '%';

				// }
				
				// if(preg_match('/fs/', $key)) {

				// 	$search_key = substr($key, 3);
					
				// 	$search_key = explode('_', $search_key);
				// 	$new_search_key = '';
				// 	foreach($search_key as $get_search_key) {
				// 		$new_search_key = $new_search_key.ucfirst($get_search_key);
				// 	}
				// 	if($search_val!="" && $new_search_key!='CreatedDate'){
				// 		$db_or_where['fs.'.$new_search_key . ' LIKE']  = '%' . $search_val . '%';
				// 	}
				// 	else if($search_val!="" && $new_search_key == 'CreatedDate'){
				// 		$db_or_where['fs.'.$new_search_key . ' LIKE'] =  '%' . date('Y-m-d', strtotime($search_val)) . '%';
				// 	}

				// }
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
		
		
		$dataRecord = $this->litigation_model->get_cases($db_where, $db_or_where, $db_where_in, $db_limit, $db_order, null, $view_type);
		// dd($dataRecord);
		$dataCount = $this->litigation_model->get_cases($db_where, $db_or_where, $db_where_in, null, null, $db_select, $view_type);
		// debug($dataCount);
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
								$button_url = ($action_btn->url) ? base_url().''.$action_btn->url.'/'.$value['caseId'] : 'javascript:void(0)';
								$button_html .= '<a href="'.$button_url.'" class="btn btn-xs btn-primary text-center" title="" action-recipt-id="'.$value['fileId'].'"><i class="'.$action_btn->icon_class.'"></i></a>';
							}
							$data[$i][] .= $button_html;
						}
	
					}
					else if($get_dt_datatable['dt_column'] == 'hardCopyReceived') {
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

					else if(($get_dt_datatable['dt_column'] == 'hearingDate' || $get_dt_datatable['dt_column'] == 'caseDate') && $value[$get_dt_datatable['dt_column']] != NULL) {

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
						$data[$i][] = $value[$get_dt_datatable['dt_column']];
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
	/*---- end: get_listing function ----*/
	
	/*
    |------------------------------------------------
    | start: create function
    |------------------------------------------------
    |
    | Load view of create file
    |
	*/
	function create($case_id = null) {
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged)) {
			$this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to this section.</p>');
			if($this->flexi_auth->is_admin())
				redirect('auth_admin');
			else
				redirect('auth_public');
		}

		if($case_id) {
			$action = 'update';
			$db_where = array("c.caseId" => $case_id);
			$this->data['file_data'] = $this->files_model->get_file($db_where);
			$this->data['case_id'] = $case_id;
		}
		else {
			$action = 'added';
		}
		// unshift crumb
		$this->breadcrumbs->unshift('Create Litigation', base_url() . 'admin/litigation/create/');
		$this->breadcrumbs->unshift('Litigation', '#');
		
		$random_number = rand(1000,100000);

		if($this->input->post() && $this->input->post('submit_check_session') == $this->session->userdata('submit_check_session')) {
			
			$post = $this->input->post();
			
			$this->load->library('form_validation');
			//Petitioner validation
			$this->form_validation->set_rules('members[0][memberFirstName]', 'Petitioner First Name', 'required');
			$this->form_validation->set_rules('members[0][memberLastName]', 'Petitioner Last Name', 'required');
			$this->form_validation->set_rules('members[0][designationId]', 'Petitioner Designation', 'required');
			//Respondent validation
			$this->form_validation->set_rules('members[1][memberFirstName]', 'Respondent First Name', 'required');
			$this->form_validation->set_rules('members[1][memberLastName]', 'Respondent Last Name', 'required');
			$this->form_validation->set_rules('members[1][designationId]', 'Respondent Designation', 'required');

			$this->form_validation->set_rules('court', 'Court Type', 'required');
			$this->form_validation->set_rules('caseNo', 'Case Number', 'required');

			$this->form_validation->set_rules('ground', 'Ground', 'required');
			$this->form_validation->set_rules('relatedTo', 'Related to', 'required');

			$this->form_validation->set_rules('caseDate', 'Case Date', 'required');
			$this->form_validation->set_rules('hearingDate', 'Case Hearing date', 'required');

			$this->form_validation->set_rules('memo', 'Memo subject', 'required');
							
			if($this->form_validation->run()) {
				$case_id = $this->litigation_model->create_case($case_id);
				if($case_id) {
					$images = $this->upload_attachment($case_id);
					$this->session->set_flashdata('message', '<p class="status_msg">Litigation has been '.$action.' Successfully</p>');
					$this->session->set_userdata('submit_check_session',$random_number);    
        			$this->data['submit_check_session'] = $random_number ;
        			// add redirection url
					redirect('admin/litigation/');
				}
			}

		}

		$this->session->set_userdata('submit_check_session',$random_number);    
        $this->data['submit_check_session'] = $random_number ;
		
		// options
		$this->data['courts_ops'] = $this->config->item('courts', 'options');
		$this->data['doc_type_ops'] = $this->config->item('doc_types', 'options');
		$this->data['designations'] = $this->general_model->get_designation();
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'Create Litigation';
				
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/litigation/create', $this->data);
			
	}
	/*---- end: create function ----*/
	

	
	
	/*
    |------------------------------------------------
    | start: upload_attachment function
    |------------------------------------------------
    |
    | upload attachment function
    |
	*/
	function upload_attachment($case_id= null) {
		$post = $this->input->post();
		
		$this->load->library('upload');
		
		$upload_dir = $this->config->item('litigationPath');
		
		$upload_config = array(
							'upload_path'   => $upload_dir,
							'allowed_types' => 'jpg',
							'max_size'      => $this->config->item('allowedFileSize'),
						);

		$this->upload->initialize($upload_config);
		
		// Change $_FILES to new vars and loop them
		foreach($_FILES['document'] as $key => $val) {
			foreach($val as $inKey => $v) {
				$field_name = $inKey;
				$_FILES[$field_name][$key] = $v; 
			}
		}
		// Unset the useless one ;)
		unset($_FILES['document']);

		$document_data = [];
		foreach($_POST['docs'] as $key => $val) { // formatting data
			foreach($val as $inKey => $v) {
				$field_name = $inKey;
				$document_data[$field_name][$key] = $v; 
			}
		}

		$n = 0;
		// main action to upload each file
		foreach($_FILES as $field_name => $file) {
			if(!$this->upload->do_upload($field_name)) {
				$this->session->set_flashdata('message', '<p class="error_msg">'.$this->upload->display_errors().'</p>');
			}
			else { 
				// if you want to use database, put insert query in this loop
				$case_docs_data = $document_data[$n];

				$case_docs_id = $this->litigation_model->create_case_docs($case_id,$case_docs_data);
				$upload_data = $this->upload->data();

				$this->litigation_model->upload_attachment($case_id, $case_docs_id, $upload_data);
			}
			$n++;
		}	
	}
	/*---- end: upload_attachment function ----*/


	function view($case_id = null) {
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged)) {
            $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to scheme document.</p>');
            if($this->flexi_auth->is_admin())
                redirect('auth_admin');
            else
                redirect('auth_public');
        }
		
		// unshift crumb
		$this->breadcrumbs->unshift('Case Details ', base_url().'admin/litigation/view/'.$case_id);

		if(!$case_id) {
			redirect('/admin/litigation/');
		}
		$db_where = array('c.caseId'=>$case_id);
		$this->data['data'] = $this->litigation_model->get_case_details($db_where);
		$this->data['documents'] = $this->litigation_model->get_case_docs($case_id)?
										$this->litigation_model->get_case_docs($case_id):[];

        $this->data['page_title'] = 'Case Details';
		
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/litigation/litigation_detail_view', $this->data);
		
	}


	/*
    |------------------------------------------------
    | start: edit function
    |------------------------------------------------
    |
    | Load view of edit file
    |
	*/
	function edit($case_id = null) {
		// Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
		if (! $this->flexi_auth->is_privileged($this->uri_privileged)) {
			$this->session->set_flashdata('message', 
				'<p class="error_msg">You do not have access privileges to this section.</p>');
			if($this->flexi_auth->is_admin())
				redirect('auth_admin');
			else
				redirect('auth_public');
		}

		$bcrumb = 'Create Litigation';
		$bcrumb_url = 'admin/litigation/create/';
		if($case_id) {
			$bcrumb = 'Update Litigation';
			$bcrumb_url = current_url();
			$action = 'update';
			$db_where = array('c.caseId'=>$case_id);
			$this->data['data'] = $this->litigation_model->get_case_details($db_where);
			$this->data['case_id'] = $case_id;
			$this->data['documents'] = $this->litigation_model->get_case_docs($case_id)?
											$this->litigation_model->get_case_docs($case_id):[];
		
		}

		// unshift crumb
		$this->breadcrumbs->unshift($bcrumb, base_url() . $bcrumb_url);
		$this->breadcrumbs->unshift('Litigation', '#');
		
		$random_number = rand(1000,100000);

		if($this->input->post() && 
			$this->input->post('submit_check_session') == $this->session->userdata('submit_check_session')) {
			$post = $this->input->post();
			
			$this->load->library('form_validation');
			
			//Petitioner validation
			$this->form_validation->set_rules('members[0][memberFirstName]', 'Petitioner First Name', 'required');
			$this->form_validation->set_rules('members[0][memberLastName]', 'Petitioner Last Name', 'required');
			$this->form_validation->set_rules('members[0][designationId]', 'Petitioner Designation', 'required');

			//Respondent validation
			$this->form_validation->set_rules('members[1][memberFirstName]', 'Respondent First Name', 'required');
			$this->form_validation->set_rules('members[1][memberLastName]', 'Respondent Last Name', 'required');
			$this->form_validation->set_rules('members[1][designationId]', 'Respondent Designation', 'required');

			$this->form_validation->set_rules('court', 'Court Type', 'required');
			$this->form_validation->set_rules('caseNo', 'Case Number', 'required');

			$this->form_validation->set_rules('ground', 'Ground', 'required');
			$this->form_validation->set_rules('relatedTo', 'Related to', 'required');

			$this->form_validation->set_rules('caseDate', 'Case Date', 'required');
			$this->form_validation->set_rules('hearingDate', 'Case Hearing date', 'required');

			$this->form_validation->set_rules('memo', 'Memo subject', 'required');
							
			if($this->form_validation->run()) {
				$case_id = $this->litigation_model->create_case($case_id);
				if(!empty($_FILES) && $case_id) {
					$images = $this->upload_attachment($case_id);
					$this->session->set_flashdata('message', '<p class="status_msg">Litigation has been '.$action.' Successfully</p>');
					$this->session->set_userdata('submit_check_session',$random_number);    
        			$this->data['submit_check_session'] = $random_number ;
					redirect('admin/litigation/');
				}
			}
		}

		$this->session->set_userdata('submit_check_session',$random_number);    
        $this->data['submit_check_session'] = $random_number ;
		
		// options
		$this->data['courts_ops'] = $this->config->item('courts', 'options');
		$this->data['doc_type_ops'] = $this->config->item('doc_types', 'options');
		$this->data['designations'] = $this->general_model->get_designation();
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		$this->data['page_title'] = 'Create Litigation';
				
		$this->load->view('admin/includes/header', $this->data);
		$this->load->view('admin/litigation/create', $this->data);	
	}
	/*---- end: edit function ----*/
}