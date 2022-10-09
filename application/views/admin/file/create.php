<!-- start: MAIN CONTAINER -->
<div class="main-container">
	<div class="navbar-content divHtml"> 
		<!-- start: SIDEBAR -->
		
		<?php $this->load->view('admin/includes/sidebar'); ?>
		<!-- end: SIDEBAR --> 
	</div>
	<!-- start: PAGE -->
	<div class="main-content divHtml">
		<?php
			$attributes = array('class' => 'form-horizontal', 'role' => '', 'id' => 'fileUploadingForm');
			echo form_open_multipart(current_url(), $attributes);
		?>
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
		<div class="container" style="min-height: 550px;"> 
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
			
			<!-- statr: INCLUSE FOOTER -->
			<?php //$this->load->view('admin/scaning/scan_count'); ?>
			<!-- end: INCLUSE FOOTER --> 
			
			<div class="row">
				<div class="col-md-12">
					<!-- start: PROGRESS BARS PANEL -->
					<div class="panel panel-default">
						<div class="panel-heading">Subject</div>
						<div class="panel-body">
							<?php /* ?>
							<div class="form-group">
								<div class="form-validation">
									<label class="col-sm-2 control-label">
										File No<span class="symbol required"></span>
									</label>
									<div class="col-sm-4">
										<input placeholder="" name="file_no" id="file_no" class="form-control" type="text">
									</div>
								</div>
							</div>
							<?php */ ?>
							<div class="form-group">
								<div class="form-validation">
									<label class="col-sm-2 control-label">File Type<span style="color:red;">*</span></label>
									<div class="col-sm-4">
										<select name="file_type_id" id="file_type_id" class="form-control" onchange="search_category_name()">
											<option value="">-----Select File Type-----</option>
											<?php
												if($file_types) {
													foreach($file_types as $file_type) {
														$select = (isset($file_data) && $file_type['fileTypeId'] == $file_data[$file_id]['fileTypeId']) ? 'selected="selected"' : set_select('file_type_id', $file_type['fileTypeId']);
														echo '<option '.$select.' value="'.$file_type['fileTypeId'].'">'.$file_type['fileType'].'</option>';
													}
												}
											?>
										</select>
									</div>
								</div>
								<div class="form-validation">
									<div id="general_cat_name">
									<label class="col-sm-2 control-label">Category Name </label>
										<div class="col-sm-4">
											<select name="general_category_id" id="general_category_id" class="form-control" disabled>
												<option value="">-----Select Category Name-----</option>
												
											</select>
										</div>
										<div class="loading-image-area loading-image" style="margin-top: -5px;">
											<label class="col-sm-6 control-label" for="form-field-1"><img src="<?php echo $includes_dir; ?>admin/images/rounded-light.gif" style="margin-left: 160px;"></label>
										</div>
								</div>
								</div>
								</div>
								<div class="form-group">
									<div class="form-validation">
										<div class="check_file_type" style="display:<?php echo ($file_data[$file_id]['fileTypeId'] == 1 || $file_data[$file_id]['fileTypeId'] == 2) ? 'block' : 'none'; ?>;">
										<label class="col-sm-2 control-label">CNIC<span id="cnic_required" style="color:red;">*</span></label>
										<div class="col-sm-4">
											<input value="<?php echo isset($file_data) ? $file_data[$file_id]['employeeCNIC'] : set_value('employee_cnic'); ?>" <?php echo isset($file_data) ? 'readonly="readonly"' : ''; ?> name="employee_cnic" id="employee_cnic" type="text" class="form-control input-mask-cnic" autocomplete="off">
											<div id="employee_exists"></div>
											<input type="hidden" value="" name="data_exists" id="data_exists">
										</div>
										</div>
									</div>
									
									<div class="form-validation">
										<div class="check_file_type" style="display:<?php echo ( $file_data[$file_id]['fileTypeId'] == 1 || $file_data[$file_id]['fileTypeId'] == 2) ? 'block' : 'none'; ?>;">
										<label class="col-sm-2 control-label">Employee Name <span style="color:red;">*</span></label>
										
										<div class="col-sm-4">
											<input  <?php echo (set_value('file_type_id') == 1) ? 'readonly' : ''; ?> value="<?php echo isset($file_data) ? $file_data[$file_id]['employeeName'] : set_value('employee_name'); ?>" name="employee_name" id="employee_name" readonly type="text" class="form-control" autocomplete="off">
											<span class="scanning_alert help-block" style="display: none;"></span>
											<div class="loading-empname-image">
												<label class="col-sm-6 control-label" for="form-field-1"><img src="<?php echo $includes_dir; ?>admin/images/rounded-light.gif"></label>
											</div>
										</div>
									
								</div>
							</div>
							</div>
							<div class="form-group">
								<div class="from-validation">
									<label class="col-sm-2 control-label">Department File Number</label>
									<div class="col-sm-4">
										<input value="<?php echo isset($file_data) ? $file_data[$file_id]['oldFileNumber'] : set_value('old_file_number'); ?>" name="old_file_number" id="old_file_number" type="text" class="form-control">
									</div>
								</div>
								<div class="form-validation">
									<label class="col-sm-2 control-label">
										Subject
									</label>
									<div class="col-sm-4">
										<textarea name="description" id="description" class="form-control"><?php echo isset($file_data[$file_id]['description']) ? $file_data[$file_id]['description'] : '' ?></textarea>
									</div>
								</div>

							</div> 
							<p class="text-right">
								<input type="hidden" value="<?php echo $submit_check_session; ?>" name="submit_check_session">
								<!-- Contextual button for informational alert messages -->
								<button type="submit" name="generate" value="generate" class="btn btn-success top_btn" id="continue_working">
									Continue Working
								</button>
								
							</p>
						</div>
											
						
						
					</div>
					<!-- end: PROGRESS BARS PANEL -->
				</div>
			</div>
			
		</div>
		<?php echo form_close(); ?>
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
<!-- end: CSS REQUIRED FOR THIS PAGE ONLY --> 

<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 
<script src="<?php echo $includes_dir; ?>admin/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/plugins/select2/select2.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/datetimepicker/build/jquery.datetimepicker.full.js"></script> 
<script src="<?php echo $includes_dir; ?>admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> 
<script src="<?php echo $includes_dir; ?>admin/plugins/jquery.maskedinput/src/jquery.maskedinput.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/jquery-maskmoney/jquery.maskMoney.js"></script>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->         

<script>
	
	jQuery(document).ready(function () {
		Main.init();
		maskCNIC();
		FormValidator.init();
	});


	$('.datepicker').datetimepicker({
		timepicker: false,
		format: 'Y-m-d',
		scrollMonth : false,
		scrollInput : false,
		maxDate : new Date
	});



	


	function maskCNIC(){
		$('.input-mask-cnic').mask('9999999999999');
	}
	jQuery.validator.addMethod("notNumber", function(value, element, param) {
				   var reg = /[0-9]/;
				   if(reg.test(value)){
						return false;
				   }else{
						return true;
				   }
			}, "Number is not permitted");
	var FormValidator = function () {
		// function to initiate category    
		var fileUploadingForm = function () {
		
			var form1 = $('#fileUploadingForm');
			var errorHandler1 = $('.errorHandler', form1);
			var successHandler1 = $('.successHandler', form1);
			$('#fileUploadingForm').validate({
				
				errorElement: "span", // contain the error msg in a span tag
				errorClass: 'help-block',
				errorPlacement: function (error, element) {
					error.insertAfter(element);
					// for other inputs, just perform default behavior
				},
				ignore: "",
				rules: {
					description:{
						required: false
					},
					file_type_id: {
								required: true
							},
							employee_name:{
								required : function(element) {
									if($("#file_type_id option:selected").val() == '1' || $("#file_type_id option:selected").val() == '2') {
										return true;
									} 
									else{
										return false;
									}
								},
								notNumber:true
							},
							employee_cnic:{
								required : function(element) {
									if($("#file_type_id option:selected").val() == '1' || $("#file_type_id option:selected").val() == '2' ) {
										return true;
									} 
									else{
										return false;
									}
								}
							},
				},
				messages: {
					//file_no: "Enter File No.",
					description: "Enter Description",
					employee_name: {
								required: "Please Enter Employee Name",
								notNumber: "Numbers are not Allowed"
							}, 
					employee_cnic: "Please Enter CNIC Number",
					
				},
				invalidHandler: function (event, validator) { //display error alert on form submit
					successHandler1.hide();
					errorHandler1.show();
				},
				highlight: function (element) {
					$(element).closest('.help-block').removeClass('valid');
					// display OK icon
					$(element).closest('.form-validation').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
					// add the Bootstrap error class to the control validation
				},
				unhighlight: function (element) { // revert the change done by hightlight
					$(element).closest('.form-validation').removeClass('has-error');
					// set error class to the control validation
				},
				success: function (label, element) {
					label.addClass('help-block valid');
					// mark the current input as valid and display OK icon
					$(element).closest('.form-validation').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
				},
				submitHandler: function (form) {
					successHandler1.show();
					errorHandler1.hide();
					// submit form
					//$('#form').submit();
					HTMLFormElement.prototype.submit.call($('#fileUploadingForm')[0]);
				}
			});
		};
		
		return {
			//main function to initiate pages
			init: function () {
				fileUploadingForm();
			}
		};
	}();
	
	$("#category_id").change(function () {
		var category_id = $(this).val();
		
		if(!category_id) {
			$('#sub_category_id').empty().append('<option value="">Select Sub Category</option>');
			return true;
		}
		
		$.ajax({
			url: '<?php echo base_url(); ?>admin/receipts/ajax_get_sub_category',
			type: 'POST',
			dataType: "JSON",
			data: {category_id: category_id},
			success: function (response) {
				//console.log(response.length);
				var select = $('#sub_category_id');

				select.empty();
				select.append('<option value="">Select Sub Category</option>');

				if (response.length != 0) {
					$.each(response, function (i, fb) {
						console.log(fb);
						select.append('<option value="' + fb.subCategoryId + '">' + fb.subCategoryName + '</option>');
					});
				}
			},
			error: function () {
				console.log('Error in retrieving Site.');
			}
		});

	});

	$('#file_type_id').on('change', function() {
		var file_type_id = this.value;
		if (file_type_id != 1 || file_type_id != 2) 
		{
			$('#general_category_id').val('');	
		}
  	});

	function search_category_name() {
			
			var file_type = $("#file_type_id").val();

			//alert(file_type);
			
			if(file_type == 1 || file_type == 2){
				$('#general_category_id').attr('disabled',true);
				$('.check_file_type').css('display','block');
			}else{
				$('#general_category_id').attr('disabled',false);
				$('.check_file_type').css('display','none');
			
			$(".loading-image-area").show();
			$('#general_category_id').html('<option value=""> Select Category Name </option>');
			$.ajax({
            url: '<?php echo $base_url; ?>admin/scaning/search_category_name',
            type: 'POST',
			dataType: 'JSON',
            data: {file_type_id:file_type},
		    success: function(response) {
				var category_name = $('#general_category_id');
                console.log(response);	
				if(response != ''){
					$.each(response.general_category, function (i, fb) {
							console.log(fb.isExtraCategory);
							
							<?php 
								$style = 'style="color:#000000;background-color:#FFFFFF"';
								$current_user_id = $this->flexi_auth->get_user_id();
								$extra = in_array($current_user_id,$this->config->item("showExtraCategoriesToIds"));
								//echo $extra;
							?>
							<?php if(in_array($current_user_id,$this->config->item("showExtraCategoriesToIds"))) { ?>
							if(fb.isExtraCategory==1){ 
								category_name.append('<option value="' + fb.generalCategoryId + '"style="color:#FFFFFF;background-color:#00641D">' + fb.generalCategoryName + '</option>');
							}
							<?php } ?>
							if(fb.isExtraCategory!=1){
								category_name.append('<option value="' + fb.generalCategoryId + '"style="color:#000000;background-color:#FFFFFF"">' + fb.generalCategoryName + '</option>');
							}
							
						});
			    $(".loading-image-area").hide();
				}
            },
            error: function () {
                console.log('Error in retrieving Site.');
            }
        }); }
		}


		//$("#employee_cnic").focusout(function () {
		$("#employee_cnic").keyup(function () {
			
			$('#employee_exists').html('');
			$('#employee_name').val('');
			$('#found_file_container').hide();
			
			var employee_cnic = $(this).val();
			var file_type_id = $('#file_type_id').val();
			
			var emp_cnic_validation = $(this).val().replace(/_/g, ""); //it will remove all _________ dashes from string
			//this condition check if EMP CNIC is not empty then it will send HRIMS request otherwise it will not send any request
			
			if(file_type_id == 2 && emp_cnic_validation.length < 13){
				 //console.log("IN my condition");
				 $("#employee_name").attr("readonly", false); 
			}
			
			if(emp_cnic_validation != "" && emp_cnic_validation.length == 13){ 
			
				//$('#submit_btn').removeAttr('disabled');
				$('#submit_btn').prop('disabled', true);
				$('.loading-empname-image').show();
				$.ajax({
					url: '<?php echo base_url() ?>admin/scaning/ajax_get_file_details',
					type: 'POST',
					dataType: "JSON",
					//data: {employee_cnic: employee_cnic, file_type_id: file_type_id},
					data: {employee_cnic: employee_cnic},
					success: function (response) {
						//console.log(response.length);
						if(response.response_data) {
							//$('#employee_exists').html(response.employeeName+' <a href="<?php echo base_url() ?>admin/scaning/edit/'+response.fileId+'">Edit</a>');
							//$('#employee_exists').html('<div class="alert alert-success"><a href="<?php echo base_url() ?>admin/scaning/edit/'+response.fileId+'" class="btn btn-green pull-right edit_btn">Edit</a><i class="fa fa-check-circle"></i><strong> Records Exist!</strong> with the name of '+response.employeeName+'</div>');
							$('#employee_exists').html('<div class="alert alert-success">'+response.response_data.emp_name_father+'</div>');
							//$('#submit_btn').attr('disabled', 'disabled');
							$('#employee_name').val(response.response_data.emp_name);
							
							
							$('#data_exists').val(response.response_data.data_exists);
							$('#submit_btn').prop('disabled', false);
							$("#employee_name").attr("readonly", true);
							$('.scanning_alert').text('').hide();
						}
						else{
							if(file_type_id == 2){
								$('#submit_btn').prop('disabled', false);
								 $("#employee_name").attr("readonly", true); 
								 
							}
							$('.scanning_alert').text('Record not found').show();
							
						}
						$('.loading-empname-image').hide();	
					},
					error: function () {
						console.log('Error in retrieving Site.');
					}
				});
				
				
				
				
				$.ajax({
					url: '<?php echo base_url() ?>admin/scaning/ajax_get_uploaded_file',
					type: 'POST',
					dataType: "JSON",
					//data: {employee_cnic: employee_cnic, file_type_id: file_type_id},
					data: {employee_cnic: employee_cnic},
					success: function (response) {
						//console.log(response.length);
						if(response.file_detail.length != 0) {
							
							var table = $('#found_files');
		

							table.empty();
							
							var html = '<tr><th></th><th>File Name</th><th>File Type</th></tr>';
							
							//if (response.division.length != 0) {
								$.each(response.file_detail, function (i, fb) {
									console.log(fb);
									//select.append('<option value="' + fb.divisionID + '">' + fb.divisionName + '</option>');
									
									html += '<tr>';
									html += '<td><span class="preview"><a href="<?php echo $base_url; ?>admin/scaning/scaning_detail/'+fb.fileId+'" title="'+fb.fileName+'" data-gallery target="_blank"><img src="<?php echo $includes_dir; ?>admin/images/pdf-icon.png" style="height: 25px;"></a></span></td>'
									html += '<td><p class="name"><a style="width: 181px; float: left; overflow: hidden;" href="<?php echo $base_url; ?>admin/scaning/scaning_detail/'+fb.fileId+'" title="'+fb.fileName+'" target="_blank">'+fb.fileName+'</a></p></td>'
									html += '<td>'+fb.fileType+'</td>';
									html += '</tr>';
									
								});
								
								table.append(html);
							//}
							
							
							$('#found_file_container').show();
						}
						
					},
					error: function () {
						console.log('Error in retrieving Site.');
					}
				});
			}
		});
			
</script>

<style>
	.panel-heading{ padding-left:10px !important; background-image:none; border-radius:0; box-shadow:none; }
	.form-group { margin:3px !important;background: #f6f6f6;padding: 2px 4px;}
	select.form-control, input.form-control { height:28px !important; padding-left:0;border: solid 1px #8e8e8e;font-size: 11px;padding-left: 5px;color: #000;}
	.form-horizontal .control-label {padding: 5px 0!important; margin:0 !important;color:#000; font-weight: bold !important;font-size: 12px;text-align: left;}
	select.form-control { font-size: 11px;color:#000; font-weight:normal !important;}
	.panel-body {padding: 5px;}
	.panel-default>.panel-heading{color: #fff;font-weight: bold;background:#282828;}
	.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control{font-size: 12px;padding-left: 5px;}
	 .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10,{ padding-left:5px; padding-right:5px;}
	.has-error .form-control{border-color: red;}
	.has-error .help-block, .has-error .control-label, .has-error .radio, .has-error .checkbox, .has-error .radio-inline, .has-error .checkbox-inline{color: red; font-size:12px;}
	
.pdf_upload_box_afterupload{ border:dashed 3px #CCC; height:70px; margin:0 auto; padding:10px;}
.pdf_icon{ margin:30px 0;}
	
.image-upload-afterupload > input{display: none; }
.image-upload-afterupload img{width: 80%;cursor: pointer; /*margin-top:25px;*/}


.pdf_upload_box{ border:dashed 3px #CCC; width:100%; min-height:450px; margin:0 auto; text-align:center;}
	
.image-upload > input{display: none; }
.image-upload img{width: 50%;cursor: pointer; margin-top:25px;}
.loading-image {
			position: absolute;
			width: 30%;
			background-color: #e6e6e6;
			line-height: 26px;
			opacity: 0.5;
			right: 37px;
			display: none;
			}
			.loading-empname-image label img {
				height: 26px;
			}
			.loading-empname-image {
				width: 94% !important;
				background-color: #e6e6e6;
				top: 0px;
				height: 100%;
				display: none;	
			}
			.loading-empname-image .control-label  {
				position: absolute;
				height: 100%;
				width: 85%;
				text-align: center;
				top: 0;
			}
			.top_btn{background: #428bca;color: #fffefe;border-radius: 0;font-size: 12px;border: solid 1px #2e5d86;}
</style>
