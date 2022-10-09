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
			$attributes = array('class' => '', 'role' => '', 'id' => 'fileUploadingForm');
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
			<?php $this->load->view('admin/scaning/scan_count'); ?>
			<!-- end: INCLUSE FOOTER --> 
			
			<div class="row">
				<div class="col-sm-7"> 
					<!-- start: DATE/TIME PICKER PANEL -->
					<div class="panel panel-default">
						<div class="panel-heading"> <i class="fa fa-external-link-square"></i> File Detail</div>
						<div class="panel-body">
							<div class="form-horizontal">
								<input type="hidden" value="<?php echo $total_number_of_files_to_be_scan['total_file_to_be_scan']; ?>" name="total_count">
								<input type="hidden" value="<?php echo $total_files_scanned['total_file_scanned']; ?>" name="total_scanend_count">
								<div class="form-group">
									<label class="col-sm-3 control-label">File Type<span style="color:red;">*</span></label>
									<div class="col-sm-9">
										<select name="file_type_id" id="file_type_id" class="form-control">
											<option value="">-----Select File Type-----</option>
											<?php
												if($file_types) {
													foreach($file_types as $file_type) {
														$select = (isset($scaning) && $file_type['fileTypeId'] == $scaning['fileTypeId']) ? 'selected="selected"' : set_select('file_type_id', $file_type['fileTypeId']);
														echo '<option '.$select.' value="'.$file_type['fileTypeId'].'">'.$file_type['fileType'].'</option>';
													}
												}
											?>
										</select>
									</div>
								</div>
								
								
								<div id="general_cat_name" style="display:<?php echo (set_value('file_type_id') == 3) ? 'block' : 'none'; ?>;">
									<div class="form-group">
										<label class="col-sm-3 control-label">Category Name <span id="general_cat_name_requried" style="color:red;">*</span></label>
										<div class="col-sm-9">
											<select name="general_category_id" id="general_category_id" class="form-control">
												<option value="">-----Select Category Name-----</option>
												<?php
													if($general_category) {
														foreach($general_category as $get_general_category) {
															$style = "style='color:#000000;background-color:#FFFFFF'";
															$current_user_id = $this->flexi_auth->get_user_id();
															
															if($get_general_category['isExtraCategory'] == 1 && in_array($current_user_id,$this->config->item("showExtraCategoriesToIds"))){ 
																$style = "style='color:#FFFFFF;background-color:#00641D'";
																
															}
															else if($get_general_category['isExtraCategory'] == 1 && !in_array($current_user_id,$this->config->item("showExtraCategoriesToIds"))){
																continue;
															}
															
															$select = (isset($scaning) && $get_general_category['generalCategoryId'] == $scaning['generalCategoryId']) ? 'selected="selected"' : set_select('general_category_id', $get_general_category['generalCategoryId']);
															echo '<option '.$select.' value="'.$get_general_category['generalCategoryId'].'" '.$style.'>'.$get_general_category['generalCategoryName'].'</option>';
														}
													}
												?>
											</select>
										</div>
										<div class="loading-image-area loading-image" style="margin-top: -5px;">
											<label class="col-sm-6 control-label" for="form-field-1"><img src="<?php echo $includes_dir; ?>admin/images/rounded-light.gif"></label>
										</div>
									</div>
								</div>
								
								<div id="check_file_type" style="display:<?php echo (set_value('file_type_id') == 1 || set_value('file_type_id') == 2) ? 'block' : 'none'; ?>;">										
									<div class="form-group">
										<label class="col-sm-3 control-label">CNIC<span id="cnic_required" style="color:red;display:<?php echo (set_value('file_type_id') == 1) ? 'block' : 'none'; ?>">*</span></label>
										<div class="col-sm-9">
											<input value="<?php echo isset($scaning) ? $scaning['employeeCNIC'] : set_value('employee_cnic'); ?>" <?php echo isset($scaning) ? 'readonly="readonly"' : ''; ?> name="employee_cnic" id="employee_cnic" type="text" class="form-control input-mask-cnic" autocomplete="off">
											<div id="employee_exists"></div>
											<input type="hidden" value="" name="data_exists" id="data_exists">
										</div>											
									</div>
									
									<div class="form-group">
										<label class="col-sm-3 control-label">Employee Name <span style="color:red;">*</span></label>
										
										<div class="col-sm-9">
											<input  <?php echo (set_value('file_type_id') == 1) ? 'readonly' : ''; ?> value="<?php echo isset($scaning) ? $scaning['employeeName'] : set_value('employee_name'); ?>" name="employee_name" id="employee_name" type="text" class="form-control" autocomplete="off">
											<span class="scanning_alert help-block" style="display: none;"></span>
											<div class="loading-empname-image">
												<label class="col-sm-6 control-label" for="form-field-1"><img src="<?php echo $includes_dir; ?>admin/images/rounded-light.gif"></label>
											</div>
										</div>
										
									</div>
								</div>
								
								<div id="check_seniority_no" style="display:<?php echo (set_value('file_type_id') == 2) ? 'block' : 'none'; ?>;">
									<div class="form-group">
										<label class="col-sm-3 control-label">Seniority Number</label>
										<div class="col-sm-9">
											<input value="<?php echo isset($scaning) ? $scaning['seniorityNo'] : set_value('seniority_no'); ?>" name="seniority_no" id="seniority_no" type="text" class="form-control" maxlength="4" autocomplete="off">
										</div>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Old File Number</label>
									<div class="col-sm-9">
										<input value="<?php echo isset($scaning) ? $scaning['oldFileNumber'] : set_value('old_file_number'); ?>" name="old_file_number" id="old_file_number" type="text" class="form-control">
									</div>
								</div>
								
								<?php /* ?>
									<div class="row" id="year" style="display:none;">
									<div class="col-sm-12">
									<div class="form-group">
									<label class="col-sm-2 control-label">Appointment Year</label>
									<input type="text" id="appointment_year" name="appointment_year" class="form-control"/>
									</div>
									</div>
									
									</div>
								<?php */ ?>
								
								<div id="check_file_subject" style="display:<?php echo (set_value('file_type_id') == 3) ? 'block' : 'none'; ?>;">
									<div class="form-group">
									<label class="col-sm-3 control-label">Subject <span style="color:red;">*</span></label></label>
									<div class="col-sm-9">
										<input value="<?php echo isset($scaning) ? $scaning['subject'] : set_value('subject'); ?>" name="subject" id="subject" type="text" class="form-control">
									</div>
								</div>
							</div>
							
							<?php /* ?>
								<div class="row" id="issue_data_type" style="display:none;">
								<div class="col-sm-12">
								<div class="form-group">
								<label class="col-sm-2 control-label">Report Year</label>
								<input type="text" id="report_year" name="report_year" class="form-control"/>
								
								</div>
								</div>
								
								</div>
							<?php */ ?>
							
							
							<?php if(count($session_section) == 1) { ?>
								<input value="<?php echo $uacc_section_fk; ?>" type="hidden" name="section_id" id="section_id">
							<?php } else { ?>
								<div class="form-group">
									<label class="col-sm-3 control-label">Section <span style="color:red;">*</span></label>
									<div class="col-sm-9">
										<select name="section_id" id="section_id" class="form-control">
											<option value="">-----Select Section-----</option>
											<?php
												if($session_section) {
													foreach($session_section as $get_session_section) {
														//$select = (isset($scaning) && $get_file_received_from['uacc_id'] == $scaning['assignedBy']) ? 'selected="selected"' : set_select('assigned_by', $get_file_received_from['uacc_id']);
														//echo '<option '.$select.' value="'.$get_file_received_from['uacc_id'].'">'.$get_file_received_from['upro_first_name'].' '.$get_file_received_from['upro_last_name'].' ('.$get_file_received_from['sectionName'].')</option>';
														$select = (isset($scaning) && $get_session_section['sectionId'] == $scaning['sectionId']) ? 'selected="selected"' : set_select('section_id', $get_session_section['sectionId']);
														echo '<option '.$select.' value="'.$get_session_section['sectionId'].'">'.$get_session_section['sectionName'].'</option>';
													}
												}
											?>
										</select>
									</div>
								</div>
							<?php } ?>
							
							
							
							
							
							<?php /* if($this->session->userdata('section')) { ?>
								<input value="<?php echo $this->session->userdata('section'); ?>" type="hidden" name="assigned_by" id="assigned_by">
								<?php } else { ?>
								<div class="form-group">
									<label class="col-sm-3 control-label">File Received From <span style="color:red;">*</span></label>
									<div class="col-sm-9">
										<select name="assigned_by" id="assigned_by" class="form-control">
											<option value="">-----Select File Received From-----</option>
											<?php
												if($file_received_from) {
													foreach($file_received_from as $get_file_received_from) {
														$select = (isset($scaning) && $get_file_received_from['uacc_id'] == $scaning['assignedBy']) ? 'selected="selected"' : set_select('assigned_by', $get_file_received_from['uacc_id']);
														echo '<option '.$select.' value="'.$get_file_received_from['uacc_id'].'">'.$get_file_received_from['upro_first_name'].' '.$get_file_received_from['upro_last_name'].' ('.$get_file_received_from['sectionName'].')</option>';
													}
												}
											?>
										</select>
									</div>
								</div>
							<?php } */ ?>
							
							<?php /* ?>
								<div class="row">
								<div class="col-sm-12">
								<div class="form-group">
								<label class="col-sm-2 control-label">File Received On <span style="color:red;">*</span></label></label>
								<div class="input-group">
								<input value="<?php echo isset($scaning) ? date('d-m-Y', strtotime($scaning['assignedDate'])) : ''; ?>" type="text" id="assigned_date" name="assigned_date" class="form-control datepicker"/>
								<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
								</div>
								</div>
								</div>
								</div>
							<?php */ ?>
							
							<div class="col-sm-12">
								<input type="hidden" name="random_submit_num" value="<?php echo $random_submit_num; ?>">
								<input type="submit" id="submit_btn" class="btn btn-primary pull-right" value="Submit" />
							</div>
						</div>
					</div>
				</div>
				<!-- end: DATE/TIME PICKER PANEL --> 
			</div>
			<div class="col-sm-5">
				<div class="panel panel-default">
					<div class="panel-heading"> <i class="fa fa-external-link-square"></i> File upload</div>
					<div class="panel-body">
						
						<!--<form id="fileupload" action="https://jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">-->
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
				</div>
				
				
				<div class="panel panel-default" id="found_file_container" style="display: <?php echo (isset($related_file) && $related_file) ? 'block' : 'none'; ?>">
					<div class="panel-heading"> <i class="fa fa-external-link-square"></i>Related File</div>
					<div class="panel-body">
						<div id="" class="">
							<table role="" class="table table-striped" id="found_files">
								<?php
								if((isset($related_file) && $related_file)) {
									?>
									<tr>
										<th></th>
										<th>File Name</th>
										<th>File Type</th>
									</tr>
									<?php
									foreach($related_file as $get_related_file) {
										?>
										<tr>
											<td><span class="preview"><a href="<?php echo $base_url; ?>admin/scaning/scaning_detail/<?php echo $get_related_file['fileId']; ?>" title="<?php echo $get_related_file['fileName']; ?>" data-gallery target="_blank"><img src="<?php echo $includes_dir; ?>admin/images/pdf-icon.png" style="height: 25px;"></a></span></td>
											<td><p class="name"><a style="width: 181px; float: left; overflow: hidden;" href="<?php echo $base_url; ?>admin/scaning/scaning_detail/<?php echo $get_related_file['fileId']; ?>" title="<?php echo $get_related_file['fileName']; ?>" target="_blank"><?php echo $get_related_file['fileName']; ?></a></p></td>
											<td><?php echo $get_related_file['fileType']; ?></td>
										</tr>
										<?php
									}
								}
								?>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo form_close(); ?>
	<!-- end: PAGE --> 
</div>
<!-- end: MAIN CONTAINER --> 
<!-- statr: INCLUSE FOOTER -->
<?php $this->load->view('admin/includes/footer'); ?>
<!-- end: INCLUSE FOOTER -->

<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/select2/select2.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/datetimepicker/jquery.datetimepicker.css"/>

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
	<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="<?php echo $includes_dir; ?>admin/images/pdf-icon.png" class="pdf-image"></a>
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
		<a style="width: 181px; float: left; overflow: hidden;" href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
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
			});
			jQuery(document).ready(function () {
				Main.init();
				maskCNIC();
				FormValidator.init();
			});
			
			var check_entered = false;
			$('#fileupload').fileupload({
				url: '<?php echo base_url() ?>admin/scaning/file_upload/add/<?php echo isset($scaning) ? $scaning['fileId'].'/'.$scaning['fileTypeId'] : ''; ?>',
				autoUpload:true,
				acceptFileTypes: /(\.|\/)(pdf)$/i,
				maxNumberOfFiles: 1,
				maxChunkSize: 10000000, // 10 MB,
				add: function (e, data) { //alert('test 1');
				
					console.log(data);
				
					var that = this;
					$.getJSON('<?php echo base_url() ?>admin/scaning/file_upload/add/<?php echo isset($scaning) ? $scaning['fileId'].'/'.$scaning['fileTypeId'] : ''; ?>', {file: data.files[0].name, size: data.files[0].size, type: data.files[0].type}, function (result) {
						
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
							var file_size = bytesToSize(result.file.file_size);
							
							var table_tbody = $('#upload_file tbody.files');
							var html = '<tr class="template-upload fade in" id="file_already_exist">';
							html += '<td><span class="preview"></span></td>';
							html += '<td><p class="name">'+result.file.name+'</p><strong class="error text-danger">This file already exists</strong></td>';
							html += '<td><p class="size">'+file_size+'</p><div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div></td>';
							html += '<td><button class="btn btn-warning cancel" onclick="remove_already_exist_row()"><i class="glyphicon glyphicon-ban-circle"></i><span>Cancel</span></button></td>';
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
							$.getJSON('<?php echo base_url() ?>admin/scaning/file_upload/add/<?php echo isset($scaning) ? $scaning['fileId'].'/'.$scaning['fileTypeId'] : ''; ?>', {file: data.files[0].name, size: data.files[0].size, type: data.files[0].type})
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
			
			function remove_already_exist_row(){
				$('table#upload_file tr#file_already_exist').remove();
			}
			
			function bytesToSize(bytes) {
				var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
				if (bytes == 0) return '0 Byte';
				var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
				return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
			};
			
			$("#file_type_id").on('change', function() {
				var file_type = $("#file_type_id option:selected").val();
				
				$('#employee_name').val('');
				$('#employee_cnic').val('');
				$('#old_file_number').val('');
				$('#seniority_no').val('');
				$('#subject').val('');
				$('#data_exists').val('');
				$('#employee_exists').html('');
				$('#found_file_container').css('display','none');
				
				
				if(file_type == "1"){
					$('#check_file_type').css('display','block');
					$('#check_file_subject').css('display','none');
					<?php if (array_search('1', array_column($general_category, 'fileTypeId'))){ ?>
						$('#general_cat_name').css('display','block');
						search_category_name();
					<?php }else{ ?>
						$('#general_cat_name').css('display','none');
					<?php } ?>
					$('#check_seniority_no').css('display','none');
					$('#cnic_required').show();
					$('#submit_btn').prop('disabled', true);
					$("#employee_name").attr("readonly", true); 
					//$("#general_cat_name_requried").attr("readonly", true); 
					
					$('#general_cat_name_requried').css('display','none');
					$('.form-group').removeClass('has-error');
					$('.help-block').css('display','none');
					
					
				}
				else if(file_type == "2" ){
					$('#check_file_type').css('display','block');
					$('#check_file_subject').css('display','none');
					$('#general_cat_name').css('display','none');
					$('#check_seniority_no').css('display','block');
					$('#cnic_required').show();
					$('#submit_btn').prop('disabled', true);
					$("#employee_name").attr("readonly", true); 
					$('#general_category_id').html('');
				}
				else if(file_type == "" ){
					$('#check_file_type').hide();
					$('#check_file_subject').hide();
					$('#general_cat_name').hide();
					$('#check_seniority_no').hide();
					$('#cnic_required').hide();
					$('#submit_btn').prop('disabled', true);
					
				}
				else {
					$('#check_file_type').css('display','none');
					$('#check_file_subject').css('display','block');
					$('#general_cat_name').css('display','block');
					$('#general_cat_name_requried').show();
					$('#check_seniority_no').css('display','none');
					$('#submit_btn').prop('disabled', false);
					
					search_category_name();
				}
				
				/*else {
					$('#check_file_type').hide();
					$('#check_file_subject').hide();
					$('#general_cat_name').hide();
					$('#check_seniority_no').hide();
					$('#cnic_required').hide();
					$('#submit_btn').prop('disabled', true);
				}*/
			});
			function maskCNIC(){
				$('.input-mask-cnic').mask('9999999999999');
			}
			
			//$('#employee_name').keyup(function() {
				
			//});
			
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
							subject:{
								required : function(element) {
									if($("#file_type_id option:selected").val() == '3') {
										return true;
									} 
									else{
										return false;
									}
								}
							},
							section_id: {
								required: true
							},
							general_category_id:{
								required : function(element) {
									if($("#file_type_id option:selected").val() == '3') {
										return true;
									} 
									else{
										return false;
									}
								}
							},
						},
						messages: {
							file_type_id: "Please select file type",
							employee_name: {
								required: "Please Enter Employee Name",
								notNumber: "Numbers are not Allowed"
							}, 
							employee_cnic: "Please Enter CNIC Number",
							section_id: "Please select section",
							//assigned_date: "Please select assigned date",
							subject: "Please enter subject",
							general_category_id: "Please select category name",
							
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
			
			<?php if(!isset($scaning)) { ?>
				$("#employee_cnic").focusout(function () {
					
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
										 $("#employee_name").attr("readonly", false); 
										 
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
			<?php } ?>
			function search_category_name(){
			var file_type = $("#file_type_id").val();
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
        });
		}
		</script>
		
		<style>
			#employee_exists .alert-success {
				margin-bottom: 0px;
				padding: 5px 15px;
				margin-top: 3px;
			}
			.loading-image {
			position: absolute;
			width: 70%;
			background-color: #e6e6e6;
			line-height: 40px;
			opacity: 0.5;
			margin-left: 160px;
			display: none;
			}
			.scanning_alert { 
				color: #a94442;
				margin-bottom: 0;
			}
			.pdf-image {
				height: 50px;
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
		</style>
		
				