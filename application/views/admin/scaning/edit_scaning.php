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
      <div class="col-sm-4">
        <div class="alert alert-block alert-info fade in center scan">
          <h3 class="alert-heading">Total No of Files to be Scan</h3>
          <h4>100</h4>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="alert alert-block alert-success fade in center scan">
          <h3 class="alert-heading">Total Files Scanned</h3>
          <h4>120</h4>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="alert alert-block alert-warning fade in center scan">
          <h3 class="alert-heading">Today Scanning </h3>
          <h4>130</h4>
        </div>
      </div>
    </div>
				<div class="row">
					<div class="col-sm-8"> 
						<!-- start: DATE/TIME PICKER PANEL -->
						<div class="panel panel-default">
							<div class="panel-heading"> <i class="fa fa-external-link-square"></i> File Detail</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											
											<label>File Type<span style="color:red;">*</span></label>
											<select name="file_type_id" id="file_type_id" class="form-control">
												<option value="">-----Select File Type-----</option>
												<?php
													if($file_types) {
														foreach($file_types as $file_type) {
															$select = ($file_type['fileTypeId'] == $scaning['fileTypeId']) ? 'selected="selected"' : '';
															echo '<option '.$select.' value="'.$file_type['fileTypeId'].'">'.$file_type['fileType'].'</option>';
														}
													}
												?>
											</select>
										</div>
									</div>
									
								</div>
								
								<div class="row" id="general_doc_type" style="display:none;">
									<div class="col-sm-12">
										<div class="form-group">
											
											<label>General Category Name<span style="color:red;">*</span></label>
											<select name="general_category_id" id="general_category_id" class="form-control">
												<option value="">-----Select General Category Name-----</option>
												<?php
													if($general_category) {
														foreach($general_category as $get_general_category) {
															$select = ($get_general_category['generalCategoryId'] == $scaning['generalCategoryId']) ? 'selected="selected"' : '';
															echo '<option '.$select.' value="'.$get_general_category['generalCategoryId'].'">'.$get_general_category['generalCategoryName'].'</option>';
														}
													}
												?>
											</select>
										</div>
									</div>
									
								</div>
								<div id="check_file_type" style="display:none;">
									<div class="row">
										<div class="col-sm-12">
											<div class="form-group">
												<label>Employee CNIC <small class="text-warning">44103145524557</small><span style="color:red;">*</span></label>
												<input value="<?php echo $scaning['employeeCNIC']; ?>" name="employee_cnic" id="employee_cnic" type="text" class="form-control input-mask-cnic">
												<div id="employee_exists"></div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<div class="form-group">
												<label>Employee Name<span style="color:red;">*</span></label>
												<input value="<?php echo $scaning['employeeName']; ?>" name="employee_name" id="employee_name" type="text" class="form-control">
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											<label>Old File Number</label>
											<input value="<?php echo $scaning['oldFileNumber']; ?>" name="old_file_number" id="old_file_number" type="text" class="form-control">
										</div>
									</div>
								</div>
								<?php /* ?>
								<div class="row" id="year" style="display:none;">
									<div class="col-sm-12">
										<div class="form-group">
											<label>Appointment Year</label>
											<input type="text" id="appointment_year" name="appointment_year" class="form-control"/>
										</div>
									</div>
									
								</div>
								<?php */ ?>
								
								<div id="check_file_subject" class="row" style="display:none;">
									<div class="col-sm-12">
										<div class="form-group">
											<label>Subject</label>
											<input value="<?php echo $scaning['subject']; ?>" name="subject" id="subject" type="text" class="form-control">
										</div>
									</div>
								</div>
								
								<?php /* ?>
								<div class="row" id="issue_data_type" style="display:none;">
									<div class="col-sm-12">
										<div class="form-group">
											
											<label>Report Year</label>
											<input type="text" id="report_year" name="report_year" class="form-control"/>
											
										</div>
									</div>
									
								</div>
								<?php */ ?>
								
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											
											<label>File Received From<span style="color:red;">*</span></label>
											<select name="assigned_by" id="assigned_by" class="form-control">
												<option value="">-----Select File Received From-----</option>
												<?php
													if($file_received_from) {
														foreach($file_received_from as $get_file_received_from) {
															$select = ($get_file_received_from['uacc_id'] == $scaning['assignedBy']) ? 'selected="selected"' : '';
															echo '<option '.$select.' value="'.$get_file_received_from['uacc_id'].'">'.$get_file_received_from['upro_first_name'].' '.$get_file_received_from['upro_last_name'].' ('.$get_file_received_from['designationName'].')</option>';
														}
													}
												?>
											</select>
										</div>
									</div>
									
								</div>
								
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group">
											
											<label>File Received On</label>
											<div class="input-group">
												<input value="<?php echo date('d-m-Y', strtotime($scaning['assignedDate'])); ?>" type="text" id="assigned_date" name="assigned_date" class="form-control datepicker"/>
												<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
											</div>
										</div>
									</div>
									
								</div>
								
								<div class="col-sm-12">
									<input type="submit" id="submit_btn" class="btn btn-primary pull-right" value="Submit" />
								</div>
							</div>
						</div>
						<!-- end: DATE/TIME PICKER PANEL --> 
					</div>
					<div class="col-sm-4">
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
									<table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
								</div>
								<!--</form>-->
								
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
		<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="<?php echo $includes_dir; ?>admin/images/pdf-icon.png"></a>
		</span>
		<input type="hidden" value="{%=file.id%}" name="file_uploaded_id[]">
		</td>
		<td>
		<p class="name">
		{% if (file.url) { %}
		<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
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
	<script src="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/js/main.js"></script>
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
		
		
		$('#fileupload').fileupload({
			url: '<?php echo base_url() ?>admin/scaning/file_upload/add/<?php echo $scaning['fileId']; ?>',
			autoUpload:true,
			acceptFileTypes: /(\.|\/)(pdf)$/i
		}).on('fileuploadsubmit', function (e, data) {
			data.formData = data.context.find(':input').serializeArray();
		});		
		
		
		$("#file_type_id").on('change', function() {
			var file_type = $("#file_type_id option:selected").text();
			if(file_type == "Personnel File"){
				$('#check_file_type').css('display','block');
				$('#check_file_subject').css('display','none');
				$('#general_doc_type').css('display','none');
				$('#issue_data_type').css('display','none');
				$('#employee_name').val('');
				$('#employee_cnic').val('');
				$('#year').css('display','block');
			}
			else if(file_type == "ACR File" ){
				$('#check_file_type').css('display','block');
				$('#check_file_subject').css('display','none');
				$('#general_doc_type').css('display','none');
				$('#issue_data_type').css('display','block');
				$('#employee_name').val('');
				$('#employee_cnic').val('');
				$('#year').css('display','none');
			}
			else{
				$('#check_file_type').css('display','none');
				$('#check_file_subject').css('display','block');
				$('#issue_data_type').css('display','none');
				$('#general_doc_type').css('display','block');
				$('#year').css('display','none');
			}
		});
		function maskCNIC(){
			$('.input-mask-cnic').mask('9999999999999');
		}
		
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
								if($("#file_type_id option:selected").text() == 'Personnel File' || $("#file_type_id option:selected").text() == 'ACR File') {
									return true;
								} 
								else{
									return false;
								}
							}
						},
						employee_cnic:{
							required : function(element) {
								if($("#file_type_id option:selected").text() == 'Personnel File') {
									return true;
								} 
								else{
									return false;
								}
							}
						},
						assigned_by: {
							required: true
						},
						assigned_date: {
							required: true
						}
					},
					messages: {
						file_type_id: "Please select file type",
						employee_name: "Please Enter Employee Name",
						employee_cnic: "Please Enter CNIC Number",
						assigned_by: "Please select assigned by name",
						assigned_date: "Please select assigned date",
						
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
	</script>
	
	
