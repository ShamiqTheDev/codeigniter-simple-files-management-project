<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General_model extends CI_Model {
    
    /*
    |------------------------------------------------
    | start: get_sections function
    |------------------------------------------------
    |
    | This function get all sections
    |
    */
	/*function get_sections($db_where = null){
		$this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }

        $result = $this->db->get('user_section s');
        
        if($result->num_rows() > 0) {            
            $data = $result->result_array();            
            return $data;
        }
        else
            return FALSE;
	}*/
	/*---- end: get_sections function ----*/
	
	/*
    |------------------------------------------------
    | start: get_file_types function
    |------------------------------------------------
    |
    | This function get all file types
    |
    */
	function get_file_types($db_where = null){
		$this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }

        $result = $this->db->get('file_types f');
        
        if($result->num_rows() > 0) {            
            $result_data = $result->result_array();            
            foreach($result_data as $get_result_data) {
				$data[$get_result_data['fileTypeId']] = $get_result_data;
			}
			
			return $data;
        }
        else
            return FALSE;
	}
	/*---- end: get_file_types function ----*/
	
	/*
    |------------------------------------------------
    | start: get_general_category_name function
    |------------------------------------------------
    |
    | This function get all file types
    |
    */
	function get_general_category_name($db_where = null){
		$this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }

        $result = $this->db->get('general_file_category gfc');
        
        if($result->num_rows() > 0) {            
            $data = $result->result_array();            
            return $data;
        }
        else
            return FALSE;
	}
	/*---- end: get_general_category_name function ----*/
	
	/*
    |------------------------------------------------
    | start: get_document_type
    |------------------------------------------------
    |
    | This function get all department names like notification and other
    |
    */
    /*function get_document_type($db_where = null) {
        
        $this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }

        $result = $this->db->get('document_name dt');
        
        if($result->num_rows() > 0) {            
            $data = $result->result_array();            
            return $data;
        }
        else
            return FALSE;
       
    }*/
    /*---- end: get_document_type function ----*/
	
	/*
    |------------------------------------------------
    | start: get_document_category
    |------------------------------------------------
    |
    | This function get all department names like notification and other
    |
    */
    function get_document_category($db_where = null) {
        
        $this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }

        $result = $this->db->get('document_categories dc');
        
        if($result->num_rows() > 0) {            
            $data = $result->result_array();            
            return $data;
        }
        else
            return FALSE;
       
    }
    /*---- end: get_document_category function ----*/
	
	/*
    |------------------------------------------------
    | start: get_receiving_mode function
    |------------------------------------------------
    |
    | This function get all receiving mode and get receiving mode by id and other cloumn
    |
    */
    function get_receiving_mode($db_where = null) {
        
        $this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }

        $result = $this->db->get('receiving_mode rm');
        
        if($result->num_rows() > 0) {            
            $data = $result->result_array();            
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_receiving_mode function ----*/
	
	
	/*
    |------------------------------------------------
    | start: get_department function
    |------------------------------------------------
    |
    | This function get all department and get department by id and other cloumn
    |
    */
    /*function get_department($db_where = null) {
        
        $this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }

        $result = $this->db->get('departments d');
        
        if($result->num_rows() > 0) {            
            $data = $result->result_array();            
            return $data;
        }
        else
            return FALSE;
        
    }*/
    /*---- end: get_department function ----*/
	
	
	
	/*
    |------------------------------------------------
    | start: get_enum_value function
    |------------------------------------------------
    |
    | This function get all enum value selected cloumn
    |
    */
	function get_enum_value($table_name, $field_name) {
        
        $query = $this->db->query("SHOW COLUMNS FROM `{$table_name}` LIKE '{$field_name}'");

        if(!$query->num_rows()) return array();
        
        preg_match_all('~\'([^\']*)\'~', $query->row('Type'), $matches);

        return $matches[1];
        
    }
	/*---- end: get_enum_value function ----*/
	
	
	/*
    |------------------------------------------------
    | start: get_section function
    |------------------------------------------------
    |
    | This function get all section and get section by id and other cloumn
    |
    */
    function get_section($db_where = null) {
        
        $this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    if($key == "sectionId"){
						$this->db->where_in($key, $columnVal);	
					}
					else{
						$this->db->where($key, $columnVal);	
					}
                }
            }
        }
		
		$this->db->where('isDeleted', '0');

        $result = $this->db->get('user_section s');
        
        if($result->num_rows() > 0) { 
			foreach($result->result_array() as $row){
				$data[$row["sectionId"]] = $row;
				
			}
			//echo "<pre>";print_r($data);exit;
            //$data = $result->result_array();            
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_section function ----*/
	
	
	/*
    |------------------------------------------------
    | start: section function
    |------------------------------------------------
    |
    | This function add and update section data
    |
	*/
    function section($section_id = null) {
        
		$user_id = $this->flexi_auth->get_user_id();
		
        $post_data = $this->input->post();
        
        $data = array(
                'sectionName'	=> $post_data['section_name'],
                //'createdBy'		=> $this->flexi_auth->get_user_id()
        );
		
        $this->db->trans_begin();
        
        if(!$section_id) {
			
			$data['createdDate']	= date('Y-m-d H:i:s');
			$data['createdBy'] 		= $user_id;
		
            $this->db->set($data);
            $this->db->insert('user_section');
            $section_id = $this->db->insert_id();
        }
        else {
            $this->db->where('sectionId', $section_id);
            $this->db->set($data);
            $this->db->update('user_section');
        }

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
			return $section_id;
        }
        
    }
    /*---- end: section function ----*/
	
	/*
    |------------------------------------------------
    | start: File Type function
    |------------------------------------------------
    |
    | This function add and update section data
    |
	*/
    function file_type($file_type_id = null) {
	
		$user_id = $this->flexi_auth->get_user_id();
        
        $post_data = $this->input->post();
        
        $data = array(
                'fileType'	=> $post_data['file_type_name'],
                //'createdBy'		=> $this->flexi_auth->get_user_id()
        );
		
        $this->db->trans_begin();
        
        if(!$file_type_id) {
		
			$data['createdDate']	= date('Y-m-d H:i:s');
			$data['createdBy'] 		= $user_id;
		
            $this->db->set($data);
            $this->db->insert('file_types');
            $file_type_id = $this->db->insert_id();
        }
        else {
            $this->db->where('fileTypeId', $file_type_id);
            $this->db->set($data);
            $this->db->update('file_types');
        }

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
			return $file_type_id;
        }
        
    }
    /*---- end: File Type function ----*/
	
	/*
    |------------------------------------------------
    | start: delete_section function
    |------------------------------------------------
    |
    | This function delete section
    |
	*/
	function delete_section($section_id) {
        
        $data = array(
                'isDeleted'	=> 1,
                'deletedBy'	=> $this->flexi_auth->get_user_id()
        );
		
        $this->db->trans_begin();
		
		$this->db->where('sectionId', $section_id);
		$this->db->set($data);
		$this->db->update('user_section');

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
			return $section_id;
        }
	
	}
	/*---- end: delete_section function ----*/
	
	/*
    |------------------------------------------------
    | start: get_menu_section_data function
    |------------------------------------------------
    |
    | This function get menu section data
    |
    */
	function get_menu_section_data() {
        
        $this->db->select('us.sectionId,us.sectionName,COUNT(fd.sectionId) AS sectionCount');

		$this->db->join("file_detail fd", "us.sectionId = fd.sectionId", "LEFT");
		$this->db->group_by('us.sectionName'); 
		$this->db->order_by('us.sectionId');
		
        $result = $this->db->get('user_section us');
        
        if($result->num_rows() > 0) {
			
			$this->db->select('fd.`sectionId`,ft.fileType, COUNT(fd.`fileTypeId`) AS fileTypeCount');
			$this->db->join("file_detail as fd","ft.fileTypeId = fd.fileTypeId","LEFT");
			$this->db->group_by(array('ft.fileType','fd.sectionId'));
			$this->db->order_by('ft.fileTypeId');
			
			$result_file = $this->db->get('file_types AS ft');
			
			$fileData = $result_file->result_array();
			$fileTypes = array_column($fileData, 'fileType'); // set array of fileTypes
			
			//echo "<pre>"; print_r($fileTypes);exit;
			
			foreach($result->result_array() as $row){
				$sectionData[$row["sectionId"]]["sectionName"] = $row["sectionName"];
				$sectionData[$row["sectionId"]]["sectionCount"] = $row["sectionCount"];
				// Set by default 0 value for all file types
				foreach($fileTypes as $file){
					$sectionData[$row["sectionId"]]["fileType"][$file] = 0;
				}	
			}
			
			//echo "<pre>"; print_r($fileTypes);exit;
			foreach($fileData as $rowset){
				if($rowset["sectionId"] != ""){ // if assigned by is not null then override fileType count e.g from 0 to 3
					$sectionData[$rowset["sectionId"]]["fileType"][$rowset["fileType"]] = $rowset["fileTypeCount"]; 
				}
			}
			
			//echo "<pre>"; print_r(array_filter($sectionData));exit;
            return $sectionData;
        }
        else
            return FALSE;
        
    }
	/*---- end: get_menu_section_data function ----*/
	
	
	
	
	
	/*
    |------------------------------------------------
    | start: Manages Location
    |------------------------------------------------
    |
    | This function will get all Location
    |
    */
	function get_locations($db_where = null) {
        
        $this->db->select('*');
        
		$this->db->join("cities c", "c.cityId = l.cityId", "INNER");
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
					$this->db->where($key, $columnVal);	
                }
            }
        }
		
		//$this->db->where('isDeleted', '0');
		
        $result = $this->db->get('locations l');
        
        if($result->num_rows() > 0) { 
			foreach($result->result_array() as $row){
				$data[$row["locationId"]] = $row;
				
			}         
            return $data;
        }
        else
            return FALSE;
        
    }
    /*End function of Location*/
	
	/*
    |------------------------------------------------
    | start: Add & Eidt Location Function
    |------------------------------------------------
    |
    | This function add and update Location data
    |
	*/
    function locations($location_id = null) {
	
		$user_id = $this->flexi_auth->get_user_id();
        
        $post_data = $this->input->post();
        
        $data = array(
                'locationName'	=> $post_data['location_name'],
                'cityId'	=> $post_data['city_id'],
                //'createdBy'		=> $this->flexi_auth->get_user_id()
        );
		
        $this->db->trans_begin();
        
        if(!$location_id) {
		
			$data['createdDate']	= date('Y-m-d H:i:s');
			$data['createdBy'] 		= $user_id;
		
            $this->db->set($data);
            $this->db->insert('locations');
            $location_id = $this->db->insert_id();
        }
        else {
            $this->db->where('locationId', $location_id);
            $this->db->set($data);
            $this->db->update('locations');
        }

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
			return $location_id;
        }
        
    }
    /*---- end: Location function ----*/
	
	
	
	
	
	
	
	/*
    |------------------------------------------------
    | start: get_classified function
    |------------------------------------------------
    |
    | This function get all classified and get classified by id and other cloumn
    |
    */
    function get_classified($db_where = null) {
        
        $this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }

        $result = $this->db->get('classified c');
        
        if($result->num_rows() > 0) { 
			foreach($result->result_array() as $row){
				$data[$row["classifiedId"]] = $row;
				
			}           
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_classified function ----*/
	
	
	/*
    |------------------------------------------------
    | start: classified function
    |------------------------------------------------
    |
    | This function add and update classified data
    |
	*/
    function classified($classified_id = null) {
        
		$user_id = $this->flexi_auth->get_user_id();
		
        $post_data = $this->input->post();
        
        $data = array(
                'classifiedName'	=> $post_data['classified_name'],
        );
		
        $this->db->trans_begin();
        
        if(!$classified_id) {
			
			$data['createdBy'] 		= $user_id;
			$data['createdDate']	= date('Y-m-d H:i:s');
		
            $this->db->set($data);
            $this->db->insert('classified');
            $classified_id = $this->db->insert_id();
        }
        else {
            $this->db->where('classifiedId', $classified_id);
            $this->db->set($data);
            $this->db->update('classified');
        }

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
			return $classified_id;
        }
        
    }
    /*---- end: classified function ----*/
	
	
	
	
	
	/*
    |------------------------------------------------
    | start: get_delivery_mode function
    |------------------------------------------------
    |
    | This function get all delivery mode and get delivery mode by id and other cloumn
    |
    */
    function get_delivery_mode($db_where = null) {
        
        $this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }

        $result = $this->db->get('delivery_mode dm');
        
        if($result->num_rows() > 0) { 
			foreach($result->result_array() as $row){
				$data[$row["deliveryModeId"]] = $row;
				
			}           
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_delivery_mode function ----*/
	
	
	/*
    |------------------------------------------------
    | start: delivery mode function
    |------------------------------------------------
    |
    | This function add and update delivery mode data
    |
	*/
    function delivery_mode($delivery_mode_id = null) {
        
		$user_id = $this->flexi_auth->get_user_id();
		
        $post_data = $this->input->post();
        
        $data = array(
                'deliveryMode'	=> $post_data['delivery_mode'],
        );
		
        $this->db->trans_begin();
        
        if(!$delivery_mode_id) {
			
			$data['createdBy'] 		= $user_id;
			$data['createdDate']	= date('Y-m-d H:i:s');
		
            $this->db->set($data);
            $this->db->insert('delivery_mode');
            $delivery_mode_id = $this->db->insert_id();
        }
        else {
            $this->db->where('deliveryModeId', $delivery_mode_id);
            $this->db->set($data);
            $this->db->update('delivery_mode');
        }

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
			return $delivery_mode_id;
        }
        
    }
    /*---- end: delivery_mode function ----*/
	
	
	
	
	/*
    |------------------------------------------------
    | start: get_document_type function
    |------------------------------------------------
    |
    | This function get all document type and get document type by id and other cloumn
    |
    */
    function get_document_type($table_name, $db_where = null) {
        
        $this->db->select('dt.*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }

		if($table_name == 'scheme_document_type') {
			$this->db->select('csd.documentType as documentTypeParent');
			$this->db->join($table_name." csd", "csd.documentTypeId = dt.documentTypeParentId", 'LEFT');
		}
		
        $result = $this->db->get($table_name.' dt');
        
        if($result->num_rows() > 0) { 
			foreach($result->result_array() as $row){
				$data[$row["documentTypeId"]] = $row;
				
			}           
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_document_type function ----*/
	
	
	/*
    |------------------------------------------------
    | start: document_type function
    |------------------------------------------------
    |
    | This function add and update document type data
    |
	*/
    function document_type($table_name, $document_type_id = null) {
        
		$user_id = $this->flexi_auth->get_user_id();
		
        $post_data = $this->input->post();
        
        $data = array(
                'documentType'	=> $post_data['document_type'],
        );
		
		if(isset($post_data['document_type_parent_id']))
			$data['documentTypeParentId'] = ($post_data['have_parent']) ? $post_data['document_type_parent_id'] : '0';
		
        $this->db->trans_begin();
        
        if(!$document_type_id) {
			
			$data['createdBy'] 		= $user_id;
			$data['createdDate']	= date('Y-m-d H:i:s');
		
            $this->db->set($data);
            $this->db->insert($table_name);
            $document_type_id = $this->db->insert_id();
        }
        else {
            $this->db->where('documentTypeId', $document_type_id);
            $this->db->set($data);
            $this->db->update($table_name);
        }

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
			return $document_type_id;
        }
        
    }
    /*---- end: document_type function ----*/
	
	
	
	
	
	/*
    |------------------------------------------------
    | start: get_ministry function
    |------------------------------------------------
    |
    | This function get all ministry and get ministry by id and other cloumn
    |
    */
    function get_ministry($db_where = null) {
        
        $this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }

        $result = $this->db->get('ministry m');
        
        if($result->num_rows() > 0) { 
			foreach($result->result_array() as $row){
				$data[$row["ministryId"]] = $row;
				
			}           
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_ministry function ----*/
	
	
	/*
    |------------------------------------------------
    | start: ministry function
    |------------------------------------------------
    |
    | This function add and update ministry data
    |
	*/
    function ministry($ministry_id = null) {
        
		$user_id = $this->flexi_auth->get_user_id();
		
        $post_data = $this->input->post();
        
        $data = array(
                'ministryName'	=> $post_data['ministry_name'],
        );
		
        $this->db->trans_begin();
        
        if(!$ministry_id) {
			
			$data['createdBy'] 		= $user_id;
			$data['createdDate']	= date('Y-m-d H:i:s');
		
            $this->db->set($data);
            $this->db->insert('ministry');
            $ministry_id = $this->db->insert_id();
        }
        else {
            $this->db->where('ministryId', $ministry_id);
            $this->db->set($data);
            $this->db->update('ministry');
        }

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
			return $ministry_id;
        }
        
    }
    /*---- end: ministry function ----*/
	
	
	
	
	
	
	/*
    |------------------------------------------------
    | start: get_department function
    |------------------------------------------------
    |
    | This function get all department and get department by id and other cloumn
    |
    */
    function get_department($db_where = null) {
        
        $this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }

		$this->db->join("ministry m", "m.ministryId = d.ministryId");
		
        $result = $this->db->get('department d');
        
        if($result->num_rows() > 0) { 
			foreach($result->result_array() as $row){
				$data[$row["departmentId"]] = $row;
				
			}           
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_department function ----*/
	
	
	/*
    |------------------------------------------------
    | start: department function
    |------------------------------------------------
    |
    | This function add and update department data
    |
	*/
    function department($department_id = null) {
        
		$user_id = $this->flexi_auth->get_user_id();
		
        $post_data = $this->input->post();
        
        $data = array(
                'ministryId'	=> $post_data['ministry_id'],
                'departmentName'	=> $post_data['department_name'],
        );
		
        $this->db->trans_begin();
        
        if(!$department_id) {
			
			$data['createdBy'] 		= $user_id;
			$data['createdDate']	= date('Y-m-d H:i:s');
		
            $this->db->set($data);
            $this->db->insert('department');
            $department_id = $this->db->insert_id();
        }
        else {
            $this->db->where('departmentId', $department_id);
            $this->db->set($data);
            $this->db->update('department');
        }

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
			return $department_id;
        }
        
    }
    /*---- end: department function ----*/
	
	
	
	
	
	
	/*
    |------------------------------------------------
    | start: get_state function
    |------------------------------------------------
    |
    | This function get all state and get state by id and other cloumn
    |
    */
    function get_state($db_where = null) {
        
        $this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }

		$this->db->join("country c", "c.countryId = s.countryId");
		
        $result = $this->db->get('state s');
        
        if($result->num_rows() > 0) { 
			foreach($result->result_array() as $row){
				$data[$row["stateId"]] = $row;
				
			}           
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_state function ----*/
	
	
	/*
    |------------------------------------------------
    | start: state function
    |------------------------------------------------
    |
    | This function add and update state data
    |
	*/
    function state($state_id = null) {
        
		$user_id = $this->flexi_auth->get_user_id();
		
        $post_data = $this->input->post();
        
        $data = array(
                'countryId'	=> $post_data['country_id'],
                'stateName'	=> $post_data['state_name'],
        );
		
        $this->db->trans_begin();
        
        if(!$state_id) {
			
			$data['createdBy'] 		= $user_id;
			$data['createdDate']	= date('Y-m-d H:i:s');
		
            $this->db->set($data);
            $this->db->insert('state');
            $state_id = $this->db->insert_id();
        }
        else {
            $this->db->where('stateId', $state_id);
            $this->db->set($data);
            $this->db->update('state');
        }

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
			return $state_id;
        }
        
    }
    /*---- end: state function ----*/
	
	
	
	
	
	/*
    |------------------------------------------------
    | start: get_category function
    |------------------------------------------------
    |
    | This function get all category and get category by id and other cloumn
    |
    */
    function get_category($db_where = null) {
        
        $this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }

        $result = $this->db->get('category c');
        
        if($result->num_rows() > 0) { 
			foreach($result->result_array() as $row){
				$data[$row["categoryId"]] = $row;
				
			}           
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_category function ----*/
	
	
	/*
    |------------------------------------------------
    | start: category function
    |------------------------------------------------
    |
    | This function add and update category data
    |
	*/
    function category($category_id = null) {
        
		$user_id = $this->flexi_auth->get_user_id();
		
        $post_data = $this->input->post();
        
        $data = array(
                'categoryName'	=> $post_data['category_name'],
                'categoryColor'	=> $post_data['category_color'],
                'categoryShow'	=> isset($post_data['category_show']) ? $post_data['category_show'] : '0',
        );
		
        $this->db->trans_begin();
        
        if(!$category_id) {
			
			$data['createdBy'] 		= $user_id;
			$data['createdDate']	= date('Y-m-d H:i:s');
		
            $this->db->set($data);
            $this->db->insert('category');
            $category_id = $this->db->insert_id();
        }
        else {
            $this->db->where('categoryId', $category_id);
            $this->db->set($data);
            $this->db->update('category');
        }

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
			return $category_id;
        }
        
    }
    /*---- end: category function ----*/
	
	
	
	
	
	/*
    |------------------------------------------------
    | start: get_country function
    |------------------------------------------------
    |
    | This function get all country and get country by id and other cloumn
    |
    */
    function get_country($db_where = null) {
        
        $this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }

        $result = $this->db->get('country c');
        
        if($result->num_rows() > 0) { 
			foreach($result->result_array() as $row){
				$data[$row["countryId"]] = $row;
				
			}           
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_country function ----*/
	
	
	/*
    |------------------------------------------------
    | start: country function
    |------------------------------------------------
    |
    | This function add and update country data
    |
	*/
    function country($country_id = null) {
        
		$user_id = $this->flexi_auth->get_user_id();
		
        $post_data = $this->input->post();
        
        $data = array(
                'countryName'	=> $post_data['country_name'],
        );
		
        $this->db->trans_begin();
        
        if(!$country_id) {
			
			$data['createdBy'] 		= $user_id;
			$data['createdDate']	= date('Y-m-d H:i:s');
		
            $this->db->set($data);
            $this->db->insert('country');
            $country_id = $this->db->insert_id();
        }
        else {
            $this->db->where('countryId', $country_id);
            $this->db->set($data);
            $this->db->update('country');
        }

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
			return $country_id;
        }
        
    }
    /*---- end: country function ----*/
	
	
	
	/*
    |------------------------------------------------
    | start: get_city function
    |------------------------------------------------
    |
    | This function get all city and get city by id and other cloumn
    |
    */
    function get_city($db_where = null) {
        
        $this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }
		
		$this->db->join("state s", "s.stateId = c.stateId");

        $result = $this->db->get('city c');
        
        if($result->num_rows() > 0) { 
			foreach($result->result_array() as $row){
				$data[$row["cityId"]] = $row;
				
			}           
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_city function ----*/
	
	/*
    |------------------------------------------------
    | start: city function
    |------------------------------------------------
    |
    | This function add and update city data
    |
	*/
    function city($city_id = null) {
	
		$user_id = $this->flexi_auth->get_user_id();
        
        $post_data = $this->input->post();
        
        $data = array(
                'stateId'	=> $post_data['state_id'],
                'cityName'	=> $post_data['city_name'],
                //'cityCode'	=> $post_data['city_code'],
        );
		
        $this->db->trans_begin();
        
        if(!$city_id) {
		
			$data['createdBy'] 		= $user_id;
			$data['createdDate']	= date('Y-m-d H:i:s');
		
            $this->db->set($data);
            $this->db->insert('city');
            $city_id = $this->db->insert_id();
        }
        else {
            $this->db->where('cityId', $city_id);
            $this->db->set($data);
            $this->db->update('city');
        }

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
			return $city_id;
        }
        
    }
    /*---- end: city function ----*/
	
	
	
	
	
	
	/*
    |------------------------------------------------
    | start: get_sub_category function
    |------------------------------------------------
    |
    | This function get all sub category and get sub category by id and other cloumn
    |
    */
    function get_sub_category($db_where = null) {
        
        $this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }
		
		$this->db->join("category c", "c.categoryId = sc.categoryId");

        $result = $this->db->get('sub_category sc');
        
        if($result->num_rows() > 0) { 
			foreach($result->result_array() as $row){
				$data[$row["subCategoryId"]] = $row;
				
			}           
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_sub_category function ----*/
	
	
	/*
    |------------------------------------------------
    | start: sub_category function
    |------------------------------------------------
    |
    | This function add and update sub category data
    |
	*/
    function sub_category($sub_category_id = null) {
        
		$user_id = $this->flexi_auth->get_user_id();
		
        $post_data = $this->input->post();
        
        $data = array(
                'categoryId'	=> $post_data['category_id'],
                'subCategoryName'	=> $post_data['sub_category_name'],
        );
		
        $this->db->trans_begin();
        
        if(!$sub_category_id) {
			
			$data['createdBy'] 		= $user_id;
			$data['createdDate']	= date('Y-m-d H:i:s');
		
            $this->db->set($data);
            $this->db->insert('sub_category');
            $sub_category_id = $this->db->insert_id();
        }
        else {
            $this->db->where('subCategoryId', $sub_category_id);
            $this->db->set($data);
            $this->db->update('sub_category');
        }

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
			return $sub_category_id;
        }
        
    }
    /*---- end: sub_category function ----*/
	
	
	
	
	
	/*
    |------------------------------------------------
    | start: get_send_action function
    |------------------------------------------------
    |
    | This function get all send action and get send action by id and other cloumn
    |
    */
    function get_send_action($db_where = null) {
        
        $this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }

        $result = $this->db->get('send_action sa');
        
        if($result->num_rows() > 0) { 
			foreach($result->result_array() as $row){
				$data[$row["sendActionId"]] = $row;
				
			}           
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_send_action function ----*/
	
	
	/*
    |------------------------------------------------
    | start: send_action function
    |------------------------------------------------
    |
    | This function add and update send action data
    |
	*/
    function send_action($send_action_id = null) {
        
		$user_id = $this->flexi_auth->get_user_id();
		
        $post_data = $this->input->post();
        
        $data = array(
                'sendAction'	=> $post_data['send_action'],
        );
		
        $this->db->trans_begin();
        
        if(!$send_action_id) {
			
			$data['createdBy'] 		= $user_id;
			$data['createdDate']	= date('Y-m-d H:i:s');
		
            $this->db->set($data);
            $this->db->insert('send_action');
            $send_action_id = $this->db->insert_id();
        }
        else {
            $this->db->where('sendActionId', $send_action_id);
            $this->db->set($data);
            $this->db->update('send_action');
        }

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
			return $send_action_id;
        }
        
    }
    /*---- end: send_action function ----*/
	
	
	
	
	
	/*
    |------------------------------------------------
    | start: get_send_priority function
    |------------------------------------------------
    |
    | This function get all send priority and get send priority by id and other cloumn
    |
    */
    function get_send_priority($db_where = null) {
        
        $this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }

        $result = $this->db->get('send_priority sp');
        
        if($result->num_rows() > 0) { 
			foreach($result->result_array() as $row){
				$data[$row["sendPriorityId"]] = $row;
				
			}           
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_send_priority function ----*/
	
	
	/*
    |------------------------------------------------
    | start: send_priority function
    |------------------------------------------------
    |
    | This function add and update send priority data
    |
	*/
    function send_priority($send_priority_id = null) {
        
		$user_id = $this->flexi_auth->get_user_id();
		
        $post_data = $this->input->post();
        
        $data = array(
                'sendPriority'		=> $post_data['send_priority'],
                'sendPriorityColor'	=> $post_data['send_priority_color'],
                'sendPriorityShow'	=> isset($post_data['send_priority_show']) ? $post_data['send_priority_show'] : '0',
        );
		
        $this->db->trans_begin();
        
        if(!$send_priority_id) {
			
			$data['createdBy'] 		= $user_id;
			$data['createdDate']	= date('Y-m-d H:i:s');
		
            $this->db->set($data);
            $this->db->insert('send_priority');
            $send_priority_id = $this->db->insert_id();
        }
        else {
            $this->db->where('sendPriorityId', $send_priority_id);
            $this->db->set($data);
            $this->db->update('send_priority');
        }

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
			return $send_priority_id;
        }
        
    }
    /*---- end: send_priority function ----*/
	
	
	
	/*
    |------------------------------------------------
    | start: get_receipt_inbox_count function
    |------------------------------------------------
    |
    | This function get count of all unread receipt inbox
    |
    */
    function get_receipt_inbox_count($menu_uri) {
	
        $user_id = $this->flexi_auth->get_user_id();
		
		$this->update_is_read_receipt($menu_uri, $user_id);
		
        $this->db->select('COUNT(*) as ccount');
		
		$this->db->group_start();
		$this->db->where('sentStatus', 'sent');
		$this->db->where('sentModule', 'receipt');
		$this->db->where('isRead', 0);
		$this->db->where('IsResponded', 0);
		$this->db->group_end();
		
		$this->db->group_start();
		//$this->db->or_where('sentTo', $user_id);
		$this->db->or_where('sentToUserGroup', $this->session->userdata('user_job_group'));
		$this->db->or_where('sentCc LIKE', '%'.$user_id.'%');
		$this->db->group_end();
		
		$result = $this->db->get('sent');
        
        if($result->num_rows() > 0) { 
			$data = $result->result_array();           
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_receipt_inbox_count function ----*/
	
	/*
    |------------------------------------------------
    | start: get_file_inbox_count function
    |------------------------------------------------
    |
    | This function get count of all unread receipt inbox
    |
    */
    function get_file_inbox_count($menu_uri) {
	
        $user_id = $this->flexi_auth->get_user_id();
		
        //echo "<pre>";print_r($menu_uri);exit;
		//$this->update_is_read_file($menu_uri, $user_id);
		
        $this->db->select('COUNT(DISTINCT(sentId)) as ccount');
		$this->db->join("file f", "f.fileId = s.fkDetailId" ,'LEFT');

		$this->db->group_start();
		$this->db->where('sentStatus', 'sent');
		$this->db->where('sentModule', 'file');
        $this->db->where('fileExistenceId', $user_id);
		//$this->db->where('isRead', 0);
		$this->db->where('isResponded', 0);
		$this->db->group_end();
		
		$this->db->group_start();
		$this->db->or_where('sentTo', $user_id);
		$this->db->or_where('sentToUserGroup', $this->session->userdata('user_job_group'));
		$this->db->or_where('sentCc LIKE', '%'.$user_id.'%');
		$this->db->group_end();
		
		$result = $this->db->get('sent s');
        
        if($result->num_rows() > 0) { 
			$data = $result->result_array();   
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_file_inbox_count function ----*/
	
	/*
    |------------------------------------------------
    | start: update_is_read_receipt function
    |------------------------------------------------
    |
    | This function update receipt sent
    |
	*/
    function update_is_read_receipt($menu_uri, $user_id) {
	
		if($menu_uri == 'admin/receipts/receipt_view') {
		
			$receipt_id = $this->uri->segment(4);
			
			$data = array(
					'isRead'	=> 1
				);			
				
			$this->db->trans_begin();
			
			
			$this->db->where('sentTo', $user_id);
			$this->db->where('isRead', '0');
			$this->db->where('sentStatus', 'sent');
			$this->db->where('fkDetailId', $receipt_id);
			$this->db->set($data);
			$this->db->update('sent');
				
			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				//return $receipt_id;
			}
			
		}
		
	}
	/*---- end: update_is_read_receipt function ----*/
	
	
	/*
    |------------------------------------------------
    | start: update_is_read_file function
    |------------------------------------------------
    |
    | This function update file sent
    |
	*/
    function update_is_read_file($menu_uri, $user_id) {
	
		if($menu_uri == 'admin/files/note_sheet_detail') {
        //if($menu_uri == 'admin/files/inbox') {

		    //echo "<pre>"; print_r($this->uri->segment(5)); exit;
			
            $file_id = $this->uri->segment(5);
			//echo "<pre>"; print_r($file_id); exit;
			$data = array(
					'isRead'	=> 1
				);			
				
			$this->db->trans_begin();
			
			$this->db->where('sentTo', $user_id);
			$this->db->where('isRead', '0');
			$this->db->where('sentStatus', 'sent');
			$this->db->where('fkDetailId', $file_id);
			$this->db->set($data);
			$this->db->update('sent');
				
			$this->db->trans_complete();
			
			if($this->db->trans_status() === FALSE) {
				$this->db->trans_rollback();
				return FALSE;
			} else {
				$this->db->trans_commit();
				//return $receipt_id;
			}
			
		}
		
	}
	/*---- end: update_is_read_receipt function ----*/
	
	
	/*
    |------------------------------------------------
    | start: get_designation function
    |------------------------------------------------
    |
    | This function get all designation and get designation by id and other cloumn
    |
    */
    function get_designation($db_where = null) {
        
        $this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }

        $result = $this->db->get('designation d');
        
        if($result->num_rows() > 0) { 
			foreach($result->result_array() as $row){
				$data[$row["designationId"]] = $row;
				
			}           
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_designation function ----*/
	
	
	/*
    |------------------------------------------------
    | start: designation function
    |------------------------------------------------
    |
    | This function add and update designation data
    |
	*/
    function designation($designation_id = null) {
        
		$user_id = $this->flexi_auth->get_user_id();
		
        $post_data = $this->input->post();
        
        $data = array(
                'designationName' => $post_data['designation_name']
        );
		
        $this->db->trans_begin();
        
        if(!$designation_id) {
			
			$data['createdBy'] 		= $user_id;
			$data['createdDate']	= date('Y-m-d H:i:s');
		
            $this->db->set($data);
            $this->db->insert('designation');
            $designation_id = $this->db->insert_id();
        }
        else {
            $this->db->where('designationId', $designation_id);
            $this->db->set($data);
            $this->db->update('designation');
        }

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
			return $designation_id;
        }
        
    }
    /*---- end: designation function ----*/
    function get_sent_email_alerts(){
		$user_id = $this->flexi_auth->get_user_id();
		$this->db->select('*,CONCAT(up.upro_first_name, " ", up.upro_last_name) as full_name,rs.createdDate as sentOn,rs.createdBy as receiptSentBy,rd.createdDate as receiptCreatedDate');
		
		$this->db->join("receipt_detail rd", "rd.receiptDetailId = rs.fkDetailId", 'LEFT');
		$this->db->join("file f", "f.fileId = rs.fkDetailId ", 'LEFT');
		
		//$this->db->join("file f", "f.fileId = rs.fkDetailId"); 
		$this->db->join("send_priority sp", "sp.sendPriorityId = rs.sendPriorityId", 'LEFT');
		$this->db->join("user_accounts ua", "ua.uacc_id = rs.createdBy");
		$this->db->join("user_profiles up", "up.upro_uacc_fk = ua.uacc_id");
		$this->db->join("designation d", "ua.uacc_designation_fk = d.designationId", 'LEFT');
		$this->db->where('rs.sentTo', $user_id);
		$this->db->where('rs.SentStatus', 'sent');
		$this->db->where('rs.isResponded', '0');
		$this->db->order_by('rs.createdDate','DESC');
		$this->db->limit(10,0);
		$result = $this->db->get('sent rs');
		if($result->num_rows() > 0) { 
				foreach($result->result_array() as $row){
					$data[$row["sentId"]] = $row;
				}        
				return $data;
			}
			else
				return FALSE;
	}
	/* end: get_sent_email_alerts */
	function get_receipt_scanned_count(){
		$this->db->select('c.categoryName, count(rd.receiptDetailId) as categoryCount');
		$this->db->join("receipt_detail rd", "rd.categoryId = c.categoryId", 'LEFT');
		$this->db->where('DATE(rd.createdDate)',Date('Y-m-d'));
		$this->db->order_by("categoryCount","DESC");
		$this->db->group_by('c.categoryName');
		$result = $this->db->get('category c');
		
		if($result->num_rows() > 0) { 
				foreach($result->result_array() as $row){
					$data[$row["categoryName"]] = $row;
				}        
				return $data;
			}
			else
				return FALSE;
	}
	
	/*
    |------------------------------------------------
    | start: get_user_job_group function
    |------------------------------------------------
    |
    | This function get all user job group and get user job group by id and other cloumn
    |
    */
    function get_user_job_group($db_where = null) {
        
        $this->db->select('*');
        
        if($db_where) {
            foreach ($db_where as $key => $columnVal) {
                if ($columnVal != "") {
                    $this->db->where($key, $columnVal);
                }
            }
        }
		
        $result = $this->db->get('user_job_group ujg');
        
        if($result->num_rows() > 0) { 
			foreach($result->result_array() as $row){
				$data[$row["userJobGroupId"]] = $row;
				
			}           
            return $data;
        }
        else
            return FALSE;
        
    }
    /*---- end: get_user_job_group function ----*/
	
	
	/*
    |------------------------------------------------
    | start: user_job_group function
    |------------------------------------------------
    |
    | This function add and update user job group data
    |
	*/
    function user_job_group($user_job_group_id = null) {
        
		$user_id = $this->flexi_auth->get_user_id();
		
        $post_data = $this->input->post();
        
        $data = array(
                'userJobGroupName' => $post_data['user_job_group_name'],
        );
		
        $this->db->trans_begin();
        
        if(!$user_job_group_id) {
			
			$data['createdBy'] 		= $user_id;
			$data['createdDate']	= date('Y-m-d H:i:s');
		
            $this->db->set($data);
            $this->db->insert('user_job_group');
            $user_job_group_id = $this->db->insert_id();
        }
        else {
            $this->db->where('userJobGroupId', $user_job_group_id);
            $this->db->set($data);
            $this->db->update('user_job_group');
        }

        $this->db->trans_complete();
        
        if($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
			return $user_job_group_id;
        }
        
    }
    /*---- end: user_job_group function ----*/
}