<?php 
	$case_date = isset($data['case']['caseDate'])
				?date('Y-m-d',strtotime($data['case']['caseDate'])):'';
	$hearing_date = isset($data['case']['hearingDate'])
				?date('Y-m-d',strtotime($data['case']['hearingDate'])):'';

?>
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
			$attributes = array('class' => 'form-horizontal', 'role' => '', 'id' => 'litigationForm');
			echo form_open_multipart(current_url(), $attributes);
		?>
		<!-- start: PANEL CONFIGURATION MODAL FORM -->
			<!-- removed modal code from here :not in use -->
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
			
			
			<div class="row">
				<div class="col-md-12">
					<!-- start: PROGRESS BARS PANEL -->
					<div class="panel panel-default">
						<div class="panel-heading">Casefile uploading</div>
						<div class="panel-body">
							<?php if (!empty($case_id)){?>
								<input type="hidden" name="case_id" value="<?=$case_id?>">
								<input type="hidden" name="members[0][memberId]" value="<?=$data['petitioner']['memberId']?>">
								<input type="hidden" name="members[1][memberId]" value="<?=$data['respondent']['memberId']?>">
							<?php } ?>
							<div class="panel-heading"><b>Petitioner Informatiion</b></div>
							<div class="form-group">
								<div class="form-validation">
									<label class="col-sm-1 control-label">First Name </label>
									<div class="col-sm-3">
										<input name="members[0][memberFirstName]" id="pet_fn" type="text" class="form-control" value="<?=$data['petitioner']['memberFirstName']?>">
									</div>
								</div>
								<div class="form-validation">
									<label class="col-sm-1 control-label">Last Name </label>
									<div class="col-sm-3">
										<input name="members[0][memberLastName]" id="pet_ln" type="text" class="form-control" value="<?=$data['petitioner']['memberLastName']?>">
									</div>
								</div>
								<div class="from-validation">
									<label class="col-sm-1 control-label"> Designation </label>
									<div class="col-sm-3">
										<select  name="members[0][designationId]" id="pet_desig" class="form-control">
											<option value=""> Select Designation </option>
											<?php
												if($designations) {
													$desig_id = $data['petitioner']['designationId'];
													foreach($designations as $desig) { 
														$select='';
														if ($desig['designationId'] == $desig_id) {
															$select = ' selected="selected" ';
														}
													?>
														<option value="<?=$desig['designationId']?>" <?=$select?>> <?=$desig['designationName']?></option>
													<?php }
												}
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="panel-heading"><b>Respondent Informatiion</b></div>
							<div class="form-group">
								<div class="form-validation">
									<label class="col-sm-1 control-label">First Name </label>
									<div class="col-sm-3">
										<input name="members[1][memberFirstName]" id="resp_fn" type="text" class="form-control" value="<?=$data['respondent']['memberFirstName']?>">
									</div>
								</div>
								<div class="form-validation">
									<label class="col-sm-1 control-label">Last Name </label>
									<div class="col-sm-3">
										<input name="members[1][memberLastName]" id="resp_ln" type="text" class="form-control" value="<?=$data['respondent']['memberLastName']?>">
									</div>
								</div>
								<div class="from-validation">
									<label class="col-sm-1 control-label"> Designation </label>
									<div class="col-sm-3">
										<select  name="members[1][designationId]" id="resp_desig" class="form-control">
											<option value=""> Select Designation </option>
											<?php
												if($designations) {
													$desig_id = $data['respondent']['designationId'];
													foreach($designations as $desig) { 
														$select='';
														if ($desig['designationId'] == $desig_id) {
															$select = ' selected="selected" ';
														}
													?>
														<option value="<?=$desig['designationId']?>" <?=$select?>> <?=$desig['designationName']?></option>
													<?php }
												}
											?>
										</select>
									</div>
								</div>
							</div>

							<div class="panel-heading"><b>Case Information</b></div>
							<div class="form-group">
								<div class="form-validation">
									<label class="col-sm-2 control-label">Court Type <span style="color:red;">*</span></label>
									<div class="col-sm-4">
										<select name="court" id="court" class="form-control">
											<option value="">Select Court Type</option>
											<?php
												if($courts_ops) {
													$court = $data['case']['court'];
													foreach($courts_ops as $key => $val) {
														$select='';
														if ($court == $key) {
															$select = ' selected="selected" ';
														}

													?>
														<option value="<?=$key?>" <?=$select?>> <?=$val?></option>
													<?php }
												}
											?>
										</select>
									</div>
								</div>
								<div class="from-validation">
									<label class="col-sm-2 control-label"> Case Number</label>
									<div class="col-sm-4">
										<input name="caseNo" id="caseNo" type="text" class="form-control" value="<?=$data['case']['caseNo']?>">
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="form-validation">
									<label class="col-sm-2 control-label"> Ground </label>
									<div class="col-sm-4">
										<input name="ground" id="ground" type="text" class="form-control" value="<?=$data['case']['ground']?>">
									</div>
								</div>
								<div class="from-validation">
									<label class="col-sm-2 control-label"> Related to </label>
									<div class="col-sm-4">
										<input name="relatedTo" id="relatedTo" type="text" class="form-control" value="<?=$data['case']['relatedTo']?>">
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="form-validation">
									<label class="col-sm-2 control-label"> Case Date </label>
									<div class="col-sm-4">
										<input name="caseDate" id="caseDate" type="text" class="form-control datepicker" autocomplete="off" value="<?=$case_date?>">
									</div>
								</div>
								<div class="from-validation">
									<label class="col-sm-2 control-label"> Case Hearing date </label>
									<div class="col-sm-4">
										<input name="hearingDate" id="hearingDate" type="text" class="form-control datepicker" autocomplete="off" value="<?=$hearing_date?>">
									</div>
								</div>
							</div>

							<div class="form-group">
								<div class="form-validation">
									<label class="col-sm-2 control-label">
										Memo subject 
									</label>
									<div class="col-sm-4">
										<textarea name="memo" id="memo" class="form-control"><?=$data['case']['memo']?></textarea>
									</div>
								</div>
							</div>
							<div class="panel-heading"><b>Documents Section</b></div>
							
							<?php 
							if (isset($case_id)){
								foreach ($documents as $doc) { ?>
								<div class="form-group">
									<div class="from-validation">
										<label class="col-sm-1 control-label"> Doc Name </label>
										<div class="col-sm-2">
											<input type="text" class="form-control" value="<?=$doc['docName']?>">
										</div>
									</div>
									<div class="from-validation">
										<label class="col-sm-1 control-label"> Doc No. </label>
										<div class="col-sm-2">
											<input type="text" class="form-control" value="<?=$doc['docNo']?>">
										</div>
									</div>
									<div class="from-validation">
										<label class="col-sm-1 control-label"> Doc Type </label>
										<div class="col-sm-3">
											<select class="form-control">
												<option value=""> Select Docuemnt Type </option>
												<?php
													if($doc_type_ops) {
														$doc_type = $doc['docType'];
														foreach($doc_type_ops as $key => $val) {
															$select=''; 
															if ($key == $doc_type) {
																$select = 'selected="selected"';
															}
														?>
															<option value="<?=$key?>" <?=$select?>> <?=$val?></option>
														<?php }
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-validation">
										<div class="col-sm-2">
											<a href="<?=base_url($doc['filePath'])?>" class="top_btn btn btn-primary" role="button" download>Download</a>
										</div>
									</div>
								</div>
								<div class="doc_row" style="display: none;">
									<div class="form-group">
										<div class="from-validation">
											<label class="col-sm-1 control-label"> Doc Name </label>
											<div class="col-sm-2">
												<input type="text" name="docs[docName][]" class="form-control">
											</div>
										</div>
										<div class="from-validation">
											<label class="col-sm-1 control-label"> Doc No. </label>
											<div class="col-sm-2">
												<input type="text" name="docs[docNo][]" class="form-control">
											</div>
										</div>
										<div class="from-validation">
											<label class="col-sm-1 control-label"> Doc Type </label>
											<div class="col-sm-3">
												<select name="docs[docType][]" class="form-control">
													<option value=""> Select Docuemnt Type </option>
													<?php
														if($doc_type_ops) {
															foreach($doc_type_ops as $key => $val) { ?>
																<option value="<?=$key?>"> <?=$val?></option>
															<?php }
														}
													?>
												</select>
											</div>
										</div>
										<div class="form-validation">
											<div class="col-sm-2">
												<div class="fileUpload btn btn-primary top_btn">
													<span>Document</span>
													<input type="file" class="upload doc" name="document[]" />
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php 
								} // end foreach
							} else { // if !$case_id
							?>
							<div class="doc_row">
								<div class="form-group">
									<div class="from-validation">
										<label class="col-sm-1 control-label"> Doc Name </label>
										<div class="col-sm-2">
											<input type="text" name="docs[docName][]" class="form-control">
										</div>
									</div>
									<div class="from-validation">
										<label class="col-sm-1 control-label"> Doc No. </label>
										<div class="col-sm-2">
											<input type="text" name="docs[docNo][]" class="form-control">
										</div>
									</div>
									<div class="from-validation">
										<label class="col-sm-1 control-label"> Doc Type </label>
										<div class="col-sm-3">
											<select name="docs[docType][]" class="form-control">
												<option value=""> Select Docuemnt Type </option>
												<?php
													if($doc_type_ops) {
														foreach($doc_type_ops as $key => $val) { ?>
															<option value="<?=$key?>"> <?=$val?></option>
														<?php }
													}
												?>
											</select>
										</div>
									</div>
									<div class="form-validation">
										<div class="col-sm-2">
											<div class="fileUpload btn btn-primary top_btn">
												<span>Document</span>
												<input type="file" class="upload doc" name="document[]" />
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php } //end if $case_id check ?>
							

							<div class="cloned_rows" id="cloned_rows">
								
							</div>

							<div class="form-group">
								<div class="form-validation">
									<div class="col-sm-2">
										<button type="button" class="top_btn btn btn-primary add_more" id="add_more" name="add_more"> More Docs (+)</button>
									</div>
								</div>
							</div>

							<p class="text-right">
								<input type="hidden" value="<?php echo $submit_check_session; ?>" name="submit_check_session">
								<!-- Contextual button for informational alert messages -->
								<button type="submit" name="generate" value="generate" class="btn btn-success top_btn" id="">
									Save and Upload Docs
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
		FormValidator.init();
	});


	$('.datepicker').datetimepicker({
		timepicker: false,
		format: 'Y-m-d',
		scrollMonth : false,
		scrollInput : false,
		// maxDate : new Date
	});

	$(document).on('click','#add_more',function () {
		var doc_row_form_grp = $('.doc_row').html();
		$('#cloned_rows').append(doc_row_form_grp);
	});






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
		var litigationForm = function () {
		
			var form1 = $('#litigationForm');
			var errorHandler1 = $('.errorHandler', form1);
			var successHandler1 = $('.successHandler', form1);
			$('#litigationForm').validate({
				
				errorElement: "span", // contain the error msg in a span tag
				errorClass: 'help-block',
				errorPlacement: function (error, element) {
					error.insertAfter(element);
					// for other inputs, just perform default behavior
				},
				ignore: "",
				rules: {
					'members[0][memberFirstName]':{
						required: true
					},
					'members[0][memberLastName]':{
						required: true
					},
					'members[0][designationId]':{
						required: true
					},
					'members[1][memberFirstName]':{
						required: true
					},
					'members[1][memberLastName]':{
						required: true
					},
					'members[1][designationId]':{
						required: true
					},
					'court':{
						required: true
					},
					'caseNo':{
						required: true
					},
					'ground':{
						required: true
					},
					'relatedTo':{
						required: true
					},
					'caseDate':{
						required: true
					},
					'hearingDate':{
						required: true
					},
					'memo':{
						required: true
					},
				},
				messages: {
					'members[0][memberFirstName]':{
						required: 'Please Enter Petitioner First Name'
					},
					'members[0][memberLastName]':{
						required: 'Please Enter Petitioner Last Name'
					},
					'members[0][designationId]':{
						required: 'Please Select Petitioner Designation'
					},
					'members[1][memberFirstName]':{
						required: 'Please Enter Respondent First Name'
					},
					'members[1][memberLastName]':{
						required: 'Please Enter Respondent Last Name'
					},
					'members[1][designationId]':{
						required: 'Please Select Respondent Designation'
					},
					'court':{
						required: 'Please Select Court'
					},
					'caseNo':{
						required: 'Please Enter Case no. '
					},
					'ground':{
						required: 'Please Enter ground'
					},
					'relatedTo':{
						required: 'Please Enter related to'
					},
					'caseDate':{
						required: 'Please Enter case date'
					},
					'hearingDate':{
						required: 'Please Enter case hearing date'
					},
					'memo':{
						required: 'Please Enter Memo'
					}
					
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
					HTMLFormElement.prototype.submit.call($('#litigationForm')[0]);
				}
			});
		};
		
		return {
			//main function to initiate pages
			init: function () {
				litigationForm();
			}
		};
	} ();
	
</script>

<style>
	.panel-heading{ padding-left:10px !important; background-image:none; border-radius:0; box-shadow:none; }
	.form-group { margin:3px !important;background: #f6f6f6;padding: 2px 4px;}
	select.form-control, input.form-control { height:28px !important; padding-left:0;border: solid 1px #8e8e8e;font-size: 11px;padding-left: 5px;color: #000;}
	.form-horizontal .control-label {padding: 5px 0!important; margin:0 !important;color:#000; font-weight: bold !important;font-size: 12px;text-align: left;}
	select.form-control { font-size: 11px;color:#000; font-weight:normal !important;}
	.panel-body {padding: 5px;}
	.panel-default>.panel-heading{color: #fff;font-weight: bold;background:#282828;}
	.panel-body>.panel-heading{color: #777;font-weight: bold;background:#eeeeee; margin: 3px 3px;}
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


	.fileUpload {
	    position: relative;
	    overflow: hidden;
	    /*margin: 10px;*/
	}
	.fileUpload input.upload {
	    position: absolute;
	    top: 0;
	    right: 0;
	    margin: 0;
	    padding: 0;
	    font-size: 20px;
	    cursor: pointer;
	    opacity: 0;
	    filter: alpha(opacity=0);
	}

</style>
