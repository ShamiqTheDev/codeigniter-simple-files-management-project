<?php
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	
	function get_session_section() {
	
		$CI = get_instance();
		$section_id = explode(',', $CI->session->userdata('section'));
		//echo "<pre>";print_r($section_id);exit;
		
		$CI->load->model('admin/general_model');
		
		//foreach($section_id as $get_section_id) {
		
			$data = $CI->general_model->get_section(array('sectionId' => $section_id));
			//$data[$get_section_id] = $section[$get_section_id];
		
		//}
		
		return $data;
		
	}
	
	function convertToReadableSize($size){
		
		$base = log($size) / log(1024);
		$suffix = array("", " KB", " MB", " GB", " TB");
		$f_base = floor($base);
		
		return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
		
	}
	
	
	function clean($string) {
		$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}


	function dd($data,$exit=true,$text='')
	{
		if (is_array($data)) {
			echo "<pre>";
			print_r($data);
			$msg = !empty($text)?$text:'';
			ee($msg,$exit);
			echo "</pre>";
		}else{
			echo "not type of an array";
			var_dump($data);
			exit;
		}
	}

	function debug($data,$exit=true,$text='')
	{
		if (is_array($data)) {
			echo "<pre>";
			print_r($data);
			if ($exit) {
				$msg= !empty($text)?$text:'';
				exit($msg);
			}
			echo "</pre>";
		}else{
			$data = json_encode($data);
			$data = json_decode($data,true);
			if (is_array($data)) {
				echo "<b>Data Type : <span style='color:red;'>Converted From Object<span></b>";
				debug($data);
			}
			exit;
		}

	}

	function ee($string,$exit=true)
	{
		echo $string;
		if ($exit) {
			exit;
		}
	}
	
?>