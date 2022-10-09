<?php

/*
 * Function: checkRequest
 * Usage: Checks the request header which must be a POST and contain credentials
 * Returns: User object
 * Exception: Dies returning a JSON object with the error
 * Notes: Also connects to the database to read user information
 */

class Common {

    function checkRequest() {
	
		$CI = &get_instance();
		$CI->load->library('PasswordEncryption');		
		$CI->passwordencryption->setValues(NULL, $CI->config->item('inputKey'), $CI->config->item('blockSize'));		
		$CI->passwordencryption->setData($_SERVER['PHP_AUTH_PW']);
		$pass = $CI->passwordencryption->decrypt();
	
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            // The request is using the POST method
            die($this->setReturnCode('405', ""));
        }
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            die($this->setReturnCode('401', ""));
        }
        if ($_SERVER['PHP_AUTH_USER'] == '') {
            die($this->setReturnCode('402', ''));
        }
		
        // verify the user from database
        $user_auth = $CI->services_model->verify_password($_SERVER['PHP_AUTH_USER'], $pass);
		
        if($user_auth==NULL){
            die($this->setReturnCode('404', ""));
        }
    }
	
	
	
	function checkRequestScheme() {
	
		$CI = &get_instance();
		$CI->load->library('PasswordEncryption');		
		$CI->passwordencryption->setValues(NULL, $CI->config->item('inputKey'), $CI->config->item('blockSize'));		
		$CI->passwordencryption->setData($_SERVER['HTTP_PASSWORD']);
		$pass = $CI->passwordencryption->decrypt();
		
		//echo json_encode($_SERVER); die();
	
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            // The request is using the POST method
            die($this->setReturnCode('405', ""));
        }
        if (!isset($_SERVER['HTTP_USERNAME'])) {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            die($this->setReturnCode('401', ""));
        }
        if ($_SERVER['HTTP_USERNAME'] == '') {
            die($this->setReturnCode('402', ''));
        }
		
        // verify the user from database
        $user_auth = $CI->services_model->verify_password($_SERVER['HTTP_USERNAME'], $pass);
		        
        if($user_auth==NULL){
            die($this->setReturnCode('404', ""));
        }
    }
	
	

    /*
     * Function: setReturnCode
     * Usage: Create a standard JSON object which would be returned to caller
     * Returns:
     */

    function setReturnCode($statusCode, $data) {
        
        echo ( json_encode(array('statusCode' => intval($statusCode), 'statusDescription' => $this->getStatusDescription($statusCode), 'data' => $data)) );
        die();
    }

    /*
     * Function: getStatusDescription
     * Usage: Sets the descriptions for the return code
     * Returns: String
     */

    function getStatusDescription($statusCode) {
        $descriptions = array(
            '0' => 'No record found',
			'1' => 'Success',
            '401' => 'Login header missing',
            '402' => 'Login credentials missing',
            '403' => 'User cannot perform this functions',
            '404' => 'User/Password not found',
            '405' => 'HTTP Method not allowed',
            '406' => 'User is blocked',
            '501' => 'Database connection error',
            '502' => 'Database authentication failed',
            '503' => 'Table error',
            '504' => 'Failed to load configuration file',
            '601' => 'Unknown security check',
            '602' => 'Missing required parameter',
            '603' => 'Required parameter has blank value',
            '604' => 'Record already Exists'
        );
        return( $descriptions[$statusCode] );
    }

}
