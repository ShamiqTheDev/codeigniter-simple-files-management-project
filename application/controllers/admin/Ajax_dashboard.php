<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ajax_dashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
 		
		// To load the CI benchmark and memory usage profiler - set 1==1.
		if (1==2) 
		{
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
		if (! $this->flexi_auth->is_logged_in_via_password() || ! $this->flexi_auth->is_admin()) 
		{
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
		
		$this->data['user_designation'] = $user_data->ugrp_name;
		$this->data['user_name'] = $user_data->upro_first_name . ' ' . $user_data->upro_last_name;
		$this->data['uacc_section_fk'] = $user_data->uacc_section_fk;
		$this->data['uacc_group_fk'] = $user_data->uacc_group_fk;
		
		// load general model
        $this->load->model('admin/general_model');
		$this->load->model('admin/dashboard_model');
		
    }
	
	function getMonthWiseEntries(){
		if($this->input->post("id")){
			
			$year = $this->input->post("id");
			$months = array("January","February","March","April","May","June","July","August","September","October","November","December");
			
			$drilldown = $this->input->post("more_drilldown");
			$graph_type = $this->input->post("graph_type");
		
			$db_where = array('YEAR(createdDate)' => $year);
			$month_wise_count = $this->dashboard_model->get_stats('MONTH(createdDate)',$db_where,'file_detail');
		
			//echo "<pre>";print_r($month_wise_count);exit;
			
			foreach($months as $key=>$month){
				$month_key = $key+1;
				$month_wise_info[$key]["name"] =  $month;
				$month_wise_info[$key]["y"] =  isset($month_wise_count[$month_key]["ccount"]) ? (int) $month_wise_count[$month_key]["ccount"] : 0;
				$month_wise_info[$key]["drilldown"] =  ($drilldown == 1) ? $month_key : false;
				$month_wise_info[$key]["url_name"] = "getDayWiseEntries";
				$month_wise_info[$key]["id"] = $month_key."|".$year;
				$month_wise_info[$key]["more_drilldown"] = $drilldown;
				$month_wise_info[$key]["graph_type"] = $graph_type;
				$month_wise_info[$key]["title"] = "Months";
			}
			
			$return_data = array(
                array(
                    "name" => "Total Scanned Month wise",
					"data" => $month_wise_info,
                    "colorByPoint" => true,
                )
             );
			 
			 echo json_encode($return_data);
		}	
	}
	
	
	function getDayWiseEntries(){
		
		if($this->input->post("id")){
			
			$month_year = $this->input->post("id");
			list($month,$year) = explode("|" , $month_year);
			$days = range(1, 31);
			
			$drilldown = $this->input->post("more_drilldown");
			$graph_type = $this->input->post("graph_type");
			
			$db_where = array('MONTH(createdDate)' => $month,'YEAR(createdDate)' => $year);
			$day_wise_count = $this->dashboard_model->get_stats('DAY(createdDate)',$db_where,'file_detail');
			//echo "<pre>";print_r($day_wise_count);exit;
			
			foreach($days as $key=>$day){
				$day_wise_info[$key]["name"] =  $day;
				$day_wise_info[$key]["y"] =  isset($day_wise_count[$day]["ccount"]) ? (int) $day_wise_count[$day]["ccount"] : 0;
				$day_wise_info[$key]["drilldown"] = ($drilldown == 1) ? $day : false;
				$day_wise_info[$key]["url_name"] = "getSectionEntries";
				$day_wise_info[$key]["id"] = $day."|".$month."|".$year;
				$day_wise_info[$key]["more_drilldown"] = $drilldown;
				$day_wise_info[$key]["graph_type"] = $graph_type;
				$day_wise_info[$key]["title"] = "Days";
			}
			
			$return_data = array(
                array(
                    "name" => "Total Scanned Day wise",
                    "data" => $day_wise_info,
                )
             );
			 
			 echo json_encode($return_data);
		}	
	}
	
	function getSectionEntries(){
		
		if($this->input->post("id")){
			
			$day_month_year = $this->input->post("id");
			list($day,$month,$year) = explode("|" , $day_month_year);
			
			$drilldown = $this->input->post("more_drilldown");
			$graph_type = $this->input->post("graph_type");
			
			$db_where = array('isDeleted' => 0);
			$sections = $this->general_model->get_section($db_where);
			//echo "<pre>";print_r($sections);exit;
			
			$day_month_year_id = "";
			$db_where = array();
			
			if($day != "00"){
				$db_where['DAY(createdDate)'] = $day;
				$day_month_year_id .= "|". $day;
			}
			if($month != "00"){
				$db_where['MONTH(createdDate)'] = $month;
				$day_month_year_id .= "|". $month;
			}
			if($year != "0000"){
				$db_where['YEAR(createdDate)'] = $year;
				$day_month_year_id .= "|". $year;
			}
			
			
			$section_wise_count = $this->dashboard_model->get_stats('sectionId',$db_where,'file_detail');
			//echo "<pre>";print_r($section_wise_count);exit;
			
			$i = 0;
			$section_wise_info = array();
			foreach($sections as $key=>$section){
				if($key == $section_wise_count[$key]["sectionId"]){
					$section_wise_info[$i]["name"] =  $section["sectionName"];
					$section_wise_info[$i]["y"] =  isset($section_wise_count[$key]["ccount"]) ? (int) $section_wise_count[$key]["ccount"] : 0;
					$section_wise_info[$i]["drilldown"] = ($drilldown == 1) ? $section["sectionName"] : false;
					$section_wise_info[$i]["url_name"] = "getFileTypeEntries";
					$section_wise_info[$i]["id"] = $key.$day_month_year_id ;
					$section_wise_info[$i]["more_drilldown"] = $drilldown;
					$section_wise_info[$i]["graph_type"] = $graph_type;
					$section_wise_info[$i]["title"] = "Sections";
					$i++;
				}	
			}
			
			$return_data = array(
                array(
                    "name" => "Total Scanned Section wise",
                    "data" => $section_wise_info,
					"colorByPoint" => true,
                )
             );
			 
			 echo json_encode($return_data);
		}
		
	}
	
	function getFileTypeEntries(){
		
		if($this->input->post("id")){
			
			$section_day_month_year = $this->input->post("id");
			list($section,$day,$month,$year) = explode("|" , $section_day_month_year);
			
			$drilldown = $this->input->post("more_drilldown");
			$graph_type = $this->input->post("graph_type");
			
			$file_types = $this->general_model->get_file_types();
			//echo "<pre>";print_r($file_types);exit;
			
			if($section != 0){
				$db_where['sectionId'] = $section;
			}	
			
			if($day != "00"){
				$db_where['DAY(createdDate)'] = $day;
			}	
			if($month != "00"){
				$db_where['MONTH(createdDate)'] = $month;
			}
			if($year != "0000"){
				$db_where['YEAR(createdDate)'] = $year;
			}
			$file_type_wise_count = $this->dashboard_model->get_stats('fileTypeId',$db_where,'file_detail');
			//echo "<pre>";print_r($file_type_wise_count);exit;
			
			$i = 0;
			$file_type_wise_info = array();
			foreach($file_types as $key=>$file_type){
					$file_type_wise_info[$i]["name"] =  $file_type["fileType"];
					$file_type_wise_info[$i]["y"] =  isset($file_type_wise_count[$key]["ccount"]) ? (int) $file_type_wise_count[$key]["ccount"] : 0;
					$file_type_wise_info[$i]["drilldown"] = ($drilldown == 1) ? "" : false;
					$file_type_wise_info[$i]["url_name"] = "";
					$file_type_wise_info[$i]["id"] = $key;
					$file_type_wise_info[$i]["more_drilldown"] = $drilldown;
					$file_type_wise_info[$i]["graph_type"] = $graph_type;
					$file_type_wise_info[$i]["title"] = "File Types";
					$i++;
			}
			
			$return_data = array(
                array(
                    "name" => "Total Scanned File Type wise",
                    "data" => $file_type_wise_info,
                )
             );
			 
			 if($graph_type == "donut"){				 
				 $return_data[0]["size"] = '90%';   
				 $return_data[0]["innerSize"] = '55%';  
			 }
			 
			 echo json_encode($return_data);
		}
		
	}
	
	

}
