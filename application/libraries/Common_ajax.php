<?php

/*
 * Function: checkRequest
 * Usage: Checks the request header which must be a POST and contain credentials
 * Returns: User object
 * Exception: Dies returning a JSON object with the error
 * Notes: Also connects to the database to read user information
 */

class Common_ajax {
    
    
    public function __construct(){
     $this->ci =& get_instance();
     $this->ci->load->library('PasswordEncryption');
     $this->ci->passwordencryption->setValues(NULL, $this->ci->config->item('inputKey'), $this->ci->config->item('blockSize'));
   }
    

    function checkRequest() {
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

        
        if(isset($_SERVER['PHP_AUTH_PW']) && $_SERVER['PHP_AUTH_PW'] != NULL){
            $this->ci->passwordencryption->setData($_SERVER['PHP_AUTH_PW']);
            $_SERVER['PHP_AUTH_PW'] = $this->ci->passwordencryption->decrypt();
        }
        
        
        // Get the user profile from the database
        $CI = &get_instance();
        $CI->load->model('services_model'); 
        $user_auth = $CI->services_model->user_authentication($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
        
        
        if($user_auth=="ERROR"){
            die($this->setReturnCode('503', ""));
        }
        
        
        if($user_auth==NULL){
            die($this->setReturnCode('404', ""));
        }else{
            
            // Check the password
            if ($user_auth[0]['Password'] != $user_auth[0]['EnteredPassword']) {
                die($this->setReturnCode('404', ''));
            }
            // Is user enabled?
            if ($user_auth[0]['IsEnabled'] != 1) {
                die($this->setReturnCode('406', ''));
            }
            
        }
        //$user = new User($row);
        // Everthing is fine
        //return($user);
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
            '0' => 'Success',
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
