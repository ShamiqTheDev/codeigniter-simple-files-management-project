<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Reports extends CI_Controller {
    
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
		//echo "<pre>";print_r($user_data);exit;

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
		
		// load general model
        $this->load->model('admin/general_model');
		$this->load->model('admin/scaning_model');
		$this->data['menu_sections'] = $this->general_model->get_menu_section_data();
		$this->data['receipt_inbox'] = $this->general_model->get_receipt_inbox_count($this->data['sub_menu']);
		
        // Get Dynamic Menus
        $this->data['get_menu'] = $this->menu_model->get_menu();
                
    }
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
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to section wise report.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
	   
		// unshift crumb
		$this->breadcrumbs->unshift('Section wise Report', base_url() . 'admin/reports/section_wise_report/');
		
		
		$this->data['page_title'] = 'Section wise Report';
		
		$this->data['file_types'] = $this->general_model->get_file_types();
		//$this->data['sections'] = $this->general_model->get_section();
		if($this->input->post()){
		
			$this->load->library('form_validation');
			
			/*$this->form_validation->set_rules('ft_file_type_id', 'File Type', 'required');
			$this->form_validation->set_rules('section_id', 'Section', 'required');*/
			
			//if($this->form_validation->run()) {
				
				// if user not select any section_id then assign uacc_section_fk values
				$section_input_session = ($this->input->post('section_id') != "") ? $this->input->post('section_id') : $this->input->post('uacc_section_fk');
				
				$db_where = array('msc.fileTypeId' => $this->input->post('ft_file_type_id'),
								   'msc.sectionId' => $section_input_session);
				$this->data['sectionwise_reporting'] = $this->scaning_model->get_sectionwise_reporting($db_where);
				if($this->data['sectionwise_reporting']==FALSE){
					//$this->session->set_flashdata('message', '<p class="status_msg">No Records available.</p>');
					//redirect('admin/reports/section_wise_report/');
					$this->data['message'] = '<p class="status_msg">No Records available.</p>';
				}
			//}
		}
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		
		//$this->data['general_category_names'] = $this->scaning_model->get_general_category_name();
		
		if($this->input->post('generate_file') == 1) {
		
			// Report info
			$sheetArray['reportFile']    		= 'section_wise (' . date("Y-m-d h-i-s") . ').xlsx'; //'Report_' . date('Y-m-d') . '.xlsx';
			$sheetArray['setWidth'] 			= array(14, 14, 14, 14, 14, 18, 14);
			$sheetArray['sheetHeadingLabel']	= 'eFile Sectionwise Reporting';
			$sheetArray['sheetName'] 			= 'Section wise report';
			$sheetArray['reportDate']     		= date("d/M/Y");
		
			// Sheet columns heading
			$sheetArray['sheetHeading'] = array(
				'str_sectionName'       => 'Section',
				'str_fileType'          => 'File Type',
				'str_startDate'         => 'Start Date',
				'str_endDate'           => 'End Date',
				'num_totalFileCount'	=> 'Total Files',
				'num_fileScan'          => 'Total Scanned Files',
				'num_toBeScanned'       => 'To be Scanned',
			);
			
			// Sheet columns footer
			$sheetArray['sheetFooter'] = array(
				'column_1'       			=> 'mergeCells',
				'column_2'          		=> 'mergeCells',
				'column_3'         			=> '',
				'str_total'           		=> 'Total',
				'num_totalFileCountSum'		=> null,
				'num_pendingCountSum'       => null,
				'num_totalFilePendingSum'	=> null,
			);
			
			// Information Sheet
			$sheetArray['infoSheet'] = array(
						'Printed By' => $this->data['user_name'],
						'Printed Date' => $sheetArray['reportDate']
			);
			
			// Manage report data in array
			if($this->data['sectionwise_reporting']) {
				foreach($this->data['sectionwise_reporting'] as $key => $get_sectionwise_reporting) {
					$sheetArray['reportData'][$key]['sectionName'] = $this->data['session_section'][$get_sectionwise_reporting['sectionId']]['sectionName'];
					$sheetArray['reportData'][$key]['fileType'] = $this->data['file_types'][$get_sectionwise_reporting['fileTypeId']]['fileType'];
					$sheetArray['reportData'][$key]['startDate'] = ($get_sectionwise_reporting['startDate'] != '0000-00-00') ? date('d-m-Y',strtotime($get_sectionwise_reporting['startDate'])) : '-';
					$sheetArray['reportData'][$key]['endDate'] = ($get_sectionwise_reporting['endDate'] != '0000-00-00') ? date('d-m-Y',strtotime($get_sectionwise_reporting['endDate'])) : '-';
					$sheetArray['reportData'][$key]['totalFileCount'] = $get_sectionwise_reporting['totalFileCount'];
					$sheetArray['reportData'][$key]['fileScan'] = $get_sectionwise_reporting['fileScan'];
					$sheetArray['reportData'][$key]['toBeScanned'] = ($get_sectionwise_reporting['totalFileCount']-$get_sectionwise_reporting['fileScan']);
					
					
					$sheetArray['sheetFooter']['num_totalFileCountSum'] = $sheetArray['sheetFooter']['num_totalFileCountSum'] + $get_sectionwise_reporting['totalFileCount'];
					$sheetArray['sheetFooter']['num_pendingCountSum'] = $sheetArray['sheetFooter']['num_pendingCountSum']+ $get_sectionwise_reporting['fileScan'];
					$total_file_pending = ($get_sectionwise_reporting['totalFileCount']-$get_sectionwise_reporting['fileScan']);
					$sheetArray['sheetFooter']['num_totalFilePendingSum'] = $sheetArray['sheetFooter']['num_totalFilePendingSum'] +  $total_file_pending;
				}
			}
			
			$this->excel_generate($sheetArray);
			
			
		}
		else if($this->input->post('generate_file') == 2) {
		
			//$this->load->view('admin/reports/pdf_userwise_reporting', $this->data);
		
			//load the view and saved it into $html variable
			$html = $this->load->view('admin/reports/pdf_sectionwise_reporting', $this->data, true);

			//this the the PDF filename that user will get to download
			$pdfFilePath = "section_wise_".date('dmY').".pdf";

			//load mPDF library
			//$this->load->library('m_pdf');
			
			//generate the PDF from the given html
			$this->m_pdf->pdf->WriteHTML($html);

			//download it.
			$this->m_pdf->pdf->Output($pdfFilePath, "D");
			//$this->m_pdf->pdf->Output();
		
		}
		else {
			$this->load->view('admin/includes/header', $this->data);
			$this->load->view('admin/reports/view_sectionwise_reporting', $this->data);
		}
		
	}
	/*End of Reporting here */
	
	
	/*
    |------------------------------------------------
    | start: User Wise Report
    |------------------------------------------------
    |
    | User Wise View
    |
   */
   function user_wise_report() {
	   
	   // Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to user wise report.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
	   
   
		// Select user data to be displayed.
		$sql_select = array(
			$this->flexi_auth->db_column('user_acc', 'id'),
			$this->flexi_auth->db_column('user_acc', 'username'),
			'upro_first_name',
			'upro_last_name'
		);
		
		$this->flexi_auth->sql_select($sql_select);
						
		// SQL WHERE IN (Set as an array)
		//$sql_where_in = explode(',', $this->data['uacc_section_fk']);
		//$this->flexi_auth->sql_where_in('user_section.sectionId', $sql_where_in);
		
		//$sql_join = 'user_section';

		// SQL JOIN condition.
		//$sql_join_on = "FIND_IN_SET(user_section.sectionId,user_accounts.`uacc_section_fk`) > '0'";

		//$this->flexi_auth->sql_join($sql_join, $sql_join_on);
						
		// Get Only Active Users
		//$sql_where[$this->flexi_auth->db_column('user_acc', 'active').'='] = 1;
		$sql_where[$this->flexi_auth->db_column('user_acc', 'group_id').'='] = 5;
				
		$this->flexi_auth->sql_where($sql_where);

		$this->data['users'] = $this->flexi_auth->get_users_array();
   
   
		// unshift crumb
		$this->breadcrumbs->unshift('User wise Report', base_url() . 'admin/reports/user_wise_report/');
		
		
		$this->data['page_title'] = 'User wise Report';
		
		$this->data['file_types'] = $this->general_model->get_file_types();
		//$this->data['sections'] = $this->general_model->get_section();
		
		// if user not select any section_id then assign uacc_section_fk values
		$section_input_session = ($this->input->post('section_id') != "") ? $this->input->post('section_id') : $this->input->post('uacc_section_fk');
		
		if($this->input->post()){
			$db_where = array('fd.fileTypeId' => $this->input->post('ft_file_type_id'),
							   'us.sectionId' => $section_input_session,
							   'ua.uacc_id' => $this->input->post('user_id')
							   );  
		   
			if($this->input->post('start_date'))
				$db_where['fd.createdDate >= '] = date('Y-m-d', strtotime($this->input->post('start_date'))).' 00:00:00';
			
			if($this->input->post('end_date'))
				$db_where['fd.createdDate <= '] = date('Y-m-d', strtotime($this->input->post('end_date'))).' 23:59:59';
				
				
				//echo '<pre>'; print_r($db_where); die();
					
				//die('TEST');
			$this->data['userwise_reporting'] = $this->scaning_model->get_userwise_reporting($db_where);
			
			if($this->data['userwise_reporting']==FALSE){
				//$this->session->set_flashdata('message', '<p class="status_msg">No Records available.</p>');
				$this->data['message'] = '<p class="status_msg">No Records available.</p>';
			}
		}
		
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		
		//$this->data['general_category_names'] = $this->scaning_model->get_general_category_name();
		
		if($this->input->post('generate_file') == 1) {
		
			// Report info
			$sheetArray['reportFile']    		= 'user_wise (' . date("Y-m-d h-i-s") . ').xlsx'; //'Report_' . date('Y-m-d') . '.xlsx';
			$sheetArray['setWidth'] 			= array(35, 20, 20, 25);
			$sheetArray['sheetHeadingLabel']	= 'eFile Userwise Reporting';
			$sheetArray['sheetName'] 			= 'User wise report';
			$sheetArray['reportDate']     		= date("d/M/Y");
		
			// Sheet columns heading
			$sheetArray['sheetHeading'] = array(
				'str_userName'      => 'User',
				'str_sectionName'	=> 'Section',
				'str_fileType'      => 'File Type',
				'num_fileScaned'    => 'Number of Files Scanned',
			);
			
			// Sheet columns footer
			$sheetArray['sheetFooter'] = array(
				'column_1'       	=> 'mergeCells',
				'column_2'          => '',
				'str_total'           	=> 'Total',
				'num_fileScanedSum'	=> null,
			);
			
			// Information Sheet
			$sheetArray['infoSheet'] = array(
						'Printed By' => $this->data['user_name'],
						'Printed Date' => $sheetArray['reportDate']
			);
			
			// Manage report data in array
			if($this->data['userwise_reporting']) {
				foreach($this->data['userwise_reporting'] as $key => $get_userwise_reporting) {
					$sheetArray['reportData'][$key]['userName'] = $get_userwise_reporting['upro_first_name']." ".$get_userwise_reporting['upro_last_name']." (".$get_userwise_reporting['uacc_username'].")";
					$sheetArray['reportData'][$key]['sectionName'] = $this->data['session_section'][$get_userwise_reporting['sectionId']]['sectionName'];
					$sheetArray['reportData'][$key]['fileType'] = $this->data['file_types'][$get_userwise_reporting['fileTypeId']]['fileType'];
					$sheetArray['reportData'][$key]['fileScaned'] = $get_userwise_reporting['fileScan'];
					
					$sheetArray['sheetFooter']['num_fileScanedSum'] = $sheetArray['sheetFooter']['num_fileScanedSum']+ $get_userwise_reporting['fileScan'];
				}
			}
			
			$this->excel_generate($sheetArray);
			
			
		}
		else if($this->input->post('generate_file') == 2) {
		
			//$this->load->view('admin/reports/pdf_userwise_reporting', $this->data);
		
			//load the view and saved it into $html variable
			$html = $this->load->view('admin/reports/pdf_userwise_reporting', $this->data, true);

			//this the the PDF filename that user will get to download
			$pdfFilePath = "user_wise_".date('dmY').".pdf";

			//load mPDF library
			//$this->load->library('m_pdf');
			
			//generate the PDF from the given html
			$this->m_pdf->pdf->WriteHTML($html);

			//download it.
			$this->m_pdf->pdf->Output($pdfFilePath, "D");
			//$this->m_pdf->pdf->Output();
		}
		else {
			$this->load->view('admin/includes/header', $this->data);
			$this->load->view('admin/reports/view_userwise_reporting', $this->data);
		}
		
	}
	/*End of Report View */
/*
    |------------------------------------------------
    | start: All Uploaded Files Report
    |------------------------------------------------
    |
    | Report of All Uploaded Files
    |
   */
   /* function uploaded_files_report() {
	   
	   // Check user has privileges to this page, else display a message to notify the user they do not have valid privileges.
        if (! $this->flexi_auth->is_privileged($this->uri_privileged))
        {
                $this->session->set_flashdata('message', '<p class="error_msg">You do not have access privileges to user wise report.</p>');
                if($this->flexi_auth->is_admin())
                    redirect('auth_admin');
                else
                    redirect('auth_public');
        }
		// unshift crumb
		$this->breadcrumbs->unshift('Uploaded Files Report', base_url() . 'admin/reports/uploaded_files_report/');
		
		$this->data['page_title'] = 'Uploaded Files Report';
		
		$this->data['file_types'] = $this->general_model->get_file_types();
		
		// if user not select any section_id then assign uacc_section_fk values
		$section_input_session = ($this->input->post('section_id') != "") ? $this->input->post('section_id') : $this->input->post('uacc_section_fk');
		
		if($this->input->post()){
			$db_where = array('fd.fileTypeId' => $this->input->post('ft_file_type_id'),
							   'us.sectionId' => $section_input_session,
							   'ua.uacc_id' => $this->input->post('user_id')
							   );  
		   
			if($this->input->post('start_date'))
				$db_where['fd.createdDate >= '] = date('Y-m-d', strtotime($this->input->post('start_date'))).' 00:00:00';
			
			if($this->input->post('end_date'))
				$db_where['fd.createdDate <= '] = date('Y-m-d', strtotime($this->input->post('end_date'))).' 23:59:59';
				
				
				//echo '<pre>'; print_r($db_where); die();
					
				//die('TEST');
			$this->data['uploaded_files_reports'] = $this->scaning_model->get_uploaded_files_report($db_where);
			
			if($this->data['uploaded_files_reports']==FALSE){
				//$this->session->set_flashdata('message', '<p class="status_msg">No Records available.</p>');
				$this->data['message'] = '<p class="status_msg">No Records available.</p>';
			}
		}
		$this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
		
		//$this->data['general_category_names'] = $this->scaning_model->get_general_category_name();
		if($this->input->post('pdf_generate')) {
		
			//$this->load->view('admin/reports/pdf_userwise_reporting', $this->data);
		
			//load the view and saved it into $html variable
			$html = $this->load->view('admin/reports/pdf_uploaded_files_reporting', $this->data, true);

			//this the the PDF filename that user will get to download
			$pdfFilePath = "user_wise_".date('dmY').".pdf";

			//load mPDF library
			//$this->load->library('m_pdf');
			
			//generate the PDF from the given html
			$this->m_pdf->pdf->WriteHTML($html);

			//download it.
			$this->m_pdf->pdf->Output($pdfFilePath, "D");
			//$this->m_pdf->pdf->Output();
		}
		else {
			$this->load->view('admin/includes/header', $this->data);
			$this->load->view('admin/reports/view_uploaded_files_reporting', $this->data);
		}
	}*/
/*End of All Uploaded Files Report */	
	
	/*
    |------------------------------------------------
    | start: excel_generate
    |------------------------------------------------
    |
    | excel_generate
    |
	*/
	function excel_generate($sheetArray) {
	
		// Report info
		$sheetColumns	= array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		$sheetCount    	= 0;
		$row    		= 1; //row count
		$column 		= 0;
		$mergeCells 	= array();
		
		// Heading Style
		$headerStyle = [
					'font' => [
						'bold' => true,
						'color' => ['argb' => 'FFFFFF'],
					],
					'fill' => [
						'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
						'startColor' => [
							'argb' => '4caf50',
						],
					],
					'borders' => [
						'outline' => [
							'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
							'color' => ['argb' => 'FFFFFF'],
						],
					],
				];
				
		// Footer Style
		$footerStyle = [
					'font' => [
						'bold' => true,
					],
					'fill' => [
						'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
						'startColor' => [
							'argb' => 'f2f2f2',
						],
					],
					'borders' => [
						'outline' => [
							'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
							'color' => ['argb' => 'CCCCCC'],
						],
					],
				];
				
		// Report Data Style
		$reportDataStyle = [
					'borders' => [
						'outline' => [
							'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
							'color' => ['argb' => 'CCCCCC'],
						],
					],
				];
		
		// Title Style
		$titleStyle = [
					'font' => [
						'size' => 20,
						'name' => 'Cambria'
					],
					'alignment' => [
						'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
						'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
					]
				];
		
		$spreadSheet = new Spreadsheet();
		
		// Default Style Excel Sheet
		$spreadSheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(12);
		$spreadSheet->getDefaultStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF');
		$spreadSheet->getDefaultStyle()->getAlignment()->setWrapText(true);
		$spreadSheet->getDefaultStyle()->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
		$spreadSheet->getDefaultStyle()->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	
		// Create Excel Sheet
		$objWorkSheet = $spreadSheet->createSheet($sheetCount);

		// Page Setting and Style
		$objWorkSheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		$objWorkSheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
		$objWorkSheet->getPageSetup()->setHorizontalCentered(true);
		$objWorkSheet->getPageMargins()->setTop(0.5);
		$objWorkSheet->getPageMargins()->setRight(0.68);
		$objWorkSheet->getPageMargins()->setLeft(0.68);
		$objWorkSheet->getPageMargins()->setBottom(0.5);
		
		// Security setting excel not editable
		$objWorkSheet->getProtection()->setPassword('PhpSpreadsheet');
		$objWorkSheet->getProtection()->setSheet(true);
		$objWorkSheet->getProtection()->setSort(true);
		$objWorkSheet->getProtection()->setInsertRows(true);
		$objWorkSheet->getProtection()->setFormatCells(true);
		
		// Set Title Style and Setting
		$objWorkSheet->mergeCells($sheetColumns[0] . $row.':'.$sheetColumns[count($sheetArray['sheetHeading'])-1] . $row);
		$objWorkSheet->setCellValue($sheetColumns[0] . $row, $sheetArray['sheetHeadingLabel']);
		$objWorkSheet->getRowDimension($row)->setRowHeight(55);
		$objWorkSheet->getStyle($sheetColumns[0] . $row.':'.$sheetColumns[count($sheetArray['sheetHeading'])] . $row)->applyFromArray($titleStyle);
		//$row = $objWorkSheet->getHighestRow() + 2; //row count
		$row = $objWorkSheet->getHighestRow() + 1; //row count
		
		
		$objWorkSheet->setShowGridlines(true);
		$objWorkSheet->setTitle($sheetArray['sheetName']);
		
		
		// Set Information value in Cell with style and setting
		/*foreach($sheetArray['infoSheet'] as $infoKey => $getInfoSheet) {
			$objWorkSheet->setCellValue($sheetColumns[$column] . $row, $infoKey);
			$objWorkSheet->setCellValue($sheetColumns[$column+1] . $row, $getInfoSheet);
			$objWorkSheet->getRowDimension($row)->setRowHeight(20);
			$objWorkSheet->getStyle($sheetColumns[$column] . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
			$objWorkSheet->getStyle($sheetColumns[$column+1] . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
			$objWorkSheet->getStyle($sheetColumns[$column] . $row)->getFont()->setBold(true);
			
			$row++;
		}
		$row = $objWorkSheet->getHighestRow() + 2; //row count*/
		
		// set sheet column value
		$column = 0;

		// Set Heading value in Cell with style and setting
		foreach($sheetArray['sheetHeading'] as $headKey => $head) {
			$objWorkSheet->getStyle($sheetColumns[$column] . $row)->applyFromArray($headerStyle);
			$objWorkSheet->getStyle($sheetColumns[$column])->getAlignment()->setWrapText(true);
			$objWorkSheet->getColumnDimension($sheetColumns[$column])->setWidth($sheetArray['setWidth'][$column]);
			$objWorkSheet->getRowDimension($row)->setRowHeight(25);
			$objWorkSheet->setCellValue($sheetColumns[$column] . $row, $head);
			
			$column++;
		}
		$row = $objWorkSheet->getHighestRow() + 1; //row count

		// Set Report data value in Cell with style and setting
		foreach($sheetArray['reportData'] as $get_report_data) {
		
			// set sheet column value
			$column = 0;
			
			foreach($sheetArray['sheetHeading'] as $keyHead => $headTitle) {
			
				if(preg_match('/str_/', $keyHead))
					$objWorkSheet->getStyle($sheetColumns[$column] . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
			
				if(preg_match('/num_/', $keyHead))
					$objWorkSheet->getStyle($sheetColumns[$column] . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
			
				$keyHead = substr($keyHead, 4);
			
				$objWorkSheet->setCellValue($sheetColumns[$column] . $row, $get_report_data[$keyHead]);
				$objWorkSheet->getStyle($sheetColumns[$column] . $row)->applyFromArray($reportDataStyle);
				
				$column++;
			}
			
			$objWorkSheet->getRowDimension($row)->setRowHeight(25);
			
			$row++;
		}
		$row = $objWorkSheet->getHighestRow() + 1; //row count
		
		// set sheet column value
		$column = 0;
		
		// Set Footer data value in Cell with style and setting
		foreach($sheetArray['sheetFooter'] as $footKey => $foot) {
		
			if($foot === 'mergeCells') {
				$mergeCells[] = $sheetColumns[$column]; //. $row;
			}
			else {
				if($mergeCells) {
					$objWorkSheet->mergeCells(current($mergeCells).$row.':'.$sheetColumns[count($mergeCells)].$row);
					$objWorkSheet->setCellValue(current($mergeCells).$row, $foot);
					$objWorkSheet->getStyle(current($mergeCells).$row.':'.$sheetColumns[count($mergeCells)].$row)->applyFromArray($footerStyle);
					//$objWorkSheet->getStyle(current($mergeCells) . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
					$mergeCells = array();
				}
				else {
					$objWorkSheet->setCellValue($sheetColumns[$column] . $row, $foot);
					$objWorkSheet->getStyle($sheetColumns[$column] . $row)->applyFromArray($footerStyle);
				}
			}
			
			if(preg_match('/str_/', $footKey))
				$objWorkSheet->getStyle($sheetColumns[$column] . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
		
			if(preg_match('/num_/', $footKey))
				$objWorkSheet->getStyle($sheetColumns[$column] . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
			
			$objWorkSheet->getRowDimension($row)->setRowHeight(20);
			
			$column++;
		}
		
		// Set Page Printing Area
		$objWorkSheet->getPageSetup()->setPrintArea('A1:'.$sheetColumns[$column-1] . $row);
		
		
		$writer = new Xlsx($spreadSheet);
		header("Content-type: application/vnd.ms-excel");
		header('Content-Disposition: attachment;filename="' . $sheetArray['reportFile'] . '"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output'); // download file 
	
	}
	
}