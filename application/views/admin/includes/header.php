<!DOCTYPE html>
<!-- Template Name: Clip-One - Responsive Admin Template build with Twitter Bootstrap 3.x Version: 1.4 Author: ClipTheme -->
<!--[if IE 8]><html class="ie8 no-js" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9 no-js" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
    <!--<![endif]-->
    <!-- start: HEAD -->
    <head>
        <!-- statr: INCLUSE HEAD -->
        <?php $this->load->view('admin/includes/head'); ?>
        <!-- end: INCLUSE HEAD -->
    </head>
    <!-- end: HEAD -->
    <!-- start: BODY -->
    <body>
        <!-- start: HEADER -->
        <div class="navbar navbar-inverse navbar-fixed-top">
            <!-- start: TOP NAVIGATION CONTAINER -->
            <div class="container">
                <div class="navbar-header">
                    <!-- start: RESPONSIVE MENU TOGGLER -->
                    <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
                        <span class="clip-list-2"></span>
                    </button>
                    <!-- end: RESPONSIVE MENU TOGGLER -->
                    <!-- start: LOGO -->                   
                    <div class="logo_img">
                        <a class="navbar-brand" href="<?php echo $base_url . 'auth_admin/'; ?>">
                            <!--<img src="<?php echo $this->config->item('logo'); ?>">-->
                            <img src="<?php echo $base_url . 'upload/' . get_logo(); ?>" >
                        </a>
                    </div>
                    <!-- end: LOGO -->
                </div>
                <div class="navbar-tools">
                    <!-- start: TOP NAVIGATION MENU -->
                    <ul class="nav navbar-right">
                        <!-- start: USER DROPDOWN -->
                        
                        <li class="dropdown current-user">
                            <a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" data-close-others="true" href="#">
                                <!--<img src="<?php echo $includes_dir; ?>admin/images/avatar-1-small.jpg" class="circle-img" alt="">-->
                                <span class="username"><?php echo $user_name; ?> <?php echo $user_section;?></span>
                                <i class="clip-chevron-down"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo $base_url; ?>auth_admin/update_account">
                                        <i class="clip-user-2"></i>
                                        &nbsp;My Profile
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="<?php echo $base_url; ?>auth/logout">
                                        <i class="clip-exit"></i>
                                        &nbsp;Log Out
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <!-- end: USER DROPDOWN -->
                    </ul>
                    <!-- end: TOP NAVIGATION MENU -->
                </div>
            </div>
            <!-- end: TOP NAVIGATION CONTAINER -->
        </div>
        <!-- end: HEADER -->