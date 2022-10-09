<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Menu_model extends CI_Model {
    /*
      |------------------------------------------------
      | start: get_menu function
      |------------------------------------------------
      |
      | This function Get Menus and its sub Menus
      |
     */

    public $table = "system_menu_privileges";

    public function get_menu($hide_privilege = TRUE,$show_disabled_parent_childs = FALSE) {
        $this->db->select("*");
		$this->db->order_by('mu_parent_id', 'ASC');
        $this->db->order_by('mu_order_by', 'ASC');
        $this->db->where("mu_delete", 0);
        
        // it will call for manage menu and manage privilege only
        // when user have no privilege to access menu then it will hide form that user
        if ($hide_privilege) {
            $this->db->where("mu_active", 1);
            $userId = $this->flexi_auth->get_user_id();
            $userGroupId = $this->flexi_auth->get_user_group_id();
            $this->db->join("user_privilege_users", "user_privilege_users.upriv_users_menu_fk =" . $this->table . ".mu_id", "LEFT");
            $this->db->join("user_privilege_groups", "user_privilege_groups.upriv_groups_menu_fk =" . $this->table . ".mu_id", "LEFT");
            $this->db->where("(user_privilege_users.upriv_users_uacc_fk = $userId OR user_privilege_groups.upriv_groups_ugrp_fk = $userGroupId)");
        }
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            $formated_menu = array();
			
            $menu = $query->result_array();
			//echo "<pre>"; print_r($menu); die();
            foreach ($menu as $key => $value) {
                if ($menu[$key]['mu_parent_id'] == 0) {
                    $formated_menu[$menu[$key]['mu_id']] = $menu[$key];
                } else {
                    if ($this->check_parent_delete($menu[$key]['mu_parent_id'])) {
                        if($this->check_parent_active($menu[$key]['mu_parent_id']) || $show_disabled_parent_childs != FALSE){
							$formated_menu[$menu[$key]['mu_parent_id']]['child_menu'][$menu[$key]["mu_id"]] = $menu[$key];	
						}
                    }
                }
            }
            return $formated_menu;
        } else {
            return NULL;
        }
    }

    /*
      |------------------------------------------------
      | start: get_privilege_name function
      |------------------------------------------------
      |
      | This function Get Privilege name to access the page using URL
      |
     */

    public function get_privilege_name($url) {

        if (is_array($url)) {
            $data = $url;
            foreach ($url as $keys => $action) {
                $this->db->select("*");
                $this->db->where("mu_url", $action['action']);
                $this->db->where("mu_active", 1);
                $this->db->where("mu_delete", 0);
                $query = $this->db->get($this->table);

                if ($query->num_rows() > 0) {
                    foreach ($query->result_array() as $key => $value) {
                        $data[$keys]['title'] = $value['mu_title'];
                        $data[$keys]['aClass'] = $value['mu_class'];
                        $data[$keys]['iClass'] = $value['mu_icon_class'];
                    }
                }
            }
            if (!empty($data)) {
                return $data;
            } else {
                return NULL;
            }
        } else {

            $this->db->select("mu_title");
            $this->db->where("mu_url", $url);
            $this->db->where("mu_active", 1);
            $this->db->where("mu_delete", 0);
            $query = $this->db->get($this->table);

            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $key => $value) {
                    $name = $value['mu_title'];
                }
                return $name;
            } else {
                return NULL;
            }
        }
    }

    /*
      |------------------------------------------------
      | start: get_parent_menu function
      |------------------------------------------------
      |
      | This function Get Parent Menus
      |
     */

    public function get_parent_menu() {
        $this->db->select("*");
        $this->db->order_by('mu_id', 'ASC');
        $this->db->where("mu_active", 1);
        $this->db->where("mu_delete", 0);
        $this->db->where("mu_parent_id", 0);
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return NULL;
        }
    }

    /*
      |------------------------------------------------
      | start: add_menu function
      |------------------------------------------------
      |
      | This function Add Menu
      |
     */

    public function add_menu() {

        $anchor_with_slash = substr($this->input->post('add_url'), -1);
        $anchor_with_slash = ($anchor_with_slash == "/") ? $this->input->post('add_url') : $this->input->post('add_url') . "/";
        $anchor_with_slash = str_replace("//", "/", $anchor_with_slash);

        $data = array(
            'mu_parent_id' => ($this->input->post('have_parent') == 1) ? $this->input->post('parent') : 0,
            'mu_order_by' => $this->input->post('order_by'),
            'mu_title' => $this->input->post('add_name'),
            'mu_class' => $this->input->post('anchor'),
            'mu_url' => $anchor_with_slash,
            'mu_icon_class' => $this->input->post('icon'),
            'mu_main_menu' => $this->input->post('main_menu'),
            'mu_active' => $this->input->post('active'),
            'mu_created_date' => date("Y-m-d H:i:s"),
            'mu_created_by' => $this->flexi_auth->get_user_id()
        );

        $this->db->trans_begin();

        $this->db->set($data);
        $this->db->insert($this->table);
        $id = $this->db->insert_id();

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $b = $this->db->trans_commit();
            return $id;
        }
    }

    /*
      |------------------------------------------------
      | start: get_menu function
      |------------------------------------------------
      |
      | This function Get Menus and its sub Menus
      |
     */

    public function get_current_menu($id) {
        $this->db->select("*");
        $this->db->order_by('mu_id', 'ASC');
        $this->db->where("mu_id", $id);
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return NULL;
        }
    }

    /*
      |------------------------------------------------
      | start: update_menu function
      |------------------------------------------------
      |
      | This function Update Menu
      |
     */

    public function update_menu() {

        $anchor_with_slash = substr($this->input->post('add_url'), -1);
        $anchor_with_slash = ($anchor_with_slash == "/") ? $this->input->post('add_url') : $this->input->post('add_url') . "/";
        $anchor_with_slash = str_replace("//", "/", $anchor_with_slash);

        $data = array(
            'mu_parent_id' => ($this->input->post('have_parent') == 1) ? $this->input->post('parent') : 0,
            'mu_order_by' => $this->input->post('order_by'),
            'mu_title' => $this->input->post('add_name'),
            'mu_class' => $this->input->post('anchor'),
            'mu_url' => $anchor_with_slash,
            'mu_icon_class' => $this->input->post('icon'),
            'mu_main_menu' => $this->input->post('main_menu'),
            'mu_active' => $this->input->post('active'),
            //'mu_created_date' => date("Y-m-d H:i:s"),
            'mu_created_by' => $this->flexi_auth->get_user_id()
        );

        $this->db->trans_begin();

        $this->db->set($data);
        $this->db->where('mu_id', $this->input->post('id'));
        $this->db->update($this->table, $data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $b = $this->db->trans_commit();
            return TRUE;
        }
    }

    /*
      |------------------------------------------------
      | start: delete_menu function
      |------------------------------------------------
      |
      | This function Update Menu
      |
     */

    public function delete_menu() {
        $update_ids = array();
        $anchor_with_slash = substr($this->input->post('add_url'), -1);
        $anchor_with_slash = ($anchor_with_slash == "/") ? $this->input->post('add_url') : $this->input->post('add_url') . "/";
        $anchor_with_slash = str_replace("//", "/", $anchor_with_slash);

        $data = array(
            'mu_delete' => 1,
            'mu_delete_by' => $this->flexi_auth->get_user_id()
        );

        $this->db->trans_begin();
        $this->db->set($data);

        foreach ($this->input->post('delete_item') as $key => $val) {
            $update_ids = $this->get_child_ids($key);
            // Add parent id in array
            array_push($update_ids, $key);
            $this->db->where_in('mu_id', $update_ids);
            $this->db->update($this->table, $data);
        }
        
        /*
        // Hard Delete User Privileges and Group Privileges
        $this->db->where_in('user_privilege_users.upriv_users_menu_fk', $update_ids);
        $this->db->delete('user_privilege_users');
        
        $this->db->where_in('user_privilege_groups.upriv_groups_menu_fk', $update_ids);
        $this->db->delete('user_privilege_groups');*/
        

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $b = $this->db->trans_commit();
            return TRUE;
        }
    }

    /*
      |------------------------------------------------
      | start: check_parent_delete function
      |------------------------------------------------
      |
      | This function Check current parent delete status
      |
     */

    public function check_parent_delete($id) {
        $this->db->select("*");
        $this->db->where("mu_delete", 0);
        $this->db->where("mu_id", $id);
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /*
      |------------------------------------------------
      | start: get_child function
      |------------------------------------------------
      |
      | This function Gets ids of child
      |
     */

    public function get_child_ids($id) {
        $child_ids = array();
        $this->db->select("mu_id");
        $this->db->where("mu_delete", 0);
        $this->db->where("mu_parent_id", $id);
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $key => $value) {
                //array_push($child_ids,$value);
                $child_ids[] = $value["mu_id"];
            }
            return $child_ids;
        } else {
            return $child_ids;
        }
    }
	
	/*
      |------------------------------------------------
      | start: check_parent_active function
      |------------------------------------------------
      |
      | This function Check current parent active status
      |
     */

    public function check_parent_active($id) {
        $this->db->select("*");
        $this->db->where("mu_active", 1);
        $this->db->where("mu_id", $id);
        $query = $this->db->get($this->table);

        if ($query->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
