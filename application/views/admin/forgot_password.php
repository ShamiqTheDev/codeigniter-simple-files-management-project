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
            <div class="logo"><?php echo $this->config->item('logo'); ?></div>
            <!-- start: Success and error message -->
            <?php if (!empty($message)) { ?>
                <div id="message">
                    <?php echo $message; ?>
                </div>
            <?php } ?>
            <!-- end: Success and error message -->
            <!-- start: FORGOT BOX -->
            <div class="box-login">
                <h3>Forget Password?</h3>
                <p>
                    Enter your e-mail address below to reset your password.
                </p>
                <?php
                $attributes = array('class' => 'form-forgot');
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
                                        'type'          => 'email',
                                        'name'          => 'forgot_password_identity',
                                        'id'            => 'identity',
                                        'value'         => '',
                                        'class'         => 'form-control',
                                        'placeholder'   => 'Email'
                                );

                                echo form_input($input_data);
                                ?>
                                <i class="fa fa-envelope"></i> </span>
                        </div>
                        <div class="form-actions">
                            <a class="btn btn-light-grey" href="<?php echo $base_url; ?>admin/auth">
                                <i class="fa fa-circle-arrow-left"></i> Back
                            </a>
                            <?php echo form_hidden('send_forgotten_password', 'Submit'); ?>
                            <button type="submit" class="btn btn-bricky pull-right">
                                Submit <i class="fa fa-arrow-circle-right"></i>
                            </button>
                        </div>
                    </fieldset>
                <?php echo form_close(); ?>
            </div>
            <!-- end: FORGOT BOX -->
            <!-- start: COPYRIGHT -->
            <div class="copyright">
                2014 &copy; clip-one by cliptheme.
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