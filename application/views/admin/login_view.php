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
    <body class="login example2">
        <div class="main-login col-sm-4 col-sm-offset-4">
            <div class="logo"><img src="<?php echo $includes_dir; ?>admin/images/sindh-health-logo.png" /></div>
            <h4 class="center" style="color: #fff;margin: 0 0 10px 0;padding: 6px;background: #717171;border-radius: 4px;">eFile / Document Archiving</h4>
            <!-- start: Success and error message -->
            <?php if (!empty($message)) { ?>
                <div id="message">
                    <?php echo $message; ?>
                </div>
            <?php } ?>
            <!-- end: Success and error message -->
            <!-- start: LOGIN BOX -->
            <div class="box-login">
                <h3>Sign in to your account</h3>
                <p>
                    Please enter username and password to log in.
                </p>
                <?php
                $attributes = array('class' => 'form-login');
                echo form_open(current_url(), $attributes);
                ?>
                    <div class="errorHandler alert alert-danger no-display">
                        <i class="fa fa-remove-sign"></i> You have some form errors. Please check below.
                    </div>
                    <fieldset>
                        <div class="form-group">
                            <span class="input-icon">
                                <?php
                                $input_data = array(
                                        'type'          => 'text',
                                        'name'          => 'login_identity',
                                        'id'            => 'identity',
                                        'value'         => set_value('login_identity', ''),//admin@admin.com
                                        'class'         => 'form-control',
                                        'placeholder'   => 'Username'
                                );

                                echo form_input($input_data);
                                ?>
                                <i class="fa fa-user"></i> </span>
                        </div>
                        <div class="form-group form-actions">
                            <span class="input-icon">
                                <?php
                                $input_data = array(
                                        'type'          => 'password',
                                        'name'          => 'login_password',
                                        'id'            => 'password',
                                        'value'         => set_value('login_password', ''),//password123
                                        'class'         => 'form-control password',
                                        'placeholder'   => 'Password'
                                );

                                echo form_input($input_data);
                                ?>
                                <i class="fa fa-lock"></i>
                                <!--<a class="forgot" href="<?php //echo $base_url; ?>admin/auth/forgotten_password">
                                    I forgot my password
                                </a>--> </span>
                        </div>
						<?php /* ?><div class="form-group form-actions">
                            <span class="">
                                <select name = "division" id="division" class="form-control division">
									<option value="">Select Division</option>
									<?php
									foreach($divisions as $key=>$division){	
									?>
									<option value="<?php echo $key?>"><?php echo $division?></option>
									<?php
									}
									?>
								</select>
                                
                                <!--<a class="forgot" href="<?php echo $base_url; ?>admin/auth/forgotten_password">
                                    I forgot my password
                                </a>--> </span>
                        </div><?php */ ?>
                        <div class="form-actions">
                            <!--<label for="remember" class="checkbox-inline">
                                <?php
                                $check_data = array(
                                        'name'      => 'remember_me',
                                        'id'        => 'remember_me',
                                        'value'     => '1',
                                        'checked'   => set_checkbox('remember_me', TRUE),
                                        'class'     => 'grey remember'
                                );

                                echo form_checkbox($check_data);
                                ?>
                                Keep me signed in
                            </label>-->
                            <?php echo form_hidden('login_user', 'Submit'); ?>
                            <button type="submit" class="btn btn-bricky pull-right">
                                Login <i class="fa fa-arrow-circle-right"></i>
                            </button>
                        </div>
                    </fieldset>
                <?php echo form_close(); ?>
            </div>
            <!-- end: LOGIN BOX -->
            <!-- start: COPYRIGHT -->
            <div class="copyright">
                <!--2014 &copy; clip-one by cliptheme.-->
            </div>
            <!-- end: COPYRIGHT -->
        </div>
        <!-- statr: INCLUSE FOOT -->
        <?php $this->load->view('admin/includes/foot'); ?>
        <!-- end: INCLUSE FOOT -->
        
        <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
        <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
        
        <!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
        <script src="<?php echo $includes_dir; ?>admin/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
        <script src="<?php echo $includes_dir; ?>admin/js/login.js"></script>
        <!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
        <script>
            jQuery(document).ready(function () {
                Main.init();
                Login.init();
            });
        </script>
    </body>
    <!-- end: BODY -->
</html>