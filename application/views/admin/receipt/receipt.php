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
	.pdf_upload_box{ border:dashed 3px #CCC; width:100%; min-height:440px; margin:0 auto; text-align:center;}
	.image-upload > input{display: none; }
	.image-upload img{cursor: pointer; margin-top:25px;}
	.fright{ float:right;}
	.accordion-teal .panel-heading .accordion-toggle{background:#282828;border-left:0;color:#ffffff;}
	.accordion-custom .panel-heading .accordion-toggle{padding: 10px 5px;}
	.accordion-teal .panel-heading .accordion-toggle.collapsed{background: #282828;border-left: none;color: #fff;font-weight: bold;} 
	
</style>
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
				<div class="col-md-6">
					<!-- start: NOTIFICATION PANEL -->
					<div class="panel panel-default" style="">
					<div class="panel-heading" >
						<h4 class="panel-title" style="font-size: 13px; font-weight: bold;">
							<i class="icon-arrow fright"></i>
								Receipt Attachments
						</h4>
					</div>
					<div class="panel-body">
						<!--<div class="panel-heading">
							Upload(Only) PDF upto 20 MB
						</div>
						<div class="panel-body afteruploadpdf" style="display:none;">
							<div class="pdf_upload_box_afterupload" style="margin-bottom:10px;">
								<div class="form-validation">
									<div class="col-md-2"></div>
									<div class="col-md-2">
										<img src="<?php echo base_url(); ?>includes/admin/images/pdf-icon.png" style="width:50px; height:50px;"/>
									</div>
									<div class="col-md-8">
										<div class="image-upload-afterupload">
											<label for="receipt_file"><img src="<?php echo base_url(); ?>includes/admin/images/upload_pdf_btn.png"/></label>
											<input type="file" value="<?php echo base_url().$browse_diaries_data[$receipt_detail_id]['filePath'] ?>" name="receipt_file11[]" id="receipt_file11"> <!--<span> (Supported Size: 20MB - Supported Type: PDF) </span>-->
										<!--</div>
									</div>
								</div>
							</div>
							<!--<hr>-->
							<!--<embed id="pdf_preview" src="<?php echo base_url().$browse_diaries_data[$receipt_detail_id]['filePath']; ?>#page=1&toolbar=1&navpanes=1&scrollbar=0&statusbar=0&pagemode=none&viewrect=0,0" type="application/pdf" width="100%" height="700px">
						</div>
						
						<div class="panel-body beforeuploadpdf">
							<div class="pdf_upload_box">
								<div class="form-validation">
									<div class="pdf_icon"><img src="<?php echo base_url(); ?>includes/admin/images/pdf-icon.png"/></div>
									<div class="image-upload">
										<label for="receipt_file"><img src="<?php echo base_url(); ?>includes/admin/images/upload_pdf_btn.png"/></label>
										<input type="file" value="<?php echo base_url().$browse_diaries_data[$receipt_detail_id]['filePath'] ?>" name="receipt_file[]" id="receipt_file">
									</div>
								</div>
								<!-- <embed id="pdf_preview" src="<?php echo base_url(); ?>/includes/admin/pdf/sample.pdf#page=1&toolbar=1&navpanes=1&scrollbar=0&statusbar=0&pagemode=none&viewrect=0,0" type="application/pdf" width="100%" height="700px">
								-->
							<!--</div>
						</div>-->
						<div id="fileupload" class="">
							<!-- Redirect browsers with JavaScript disabled to the origin page -->
							<noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
							<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
							<div class="row fileupload-buttonbar">
								<div class="col-lg-12">
									<!-- The fileinput-button span is used to style the file input field as button -->
									<span class="btn btn-success fileinput-button">
										<i class="glyphicon glyphicon-plus"></i>
										<span>Add files...</span>
										<input type="file" name="files[]" multiple>
									</span>
									<!-- The global file processing state -->
									<span class="fileupload-process"></span>
								</div>
								<!-- The global progress state -->
								<div class="col-lg-12 fileupload-progress fade">
									<!-- The global progress bar -->
									<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
										<div class="progress-bar progress-bar-success" style="width:0%;"></div>
									</div>
									<!-- The extended global progress state -->
									<div class="progress-extended">&nbsp;</div>
								</div>
							</div>
							<!-- The table listing the files available for upload/download -->
							<table role="presentation" class="table table-striped" id="upload_file"><tbody class="files"></tbody></table>
						</div>
						<!--</form>-->
					</div>
					<!-- end: NOTIFICATION PANEL -->
				</div>
				</div>
                
				<div class="col-md-6">
					<!-- start: PROGRESS BARS PANEL -->
					<div class="panel panel-default">
						
                        
                        <div class="panel-group accordion-custom accordion-teal" id="accordion">
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a class="accordion-toggle accordionOne" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
											<i class="icon-arrow fright"></i>
											Diary Details
										</a>
									</h4>
								</div>
								<div id="collapseOne" class="panel-collapse collapse in">
									<div class="panel-body">
										<div class="form-group">
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Classified
												</label>
												<div class="col-sm-4">										
													<select name="classified_id" id="classified_id" class="form-control">
														<?php
															if($classified) {
																foreach($classified as $get_classified) {
																	if($get_classified['classifiedId'] == '2' && empty($browse_diaries_data)){
																		$selected = "selected='selected'";
																	}else{
																	$selected = (isset($browse_diaries_data[$receipt_detail_id]['classifiedId']) && $get_classified['classifiedId'] == $browse_diaries_data[$receipt_detail_id]['classifiedId']) ? "selected='selected'" : "";
																	}
																	echo '<option value="'.$get_classified['classifiedId'].'" '.$selected.'>'.$get_classified['classifiedName'].'</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Sender Type
												</label>
												<div class="col-sm-4">
													<select name="sender_type" id="sender_type" class="form-control">
														<?php
															if($sender_type) {
																foreach($sender_type as $get_sender_type) {
																	if($get_sender_type == 'Individual' && empty($browse_diaries_data)){
																		$selected = "selected='selected'";
																	}else{
																		$selected = (isset($browse_diaries_data[$receipt_detail_id]['senderType']) && $get_sender_type == $browse_diaries_data[$receipt_detail_id]['senderType']) ? "selected='selected'" : "";
																	}
																	echo '<option value="'.$get_sender_type.'" '.$selected.'>'.$get_sender_type.'</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
										</div>
										
										<div class="form-group">
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Delivery Mode<span class="symbol required"></span>
												</label>
												<div class="col-sm-4">
													<select name="delivery_mode_id" id="delivery_mode_id" class="form-control">
														<?php
															if($delivery_mode) {
																foreach($delivery_mode as $get_delivery_mode) {
																	if($get_delivery_mode['deliveryModeId'] == '2' && empty($browse_diaries_data)){
																		$selected = "selected='selected'";
																	}else{
																	$selected = (isset($browse_diaries_data[$receipt_detail_id]['deliveryModeId']) && $get_delivery_mode['deliveryModeId'] == $browse_diaries_data[$receipt_detail_id]['deliveryModeId']) ? "selected='selected'" : "";}
																	echo '<option value="'.$get_delivery_mode['deliveryModeId'].'" '.$selected.'>'.$get_delivery_mode['deliveryMode'].'</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Language
												</label>
												<div class="col-sm-4">
													<select name="language" id="language" class="form-control">
														<option value="">Select Language</option>
														<?php
															if($language) {
																foreach($language as $get_language) {
																	if($get_language == 'English' && empty($browse_diaries_data)){
																		$selected = "selected='selected'";
																	}else{
																	$selected = (isset($browse_diaries_data[$receipt_detail_id]['language']) && $get_language == $browse_diaries_data[$receipt_detail_id]['language']) ? "selected='selected'" : "";}
																	echo '<option value="'.$get_language.'"'.$selected.'>'.$get_language.'</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
										</div>
										
										<div class="form-group">
											<!--<div class="form-validation">
												<label class="col-sm-2 control-label">
													Mode Number
												</label>
												<div class="col-sm-4">
													<input placeholder="" name="mode_number" id="mode_number" class="form-control" value="<?php echo isset($browse_diaries_data[$receipt_detail_id]['modeNumber']) ? $browse_diaries_data[$receipt_detail_id]['modeNumber'] : "";  ?>" type="text" autocomplete="off">
												</div>
											</div>-->
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Letter Ref No
												</label>
												<div class="col-sm-4">
													<input placeholder="" name="letter_ref_no" id="letter_ref_no" class="form-control" value="<?php echo isset($browse_diaries_data[$receipt_detail_id]['letterRefNo']) ? $browse_diaries_data[$receipt_detail_id]['letterRefNo'] : "";  ?>" type="text" autocomplete="off">
												</div>
											</div>
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Type<span class="symbol required"></span>
												</label>
												<div class="col-sm-4">
													<select name="document_type_id" id="document_type_id" class="form-control">
														<?php
															if($document_type) {
																foreach($document_type as $get_document_type) {
																	if($get_document_type['documentTypeId'] == '10' && empty($browse_diaries_data)){
																		$selected = "selected='selected'";
																	}else{
																	$selected = (isset($browse_diaries_data[$receipt_detail_id]['documentTypeId']) && $get_document_type['documentTypeId'] == $browse_diaries_data[$receipt_detail_id]['documentTypeId']) ? "selected='selected'" : "";}
																	echo '<option value="'.$get_document_type['documentTypeId'].'"'.$selected.'>'.$get_document_type['documentType'].'</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
										</div>
										
										<div class="form-group">
											
											<!--<div class="form-validation">
												<label class="col-sm-2 control-label">
													File Number
												</label>
												<div class="col-sm-4">
													<input placeholder="" name="file_number" id="file_number" class="form-control" type="text" value="<?php echo isset($browse_diaries_data[$receipt_detail_id]['fileNumber']) ? $browse_diaries_data[$receipt_detail_id]['fileNumber'] : "";  ?>" autocomplete="off">
												</div>
											</div>-->
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Letter Date
												</label>
												<div class="col-sm-4">
													<input placeholder="" name="letter_date" id="letter_date" class="form-control datepicker" type="text" value="<?php echo (isset($browse_diaries_data[$receipt_detail_id]['letterDate']) && ($browse_diaries_data[$receipt_detail_id]['letterDate'] != '0000-00-00')) ? date(('d-m-Y'),strtotime($browse_diaries_data[$receipt_detail_id]['letterDate'])) : ""; ?>" autocomplete="off">
												</div>
											</div>
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Received Date
												</label>
												<div class="col-sm-4">
													<input placeholder="" name="received_date" id="received_date" class="form-control datepicker" type="text" value="<?php echo (isset($browse_diaries_data[$receipt_detail_id]['receivedDate']) && ($browse_diaries_data[$receipt_detail_id]['receivedDate'] != '0000-00-00')) ? date(('d-m-Y'),strtotime($browse_diaries_data[$receipt_detail_id]['receivedDate'])) : ""; ?>" autocomplete="off">
												</div>
											</div>
										</div>

										<div class="form-group">
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													R & I Diary No:
												</label>
												<div class="col-sm-4">
													<input placeholder="" name="r_and_i_diary_no" id="r_and_i_diary_no" class="form-control" value="<?php echo isset($browse_diaries_data[$receipt_detail_id]['rAndIDiaryNo']) ? $browse_diaries_data[$receipt_detail_id]['rAndIDiaryNo'] : "";  ?>" type="text" autocomplete="off" maxlength="20">
												</div>
											</div>
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Diary Date
												</label>
												<div class="col-sm-4">
													<input placeholder="" readonly value="<?php echo (isset($browse_diaries_data[$receipt_detail_id]['diaryDate']) && ($browse_diaries_data[$receipt_detail_id]['diaryDate'] != '0000-00-00')) ? date(('d-m-Y'),strtotime($browse_diaries_data[$receipt_detail_id]['diaryDate'])) : date('d-m-Y'); ?>" name="diary_date" id="diary_date" class="form-control" type="text" autocomplete="off">
												</div>
											</div>
										</div>
										
										<!--<div class="form-group">
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													VIP
												</label>
												<div class="col-sm-4">
													<select name="vip" id="vip" class="form-control">
														<option value="">Select VIP</option>
														<?php
															if($vip) {
																foreach($vip as $get_vip) {
																	$selected = (isset($browse_diaries_data[$receipt_detail_id]['vip']) && $get_vip == $browse_diaries_data[$receipt_detail_id]['vip']) ? "selected='selected'" : "";
																	echo '<option value="'.$get_vip.'"'.$selected.'>'.$get_vip.'</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
											
										</div>-->
										
										<!--<div class="form-group">
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													VIP Name
												</label>
												<div class="col-sm-4">
													<select name="vip_name" id="vip_name" class="form-control">
														<option value="">Select VIP Name</option>
														<?php
															if($vip_name) {
																foreach($vip_name as $get_vip_name) {
																	$selected = (isset($browse_diaries_data[$receipt_detail_id]['vipName']) && $get_vip_name == $browse_diaries_data[$receipt_detail_id]['vipName']) ? "selected='selected'" : "";
																	echo '<option value="'.$get_vip_name.'"'.$selected.'>'.$get_vip_name.'</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Dealing Hands
												</label>
												<div class="col-sm-4">
													<select name="dealing_hands" id="dealing_hands" class="form-control">
														<option value="">Select Dealing Hands</option>
														<?php
															if($dealing_hands) {
																foreach($dealing_hands as $get_dealing_hands) {
																	$selected = (isset($browse_diaries_data[$receipt_detail_id]['dealingHands']) && $get_dealing_hands == $browse_diaries_data[$receipt_detail_id]['dealingHands']) ? "selected='selected'" : "";
																	echo '<option value="'.$get_dealing_hands.'"'.$selected.'>'.$get_dealing_hands.'</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
										</div>-->
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a class="accordion-toggle collapsed accordionTwo" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
											<i class="icon-arrow fright"></i>
											Contact Details
										</a>
									</h4>
								</div>
								<div id="collapseTwo" class="panel-collapse collapse">
									<div class="panel-body">
										<div class="form-group">
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Source
												</label>
												<div class="col-sm-10">
													<select name="ministry_id" id="ministry_id" class="form-control">
														<option value="">Select Source</option>
														<?php
															if($ministry) {
																foreach($ministry as $get_ministry) {
																	$selected = (isset($browse_diaries_data[$receipt_detail_id]['ministryId']) && $get_ministry['ministryId'] == $browse_diaries_data[$receipt_detail_id]['ministryId']) ? "selected='selected'" : "";
																	echo '<option value="'.$get_ministry['ministryId'].'"'.$selected.'>'.$get_ministry['ministryName'].'</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
										</div>
										
										<div class="form-group">
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Department
												</label>
												<div class="col-sm-10">
													<select name="department_id" id="department_id" class="form-control">
														<option value="">Select Department</option>
														<?php
															if($department) {
																foreach($department as $get_department) {
																	$selected = (isset($browse_diaries_data[$receipt_detail_id]['departmentId']) && $get_department['departmentId'] == $browse_diaries_data[$receipt_detail_id]['departmentId']) ? "selected='selected'" : "";
																	echo '<option value="'.$get_department['departmentId'].'"'.$selected.'>'.$get_department['departmentName'].'</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
										</div>
										
										<div class="form-group">
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Name<span class="symbol required contact_name" style="display: block"></span>
												</label>
												<div class="col-sm-4">
													<input placeholder="" name="contact_name" id="contact_name" class="form-control" type="text" value="<?php echo isset($browse_diaries_data[$receipt_detail_id]['contactName']) ? $browse_diaries_data[$receipt_detail_id]['contactName'] : "";  ?>" autocomplete="none">
												</div>
											</div>
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Designation
												</label>
												<div class="col-sm-4">
													<input placeholder="" name="designation" id="designation" class="form-control" type="text" value="<?php echo isset($browse_diaries_data[$receipt_detail_id]['designation']) ? $browse_diaries_data[$receipt_detail_id]['designation'] : "";  ?>" autocomplete="none">
												</div>
											</div>
										</div>
										
										<!--<div class="form-group">
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Organization
												</label>
												<div class="col-sm-10">
													<input placeholder="" name="organization" id="organization" class="form-control" type="text" value="<?php echo isset($browse_diaries_data[$receipt_detail_id]['organization']) ? $browse_diaries_data[$receipt_detail_id]['organization'] : "";  ?>" autocomplete="none">
												</div>
											</div>
										</div>-->
										
										<!--<div class="form-group">
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Address 2
												</label>
												<div class="col-sm-10">
													<input placeholder="" name="address_two" id="address_two" class="form-control" type="text" value="<?php echo isset($browse_diaries_data[$receipt_detail_id]['addressTwo']) ? $browse_diaries_data[$receipt_detail_id]['addressTwo'] : "";  ?>" autocomplete="none">
												</div>
											</div>
										</div>-->
										
										<div class="form-group">
											<!--<div class="form-validation">
												<label class="col-sm-2 control-label">
													Country
												</label>
												<div class="col-sm-4">
													<select name="country_id" id="country_id" class="form-control">
														<option value="">Select Country</option>
														<?php
															if($country) {
																foreach($country as $get_country) {
																	$selected = (isset($browse_diaries_data[$receipt_detail_id]['countryId']) && $get_country['countryId'] == $browse_diaries_data[$receipt_detail_id]['countryId']) ? "selected='selected'" : "";
																	echo '<option value="'.$get_country['countryId'].'"'.$selected.'>'.$get_country['countryName'].'</option>';
																}
															}
														?>
													</select>
												</div>
											</div>-->
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Province
												</label>
												<div class="col-sm-4">
													<select name="state_id" id="state_id" class="form-control">
														<option value="">Select Province</option>
														<?php
															if($state) {
																foreach($state as $get_state) {
																	if($get_state['stateId'] == '1' && empty($browse_diaries_data)){
																		$selected = "selected='selected'";
																	}else{
																	$selected = (isset($browse_diaries_data[$receipt_detail_id]['stateId']) && $get_state['stateId'] == $browse_diaries_data[$receipt_detail_id]['stateId']) ? "selected='selected'" : "";}
																	echo '<option value="'.$get_state['stateId'].'"'.$selected.'>'.$get_state['stateName'].'</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Contact No
												</label>
												<div class="col-sm-4">
													<input placeholder="" name="contact_mobile" id="contact_mobile" class="form-control input-mask-mobile" type="text" value="<?php echo isset($browse_diaries_data[$receipt_detail_id]['contactMobile']) ? $browse_diaries_data[$receipt_detail_id]['contactMobile'] : "";  ?>" autocomplete="none">
												</div>
											</div>
										</div>
										
										<div class="form-group">
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Address <!--<span class="symbol required"></span>-->
												</label>
												<div class="col-sm-10">
													<input placeholder="" name="address_one" id="address_one" class="form-control" type="text" value="<?php echo isset($browse_diaries_data[$receipt_detail_id]['addressOne']) ? $browse_diaries_data[$receipt_detail_id]['addressOne'] : "";  ?>" autocomplete="none">
												</div>
											</div>
										</div>
										
										<!--<div class="form-group">
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													City
												</label>
												<div class="col-sm-4">
													<select name="city_id" id="city_id" class="form-control">
														<option value="">Select City</option>
														<?php
															if($city) {
																foreach($city as $get_city) {
																	$selected = (isset($browse_diaries_data[$receipt_detail_id]['cityId']) && $get_city['cityId'] == $browse_diaries_data[$receipt_detail_id]['cityId']) ? "selected='selected'" : "";
																	echo '<option value="'.$get_city['cityId'].'"'.$selected.'>'.$get_city['cityName'].'</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Pincode
												</label>
												<div class="col-sm-4">
													<input placeholder="" name="pin_code" id="pin_code" class="form-control" type="text" value="<?php echo isset($browse_diaries_data[$receipt_detail_id]['pinCode']) ? $browse_diaries_data[$receipt_detail_id]['pinCode'] : "";  ?>" autocomplete="none">
												</div>
											</div>
										</div>-->
										
										<div class="form-group">
											
											<!--<div class="form-validation">
												<label class="col-sm-2 control-label">
													Landline
												</label>
												<div class="col-sm-4">
													<input placeholder="" name="contact_landline" id="contact_landline" class="form-control input-mask-phone" type="text" value="<?php echo isset($browse_diaries_data[$receipt_detail_id]['contactLandline']) ? $browse_diaries_data[$receipt_detail_id]['contactLandline'] : "";  ?>" autocomplete="none">
												</div>
											</div>-->
										</div>
										
										<!--<div class="form-group">
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Fax
												</label>
												<div class="col-sm-4">
													<input placeholder="" name="contact_fax" id="contact_fax" class="form-control" type="text" value="<?php echo isset($browse_diaries_data[$receipt_detail_id]['contactFax']) ? $browse_diaries_data[$receipt_detail_id]['contactFax'] : "";  ?>" autocomplete="none">
												</div>
											</div>
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Email
												</label>
												<div class="col-sm-4">
													<input placeholder="" name="contact_email" id="contact_email" class="form-control" type="text" value="<?php echo isset($browse_diaries_data[$receipt_detail_id]['contactEmail']) ? $browse_diaries_data[$receipt_detail_id]['contactEmail'] : "";  ?>" autocomplete="none">
												</div>
											</div>
										</div>-->
									</div>		
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a class="accordion-toggle collapsed accordionThree" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
											<i class="icon-arrow fright"></i>
											Category & Subject
										</a></h4>
								</div>
								<div id="collapseThree" class="panel-collapse collapse">
									<div class="panel-body">
										<div class="form-group">
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Main Category<span class="symbol required"></span>
												</label>
												<div class="col-sm-10">
													<select name="category_id" id="category_id" class="form-control">
														<option value="">Select Main Category</option>
														<?php
															if($category) {
																foreach($category as $get_category) {
																	$selected = (isset($browse_diaries_data[$receipt_detail_id]['categoryId']) && $get_category['categoryId'] == $browse_diaries_data[$receipt_detail_id]['categoryId']) ? "selected='selected'" : "";
																	echo '<option value="'.$get_category['categoryId'].'"'.$selected.'>'.$get_category['categoryName'].'</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
										</div>
										
										<!--<div class="form-group">
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Sub Category
												</label>
												<div class="col-sm-10">
													<select name="sub_category_id" id="sub_category_id" class="form-control">
														<option value="">Select Sub Category</option>
														<?php
															if($sub_category) {
																foreach($sub_category as $get_sub_category) {
																	$selected = (isset($browse_diaries_data[$receipt_detail_id]['subCategoryId']) && $get_sub_category['subCategoryId'] == $browse_diaries_data[$receipt_detail_id]['subCategoryId']) ? "selected='selected'" : "";
																	echo '<option value="'.$get_sub_category['subCategoryId'].'"'.$selected.'>'.$get_sub_category['subCategoryName'].'</option>';
																}
															}
														?>
													</select>
												</div>
											</div>
										</div>-->
										
										<div class="form-group">
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Subject<span class="symbol required"></span>
												</label>
												<div class="col-sm-10">
													<textarea name="subject" id="subject" class="form-control"><?php echo isset($browse_diaries_data[$receipt_detail_id]['subject']) ? $browse_diaries_data[$receipt_detail_id]['subject'] : "";  ?></textarea>
												</div>
											</div>
										</div>
										
										<!--<div class="form-group">
											<div class="form-validation">
												<label class="col-sm-2 control-label">
													Enclosures
												</label>
												<div class="col-sm-10">
													<textarea name="enclosures" id="enclosures" class="form-control"><?php echo isset($browse_diaries_data[$receipt_detail_id]['enclosures']) ? $browse_diaries_data[$receipt_detail_id]['enclosures'] : "";  ?></textarea>
												</div>
											</div>
										</div>-->
										
										<br>
										
										
									</div>
								</div>
							</div>
						</div>
						<?php if(isset($browse_diaries_data[$receipt_detail_id]['filePath'])){ ?>
							<!-- Hidden Field -->
							<input type="hidden" value="<?php echo base_url().$browse_diaries_data[$receipt_detail_id]['filePath'] ?>" name="receipt_file_hidden" id="receipt_file_hidden"> 
						<?php } ?>
						<?php if(isset($browse_diaries_data[$receipt_detail_id]['contactDetailId'])){ ?>
							<!-- Hidden Field -->
							<input type="hidden" value="<?php echo $browse_diaries_data[$receipt_detail_id]['contactDetailId'] ?>" name="contactDetailId" id="contactDetailId"> 
						<?php } ?>
						<?php if(isset($browse_diaries_data[$receipt_detail_id]['fileUploadedId'])){ ?>
							<!-- Hidden Field -->
							<input type="hidden" value="<?php echo $browse_diaries_data[$receipt_detail_id]['fileUploadedId'] ?>" name="fileUploadedId" id="fileUploadedId"> 
						<?php } ?>
						<p class="text-right">
							<!-- Contextual button for informational alert messages -->
							<button type="submit" name="generate" value="generate" id="generate" class="btn btn-dark-grey">
								<?php if(isset($browse_diaries_data)){ ?>
									Update
									<?php }else{ ?>
									Generate
								<?php } ?>
								
							</button>
							<!-- Provides extra visual weight and identifies the primary action in a set of buttons -->
							<input type="hidden" name="random_submit_num" value="<?php echo $random_submit_num; ?>">
							<button type="submit" name="generate_send" value="generate_send" id="generate_send" class="btn btn-success">
								<?php if(isset($browse_diaries_data)){ ?>
									Update & Send
									<?php }else{ ?>
									Generate & Send
								<?php } ?>
							</button>
							<?php
							//echo isset($browse_diaries_data) ? $browse_diaries_data[$receipt_detail_id]["fileId"] : '';
							//echo '<pre>'; print_r($browse_diaries_data[$receipt_detail_id]); die();
							?>
						</p>						
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
<script src="<?php echo $includes_dir; ?>admin/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/plugins/select2/select2.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/datetimepicker/build/jquery.datetimepicker.full.js"></script> 
<script src="<?php echo $includes_dir; ?>admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> 
<script src="<?php echo $includes_dir; ?>admin/js/script.js"></script> 
<script src="<?php echo $includes_dir; ?>admin/plugins/jquery.maskedinput/src/jquery.maskedinput.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/jquery-maskmoney/jquery.maskMoney.js"></script>	
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
	{% console.log(o.options.fileInput[0].id); for (var i=0, file; file=o.files[i]; i++) { %}
		<tr class="template-upload fade">
		<td>
	<span class="preview"></span>
</td>
<td>
	<p class="name">{%=file.name%}</p>
	<strong class="error text-danger"></strong>
</td>

<td>
	<p class="size">Processing...</p>
	<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
</td>
<td>
	{% if (!i && !o.options.autoUpload) { %}
	<button class="btn btn-primary start" disabled>
		<i class="glyphicon glyphicon-upload"></i>
		<span>Start</span>
	</button>
	{% } else { %}
		<button class="btn btn-warning cancel">
		<i class="glyphicon glyphicon-ban-circle"></i>
		<span>Cancel</span>
		</button>
	{% } %}
</td>
</tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
	{% console.log(o); for (var i=0, file; file=o.files[i]; i++) { %}
		<tr class="template-download fade">
		<td>
		<span class="preview">
	
{% var fileType = file.name.split('.').pop(), allowdtypes = 'jpeg,jpg,png'; if (allowdtypes.indexOf(fileType.toLowerCase()) > 0) { %}
<img src="<?php echo $includes_dir; ?>admin/images/image-icon.png" width="60" height="48" />
{% } else { %}
<a target="_blank" href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="<?php echo $includes_dir; ?>admin/images/pdf-icon.png" class="pdf-image"></a>
{% } %}
	
</span>
<input type="hidden" value="{%=file.id%}" name="file_uploaded_id[]">
<input type="hidden" value="{%=file.name%}" name="file_uploaded_name[]">
<input type="hidden" value="{%=file.real_name%}" name="file_uploaded_real_name[]">
<input type="hidden" value="{%=file.size%}" name="file_uploaded_size[]">
<input type="hidden" value="{%=file.type%}" name="file_uploaded_type[]">
<input type="hidden" value="{%=file.url%}" name="file_uploaded_url[]">
</td>
<td>
	<p class="name">
		{% if (file.url) { %}
		<a style="width: 181px; float: left; overflow: hidden;" href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.real_name%}</a>
		{% } else { %}
		<span>{%=file.name%}</span>
		{% } %}
		</p>
		{% if (file.error) { %}
		<div><span class="label label-danger">Error</span> {%=file.error%}</div>
		{% } %}
		</td>
		<td>
		<?php /* ?><span class="size">{%=o.formatFileSize(file.size)%}</span><?php */ ?>
		</td>
		<td>
		{% if (file.deleteUrl) { %}
		<button class="btn btn-info btn-squared delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
		<i class="fa fa-undo"></i>
		<span>Reload</span>
		</button>
		{% } else { %}
		<button class="btn btn-warning cancel">
		<i class="glyphicon glyphicon-ban-circle"></i>
		<span>Cancel</span>
		</button>
		{% } %}
		</td>
		</tr>
		{% } %}
		</script>
		
		<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
		<script src="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/js/vendor/jquery.ui.widget.js"></script>
		<!-- The Templates plugin is included to render the upload/download listings -->
		<script src="<?php echo $includes_dir; ?>admin/plugins/blueimp/tmpl.min.js"></script>
		<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
		<script src="<?php echo $includes_dir; ?>admin/plugins/blueimp/load-image.all.min.js"></script>
		<!-- The Canvas to Blob plugin is included for image resizing functionality -->
		<script src="<?php echo $includes_dir; ?>admin/plugins/blueimp/canvas-to-blob.min.js"></script>
		
		<!-- blueimp Gallery script -->
		<script src="<?php echo $includes_dir; ?>admin/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>
		<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
		<script src="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/js/jquery.iframe-transport.js"></script>
		<!-- The basic File Upload plugin -->
		<script src="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/js/jquery.fileupload.js"></script>
		<!-- The File Upload processing plugin -->
		<script src="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/js/jquery.fileupload-process.js"></script>
		<!-- The File Upload image preview & resize plugin -->
		<script src="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/js/jquery.fileupload-image.js"></script>
		<!-- The File Upload audio preview plugin -->
		<script src="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/js/jquery.fileupload-audio.js"></script>
		<!-- The File Upload video preview plugin -->
		<script src="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/js/jquery.fileupload-video.js"></script>
		<!-- The File Upload validation plugin -->
		<script src="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/js/jquery.fileupload-validate.js"></script>
		<!-- The File Upload user interface plugin -->
		<script src="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/js/jquery.fileupload-ui.js"></script>
		<!-- The main application script -->
		<script src="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/js/main.js?"></script>
		<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
		<!--[if (gte IE 8)&(lt IE 10)]>
			<script src="js/cors/jquery.xdr-transport.js"></script>
		<![endif]-->

<script>
	$('.datepicker').datetimepicker({
		timepicker: false,
		format: 'd-m-Y',
		scrollMonth : false,
		scrollInput : false,
		//minDate: 0,
		maxDate: 0,
	});
	<?php if(isset($browse_diaries_data)){ ?>
		$('.afteruploadpdf').css('display','block');
		$('.beforeuploadpdf').css('display','none');
		//$("receipt_file").val('<?php echo base_url().$browse_diaries_data[$receipt_detail_id]['filePath']; ?>');
	<?php } ?>
	jQuery(document).ready(function () {
		Main.init();
		FormValidator.init();
		maskBrowseDairies();
	});
	function maskBrowseDairies(){
		$('.input-mask-mobile').mask('99999999999');
		//$('.input-mask-phone').mask('9999999999');
		//$('.input-mask-email').mask('9999@99999.999');
	}
	
	$('#sender_type').change(function() {
		if($("#sender_type option:selected").val() == 'Individual') {
			$('.contact_name').show();
		} 
		else {
			$('.contact_name').hide();
		}	
	})
	
	var FormValidator = function () {
		// function to initiate category    
		var fileUploadingForm = function () {
			
			$.validator.addMethod("fileValidation", function (value, element) {
				var fileType = value.split('.').pop();
				var allowdtypes = 'pdf';
				
				if (allowdtypes.indexOf(fileType.toLowerCase()) >= 0 && element.files[0].size <= 20971520) {
					return true;
					} else {
					return false;
				}
			});
			
			/*jQuery.validator.addMethod("emailValidation", function(value, element) {
				// allow any non-whitespace characters as the host part
				return this.optional( element ) || /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test( value );
			}, 'Enter valid email address.');*/
			
			
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
					delivery_mode_id:{
						required: true
					},
					document_type_id:{
						required: true
					},
					contact_name:{
						required : function(element) {
							if($("#sender_type option:selected").val() == 'Individual') {
								return true;
							} 
							else {
								return false;
							}
						},
					},
					designation: {
						required: false
					},
					address_one:{
						required: false
					},
					category_id:{
						required: true
					},
					subject:{
						required: true
					},
					/*contact_email:{
						required: false,
						emailValidation:true,
					},*/
					/*'receipt_file[]': {
						<?php if(isset($browse_diaries_data)){ ?>
							
							<?php } else{ ?>
							required: true,
							fileValidation: true,
						<?php } ?>
						//fileSizeValidation: true
					},*/
					
				},
				messages: {
					delivery_mode_id: "Select delivery mode",
					document_type_id: "Select document type",
					contact_name: "Enter name",
					//designation: "Enter designation",
					address_one: "Enter address",
					category_id: "Select category",
					subject: "Enter subject",
					//'receipt_file[]': "<?php echo $this->config->item('allowedFileGeneralMessage'); ?>",
					/*'contact_email': {
						emailValidation:"Enter a valid email"
					},*/
					
				},
				invalidHandler: function (event, validator) { //display error alert on form submit
					successHandler1.hide();
					errorHandler1.show();
					if($('#delivery_mode_id').val()=="" || $("#document_type_id").val()==""){
						$('.accordionOne').removeClass('collapsed');
						$('#collapseOne').removeClass('collapse');
						$('#collapseOne').addClass('in');
						$('#collapseOne').css("height","255px");
					}
					if($('#contact_name').val()=="" || $("#designation").val()=="" || $("#address_one").val()==""){
						$('.accordionTwo').removeClass('collapsed');
						$('#collapseTwo').removeClass('collapse');
						$('#collapseTwo').addClass('in');
						$('#collapseTwo').css("height","260px");
					}
					if($('#category_id').val()=="" || $("#subject").val()==""){
						$('.accordionThree').removeClass('collapsed');
						$('#collapseThree').removeClass('collapse');
						$('#collapseThree').addClass('in');
						$('#collapseThree').css("height","180px");
					}
					/*$('.accordionOne').removeClass('collapsed');
						$('.accordionTwo').removeClass('collapsed');
						$('.accordionThree').removeClass('collapsed');
						$('#collapseOne').removeClass('collapse');
						$('#collapseOne').addClass('in');
						$('#collapseTwo').removeClass('collapse');
						$('#collapseTwo').addClass('in');
						$('#collapseThree').removeClass('collapse');
					$('#collapseThree').addClass('in');*/
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
					$("#generate").attr("disabled", true);
					$("#generate_send").attr("disabled", true);
					
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
	
	
	function readURL(input) {
		
		$('#receipt_file').parents('.form-validation').removeClass('has-error');
		$('#receipt_file').next('.help-block').remove();
		
		var mime= input.files[0].type;
		var fileName = input.files[0].name;
		var fileSize = input.files[0].size;
		//alert(fileSize);
		var allowedExtensions = /(\.<?php echo $this->config->item('allowedFileType'); ?>)$/i;
		
		if(!allowedExtensions.exec(fileName)){
			<?php if($browse_diaries_data[$receipt_detail_id]['filePath']!=""){?>
				$('#pdf_preview').attr('src', '<?php echo base_url().$browse_diaries_data[$receipt_detail_id]['filePath']; ?>');
				<?php }else { ?>
				$('#pdf_preview').attr('src', '<?php echo base_url(); ?>/includes/admin/pdf/sample.pdf');
			<?php } ?>
			$('#receipt_file').after('<span for="receipt_file" class="help-block"><?php echo $this->config->item('allowedFileMessage'); ?></span>');
			$('#receipt_file11').after('<span for="receipt_file" class="help-block"><?php echo $this->config->item('allowedFileMessage'); ?></span>');
			$('#receipt_file').parents('.form-validation').addClass('has-error');
			$('#receipt_file11').parents('.form-validation').addClass('has-error');
			return false;
		}
		else if(fileSize > 20971520){
			<?php if($browse_diaries_data[$receipt_detail_id]['filePath']!=""){?>
				$('#pdf_preview').attr('src', '<?php echo base_url().$browse_diaries_data[$receipt_detail_id]['filePath']; ?>');
				<?php }else { ?>
				$('#pdf_preview').attr('src', '<?php echo base_url(); ?>/includes/admin/pdf/sample.pdf');
			<?php } ?>
			$('#receipt_file').after('<span for="receipt_file" class="help-block"><?php echo $this->config->item('allowedFileMessageSize'); ?></span>');
			$('#receipt_file11').after('<span for="receipt_file" class="help-block"><?php echo $this->config->item('allowedFileMessageSize'); ?></span>');
			$('#receipt_file').parents('.form-validation').addClass('has-error');
			$('#receipt_file11').parents('.form-validation').addClass('has-error');
			return false;
		}
		else{
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				$('.afteruploadpdf').css('display','block');
				$('.beforeuploadpdf').css('display','none');
				//$('#receipt_file').attr('id', 'receipt_file22');
				//$('#receipt_file11').attr('id', 'receipt_file');
				$('.help-block').css('display','none');
				reader.onload = function (e) {
					$('#pdf_preview').attr('src', e.target.result);	
				}
				<?php if($browse_diaries_data[$receipt_detail_id]['filePath']!=""){?>
					$('#receipt_file_hidden').val('new_file_uploaded');
				<?php } ?>
				reader.readAsDataURL(input.files[0]);
			}
		}
	}
	
	$(function() {
		$("#receipt_file").change(function() {
			readURL(this);
		});
	})
	
	$("#ministry_id").change(function () {
		var ministry_id = $(this).val();
		
		if(!ministry_id) {
			$('#department_id').empty().append('<option value="">Select Department</option>');
			return true;
		}
		
		$.ajax({
			url: '<?php echo base_url(); ?>admin/receipts/ajax_get_department',
			type: 'POST',
			dataType: "JSON",
			data: {ministry_id: ministry_id},
			success: function (response) {
				//console.log(response.length);
				var select = $('#department_id');
				
				select.empty();
				select.append('<option value="">Select Department</option>');
				
				if (response.length != 0) {
					$.each(response, function (i, fb) {
						console.log(fb);
						select.append('<option value="' + fb.departmentId + '">' + fb.departmentName + '</option>');
					});
				}
			},
			error: function () {
				console.log('Error in retrieving Site.');
			}
		});
		
	});
	
	
	/*$("#category_id").change(function () {
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
		
	});*/
	
	
	/*$("#country_id").change(function () {
		var country_id = $(this).val();
		
		$('#city_id').empty().append('<option value="">Select City</option>');
		
		if(!country_id) {
			$('#state_id').empty().append('<option value="">Select State</option>');
			return true;
		}
		
		$.ajax({
			url: '<?php echo base_url(); ?>admin/receipts/ajax_get_state',
			type: 'POST',
			dataType: "JSON",
			data: {country_id: country_id},
			success: function (response) {
				//console.log(response.length);
				var select = $('#state_id');
				
				select.empty();
				select.append('<option value="">Select State</option>');
				
				if (response.length != 0) {
					$.each(response, function (i, fb) {
						console.log(fb);
						select.append('<option value="' + fb.stateId + '">' + fb.stateName + '</option>');
					});
				}
			},
			error: function () {
				console.log('Error in retrieving Site.');
			}
		});
		
	});*/
	
	
	/*$("#state_id").change(function () {
		var state_id = $(this).val();
		
		if(!state_id) {
			$('#city_id').empty().append('<option value="">Select City</option>');
			return true;
		}
		
		$.ajax({
			url: '<?php echo base_url(); ?>admin/receipts/ajax_get_city',
			type: 'POST',
			dataType: "JSON",
			data: {state_id: state_id},
			success: function (response) {
				//console.log(response.length);
				var select = $('#city_id');
				
				select.empty();
				select.append('<option value="">Select City</option>');
				
				if (response.length != 0) {
					$.each(response, function (i, fb) {
						console.log(fb);
						select.append('<option value="' + fb.cityId + '">' + fb.cityName + '</option>');
					});
				}
			},
			error: function () {
				console.log('Error in retrieving Site.');
			}
		});
		
	});*/
	
	
	
	
		var check_entered = false;
			$('#fileupload').fileupload({
				url: '<?php echo base_url() ?>admin/receipts/file_upload/add/<?php echo isset($browse_diaries_data) ? $browse_diaries_data[$receipt_detail_id]["receiptDetailId"] : ''; ?>',
				autoUpload:true,
				acceptFileTypes: /(\.|\/)(pdf|jpe?g|png)$/i,
				maxNumberOfFiles: 3,
				maxChunkSize: 10000000, // 10 MB,
				add: function (e, data) { //alert('test 1');
				
					console.log(data);
				
					var that = this;
					$.getJSON('<?php echo base_url() ?>admin/receipts/file_upload/add/<?php echo isset($browse_diaries_data) ? $browse_diaries_data[$receipt_detail_id]["receiptDetailId"] : ''; ?>', {file: data.files[0].name, size: data.files[0].size, type: data.files[0].type}, function (result) {
						
						//data._progress.total = data._progress.total + 1;
						
						console.log('console=>log '+result);
						
						if(!result.file.file_exists) { //alert('TEST');
						
							
						
						
							var file = result.file;
							data.uploadedBytes = file && file.size;
							
							//alert(data.uploadedBytes+' == '+data.files[0].size);
							
							//data.uploadedBytes  = data.uploadedBytes-1; 
							
							if(data.files[0].size != 0 && data.uploadedBytes == data.files[0].size && check_entered == false) {
								//var file_size = bytesToSize(result.file.file_size);
								
								var table_tbody = $('#upload_file tbody.files');
								var html = '<tr class="template-download fade in">';
								html += '<td><span class="preview"><a href="'+result.file.url+'" title="'+result.file.name+'" download="'+result.file.name+'" data-gallery=""><img src="<?php echo $includes_dir; ?>admin/images/pdf-icon.png" class="pdf-image"></a></span>';
								html += '<input value="" name="file_uploaded_id[]" type="hidden">';
								html += '<input value="'+result.file.name+'" name="file_uploaded_name[]" type="hidden">';
								html += '<input value="'+result.file.real_name+'" name="file_uploaded_real_name[]" type="hidden">';
								html += '<input value="'+result.file.size+'" name="file_uploaded_size[]" type="hidden">';
								html += '<input value="'+result.file.type+'" name="file_uploaded_type[]" type="hidden">';
								html += '<input value="'+result.file.url+'" name="file_uploaded_url[]" type="hidden">';
								html += '</td>';
								html += '<td><p class="name"><a style="width: 181px; float: left; overflow: hidden;" href="'+result.file.url+'" title="'+result.file.name+'" download="'+result.file.name+'">'+result.file.name+'</a></p></td>';
								html += '<td></td>';
								html += '<td><button class="btn btn-info btn-squared delete" data-type="DELETE" data-url="'+result.file.deleteUrl+'"><i class="fa fa-undo"></i><span>Reload</span></button></td>';
								html += '</tr>';
								
							
								table_tbody.append(html);
								check_entered = true;
							}
							else {
								$.blueimp.fileupload.prototype
									.options.add.call(that, e, data);
								check_entered = true;
							}
							
							
							//console.log(data); //112022278 30000000 70000000 100000000
							
							
						}
						else {
							
							//console.log(result);
							
							var file_size = bytesToSize(result.file.file_size);
							
							var table_tbody = $('#upload_file tbody.files');
							var html = '<tr class="template-upload fade in" id="file_already_exist_'+result.file.id+'">';
							html += '<td><span class="preview"></span></td>';
							html += '<td><p class="name">'+result.file.name+'</p><strong class="error text-danger">This file already exists</strong></td>';
							html += '<td><p class="size">'+file_size+'</p><div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div></td>';
							html += '<td><button class="btn btn-warning cancel" onclick="remove_already_exist_row('+result.file.id+')"><i class="glyphicon glyphicon-ban-circle"></i><span>Cancel</span></button></td>';
							html += '</tr>';
						
							table_tbody.append(html);
							
						}
							
					});
				},
				maxRetries: 100,
				retryTimeout: 500,
				fail: function (e, data) {
				
					var result = '';
					check_entered = false;
					
					// jQuery Widget Factory uses "namespace-widgetname" since version 1.10.0:
					var fu = $(this).data('blueimp-fileupload') || $(this).data('fileupload'),
						retries = data.context.data('retries') || 0,
						retry = function () {
							$.getJSON('<?php echo base_url() ?>admin/receipts/file_upload/add/<?php echo isset($browse_diaries_data) ? $browse_diaries_data[$receipt_detail_id]["receiptDetailId"] : ''; ?>', {file: data.files[0].name, size: data.files[0].size, type: data.files[0].type})
								.done(function (result) {
									var file = result.file;
									data.uploadedBytes = file && file.size;
									// clear the previous data:
									data.data = null;
									data.submit();
								})
								.fail(function () {
									fu._trigger('fail', e, data);
								});
						};
					if (data.errorThrown !== 'abort' &&
							data.uploadedBytes < data.files[0].size &&
							retries < fu.options.maxRetries) {
						retries += 1;
						data.context.data('retries', retries);
						window.setTimeout(retry, retries * fu.options.retryTimeout);
						return;
					}
					data.context.removeData('retries');
					
						
					if(data.uploadedBytes == data.files[0].size && result) {
						//var file_size = bytesToSize(result.file.file_size);
						
						var table_tbody = $('#upload_file tbody.files');
						var html = '<tr class="template-download fade in">';
						html += '<td><span class="preview"><a href="'+result.file.url+'" title="'+result.file.name+'" download="'+result.file.name+'" data-gallery=""><img src="<?php echo $includes_dir; ?>admin/images/pdf-icon.png" class="pdf-image"></a></span>';
						html += '<input value="" name="file_uploaded_id[]" type="hidden">';
						html += '<input value="'+result.file.name+'" name="file_uploaded_name[]" type="hidden">';
						html += '<input value="'+result.file.real_name+'" name="file_uploaded_real_name[]" type="hidden">';
						html += '<input value="'+result.file.size+'" name="file_uploaded_size[]" type="hidden">';
						html += '<input value="'+result.file.type+'" name="file_uploaded_type[]" type="hidden">';
						html += '<input value="'+result.file.url+'" name="file_uploaded_url[]" type="hidden">';
						html += '</td>';
						html += '<td><p class="name"><a style="width: 181px; float: left; overflow: hidden;" href="'+result.file.url+'" title="'+result.file.name+'" download="'+result.file.name+'">'+result.file.name+'</a></p></td>';
						html += '<td></td>';
						html += '<td><button class="btn btn-info btn-squared delete" data-type="DELETE" data-url="'+result.file.deleteUrl+'"><i class="fa fa-undo"></i><span>Reload</span></button></td>';
						html += '</tr>';
						
					
						table_tbody.append(html);
					}
					else {
						$.blueimp.fileupload.prototype
						.options.fail.call(this, e, data);
					}	
				}
			}).on('fileuploadsubmit', function (e, data) {
				data.formData = data.context.find(':input').serializeArray();
			});
			
			function remove_already_exist_row(id){
				$('table#upload_file tr#file_already_exist_'+id).remove();
			}
			
			function bytesToSize(bytes) {
				var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
				if (bytes == 0) return '0 Byte';
				var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
				return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
			};
			
	
</script>


