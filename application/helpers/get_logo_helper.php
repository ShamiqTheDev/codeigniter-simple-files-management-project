<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------
  | This Helper will check the cart having product or empty
  | -------------------------------------------------------------------
 */

function get_logo() {

    $CI = get_instance();
    $CI->load->model('admin/Header_footer_setting_model');
    $get_logo = $CI->Header_footer_setting_model->get_setting(array('hf.header_footer_id'), array('1'));
    $logo = $get_logo['header_image'];
    return $logo;
}

?>