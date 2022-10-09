<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fileupload extends CI_Controller {

    function __construct() {
        parent::__construct();
        
        // Load required CI libraries and helpers.
        $this->load->database();
    }

    function index($module_type, $date_para = null) {
        
        $options = array('module_type' => $module_type, 'date_para' => $date_para);        
        $this->load->library("CustomUploadHandler", $options);
        
    }

}
