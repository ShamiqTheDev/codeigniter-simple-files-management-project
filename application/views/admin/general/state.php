<!-- start: MAIN CONTAINER -->
<div class="main-container">
	<div class="navbar-content divHtml"> 
		<!-- start: SIDEBAR -->
		
		<?php $this->load->view('admin/includes/sidebar'); ?>
		<!-- end: SIDEBAR --> 
	</div>
	<!-- start: PAGE -->
	<div class="main-content divHtml">
		<!-- start: PANEL CONFIGURATION MODAL FORM -->
		<div class="modal fade" id="panel-config" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
						<h4 class="modal-title">Panel Configuration</h4>
					</div>
					<div class="modal-body"> Here will be a configuration form </div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal"> Close </button>
						<button type="button" class="btn btn-primary"> Save changes </button>
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
					<?php if ($this->session->flashdata('message')) { ?>
						<div id="message"> <?php echo $this->session->flashdata('message'); ?> </div>
					<?php } ?>
					<!-- end: Success and error message -->
					<div style="padding-top:10px;"></div>
					
					<!-- end: PAGE TITLE & BREADCRUMB width="1090" height="300" --> 
				</div>
			</div>
			<!-- end: PAGE HEADER -->
			<div class="row">
                <div class="col-sm-12">
                    <!-- start: TEXT FIELDS PANEL -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-external-link-square"></i>
                            <?php echo $page_title; ?>
						</div>
                        <?php
							$attributes = array('class' => 'form-horizontal', 'role' => 'custom_field_form', 'id' => 'custom_field_form');
							echo form_open_multipart($formUrl, $attributes);
						?>
                        <div class="panel-body">
							<div class="form-group">
                                <label class="col-sm-3 control-label" for="project_name">
                                    Country <span style="color:red;">*</span>
								</label>
                                <div class="col-sm-8">
									<select class="form-control" name="country_id" id="country_id">
										<option value="">--Select Country--</option>
										<?php
											if($country) {
												foreach($country as $get_country) {
													$selected = ($state && $state['countryId'] == $get_country['countryId']) ? 'selected="selected"' : '';
													
													echo '<option '.$selected.' value="'.$get_country['countryId'].'">'.$get_country['countryName'].'</option>';
												}
											}
										?>
									</select>
								</div>
							</div>
							
							<div class="form-group">
                                <label class="col-sm-3 control-label" for="project_name">
                                    State Name <span style="color:red;">*</span>
								</label>
                                <div class="col-sm-8">
									<input type="text" name="state_name" id="state_name" value="<?php echo isset($state['stateName']) ? $state['stateName'] : '' ?>" class="form-control">
								</div>
							</div>
							
						</div>
                        <div class="panel-body">
                            <div class="col-md-2 pull-right">
                                <button id="submit_btn" class="btn btn-yellow btn-block" type="submit">
                                    <?php echo isset($state) ? 'Update' : 'Submit'; ?> <i class="fa fa-arrow-circle-right"></i>
								</button>
							</div>
						</div>
                        <?php echo form_close(); ?>
					</div>
                    <!-- end: TEXT FIELDS PANEL -->
				</div>
			</div>
			<!-- end: PAGE CONTENT-->
			
		</div>
		<!-- end: PAGE --> 
	</div>
</div>
<!-- end: MAIN CONTAINER --> 
<!-- statr: INCLUSE FOOTER -->
<?php $this->load->view('admin/includes/footer'); ?>
<!-- end: INCLUSE FOOTER --> 

<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
<!-- end: CSS REQUIRED FOR THIS PAGE ONLY --> 

<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 
<script src="<?php echo $includes_dir; ?>admin/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 

<script>
	
	jQuery(document).ready(function () {
		Main.init();
		FormValidator.init();
	});
	
	var FormValidator = function () {
		
		// function to initiate category    
		var custom_field_form = function () {
			var form1 = $('#custom_field_form');
			var errorHandler1 = $('.errorHandler', form1);
			var successHandler1 = $('.successHandler', form1);
			$('#custom_field_form').validate({
				
				errorElement: "span", // contain the error msg in a span tag
				errorClass: 'help-block',
				errorPlacement: function (error, element) {
                    error.insertAfter(element);
                    // for other inputs, just perform default behavior
				},
				ignore: "",
				rules: {
					country_id: {
						required: true
					},
					state_name: {
						required: true
					}
				},
				messages: {
					country_id: "Please select country",
					state_name: "Please enter state name",
					
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
					//$('#form').submit();
					HTMLFormElement.prototype.submit.call($('#custom_field_form')[0]);
				}
			});
		};
		
		return {
			//main function to initiate pages
			init: function () {
				custom_field_form();
			}
		};
	}();
	
</script>



