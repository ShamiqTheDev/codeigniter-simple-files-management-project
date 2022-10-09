
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
                        <h1 class="col-sm-6">Insert User Account <small></small></h1>
                    </div>
                    <!-- end: PAGE TITLE & BREADCRUMB -->
                </div>
            </div>
            <!-- end: PAGE HEADER -->

            <!-- start: PAGE CONTENT -->
            <div class="row">
                <div class="col-sm-12">
                    <!-- start: TEXT FIELDS PANEL -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-external-link-square"></i>
                            Manage User Account
                            <!-- <div class="panel-tools">
                                    <a class="btn btn-xs btn-link panel-collapse collapses" href="#">
                                    </a>
                                    <a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal">
                                        <i class="fa fa-wrench"></i>
                                    </a>
                                    <a class="btn btn-xs btn-link panel-refresh" href="#">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                    <a class="btn btn-xs btn-link panel-expand" href="#">
                                        <i class="fa fa-resize-full"></i>
                                    </a>
                                    <a class="btn btn-xs btn-link panel-close" href="#">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div> -->

                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="col-sm-2 pull-left">
                                    <a href="<?php echo $base_url; ?>auth_admin/manage_user_accounts" class="btn btn-info ladda-button">Manage User Accounts</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end: TEXT FIELDS PANEL -->
                </div>
            </div>
            <!-- end: PAGE CONTENT-->

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
                            <!-- <div class="panel-tools">
                                <a class="btn btn-xs btn-link panel-collapse collapses" href="#">
                                </a>
                                <a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <a class="btn btn-xs btn-link panel-refresh" href="#">
                                    <i class="fa fa-refresh"></i>
                                </a>
                                <a class="btn btn-xs btn-link panel-expand" href="#">
                                    <i class="fa fa-resize-full"></i>
                                </a>
                                <a class="btn btn-xs btn-link panel-close" href="#">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div> -->

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
                                            'type' => 'text',
                                            'name' => 'register_first_name',
                                            'id' => 'register_first_name',
                                            'value' => set_value('register_first_name'),
                                            'class' => 'form-control',
                                            'placeholder' => ''
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
                                            'type' => 'text',
                                            'name' => 'register_last_name',
                                            'id' => 'register_last_name',
                                            'value' => set_value('register_last_name'),
                                            'class' => 'form-control',
                                            'placeholder' => ''
                                        );

                                        echo form_input($input_data);
                                        ?>
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
                                            'type' => 'text',
                                            'name' => 'register_phone_number',
                                            'id' => 'register_phone_number',
                                            'value' => set_value('register_phone_number'),
                                            'class' => 'form-control',
                                            'placeholder' => ''
                                        );

                                        echo form_input($input_data);
                                        ?>
                                    </div>
                                </div>
                                <input type="hidden" name="register_newsletter" value="0">
                                <?php /* ?>
                                  <div class="form-group">
                                  <label class="col-sm-3 control-label" for="form-field-1">
                                  Subscribe to Newsletter
                                  </label>
                                  <div class="col-sm-7">
                                  <input type="checkbox" id="register_newsletter" name="register_newsletter" value="1"/>
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
                                            'type' => 'email',
                                            'name' => 'register_email_address',
                                            'id' => 'register_email_address',
                                            'value' => set_value('register_email_address'),
                                            'class' => 'form-control',
                                            'placeholder' => ''
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
                                            'type' => 'text',
                                            'name' => 'register_username',
                                            'id' => 'register_username',
                                            'value' => set_value('register_username'),
                                            'class' => 'form-control',
                                            'placeholder' => ''
                                        );

                                        echo form_input($input_data);
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="form-field-1">
                                        Password <span class="validField">*</span>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php
                                        $input_data = array(
                                            'type' => 'password',
                                            'name' => 'register_password',
                                            'id' => 'register_password',
                                            'value' => '',
                                            'class' => 'form-control',
                                            'placeholder' => ''
                                        );

                                        echo form_input($input_data);
                                        ?>
										<span style="display: none; color: #a94442; margin-top: 5px;" id="password_same">
											Username and password are same
										</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="form-field-1">
                                        Confirm Password <span class="validField">*</span>
                                    </label>
                                    <div class="col-sm-7">
                                        <?php
                                        $input_data = array(
                                            'type' => 'password',
                                            'name' => 'register_confirm_password',
                                            'id' => 'register_confirm_password',
                                            'value' => '',
                                            'class' => 'form-control',
                                            'placeholder' => ''
                                        );

                                        echo form_input($input_data);
                                        ?>
                                    </div>
                                </div>
								
								<?php /* ?>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="form-field-1">
										City <span class="validField">*</span>
									</label>
									<div class="col-sm-7">
										<select name="register_city" id="register_city" class="form-control">
											<option value="">--Select City--</option>
											<?php
											if($cities) {
												foreach($cities as $get_city) {
													echo '<option value="'.$get_city['cityId'].'">'.$get_city['cityName'].'</option>';
												}
											}
											?>
										</select>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label" for="form-field-1">
										Location <span class="validField">*</span>
									</label>
									<div class="col-sm-7">
										<select name="register_location" id="register_location" class="form-control">
											<option value="">--Select Location--</option>
											<?php
											if($location) {
												foreach($location as $get_location) {
													echo '<option value="'.$get_location['locationId'].'">'.$get_location['locationName'].'</option>';
												}
											}
											?>
										</select>
									</div>
								</div>
								<?php */ ?>
								
								<div class="form-group">
									<label class="col-sm-3 control-label" for="form-field-1">
										Section
									</label>
									<div class="col-sm-7">
										<select name="register_section[]" id="register_section" class="form-control" multiple>
											<!--<option value="">--Select Section--</option>-->
											<?php
											if($section) {
												foreach($section as $get_section) {
													echo '<option value="'.$get_section['sectionId'].'">'.$get_section['sectionName'].'</option>';
												}
											}
											?>
										</select>
									</div>
								</div>
								
								<div class="form-group">
                                    <label class="col-sm-3 control-label" for="form-field-1">
                                        Designation
                                    </label>
                                    <div class="col-sm-7">
                                        <select id="register_designation" name="register_designation" class="form-control">
											<option value="">--Select Designation--</option>
                                            <?php foreach ($designation as $get_designation) { ?>
                                                <option value="<?php echo $get_designation['designationId']; ?>">
                                                    <?php echo $get_designation['designationName']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label" for="form-field-1">
                                        Group <span class="validField">*</span>
                                    </label>
                                    <div class="col-sm-7">
                                        <select id="insert_group" name="insert_group" class="form-control">
                                            <?php foreach ($groups as $group) { ?>
                                                <option value="<?php echo $group[$this->flexi_auth->db_column('user_group', 'id')]; ?>">
                                                    <?php echo $group[$this->flexi_auth->db_column('user_group', 'name')]; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label class="col-sm-3 control-label" for="form-field-1">
                                        User Job Group
                                    </label>
                                    <div class="col-sm-7">
                                        <select id="register_user_job_group" name="register_user_job_group" class="form-control">
											<option value="">--Select User Job Group--</option>
                                            <?php foreach ($user_job_groups as $get_user_job_group) { ?>
                                                <option value="<?php echo $get_user_job_group['userJobGroupId']; ?>">
                                                    <?php echo $get_user_job_group['userJobGroupName']; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-group">
                                <div class="col-sm-2 pull-right">
                                    <input type="hidden" value="Submit" name="register_user">
                                    <button type="submit" class="btn btn-info btn-block" id="search_btn">
                                        Submit <i class=""></i>
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
<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/select2/select2.css" />
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
        TableData.init();
    });

	$('#register_password').focusout(function() {
		
		var register_password = $(this).val();
		var register_username = $('#register_username').val();
		
		if(register_password == register_username) {
			$('#register_password').val('');
			$('#register_confirm_password').val('');
			$('#password_same').css('display', 'block');
		}
		 else {
			$('#password_same').css('display', 'none');
		}
		
	})
	
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
                    register_first_name: {
                        required: true
                    },
                    register_email_address: {
                        required: true,
                        email: true
                    },
                    register_username: {
                        required: true
                    },
                    register_password: {
                        required: true,
                    },
                    register_confirm_password: {
                        required: true,
                    },
                    insert_group: {
                        required: true,
                    },
					/*register_city: {
                        required: true,
                    },
					register_location: {
                        required: true,
                    },*/
					/*'register_section[]': {
						required: true,
					}*/
                },
                messages: {
                    register_first_name: "Please enter First Name",
                    register_email_address: "Please enter vaild Email Address",
                    register_username: "Please enter Username ",
                    register_password: "Please enter Password",
                    register_confirm_password: "Please enter Confirm Password and should ne match with above Password",
                    insert_group: "Please select User Group",
                    //register_city: "Please select City",
                    //register_location: "Please select Location",
                    //'register_section[]': "Please select section",
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
	
    $("#register_username").focusout(function (e) {
        var username = $(this).val();
        $.ajax({
            url: '<?php echo base_url(); ?>auth_admin/check_username',
            type: 'POST',
            data: {username: username},
            dataType: "JSON",
            success: function (data) {
                if (data == 1) {
                    $("#register_username").val('');
                    alert('Username already exist, please enter another');
                }
            },
            error: function () {
            }
        });
		
		var register_password = $('#register_password').val();
		
		if(register_password == username) {
			$('#register_password').val('');
			$('#register_confirm_password').val('');
			$('#password_same').css('display', 'block');
		}
		 else {
			$('#password_same').css('display', 'none');
		}
		
        //e.preventDefault();
    })
	
	
	/*$("#station_id").change(function () {
		var station_id = $(this).val();
		
		if(!station_id) {
			$('#division_id').empty().append('<option value="">Select Division</option>');
			return true;
		}
		
		$(".loading-image-division").show();
		
		$.ajax({
			url: '<?php echo base_url(); ?>/admin/property_pt1/get_division_list',
			type: 'POST',
			dataType: "JSON",
			data: {station_id: station_id},
			success: function (response) {
				//console.log(response.length);
				var select = $('#division_id');
				
				
				select.empty();
				select.append('<option value="">Select Division</option>');
				
				if (response.division.length != 0) {
					$.each(response.division, function (i, fb) {
						console.log(fb);
						select.append('<option value="' + fb.divisionID + '">' + fb.divisionName + '</option>');
					});
				}
				
				
				
				$(".loading-image-division").hide();
				
			},
			error: function () {
				console.log('Error in retrieving Site.');
			}
		});
		
	});*/
	
</script>