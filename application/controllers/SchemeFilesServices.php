<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class SchemeFilesServices extends CI_Controller {
	
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
	
	
	public function get_scheme_details() {
		
		if(isset($_POST['doc_id'])){
			$this->common->checkRequest();
		}
		else{
			$this->common->checkRequestScheme();
		}	
        
		$returnData = $this->services_model->get_scheme($_POST);
		
		if($returnData)
			$statusCode = 1;
		else
			$statusCode = 0;
		
        $this->common->setReturnCode($statusCode, ($returnData));
		
    }
	
}