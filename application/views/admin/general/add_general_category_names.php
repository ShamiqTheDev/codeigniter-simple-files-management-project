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
							  echo form_hidden('generalCategoryId',$generalCategoryName['generalCategoryId']);
						?>
                        <div class="panel-body">
							<div class="form-group">
                                <label class="col-sm-3 control-label" for="project_name">
                                    General Category Name
								</label>
                                <div class="col-sm-8">
									<input type="text" name="general_category_name" id="general_category_name" value="<?php echo isset($generalCategoryName['generalCategoryName']) ? $generalCategoryName['generalCategoryName'] : '' ?>" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="project_name">
                                    File Type
								</label>
								<div class="col-sm-8">
									<select name="file_type_id" id="file_type_id" class="form-control">
											<option value="">-----Select File Type-----</option>
											<?php
												if($file_types) {
													foreach($file_types as $file_type) {
														$selected = (isset($_POST["file_type_id"]) && ($_POST["file_type_id"] == $file_type['fileTypeId'])) || (isset($generalCategoryName["fileTypeId"]) && ($generalCategoryName["fileTypeId"] == $file_type['fileTypeId'])) ? "selected='selected'" : "" ;
														echo '<option value="'.$file_type['fileTypeId'].'"'.$selected.'>'.$file_type['fileType'].'</option>';
													}
												}
											?>
								</select>
								</div>
							</div>
							<div class="form-group">
                                <label class="col-sm-3 control-label" for="project_name">
                                    Is Extra Category
								</label>
                                <div class="col-sm-8">
									<input type="checkbox" value="1" <?php echo ($generalCategoryName['isExtraCategory'] == 1) ? 'checked=""' : ''; ?> name="isExtraCategory" id="isExtraCategory">
								</div>
							</div>
						
						</div>
                        <div class="panel-body">
                            <div class="col-md-2 pull-right">
                                <button id="submit_btn" class="btn btn-yellow btn-block" type="submit">
                                    <?php echo 'Save' ?> <i class="fa fa-arrow-circle-right"></i>
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
	<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/select2/select2.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/datetimepicker/jquery.datetimepicker.css"/>
	<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/bootstrap-datepicker/datepicker.css">
	<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.css">
	
	<!-- Generic page styles -->
	<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/css/style.css">
	<!-- blueimp Gallery styles -->
	<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/css/blueimp-gallery.min.css">
	<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
	<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/css/jquery.fileupload.css">
	<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/css/jquery.fileupload-ui.css">
	<!-- CSS adjustments for browsers with JavaScript disabled -->
	<noscript><link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/css/jquery.fileupload-noscript.css"></noscript>
	<noscript><link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/css/jquery.fileupload-ui-noscript.css"></noscript>
	<!-- end: CSS REQUIRED FOR THIS PAGE ONLY --> 
	
	<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 
	<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
	<script src="<?php echo $includes_dir; ?>admin/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/plugins/select2/select2.min.js"></script>
	<script src="<?php echo $includes_dir; ?>admin/plugins/datetimepicker/build/jquery.datetimepicker.full.js"></script> 
	<script src="<?php echo $includes_dir; ?>admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> 
	<script src="<?php echo $includes_dir; ?>admin/js/script.js"></script> 
	<script src="<?php echo $includes_dir; ?>admin/plugins/jquery.maskedinput/src/jquery.maskedinput.js"></script>
	<script src="<?php echo $includes_dir; ?>admin/plugins/jquery-maskmoney/jquery.maskMoney.js"></script>	
	
	
	<script>
        $('.datepicker').datetimepicker({
			timepicker: false,
			format: 'd-m-Y',
			scrollMonth : false,
			scrollInput : false,
		});
        jQuery(document).ready(function () {
			Main.init();
			maskCNIC();
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
						general_category_name: {
							required: true
						},
						file_type_id: {
						required: true
						}
						
					},
					messages: {
						general_category_name: "Please Enter Category Name",
						file_type_id: "Please Select Valid File type",
						
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
	
	
	
