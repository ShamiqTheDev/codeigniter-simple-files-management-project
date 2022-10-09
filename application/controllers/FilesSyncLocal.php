<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class FilesSyncLocal extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
		// load library
		$this->load->library('RestService');
		$this->load->library('PasswordEncryption');
		
		// load services and scanning model
		$this->load->model('services_model');
		//$this->load->model('admin/scaning_model');
	
    }
	
	
	function sync_file_details() {
	
		$dbWhere 	= array('fd.syncFile' => '0');
		$dbLimit	= '3';
		$dbSelect	= '*';
		$fileDetail	= $this->services_model->get_scanning($dbWhere, $dbLimit, $dbSelect);
		
		//echo '<pre>'; print_r($fileDetail); die();
				
		$this->passwordencryption->setValues($this->config->item("eFileSync_password"), $this->config->item("inputKey"), $this->config->item("blockSize"));
		
		$sourceLocationFileDetails	= $this->config->item("setSyncFileDetails");
		$sourceLocationFileUpload	= $this->config->item("setSyncFileUpload");
		$headers 		= array("user" => $this->config->item("eFileSync_user"), "pass" => $this->passwordencryption->encrypt());
		
		$responseData = array();
		$maintainLogsAction = 'WebServicesSynFile';
		
		if($fileDetail) {
			foreach($fileDetail as $getFileDetail) {
			
				$cFile = new CURLFile($this->config->item("fileApplicationPath").$getFileDetail['filePath'], $getFileDetail['fileType'], $getFileDetail['fileName']);

				// Assign FILE POST data
				$fileData = array('fileContent' => $cFile, 'fileUploadedDate' => $getFileDetail['fileUploadedDate']);
				$fileUploadResponse = json_decode($this->restservice->file_uploading_function($sourceLocationFileUpload, $headers, $fileData, $maintainLogsAction));
				
				if(!$fileUploadResponse->data->error) {
					$fileDetailResponse = $this->restservice->put($sourceLocationFileDetails, $headers, $getFileDetail, $maintainLogsAction);
					
					if($fileDetailResponse->data) {
						$this->services_model->update_scanning($fileDetailResponse->data);
					}
				}
				
				
				$responseData['fileUploadResponse'][] = $fileUploadResponse;
				$responseData['fileDetailResponse'][] = $fileDetailResponse;
			}
		}
		
		$responseData['sectionCount'] = $this->sync_section_count();
		
		echo json_encode($responseData);
		
		//echo '<pre>'; print_r($responseData); die();
	
	}
	
	
	function sync_section_count() {
	
		$this->passwordencryption->setValues($this->config->item("eFileSync_password"), $this->config->item("inputKey"), $this->config->item("blockSize"));
	
		$dbWhere 		= array('msc.syncFile' => '0');
		$sectionCount	= $this->services_model->get_section_count($dbWhere);
		
		$maintainLogsAction = 'WebServicesSynFile';
		$headers 		= array("user" => $this->config->item("eFileSync_user"), "pass" => $this->passwordencryption->encrypt());
		$sourceLocationSectionCount	= $this->config->item("setSyncSectionCount");
		
		$responseData = array();
		
		if($sectionCount) {
			$responseData['sectionCountResponse'] = $this->restservice->put($sourceLocationSectionCount, $headers, $sectionCount, $maintainLogsAction);
			
			if($responseData['sectionCountResponse']->data) {
				$this->services_model->update_section_count($responseData['sectionCountResponse']->data);
				$responseData['sectionCountHistoryResponse'] = $this->sync_section_count_history();
			}
		}
		
		return $responseData;
		
		//echo '<pre>'; print_r($responseData); die();
		
	}
	
	
	function sync_section_count_history() {
	
		$this->passwordencryption->setValues($this->config->item("eFileSync_password"), $this->config->item("inputKey"), $this->config->item("blockSize"));
	
		$dbWhere 		= array('msch.syncFile' => '0');
		$sectionCountHistory	= $this->services_model->get_section_count_history($dbWhere);
		
		$maintainLogsAction = 'WebServicesSynFile';
		$headers 		= array("user" => $this->config->item("eFileSync_user"), "pass" => $this->passwordencryption->encrypt());
		$sourceLocationSectionCountHistory	= $this->config->item("setSyncSectionCountHistory");
		
		$sectionCountHistoryResponse = array();
		
		if($sectionCountHistory) {
			$sectionCountHistoryResponse = $this->restservice->put($sourceLocationSectionCountHistory, $headers, $sectionCountHistory, $maintainLogsAction);
			
			if($sectionCountHistoryResponse->data) {
				$this->services_model->update_section_count_history($sectionCountHistoryResponse->data);
			}
		}
		
		return $sectionCountHistoryResponse;
		
		//echo '<pre>'; print_r($sectionCountHistoryResponse); die();
		
	}
	
	
}