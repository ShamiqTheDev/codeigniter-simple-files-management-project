<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/semantic-ui/dist/semantic.css">
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
			
			if(isset($roll_back_close))
				echo '<input type="hidden" name="roll_back_close" value="'.$roll_back_close.'">';
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
				<div class="col-md-6">
					<div class="box_style">
						<div class="panel-body">
							<div class="form-group">
								<div class="form-validation">
									<label class="col-sm-3 control-label">
										To
									</label>
									<div class="col-sm-9">
										<select id="sent_to" name="sent_to" class="form-control">
											<option value="">Select To</option>
											<?php
											if($send_user) {
												foreach($send_user as $key_send_user => $get_send_user) {
													echo '<optgroup label="'.$key_send_user.'">';
														
													foreach($get_send_user as $get_user) {
														//$designation = ($get_user['designationName']) ? ' ('.$get_user['designationName'].')' : '';
														$selected = (isset($_POST["current_status"]) && ($_POST["current_status"] == $get_user['uacc_id'])) ? "selected='selected'" : "" ;
														echo '<option '.$selected.' class="item" value="'.$get_user['uacc_id'].'">'.$get_user['upro_first_name'].' '.$get_user['upro_last_name'].'</option>';
													}

													echo '</optgroup>';
												}
											}
											?>
										</select>
										<?php
										if($send_user) {
											foreach($send_user as $get_send_user) {
												//$designation = ($get_send_user['designationName']) ? ' ('.$get_send_user['designationName'].')' : '';
												/*$selected = (isset($_POST["fd_file_type_id"]) && ($_POST["fd_file_type_id"] == $file_type['fileTypeId'])) ? "selected='selected'" : "" ;*/
												//echo '<option class="item" value="'.$get_send_user['uacc_id'].'">'.$get_send_user['upro_first_name'].' '.$get_send_user['upro_last_name'].$designation.'</option>';
											
												foreach($get_send_user as $get_user) {
													echo '<input type="hidden" name="user_job_group_id['.$get_user['uacc_id'].']" value="'.$get_user['uacc_user_job_group_fk'].'">';
												}
											
												
											}
										}
										?>
										<!--<div class="ui fluid search dropdown selection">
											<input type="hidden" name="sent_to" id="sent_to">
											
											<i class="dropdown icon"></i>
											<div class="default text">Select</div>
											<div class="menu">
												<?php
												/*if($send_user) {
													foreach($send_user as $get_send_user) {
														$designation = ($get_send_user['designationName']) ? ' ('.$get_send_user['designationName'].')' : '';
														echo '<div class="item" data-value="'.$get_send_user['uacc_id'].'">'.$get_send_user['upro_first_name'].' '.$get_send_user['upro_last_name'].$designation.'</div>';
													}
												}*/
												?>
											</div>
										</div>-->
									</div>
								</div>
							</div>
							
							<!--<div class="form-group">
								<label class="col-sm-3 control-label">
									CC
								</label>
								<div class="col-sm-9">
									<div class="ui fluid search dropdown selection multiple">
										<input type="hidden" name="sent_cc">
										<i class="dropdown icon"></i>
										<div class="default text">Select</div>
										<div class="menu">
											<?php
											if($send_user) {
												foreach($send_user as $get_send_user) {
													$designation = ($get_send_user['designationName']) ? ' ('.$get_send_user['designationName'].')' : '';
													echo '<div class="item" data-value="'.$get_send_user['uacc_id'].'">'.$get_send_user['upro_first_name'].' '.$get_send_user['upro_last_name'].$designation.'</div>';
												}
											}
											?>
										</div>
									</div>
									
									<p style="font-size:12px; padding:5px;">(Use semicolon(;) to seperate recipients.)<br />
										<span style="color:red">Note :</span> CC copies are non-editable (both pdf and metadata). Any change in the main receipt will be reflected in the CC copies, till the time not put inside the file
									</p>
								</div>
							</div>-->
							
							<div class="form-group">
								<label class="col-sm-3 control-label">
									Set Due Date
								</label>
								<div class="col-sm-9">
									<div class="input-group">
										<input name="due_date" id="due_date" class="form-control datepicker" type="text" autocomplete="off">
										<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
									</div>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-3 control-label">
									Action
								</label>
								<div class="col-sm-9">
									
									<select name="send_action_id" id="send_action_id" class="form-control">
										<option value="">Select Action</option>
										<?php
										if($send_action) {
											foreach($send_action as $get_send_action) {
												echo '<option value="'.$get_send_action['sendActionId'].'">'.$get_send_action['sendAction'].'</option>';
											}
										}
										?>
									</select>
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-3 control-label">
									Priority
								</label>
								<div class="col-sm-9">
									
									<select name="send_priority_id" id="send_priority_id" class="form-control">
										<option value="">Select Priority</option>
										<?php
										if($send_priority) {
											foreach($send_priority as $get_send_priority) {
												echo '<option value="'.$get_send_priority['sendPriorityId'].'">'.$get_send_priority['sendPriority'].'</option>';
											}
										}
										?>
									</select>
								</div>	
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label">
									Forward Date
								</label>
								<div class="col-sm-9">
									<div class="input-group">
										<input name="forward_date" id="forward_date" class="form-control datepicker_forward_date" type="text" autocomplete="off">
										<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
									</div>
								</div>
							</div> 
							
							<div class="form-group">
								<label class="col-sm-3 control-label">
									Remarks
								</label>
								<div class="col-sm-9">
									<?php
									$field_data = array(
										'name' => 'remarks',
										'value' => '',
										'id' => 'remarks',
										'class' => 'input-xlarge maxLength',
										//'onKeyDown' => 'limitText(this.form.remarks, this.form.countdown, 1000)',
										//'onKeyUp' => 'limitText(this.form.remarks, this.form.countdown, 1000)',
										'class' => 'col-sm-12',
										'rows' => 3,
										'cols' => ''
									);
									
									echo html_entity_decode(form_textarea($field_data));
									?>
									<p style="font-size:11px; padding-bottom:5px;">Total <span><?php echo $this->config->item('remarksTextLimit'); ?></span> | <span id="countdown"><?php echo $this->config->item('remarksTextLimit'); ?></span> character left</p>
									<!--<p style="font-size:11px; padding-bottom:5px;">Total <input readonly type="text" name="countdown" size="3" class='inputForm' value="1000"> | <input readonly type="text" name="countChar" size="3" class='inputForm' value="1000"> character left</p>-->
								</div>
							</div>
							<hr>
							<input name="send_id_old" type='hidden' id="send_id_old" value="<?php echo $sent_receipt['sentId']; ?>">
							<?php if($send_type!=''){ ?>
							<input name="send_type" type='hidden' id="send_type" value="<?php echo $send_type; ?>">
							<?php } ?>
							<button type="submit" name="sent_receipt" value="sent_receipt" id="sent_receipt" class="btn btn-success pull-right">
								Send
							</button>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="gray_box">
                        <div class="row header-txt">
                        	<div class="col-md-1">
								<div class="checkbox-table">
									<input checked id="check_all_box"  type="checkbox">
								</div>
							</div>
                            <div class="col-md-8"><strong>Receipt No</strong></div>
                            <div class="col-md-3"><strong>Subject</strong></div>
						</div>
                        
						<?php
							if($receipt) {
								$i = 1;
								foreach($receipt as $get_receipt) {
								?>
								<div class="row">
									<div class="white_row">
										<div class="col-md-1">
											<div class="checkbox-table">
												<input  checked id="" name="receipt_id[]" value="<?php echo $get_receipt['receiptDetailId']; ?>" type="checkbox">
											</div>
										</div>
										<div class="col-md-8">
											<div class="accordion-custom accordion-teal" id="accordion">
												<div class="panel-default">
													<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse_<?php echo $i; ?>">
														<i class="icon-arrow"></i>
														<?php echo $get_receipt['receiptNo']; ?>
													</a>
												</div>
											</div>
										</div>
										<div class="col-md-3"><?php echo $get_receipt['subject']; ?></div>
									</div> 
									<div id="collapse_<?php echo $i; ?>" class="panel-collapse collapse" style="height: 0px;">
										<div class="panel-body details-info">
											<div class="form-group">
												<div class="form-validation">
													<label class="col-sm-2 control-label">
														Receipt No:
													</label>
													<div class="col-sm-4 file-detail">										
														<?php echo $get_receipt['receiptNo']; ?>
														<img src="<?php echo $includes_dir; ?>admin/images/pdf-icon.png" style="height: 15px;">
													</div>
												</div>
												<div class="form-validation">
													<label class="col-sm-2 control-label">
														File No:
													</label>
													<div class="col-sm-4 file-detail">
														<?php echo $get_receipt['fileNumber']; ?>
													</div>
												</div>
											</div>
											
											<div class="form-group">
												<div class="form-validation">
													<label class="col-sm-2 control-label">
														From:
													</label>
													<div class="col-sm-4 file-detail">
														<?php echo $get_receipt['contactName']; ?>
													</div>
												</div>
												<div class="form-validation">
													<label class="col-sm-2 control-label">
														Designation:
													</label>
													<div class="col-sm-4 file-detail">
														<?php echo $get_receipt['designation']; ?>
													</div>
												</div>
											</div>
											
											<div class="form-group">
												<div class="form-validation">
													<label class="col-sm-2 control-label">
														Main Category:
													</label>
													<div class="col-sm-4 file-detail">
														<?php echo $get_receipt['categoryName']; ?>
													</div>
												</div>
												<div class="form-validation">
													<label class="col-sm-2 control-label">
														Letter Ref No:
													</label>
													<div class="col-sm-4 label_detail">
														<?php echo $get_receipt['letterRefNo']; ?>
													</div>
												</div>
												<!--<div class="form-validation">
													<label class="col-sm-2 control-label">
														Sub Category:
													</label>
													<div class="col-sm-4 file-detail">
														<?php echo $get_receipt['subCategory']; ?>
													</div>
												</div>-->
											</div>
											
											<div class="form-group">
												<div class="form-validation">
													<label class="col-sm-2 control-label">
														Address:
													</label>
													<div class="col-sm-8 file-detail">
														<?php echo $get_receipt['addressOne']; ?>
													</div>
												</div>
												<!--<div class="form-validation">
													<label class="col-sm-2 control-label">
														Sent Date:
													</label>
													<div class="col-sm-4 file-detail">
														<?php //echo $receipt['addressOne']; ?>
													</div>
												</div>-->
											</div>
											
											<div class="form-group">
											
												<div class="form-validation">
													<label class="col-sm-2 control-label">
														Letter Date:
													</label>
													<div class="col-sm-4 file-detail">
														<?php echo ($get_receipt['letterDate'] == '0000-00-00') ? '' : date('d-m-Y', strtotime($get_receipt['letterDate'])); ?>
													</div>
												</div>
												<div class="form-validation">
													<label class="col-sm-2 control-label">
														Diary Date:
													</label>
													<div class="col-sm-4 label_detail">
														<?php echo date('d-m-Y', strtotime($get_receipt['diaryDate'])); ?>
													</div>
												</div>
											</div>
											
											<div class="form-group">
												<div class="form-validation">
													<label class="col-sm-2 control-label">
														Subject:
													</label>
													<div class="col-sm-8 file-detail">
														<?php echo $get_receipt['subject']; ?>
													</div>
												</div>
												<!--<div class="form-validation">
													<label class="col-sm-2 control-label">
														Enclosures:
													</label>
													<div class="col-sm-4 file-detail">
														<?php echo $get_receipt['enclosures']; ?>
													</div>
												</div>-->
											</div>
											
											<div class="form-group">
												<div class="form-validation">
													<label class="col-sm-2 control-label">
														Delivery Mode:
													</label>
													<div class="col-sm-4 file-detail">
														<?php echo $get_receipt['deliveryMode']; ?>
													</div>
												</div>
												<div class="form-validation">
													<label class="col-sm-2 control-label">
														Sender Type:
													</label>
													<div class="col-sm-4 file-detail">
														<?php echo $get_receipt['senderType']; ?>
													</div>
													
												</div>
											</div>
										</div>
									</div>
								</div>
								<?php
									$i++;
								}
							}
						?>
					</div>
				</div>
				<!-- end: PAGE CONTENT-->
			</div>
		<?php echo form_close(); ?>
		<!-- end: PAGE --> 
	</div>
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
<script src="<?php echo $includes_dir; ?>admin/plugins/semantic-ui/dist/semantic.js"></script>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 

<script>
	$('.datepicker').datetimepicker({
		timepicker: false,
		format: 'd-m-Y',
		minDate:0,
		scrollMonth : false,
		scrollInput : false,
	});

	$('.datepicker_forward_date').datetimepicker({
		timepicker: false,
		format: 'd-m-Y',
		maxDate: 0,
		scrollMonth : false,
		scrollInput : false,
	});
	
	jQuery(document).ready(function () {
		Main.init();
		FormValidator.init();
	});
	
	$('.ui.selection.dropdown').dropdown();
	
	var FormValidator = function () {
		// function to initiate category    
		var fileUploadingForm = function () {
			
			$.validator.addMethod("fileValidation", function (value, element) {
				
				var fileType = value.split('.').pop();
				var allowdtypes = 'pdf';
				
				if (allowdtypes.indexOf(fileType.toLowerCase()) >= 0) {
					return true;
					} else {
					return false;
				}
			});
			
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
					sent_to:{
						required: true
					},
					due_date:{
						required: true
					},
					remarks:{
						required: true
					}
				},
				messages: {
					sent_to: "Please enter to",
					due_date: "Please enter due date",
					remarks: "Please enter remarks of your email",
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
	
	
	$('#check_all_box').on('ifChanged', function(event){
		
		alert('1');
		
		//alert('checked = ' + event.target.checked);
		//alert('value = ' + event.target.value);
		
		if(event.target.checked) {
			$('.check_box').iCheck('check');
		}
		else {
			$('.check_box').iCheck('uncheck');
		}
		
		//console.log(event);
		
		//alert(event.type + ' callback');
		
	});
	
	
	$('.check_box_child').on('ifChanged', function(event){
		
		alert('2');
		
		//alert('checked = ' + event.target.checked);
		//alert('value = ' + event.target.value);
		
		//alert(event.currentTarget.checked.length);
		
		//alert($('.check_box').is(":checked").length);
		
		//var check_box_length = $(".checked_box").length;
		//var child_checked_box_length = $(".checked_box:checked").length;
		
		//alert(check_box_length);
		
		/*if(event.target.checked) {
			//$('#check_all_box').iCheck('check');
			}
			else {
			//$('#check_all_box').iCheck('uncheck');
		}*/
		
		//console.log(event.currentTarget.form);
		
		//alert(event.type + ' callback');
		
	});
	
	
	
	//$("#check_all_box").click(function () {	alert('test');
	/*if($(this).prop('checked')) {
		$('.checked_box').prop('checked', true);
		}
		else {
		$('.checked_box').prop('checked', false);
	}*/
	//});
	
	
	/*$('body').on('click', '.checked_box', function() {
		
		var parent_checked_box_length = $(".checked_box").length;
		var child_checked_box_length = $(".checked_box:checked").length;
		
		if(parent_checked_box_length == child_checked_box_length) {
		$('#check_all_box').prop('checked', true);
		}
		else {
		$('#check_all_box').prop('checked', false);
		}
	});*/
	
	
	$('#remarks').keyup(function() {
		
		var remarks_text = $(this).val();
		var remarks_text_limit = <?php echo $this->config->item('remarksTextLimit'); ?>;
		var countdown = remarks_text_limit - remarks_text.length;
		
		if(remarks_text.length > remarks_text_limit) {
			var new_remarks_text = $(this).val().substring(0, remarks_text_limit);
			$(this).val(new_remarks_text);
		}
		else {
			$('#countdown').text(countdown)
		}
		
	});
	
</script>

<style>

	.panel-heading{ padding-left:10px !important; background-image:none; border-radius:0; box-shadow:none; }
	.form-group { margin:3px !important;background: #f6f6f6;padding: 2px 4px;}
	select.form-control, input.form-control { height:28px !important; padding-left:0;border: solid 1px #8e8e8e;font-size: 12px;padding-left: 5px;color: #000;}
	.form-horizontal .control-label {padding: 5px 0!important; margin:0 !important;color:#000; font-weight: bold !important;font-size: 12px;text-align: left;}
	select.form-control { font-size: 12px;color:#000; font-weight:normal !important;}
	.panel-body {padding: 5px;}
	.panel-default>.panel-heading{color: #fff;font-weight: bold;background:#282828;}
	.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control{font-size: 12px;padding-left: 5px;}
	 .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10,{ padding-left:5px; padding-right:5px;}
	.has-error .form-control{border-color: red;}
	.has-error .help-block, .has-error .control-label, .has-error .radio, .has-error .checkbox, .has-error .radio-inline, .has-error .checkbox-inline{color: red; font-size:12px;}
	.help-block {
    display: block;
    margin-top: 5px;
    margin-bottom: 10px;
    color: red;
}
	.details-info { background-color: #eeeeee; }
	.details-info .form-group { padding: 0 20px; }
	.box_style{ border:solid 1px #ccc; margin-bottom:10px;}
	select.form-control{padding: 3px 5px !important;}
	.input-group-addon{padding: 3px 12px !important; border-radius:0 !important;}
	
	.gray_box{ /* background: #ebedfb; */padding: 5px 20px;/* border: solid 1px #ccc; */margin-bottom: 10px;font-size: 12px;color: #000;font-weight: bold;background: #282828;
	}
	.white_row{ padding: 5px 0 0 0;border: solid 1px #ccc;overflow: hidden;background: #fff;margin-top:5px;}
	.gray_row{ padding: 5px 0 0 0;border: solid 1px #ddd;overflow: hidden;background: #fff;margin-top:0;}
	.border_right{ border-right: solid 1px #ccc;}
	
	
	.form-group{ margin-bottom:5px !important;}
	.form-control{ height:25px !important;}
	.form-horizontal .control-label{ padding:4px 0 !important; margin:0 !important;}
	.file-detail {padding:4px 18px;font-size:12px; color:#000;font-weight: normal; }
	
	.ui.selection.active.dropdown{border-color: #0a0a0a !important;}
	.ui.selection.active.dropdown:hover{border-color: #0a0a0a !important;}
	.ui.selection.dropdown{min-height: 2em !important;}
	.form-group .text{font-size: 12px;margin-top: 7px;font-family: sans-serif;}
	.ui.fluid.dropdown {display: block;width: 100%;min-width: 0em;border-radius: 0;padding: 0px 7px;margin: 0; border: solid 1px #000;}
	.ui.selection.dropdown > .search.icon, .ui.selection.dropdown > .delete.icon, .ui.selection.dropdown > .dropdown.icon{ top:5px !important;}

	.header-txt {
		color: #ffffff;
	}
</style>


