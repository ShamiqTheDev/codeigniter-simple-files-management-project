<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ScanningFilesServices extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		// load services and scanning model
		$this->load->model('services_model');
		
		// load library
		$this->load->library('RestService');
		$this->load->library('Common');
		
		$this->auth = new stdClass;
		
		// Load 'standard' flexi auth library by default.
        $this->load->library('flexi_auth');
	
    }
	
	
	public function get_table_details() {
				
		$this->common->checkRequest();
		//$this->common->checkRequestScheme();
        
		$returnData = $this->services_model->get_table_details($_POST);
		
		if($returnData)
			$statusCode = 1;
		else
			$statusCode = 0;
		
        $this->common->setReturnCode($statusCode, ($returnData));
		
    }
	
	
	public function get_file_details() {
		
		$this->common->checkRequest();
        
		$returnData = $this->services_model->get_file_details($_POST);
		
		if($returnData)
			$statusCode = 1;
		else
			$statusCode = 0;
		
        $this->common->setReturnCode($statusCode, ($returnData));
		
    }
	
	
	function scanning_file_uploaded() {
		
		$this->common->checkRequest();
	
		$returnData = $this->services_model->scanning_file_uploaded();
				
		// load scanning model
        $this->load->model('admin/scaning_model');
		
		$section_count_data = array(
								'section_id' 	=> $_POST['section_id'],
								'file_type_id'	=> $_POST['file_type_id'],
								'file_count' 	=> $returnData,
							);
		
		$this->scaning_model->update_section_count($section_count_data);
		
		if($returnData)
			$statusCode = 1;
		else
			$statusCode = 0;
		
        $this->common->setReturnCode('0', ($returnData));
		
    }
	
	
	function notification_upload() {
	
		$this->load->view('admin/scaning/notification_upload', $this->data);
	
	}
	
	
	function upload_file_details() {
		
		$fileData = $_POST;
		
		$employee[0] = array('employee_name' => 'Zia', 'employee_cnic' => '123456789');
		$employee[1] = array('employee_name' => 'Waris', 'employee_cnic' => '987654321');
		
		$fileData['employee'] = json_encode($employee);
		
		// Change $_FILES to new vars and loop them
		foreach($_FILES['file'] as $key => $val) {
			foreach($val as $inKey => $v) {
				$field_name = $inKey;
				$_FILES[$field_name][$key] = $v; 
			}
		}
	
		// Unset the useless one ;)
		unset($_FILES['file']);
		
		$eFileScanningUploadUrl	= $this->config->item("eFileScanningUpload_server_cloud");
		$headers = array("user" => $this->config->item("eFileScanningUpload_user"), "pass" => $this->config->item("eFileScanningUpload_password"));
		
		$responseData = array();
		//$maintainLogsAction = 'WebServicesScanningFiles';
		$maintainLogsAction = null;
		
		if($_FILES) {
			foreach($_FILES as $key => $getFileDetail) {
			
				if($getFileDetail['error'])
					continue;
		
				$fileData[$key] = new CURLFile($getFileDetail['tmp_name'], $getFileDetail['type'], $getFileDetail['name']);
			}
			
			$fileUploadResponse = json_decode($this->restservice->file_uploading_function($eFileScanningUploadUrl, $headers, $fileData, $maintainLogsAction));
		}
		
		//echo json_encode($fileUploadResponse);
		
		echo '<pre>'; print_r($fileUploadResponse); die();
	
	}
        
        public function get_emp_efiles() {

        $this->common->checkRequest();
        $entityBody = file_get_contents("php://input");
        $returnData = $this->services_model->get_emp_efiles(json_decode($entityBody));
        if ($returnData)
            $statusCode = 1;
        else
            $statusCode = 0;
        $this->common->setReturnCode($statusCode, ($returnData));
    }
	
}