<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Header_footer_setting extends CI_Controller {
    
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

        // load product model
        $this->load->model('admin/Header_footer_setting_model');
        
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
		$this->data['menu_sections'] = $this->general_model->get_menu_section_data();
		$this->data['receipt_inbox'] = $this->general_model->get_receipt_inbox_count($this->data['sub_menu']);
		$this->data['file_inbox'] = $this->general_model->get_file_inbox_count($this->data['sub_menu']);

        // Get Dynamic Menus
        $this->data['get_menu'] = $this->menu_model->get_menu();
        
            
        
        
        $this->cat_tree = array();
        
    }
    
    
    /*
    |------------------------------------------------
    | start: index function
    |------------------------------------------------
    |
    | This function work setting of front side
    |
   */
    function index() {
        
        if($this->input->post()) {
            
            $file_name = $this->logo_upload();
            $this->Header_footer_setting_model->add_general_setting($file_name);
            $this->session->set_flashdata('message', '<p class="status_msg">Setting Updated Successfully.</p>');
            $this->session->set_userdata('header_image', $file_name);
            $this->session->set_userdata('footer_message', $this->input->post('copyright'));
            redirect('admin/Header_footer_setting');
        }
        
        // unshift crumb
        $this->breadcrumbs->unshift('Header / Footer Setting', base_url().'admin/Header_footer_setting');
        
        $this->data['general_setting'] = $this->Header_footer_setting_model->get_setting(array('hf.header_footer_id'), array('1'));
        $this->data['message'] = (!isset($this->data['message'])) ? $this->session->flashdata('message') : $this->data['message'];
        $this->data['page_title'] = 'Header Footer Setting';
        
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/header_footer/general_setting', $this->data);
        //echo '<pre>'; print_r($this->data['general_setting']); die;
        
    }
    /*---- end: index function ----*/
    
    
    /*
    |------------------------------------------------
    | start: logo_upload function
    |------------------------------------------------
    |
    | This function upload logo
    |
   */
    function logo_upload() {
        
        /*
        |--------------------------------------------------------------------------
        | start: UPLOAD PROFILE IMAGE AND RESIZE
        |--------------------------------------------------------------------------
        */
        $this->load->library('upload');
        $this->load->library('image_lib');

        $upload_conf = array(
                            'upload_path'   => 'upload',
                            'allowed_types' => 'gif|jpg|png',
                            'max_size'      => '30000',
                        );

        $this->upload->initialize( $upload_conf );
        
        if(!$this->upload->do_upload('website_logo')) {
            // if image not upload
            if($this->input->post('website_logo_old'))
               return $this->input->post('website_logo_old');
            else
                return null;
        }
        else {
            
            if($this->input->post('website_logo_old'))
                unlink('upload/' . $this->input->post('website_logo_old'));
            
            // otherwise, put the upload datas here.
            // if you want to use database, put insert query in this loop
            $upload_data = $this->upload->data();

            $infoFile = new SplFileInfo($upload_data['file_name']);
            $extension = $infoFile->getExtension();

            // create new file name
            $full_name = str_replace(" ", "-", $this->input->post('first_name'));
            $new_file_name = 'header_image-' . date('Ymd') . '.' . $extension;
            
            /***************************
                SET LOGO IMAGE SIZE
            ***************************/
            list($width, $height, $type, $attr) = getimagesize($upload_data['full_path']);                                  
            $newWidth = 576;
            $newHeight = ceil($height/$width*$newWidth);

            // set the resize config
            $resize_conf = array(
                    // it's something like "/full/path/to/the/image.jpg" maybe
                    'source_image'  => $upload_data['full_path'], 
                    // and it's "/full/path/to/the/" + "thumb_" + "image.jpg
                    // or you can use 'create_thumbs' => true option instead
                    'new_image'     => $upload_data['file_path'] . $new_file_name,
                    'width'         => $newWidth,
                    'height'        => $newHeight
                );

            // initializing
            $this->image_lib->initialize($resize_conf);

            // do it!
            if ( ! $this->image_lib->resize()) {
                // if got fail.
                $data['resize'][] = $this->image_lib->display_errors();
            }
            else {
                // otherwise, put each upload data to an array.
                unlink($upload_data['full_path']);
                return $new_file_name;
            }
            
        }
        
    }
    /*---- end: logo_upload function ----*/
    
}