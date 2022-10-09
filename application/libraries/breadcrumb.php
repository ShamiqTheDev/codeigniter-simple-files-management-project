<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Breadcrumb {

    private $breadcrumbs = array();
    private $separator = '  /  ';
    private $start = '<div id="breadcrumb">';
    private $end = '</div>';

    public function __construct($params = array()) {
        if (count($params) > 0) {
            $this->initialize($params);
        }
    }

    private function initialize($params = array()) {
        if (count($params) > 0) {
            foreach ($params as $key => $val) {
                if (isset($this->{'_' . $key})) {
                    $this->{'_' . $key} = $val;
                }
            }
        }
    }

    function add($title, $href) {
        if (!$title OR ! $href)
            return;
        $this->breadcrumbs[] = array('title' => $title, 'href' => $href);
    }

    function output() {

        if ($this->breadcrumbs) {

            //$output = $this->start;
            $output = '';

            foreach ($this->breadcrumbs as $key => $crumb) {
                if ($key) {
                    //$output .= $this->separator;
                }

                if (end(array_keys($this->breadcrumbs)) == $key) {
                    $output .= '<li class="active">' . $crumb['title'] . '</li>';
                } else {
                    $output .= '<li><a href="' . $crumb['href'] . '">' . $crumb['title'] . '</a></li>';
                }
            }
            
            //return $output . $this->end . PHP_EOL;
            return $output . PHP_EOL;
        }

        return '';
    }
    
    function add_menu_breadcrumb() {
        
        $CI = get_instance();
        $CI->load->model('admin/navigation');

        $url_explode = explode('/', current_url());

        $a = isset($url_explode[6]) ? $url_explode[6] : '';

        $new_url = $url_explode[4].'/'.$url_explode[5].'/'.$a;

        $bread_menu = $CI->navigation->get_navigation();

        //echo '<pre>'; print_r($bread_menu);

        foreach($bread_menu as $bread_val) {

            if($bread_val['mu_url'] == $new_url) {
                $this->add($bread_val['mu_title'], base_url().$bread_val['mu_url']);
            }
            else {
                if(isset($bread_val['mu_children'])) {

                    foreach($bread_val['mu_children'] as $child_menu) {
                        if($child_menu['mu_url'] == $new_url) {
                            $this->add($bread_val['mu_title'], base_url().$bread_val['mu_url']);
                            $this->add($child_menu['mu_title'], base_url().$child_menu['mu_url']);
                        }
                    }

                }
            }

        }
        
    }

}
