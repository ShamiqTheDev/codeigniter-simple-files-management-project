<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class FilesSyncCloud extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		// load services and scanning model
		$this->load->model('services_model');
		
		// load library
		$this->load->library('Common');
		//$this->load->library('Common_ajax');
		
		$this->auth = new stdClass;
		
		// Load 'standard' flexi auth library by default.
        $this->load->library('flexi_auth');
	
    }
	
	
	public function set_sync_file_details() {
		
		//echo json_encode($_SERVER); die();
		
		$this->common->checkRequest();
        $entityBody = file_get_contents("php://input");
		
		$returnData = $this->services_model->sync_scanning_details(json_decode($entityBody));
		
        $this->common->setReturnCode('0', ($returnData));
		
    }
	
	function set_sync_file_upload() {
	
		//echo json_encode($_POST['fileUploadedDate']); die();
	
		$this->common->checkRequest();
				
		list($day, $month, $year) = explode('-', date('d-m-Y', strtotime($_POST['fileUploadedDate'])));
		
		$upload_dir = $this->config->item("fileUploadPathCloud");
		
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
	
		$upload_dir = $upload_dir.$year.'/'.$month.'/'.$day.'/';
	
        $uploadFile = $upload_dir.basename($_FILES['fileContent']['name']);

        if(move_uploaded_file($_FILES['fileContent']['tmp_name'], $uploadFile)) {
            $returnData['error'] = $_FILES["fileContent"]["error"];
        } else {
            $returnData['error'] = true;
        }
		
		$this->common->setReturnCode('0', ($returnData));
	
	}
		
	public function set_sync_section_count() {
		
		//echo json_encode($_SERVER); die();
		
		$this->common->checkRequest();
        $entityBody = file_get_contents("php://input");
		
		//echo json_encode($entityBody); die();
		
		$returnData = $this->services_model->sync_section_count(json_decode($entityBody));
	
        $this->common->setReturnCode('0', ($returnData));
		
    }
	
	public function set_sync_section_count_history() {
		
		//echo json_encode($_SERVER); die();
		
		$this->common->checkRequest();
        $entityBody = file_get_contents("php://input");
		
		//echo json_encode($entityBody); die();
		
		$returnData = $this->services_model->sync_section_count_history(json_decode($entityBody));
	
        $this->common->setReturnCode('0', ($returnData));
		
    }
	
}