<?php

error_reporting(E_ALL | E_STRICT);

require('UploadHandler.php');

class CustomUploadHandler extends UploadHandler {

    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index = null, $content_range = null) {
		
		$module_type = $this->options['module_type'];
		
		/*$infoFile = new SplFileInfo($name);
		$extension = $infoFile->getExtension();
		$filename = $infoFile->getBasename('.'.$extension);
		$user_id = $this->CI->flexi_auth->get_user_id();
		
		$new_file_name = $filename.'-'.$user_id.'.'.$extension;*/
		$new_file_name = $name;
		
		
		
		if(!$this->CI->uploadhandler_model->get_file(array('OriginalFileName', 'moduleType'), array($name, $module_type))) {	
			$file = parent::handle_file_upload(
							$uploaded_file, $new_file_name, $size, $type, $error, $index, $content_range
			);
			
			/*if (empty($file->error)) {
				$file_detail_id = $this->options['file_detail_id'];
				$file_type_id = $this->options['file_type_id'];
				
				$file_data = $this->CI->uploadhandler_model->insert_file($file, $file_detail_id, $file_type_id);
				$file->id = $file_data['file_id'];
				$file->name = $file_data['file_name'];
				$file->url = $file_data['file_url'];
				$file->deleteUrl = $file_data['file_url_delete'];
			}*/
			
			$file->real_name = $name;
			$file->url = $this->get_full_url().'/upload/'.$module_type.'/temp_files/'.$name;
						
			return $file;
		}
		
		$file['name'] = 'This file already exists';
		
		return $file;
		
		//echo '<pre>'; print_r($file); die();
        
        
    }

    protected function set_additional_file_properties($file, $date_para = null) {
		//echo substr("Hello world",5,3); die();
		//echo '<pre>'; print_r($file); //die();
		
		$module_type = $this->options['module_type'];
		$upload_url = $this->get_full_url().'/upload/'.$module_type.'/';
		$url_start_point = strlen($upload_url);
		$url_length = 10;
		
		$date = str_replace('/', '-', substr($file->url, $url_start_point, $url_length));
		
		if(preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $date, $matches)) { // /0000/00/00/
			$date_para = '/'.$date;   //str_replace("world","Peter","Hello world!"); //echo '<pre>'; print_r($matches); //$date_path = '';
		}
			
		
		//preg_match('/^\d{4}\/\d{2}\/\d{2}$/', $date_path, $matches);
		//echo '<pre>'; print_r($matches);
		
		//echo '<pre>';
		//echo $date_path; //^\/\d{4}\/\d{2}\/\d{2}\/$
		
        parent::set_additional_file_properties($file, $date_para);
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		
			$module_type = $this->options['module_type'];
            
            $db_where_column = array('OriginalFileName', 'fileSize', 'moduleType');
            $db_where_value = array($file->name, $file->file_size, $module_type);
			//$db_where_column = array();
            //$db_where_value = array();
            $db_select_value = array('*');
            
            //echo $file->name;

            $files_data = $this->CI->uploadhandler_model->get_file($db_where_column, $db_where_value, $db_select_value);

            //echo '<pre>'; print_r($files_data);
            
            if($files_data) {
                foreach($files_data as $value) {
                    $file->id = $value->fileUploadedId;
                    //$file->type = $value->fileType;
                    //$file->embed_url = $value->filePath;
                    //$file->file_detail_id = $value->fileDetailId;
                    //$file->file_detail_id = $value->fileDetailId;
                    $file->file_uploaded_date = date('d-m-Y H:i:s', strtotime($value->fileUploadedDate));
                    $file->file_uploaded_by = $value->upro_first_name.' '.$value->upro_last_name.' ('.$value->uacc_username.')';
                    //$file->username = $value->uacc_username;
                }
            }
        }
    }
    
    protected function get_file_objects($iteration_method = 'get_file_object') {
        
        //echo $this->options['product_id']; die();
        
        parent::get_file_objects($iteration_method = 'get_file_object');
        
        $upload_dir = $this->get_upload_path();
        
        if (!is_dir($upload_dir)) {
            return array();
        }
        
        if(empty($this->options['file_detail_id'])) {
            if($this->options['action'] == 'raw') {
                $db_where_column = array('fileDetailId');
                $db_where_value = array($this->options['file_detail_id']);
                
            } else {
                $db_where_column = array('fileDetailId !=', 'fileDetailId =');
                $db_where_value = array($this->options['file_detail_id'], $this->options['file_detail_id']);
            }
            
        }
        else {
            $db_where_column = array('fileDetailId');
            $db_where_value = array($this->options['file_detail_id']);
        }
            
        //$db_select_value = array('OriginalFileName');
		$db_select_value = array('file_uploaded.*');
            
        $files = $this->CI->uploadhandler_model->get_file($db_where_column, $db_where_value, $db_select_value);
        
        if($files) {
            foreach($files as $value) {
                //$files_name[] = $value->OriginalFileName;
				$files_name[] = $value;
            }
        }
        else {
            $files_name = array();
        }
        return array_values(array_filter( array_map( array($this, $iteration_method), $files_name ) ) );
    }
	
	
	protected function get_file_object($file_name, $file_size = null, $file_url = null, $file_id = 0) {
	
		//$file['file_exists'] = 0;
		
		//echo '<pre>'; print_r($file_name);
        //die();
		
		$real_name = '';
		//$file_url = '';
		if(is_object($file_name)) {
			$file_id = $file_name->fileUploadedId;
			$real_name = $file_name->OriginalFileName;
			$file_url = $this->get_full_url().'/'.$file_name->filePath;
			$file_name = $file_name->fileName;
		}
	
		$module_type = $this->options['module_type'];
	
		/*$infoFile = new SplFileInfo($file_name);
		$extension = $infoFile->getExtension();
		$filename = $infoFile->getBasename('.'.$extension);
		$user_id = $this->CI->flexi_auth->get_user_id();
		
		$new_file_name = $filename.'-'.$user_id.'.'.$extension;*/
		$new_file_name = $file_name;
	
		$file = parent::get_file_object($new_file_name, $file_size, $file_url, $file_id);
		
		$file->file_exists = 0;
		//$file->real_name = $file_name;
		$file->real_name = $real_name;
		
		if($this->CI->uploadhandler_model->get_file(array('OriginalFileName', 'fileSize', 'moduleType'), array($file_name, $file_size, $module_type))) {
			
			//$file['file_exists'] = 'This file already exists';
			$file->file_exists = 1;
			
		}
		
		//echo '<pre>'; print_r($file); die();
		
		return $file;
	}
	

    public function delete($print_response = true, $date_para = null) {
	
		//die('test');
		$date_para = $this->options['date_para'];
		
        $response = parent::delete(false, $date_para);
		//echo '<pre>'; print_r($response); die();
       // die();
        foreach ($response as $name => $deleted) {
            if ($deleted) {
                $this->CI->uploadhandler_model->delete_file($name);
            } else {
                $this->CI->uploadhandler_model->delete_file($name); // for deleting the Youtube Videos
            }
        }

        return $this->generate_response($response, $print_response);
    }

}