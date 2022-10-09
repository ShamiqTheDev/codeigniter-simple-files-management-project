
<div class="main-navigation navbar-collapse collapse">
    <!-- start: MAIN MENU TOGGLER BUTTON -->
    <div class="navigation-toggler">
        <i class="clip-chevron-left"></i>
        <i class="clip-chevron-right"></i>
    </div>
    <!-- end: MAIN MENU TOGGLER BUTTON -->
    <!-- start: MAIN NAVIGATION MENU -->
    <ul class="main-navigation-menu">
        <?php
        //**************************
        //  DYNAMIC MENU WORK START
        //************************** 
		//echo "<pre>"; print_r($get_menu); die();
		
        if (!empty($get_menu)) {
            foreach ($get_menu as $get_val) {
				$menu_child = array();
                $main_menu_without_child = array();
                $mu_url = $get_val['mu_url'];
                $exploded_url = explode("/", $get_val['mu_url']);
				$get_val['only_privilege_count'] = false; // 
                if (isset($get_val['child_menu'])) {
                    if (sizeof($get_val['child_menu']) > 0) {
                        foreach ($get_val['child_menu'] as $key => $child_val) {
                            $menu_child[] = $child_val['mu_title'];
                            // Unset those childs which are not showing in menus
                            if ($child_val["mu_main_menu"] == 0) { 
								$get_val['only_privilege_count'] = true; // if menu has assigned any privilege then only_privilege_count = true 
                                unset($get_val['child_menu'][$key]);
                                if (sizeof($get_val['child_menu']) == 0) {
                                    unset($get_val['child_menu']);
                                }
                            }
                        }
                    }
                }
                $arrow_class = "icon-arrow";
				$hide_menu = false;
                // if no any child then
                if (!isset($get_val['child_menu'])) {
					// if no any child_menu and only_privilege_count = true then hide that menu
					/*if($get_val["only_privilege_count"]){
						$hide_menu = true;
					}*/
					
                    $main_menu_without_child[] = $get_val['mu_title'];
                    $arrow_class = "";
                }
			
                // If child menus then change url
                if (isset($get_val['child_menu'])) {
                    if (sizeof($get_val['child_menu']) > 0) {
                        $mu_url = "javascript:void(0)";
                    }
                }
				
				//echo $menu_title."---".$exploded_url[0]."---".$menu;print_r($main_menu_without_child);echo "--";print_r($menu_child);
				// allow scan_listing because for section $this->uri_privileged is not set
                if (($this->flexi_auth->is_privileged($this->uri_privileged) || $uri_3 == "scan_listing") && $hide_menu == false) {
                    ?>
                    <?php /*<li class="<?php echo (in_array($menu_title, $main_menu_without_child) || ($exploded_url[0] == $menu && empty($menu_child)) || (!empty($menu_child) && isset($menu_title) && in_array($menu_title, $menu_child))) ? 'active open' : '' ?>"> */?>
					<li class="<?php echo (in_array($menu_title, $main_menu_without_child) || (!empty($menu_child) && isset($menu_title) && in_array($menu_title, $menu_child))) ? 'active open' : '' ?>"> 
                        <a href="<?php echo (strpos($mu_url, 'javascript:void') !== false) ? "javascript:void(0);" : $base_url . $get_val['mu_url']; ?>"><i class="<?php echo $get_val['mu_icon_class']; ?>"></i>
                            <span class="title"> <?php echo $get_val["mu_title"] ?> </span>
                            <?php
                            if($arrow_class != ""){
                            ?>
                            <i class="<?php echo $arrow_class; ?>"></i>
                            <?php
                            }
                            ?>
                            <span class="selected"></span>
                        </a>
                        <?php
                        if (isset($get_val['child_menu'])) {
                            if (sizeof($get_val['child_menu']) > 0) { ?>
                                <ul class="sub-menu">
                                <?php
                                foreach ($get_val['child_menu'] as $child_val) {
                                    if ($child_val["mu_main_menu"] == 0)
                                        continue;

                                    if ($this->flexi_auth->is_privileged($this->uri_privileged) || $uri_3 == "scan_listing") {
                                        ?>
                                        
                                            <li class="<?php echo (uri_string() . '/' == $child_val['mu_url']) ? $child_val['mu_class'] : ""; ?>">
                                                <a href="<?php echo $base_url . $child_val['mu_url']; ?>"><i class="<?php echo $child_val['mu_icon_class']; ?>"></i>
                                                    <span class="title"> <?php echo $child_val['mu_title']; ?> </span>
													<?php if($child_val['mu_url'] == 'admin/receipts/inbox/'){
														if($receipt_inbox[0]['ccount'] > 0 ){ ?>
														<span class="badge badge-new" ><?php echo $receipt_inbox[0]['ccount'] ?></span> 
													<?php } } ?>
													<?php if($child_val['mu_url'] == 'admin/files/inbox/'){
														if($file_inbox[0]['ccount'] > 0 ){ ?>
														<span class="badge badge-new" ><?php echo $file_inbox[0]['ccount'] ?></span> 
													<?php } } ?>
                                                    <?php 
                                                    $status = str_replace('Orders', '', $child_val['mu_title']);
                                                    $status = trim($status);
                                                    if(isset($total_orders_by_status[$status])){ ?>
                                                    <span class="badge badge-new"><?php echo $total_orders_by_status[$status]; ?></span>
                                                    <?php } ?>
                                                </a>
												</li>
                                        
                                        <?php
                                    }
                                }
                                ?>
                                </ul>
                            <?php
                            }
                        }
                        ?>
                    </li>

                    <?php
                }
            }
        }
		//echo "<pre>"; print_r($main_menu_without_child); die();
        //**************************
        //  DYNAMIC MENU WORK END
        //**************************
        ?>
		
		<?php
		
		$section_session = $this->session->userdata('section');
		
		if(count($menu_sections) > 0 && $section_session){
			
			$active_menu = ($uri_3 == 'scan_listing') ? " active open " : "";
			/* if group is section officer and page is auth_admin then by default open section parent menu
			 ** slidedown_default_menu js defined in footer.php script
			 */
			$open_menu = ($uri_1 == 'auth_admin' && isset($uacc_group_fk) && $uacc_group_fk == 4) ? " open slidedown_default_menu ": "" ; 
		?>
			<li class="<?php echo $active_menu.$open_menu?>" ><a href="javascript:void(0)"> <i class="clip-tree"></i> <span class="title"> Sections </span> <i class="icon-arrow"></i><span class="selected"></span></a>
			<?php
			//echo "<pre>"; print_r($menu_sections);exit;
			foreach($menu_sections as $key=>$section){
			
				$disabled = TRUE;
				$active_open_submenu = false;
				// logic for bold user assigned sections name
				if(in_array($key, explode(',', $section_session))){
					$disabled = FALSE;
					// if group is section officer and page is auth_admin then by default open section child menus
					$active_open_submenu = ($uri_1 == 'auth_admin' && isset($uacc_group_fk) && $uacc_group_fk == 4) ? true : false ;
				}
					
			?>
				<ul class="sub-menu">
					<li class="<?php echo ((isset($section_selected) && $section_selected == $key) || ($active_open_submenu)) ? 'active open' : ''?>"><a href="javascript:void(0);"><span class="title" style="<?php echo $disabled ? '' : 'font-weight: bold'; ?>"><?php echo $section["sectionName"]?></span> <span class="badge badge-new" style="<?php echo $disabled ? 'background-color: #aaaaaa !important;' : ''; ?>"><?php echo $section["sectionCount"]?></span> <span class="selected"></span> </a>
						<ul class="sub-menu">
							<?php foreach($section["fileType"] as $fileType=>$fileCount){
								$url_listing = "javascript:void(0)";
								
								$disabled = TRUE;
								//if(($key == $section_session || !$section_session ) && $fileCount != 0){
								if(in_array($key, explode(',', $section_session)) && $fileCount != 0){
									$url_listing = $base_url . "admin/scaning/scan_listing/".$key."/".str_replace(" ","_",$fileType);
									$disabled = FALSE;
								}
							?>
							<li class="<?php echo (isset($fileType_selected) && $fileType_selected == $fileType) ? 'active open' : ''?>"><a href="<?php echo $url_listing ?>"><span class="title" style="<?php echo $disabled ? 'color: #a7aaaa;' : ''; ?>"><?php echo $fileType?></span> <span class="badge badge-new badge-child" style="color:#000; <?php echo $disabled ? 'background-color: #aaaaaa !important;' : ''; ?>"><?php echo $fileCount?></span> <span class="selected"></span></a></li>
							<?php }?>
						</ul>
					</li>
				</ul>
			<?php
			}	
			?>
			</li>
		<?php
		}
		?>
    </ul>
    <!-- end: MAIN NAVIGATION MENU -->
</div>
<script>
//$('.check').slideDown();
</script>