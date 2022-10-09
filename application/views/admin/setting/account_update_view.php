
<!-- start: MAIN CONTAINER -->
<div class="main-container">
    <div class="navbar-content">
        <!-- start: SIDEBAR -->
        <?php $this->load->view('admin/includes/sidebar'); ?>
        <!-- end: SIDEBAR -->
    </div>
    <!-- start: PAGE -->
    <div class="main-content">
        <!-- start: PANEL CONFIGURATION MODAL FORM -->
        <div class="modal fade" id="panel-config" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title">Panel Configuration</h4>
                    </div>
                    <div class="modal-body">
                        Here will be a configuration form
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-primary">
                            Save changes
                        </button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
        <!-- end: SPANEL CONFIGURATION MODAL FORM -->
        <div class="container">
            <!-- start: PAGE HEADER -->
            <div class="row">
                <div class="col-sm-12">
                    <!-- start: PAGE TITLE & BREADCRUMB -->
                    <ol class="breadcrumb">
                        <?php echo $this->breadcrumbs->show(); ?>
                    </ol>
                    <!-- start: Success and error message -->
                    <?php if (!empty($message)) { ?>
                        <div id="message">
                            <?php echo $message; ?>
                        </div>
                    <?php } ?>
                    <!-- end: Success and error message -->
                    <div class="page-header row">
                        <h1 class="col-sm-6">Update User Account <small></small></h1>
                    </div>
                    <!-- end: PAGE TITLE & BREADCRUMB -->
                </div>
            </div>
            <!-- end: PAGE HEADER -->
            
            <!-- start: PAGE CONTENT -->
            <div class="row">
                <div class="col-md-12">
                    <!-- start: BASIC TABLE PANEL -->
                    <?php
                    $attributes = array('class' => 'form-horizontal', 'role' => 'cat_form', 'id' => 'search_form');
                    echo form_open(current_url(), $attributes);
                    ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-external-link-square"></i>
                                User Account
                                
                            </div>
                            <div class="panel-body">
                                <fieldset>
                                    <legend>Personal Details</legend>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1">
                                            First Name <span class="validField">*</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <?php
                                            $input_data = array(
                                                    'type'          => 'text',
                                                    'name'          => 'update_first_name',
                                                    'id'            => 'first_name',
                                                    'value'         => set_value('update_first_name', $user['upro_first_name']),
                                                    'class'         => 'form-control',
                                                    'placeholder'   => ''
                                            );

                                            echo form_input($input_data);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1">
                                            Last Name
                                        </label>
                                        <div class="col-sm-7">
                                            <?php
                                            $input_data = array(
                                                    'type'          => 'text',
                                                    'name'          => 'update_last_name',
                                                    'id'            => 'last_name',
                                                    'value'         => set_value('update_last_name', $user['upro_last_name']),
                                                    'class'         => 'form-control',
                                                    'placeholder'   => ''
                                            );

                                            echo form_input($input_data);
                                            ?>
                                        </div>
                                    </div>
									
									<div class="form-group">
											<label class="col-sm-3 control-label" for="form-field-1">
											Section
										</label>
										<div class="col-sm-7">
											<select name="update_section[]" id="update_section" class="form-control" multiple>
												<!--<option value="">--Select Section--</option>-->
												<?php
												if($sections) {
													foreach($sections as $section) {
														$select = (in_array($section['sectionId'], explode(',', $user[$this->flexi_auth->db_column('user_acc', 'section')]))) ? 'selected="selected"' : '';
														echo '<option '.$select.' value="'.$section['sectionId'].'">'.$section['sectionName'].'</option>';
													}
												}
												?>
											</select>
										</div>
									</div>
									
                                </fieldset>

                                <fieldset>
                                    <legend>Contact Details</legend>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1">
                                            Phone Number
                                        </label>
                                        <div class="col-sm-7">
                                            <?php
                                            $input_data = array(
                                                    'type'          => 'text',
                                                    'name'          => 'update_phone_number',
                                                    'id'            => 'phone_number',
                                                    'value'         => set_value('update_phone_number', $user['upro_phone']),
                                                    'class'         => 'form-control',
                                                    'placeholder'   => ''
                                            );

                                            echo form_input($input_data);
                                            ?>
                                        </div>
                                    </div>
                                    <input type="hidden" name="update_newsletter" value="0">
                                    <?php /* ?>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1">
                                            Subscribe to Newsletter
                                        </label>
                                        <div class="col-sm-7">
                                            <?php $newsletter = ($user['upro_newsletter'] == 1); ?>
                                            <input type="checkbox" id="newsletter" name="update_newsletter" value="1" <?php echo set_checkbox('update_newsletter', '1', $newsletter); ?>/>
                                        </div>
                                    </div>
                                    <?php */ ?>
                                </fieldset>

                                <fieldset>
                                    <legend>Login Details</legend>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1">
                                            Email Address <span class="validField">*</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <?php
                                            $input_data = array(
                                                    'type'          => 'text',
                                                    'name'          => 'update_email',
                                                    'id'            => 'email_address',
                                                    'value'         => set_value('update_email', $user[$this->flexi_auth->db_column('user_acc', 'email')]),
                                                    'class'         => 'form-control',
                                                    'placeholder'   => ''
                                            );

                                            echo form_input($input_data);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1">
                                            Username <span class="validField">*</span>
                                        </label>
                                        <div class="col-sm-7">
                                            <?php
                                            $input_data = array(
                                                    'type'          => 'text',
                                                    'name'          => 'update_username',
                                                    'id'            => 'username',
                                                    'value'         => set_value('update_username', $user[$this->flexi_auth->db_column('user_acc', 'username')]),
                                                    'class'         => 'form-control',
                                                    'placeholder'   => '',
                                                    'readonly'      => 'readonly'
                                            );

                                            echo form_input($input_data);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label" for="form-field-1">
                                            Password
                                        </label>
                                        <div class="col-sm-7">
                                            <label class="col-sm-0 control-label" for="form-field-1"><a href="<?php echo $base_url . 'auth_admin/change_password/'.$user['uacc_id']; ?>" class="">Change your password</a></label>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="form-group">
                                    <div class="col-sm-2 pull-right">
                                        <input type="hidden" name="update_account" value="Submit"/>
                                        <button type="submit" class="btn btn-info btn-block" id="search_btn">
                                            Update <i class=""></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
                    <!-- end: BASIC TABLE PANEL -->
                </div>
            </div>
            <!-- end: PAGE CONTENT-->
        </div>
    </div>
    <!-- end: PAGE -->
</div>
<!-- end: MAIN CONTAINER -->

<!-- statr: INCLUSE FOOTER -->
<?php $this->load->view('admin/includes/footer'); ?>
<!-- end: INCLUSE FOOTER -->

<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/select2/select2.css" />>
<!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->

<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
<script src="<?php echo $includes_dir; ?>admin/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/js/form-validation-js.js"></script>
<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/plugins/select2/select2.min.js"></script>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->

<script>
    jQuery(document).ready(function () {
        Main.init();
        FormValidator.init();
        //TableData.init();
    });
	
	$.validator.addMethod("phone_number", function (value, element) {
		return this.optional(element) || value.match(/^0[\d]{10}$/g);
        }, "Please enter a valid number");
	
	var FormValidator = function () {
        // function to initiate category
        var addProductForm = function () {
            var form1 = $('#search_form');
            var errorHandler1 = $('.errorHandler', form1);
            var successHandler1 = $('.successHandler', form1);
            $('#search_form').validate({
                errorElement: "span", // contain the error msg in a span tag
                errorClass: 'help-block',
                errorPlacement: function (error, element) {
                    error.insertAfter(element);
                    // for other inputs, just perform default behavior
                },
                ignore: "",
                rules: {
                    update_first_name: {
                        required: true
                    },
                    update_email: {
                        required: true,
                        email: true
                    },
                    update_username: {
                        required: true
                    },
                    /*update_phone_number: {
                        required: true,
                        phone_number: true
                    },*/
					/*'update_section[]': {
						required: true,
					}*/
                 },
                messages: {
                    update_first_name: "Please enter First Name",
                    update_email: "Please enter vaild Email Address",
                    update_username: "Please enter Username ",
					//'update_section[]': "Please select section",
                    
                },
                invalidHandler: function (event, validator) { //display error alert on form submit
                    successHandler1.hide();
                    errorHandler1.show();
                },
                highlight: function (element) {
                    $(element).closest('.help-block').removeClass('valid');
                    // display OK icon
                    $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
                    // add the Bootstrap error class to the control group
                },
                unhighlight: function (element) { // revert the change done by hightlight
                    $(element).closest('.form-group').removeClass('has-error');
                    // set error class to the control group
                },
                success: function (label, element) {
                    label.addClass('help-block valid');
                    // mark the current input as valid and display OK icon
                    $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
                },
                submitHandler: function (form) {
                    successHandler1.show();
                    errorHandler1.hide();
                    // submit form
                    //$('#search_form').submit();
                    HTMLFormElement.prototype.submit.call($('#search_form')[0]);
                }
            });
        };


        return {
            //main function to initiate pages
            init: function () {
                addProductForm();
            }
        };
    }();
</script>