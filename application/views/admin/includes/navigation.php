<?php
/*echo '<pre>'; print_r($menu);
if(isset($menu)) {
            //echo '<ul class="main-navigation-menu">';
            
                foreach($menu as $menu_parent) {
                    
                    $ective_menu = ($menu_parent['mu_active'] == 1) ? 'active open' : '';
                    
                    echo '<br />';
                    echo $searchStr = strpos(base_url(), $menu_parent['mu_url']);
                    
                    echo '<br />';
                    echo $menu_parent['mu_url'];
                    
                    //echo @$menu_parent['mu_children'];
                    
                    //echo '<li class="'.$ective_menu.'">';
                        $baseURL = isset($menu_parent['mu_children']) ? '' : base_url();
                        //echo '<a href="'.$baseURL.$menu_parent['mu_url'].'"><i class="'.$menu_parent['mu_icon_class'].'"></i>';
                            //echo '<span class="title"> '.$menu_parent['mu_title'].' </span>';
                                //echo isset($menu_parent['mu_children']) ? '<i class="icon-arrow"></i>' : '';
                            //echo '<span class="selected"></span>';
                        //echo '</a>';
                        
                        if(isset($menu_parent['mu_children'])) {
                            
                            //echo '<ul class="sub-menu">';
                            
                                foreach($menu_parent['mu_children'] as $menu_child) {
                                    //echo '<li>';
                                        //echo '<a href="'. base_url().$menu_child['mu_url'].'">';
                                            //echo '<span class="title"> '.$menu_child['mu_title'].' </span>';
                                        //echo '</a>';
                                    //echo '</li>';
                                }
                            
                            //echo '</ul>';
                            
                        }
                        
                    //echo '</li>';
                }
                    
            //echo '</ul>';
        }
die()*/
?>

<div class="navbar-content">
    <!-- start: SIDEBAR -->
    <div class="main-navigation navbar-collapse collapse">
        <!-- start: MAIN MENU TOGGLER BUTTON -->
        <div class="navigation-toggler">
            <i class="clip-chevron-left"></i>
            <i class="clip-chevron-right"></i>
        </div>
        <!-- end: MAIN MENU TOGGLER BUTTON -->
        <!-- start: MAIN NAVIGATION MENU -->
        <?php
        if(isset($menu)) {
            echo '<ul class="main-navigation-menu">';
            
                foreach($menu as $menu_parent) {
                    
                    $ective_menu = ($menu_parent['mu_active'] == 1) ? 'active open' : '';
                    
                    //echo $searchStr = strpos(base_url(), $menu_parent['mu_children']);
                    
                    echo '<li class="'.$ective_menu.'">';
                        $baseURL = isset($menu_parent['mu_children']) ? '' : base_url();
                        echo '<a href="'.$baseURL.$menu_parent['mu_url'].'"><i class="'.$menu_parent['mu_icon_class'].'"></i>';
                            echo '<span class="title"> '.$menu_parent['mu_title'].' </span>';
                                echo isset($menu_parent['mu_children']) ? '<i class="icon-arrow"></i>' : '';
                            echo '<span class="selected"></span>';
                        echo '</a>';
                        
                        if(isset($menu_parent['mu_children'])) {
                            
                            echo '<ul class="sub-menu">';
                            
                                foreach($menu_parent['mu_children'] as $menu_child) {
                                    echo '<li>';
                                        echo '<a href="'. base_url().$menu_child['mu_url'].'">';
                                            echo '<span class="title"> '.$menu_child['mu_title'].' </span>';
                                        echo '</a>';
                                    echo '</li>';
                                }
                            
                            echo '</ul>';
                            
                        }
                        
                    echo '</li>';
                }
                    
            echo '</ul>';
        }
        ?>
        <!-- end: MAIN NAVIGATION MENU -->
    </div>
    <!-- end: SIDEBAR -->
</div>