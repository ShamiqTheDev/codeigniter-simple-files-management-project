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
			
			<div class="row">
				<div class="col-sm-7"> 
					<!-- start: DATE/TIME PICKER PANEL -->
					<div class="panel panel-default">
						<div class="panel-heading"> <i class="fa fa-external-link-square"></i> Scheme Detail</div>
						<div class="panel-body">
							<div class="form-horizontal">
								<input type="hidden" value="<?php echo isset($scheme_data) ? $scheme_data['parentAdpNumber'] : set_value('parent_adp_number'); ?>" name="parent_adp_number" id="parent_adp_number">
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Document Code</label>
									<div class="col-sm-6">
										<input value="<?php echo $uuid ?>" name="uuid" id="uuid" type="text" class="form-control" autocomplete="off" readonly>
										
									</div>
									<div class="col-sm-3">
										<button class="btn btn-yellow pull-right" id="copy_btn">Copy Code</button>
									</div>
								</div>
								
								<div class="form-group">
									<?php
									
									$start_year = '2008';
									$current_year = (date('m') > 6) ? date('Y') : date('Y', strtotime('-1 year'));
									
									for($i = $start_year; $i <= $current_year; $i++) {
										$next_year = $i+1;
										$year_array[] = $i .'-'. $next_year;
									}									
									
									?>
									<label class="col-sm-3 control-label">ADP Year<span style="color:red;">*</span></label>
									<div class="col-sm-9">
										<select name="adp_year" id="adp_year" class="form-control">
											<?php
												if($year_array) {
													foreach($year_array as $get_year) {
														$select = ($_POST) ? set_select('adp_year', $get_year) : (($current_year.'-'.($current_year + 1) == $get_year) ? 'selected="selected"' : '');
														echo '<option '.$select.' value="'.$get_year.'">'.$get_year.'</option>';
													}
												}
											?>
										</select>
									
										<?php /* ?>
										<input value="<?php echo isset($scheme_data) ? $scheme_data['adpYear'] : (set_value('adp_year') ? set_value('adp_year') : ''); ?>" name="adp_year" id="adp_year" type="text" class="form-control">
										<?php */ ?>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">ADP Number<span style="color:red;">*</span></label>
									<div class="col-sm-9">
										<input value="<?php echo isset($scheme_data) ? $scheme_data['adpNumber'] : set_value('adp_number'); ?>" name="adp_number" id="adp_number" type="text" class="form-control input-mask-cnic11" autocomplete="off">
									</div>
									<input type="hidden" value="<?php echo set_value('data_exists'); ?>" name="data_exists" id="data_exists">
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Scheme Name<span style="color:red;">*</span></label>
									<div class="col-sm-9">
										<?php /* ?><input  readonly value="<?php echo isset($scheme_data) ? $scheme_data['schemeName'] : set_value('scheme_name'); ?>" name="scheme_name" id="scheme_name" type="text" class="form-control" autocomplete="off"><?php */ ?>
										<textarea readonly name="scheme_name" id="scheme_name" type="text" class="form-control" autocomplete="off"><?php echo isset($scheme_data) ? $scheme_data['schemeName'] : trim(set_value('scheme_name')); ?></textarea>
										<?php //echo  trim(set_value('scheme_name')); ?>
										<span class="scheme_alert help-block" style="display: none;"></span>
										<div class="loading-image">
											<label class="col-sm-6 control-label" for="form-field-1"><img src="<?php echo $includes_dir; ?>admin/images/rounded-light.gif"></label>
										</div>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Document Type<span style="color:red;">*</span></label>
									<div class="col-sm-9">
										<select name="document_type_id" id="document_type_id" class="form-control">
											<option value="">-----Select Document Type-----</option>
											<?php
												if($document_type) {
													foreach($document_type as $get_document_type) {
														$select = (isset($scheme_data) && $get_document_type['documentTypeId'] == $scheme_data['documentTypeId']) ? 'selected="selected"' : set_select('document_type_id', $get_document_type['documentTypeId']);
														echo '<option '.$select.' value="'.$get_document_type['documentTypeId'].'">'.$get_document_type['documentType'].'</option>';
													}
												}
											?>
										</select>
									</div>
								</div>
								<input type="hidden" name="document_type" id="document_type" value="<?php echo set_value('document_type'); ?>">
								
								<div class="form-group document_type_child_div" style="display: <?php echo ($_POST && $_POST['document_type_child_id'] ) ? 'block' : 'none'; ?>;">
									<label class="col-sm-3 control-label"><span id="label_name"><?php echo ($_POST && $_POST['document_type_child_id'] ) ? set_value('document_type') : ''; ?></span></label>
									<div class="col-sm-9">
										<select name="document_type_child_id" id="document_type_child_id" class="form-control">
											<?php
												if($child_document_type) {
													foreach($child_document_type as $get_child_document_type) {
														$select = (isset($scheme_data) && $get_child_document_type['documentTypeId'] == $scheme_data['documentTypeChildId']) ? 'selected="selected"' : set_select('document_type_child_id', $get_child_document_type['documentTypeId']);
														echo '<option '.$select.' value="'.$get_child_document_type['documentTypeId'].'">'.$get_child_document_type['documentType'].'</option>';
													}
												}
											?>
										</select>
									</div>
								</div>
								
								<div class="form-group miscellaneous_type_child" style="display: <?php echo ($_POST && $_POST['miscellaneous_type']) ? 'block' : 'none'; ?>;">
									<label class="col-sm-3 control-label">Miscellaneous Type<span style="color:red;">*</span></label>
									<div class="col-sm-9">
										<input placeholder="" name="miscellaneous_type" id="miscellaneous_type" class="form-control" type="text" value="<?php echo (isset($scheme_data['miscellaneousType'])) ? $scheme_data['miscellaneousType'] : set_value('miscellaneous_type'); ?>" autocomplete="off">
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-sm-3 control-label">Document Date<span style="color:red;">*</span></label>
									<div class="col-sm-9">
										<input placeholder="" name="scheme_date" id="scheme_date" class="form-control datepicker" type="text" value="<?php echo (isset($scheme_data['schemeDate']) && ($scheme_data['schemeDate'] != '0000-00-00')) ? date(('d-m-Y'), strtotime($scheme_data['schemeDate'])) : set_value('scheme_date'); ?>" autocomplete="off">
									</div>
								</div>
								
								
								<div class="col-sm-12">
									<input type="hidden" name="random_submit_num" value="<?php echo $random_submit_num; ?>">
									<input <?php echo $_POST ? '' : 'disabled'; ?> type="submit" id="submit_btn" class="btn btn-primary pull-right" value="Submit" />
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
											<th>Document Type</th>
										</tr>
										<?php
										foreach($related_file as $get_related_file) {
											?>
											<tr>
												<td><span class="preview"><a href="<?php echo $base_url; ?>admin/scheme/scheme_detail/<?php echo $get_related_file['schemeId']; ?>" title="<?php echo $get_related_file['fileName']; ?>" data-gallery target="_blank"><img src="<?php echo $includes_dir; ?>admin/images/pdf-icon.png" style="height: 25px;"></a></span></td>
												<td><p class="name"><a style="width: 181px; float: left; overflow: hidden;" href="<?php echo $base_url; ?>admin/scheme/scheme_detail/<?php echo $get_related_file['schemeId']; ?>" title="<?php echo $get_related_file['fileName']; ?>" target="_blank"><?php echo $get_related_file['fileName']; ?></a></p></td>
												<td><?php echo $get_related_file['documentType']; ?></td>
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
	<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/plugins/Inputmask-4.x/dist/jquery.inputmask.bundle.js" charset="utf-8"></script>
	

<script src="<?php echo $includes_dir; ?>admin/js/script.js"></script> 


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
			
			
			jQuery(document).ready(function () {
				Main.init();
				maskCNIC();
				
				document.getElementById("submit_btn").onclick = function() {
					FormValidator.init();
				}
				document.getElementById("copy_btn").onclick = function() {
					//FormValidator.init();
					var uuid = $('#uuid').val();
					copyUid(uuid);
					$("#copy_btn").text('Copied');
					return false;
				}
				
				
				dateTimePicker();
			});
			
			function dateTimePicker(){
				
				$("#scheme_date").val('');
				
				// START: set max date 
				var current_date = <?php echo date("d")?>;
				var current_month = <?php echo date("m")?>;
				var current_year = <?php echo date("Y")?>;
				
				var adp_year = $("#adp_year").val();
				var document_type_id = $("#document_type_id").val();
				
				var splitted_adp_year = adp_year.split("-");
				var min_year = splitted_adp_year[0];
				var max_year = splitted_adp_year[1];
				
				if(document_type_id == 6){
					min_year = 2008;
					max_year = current_year;
					
				}
				
				var max_date = current_year + "/" + current_month + "/" + current_date;
				var max_date_conversion = current_year + "-" + current_month + "-" + current_date;
				
				var min_date = min_year + "/07/01"; 
				var default_date = max_date;
				var year_plus_one = parseInt(current_year) + 1;
				
				var show_max_year = parseInt(max_year) + 1;
				var show_max_date_conversion = show_max_year + "-06-30";
				
				
				// if year is not equal to max_year and max year is not equal to 'year plus one value'
				if(current_year != max_year && max_year != year_plus_one && parseInt(show_max_date_conversion) < parseInt(max_date_conversion)){
					
					max_date = show_max_year + "/06/30";
					min_date = min_year + "/07/01";
					default_date = min_date;
				}
				console.log("min_date = "+min_date);
				console.log("max_date = "+max_date);
 				
				
				//$("#datepicker").datetimepicker("setDate", startDate);
				
				$('.datepicker').datetimepicker({
					startDate: default_date,
					timepicker: false,
					format: 'd-m-Y',
					scrollMonth : false,
					scrollInput : false,
					minDate: min_date,
					maxDate: max_date,
					//minDate: 0,
				});
			}
			
			var check_entered = false;
			$('#fileupload').fileupload({
				url: '<?php echo base_url() ?>admin/scheme/file_upload/add/<?php echo isset($scheme_data) ? $scheme_data['schemeId'] : ''; ?>',
				autoUpload:true,
				acceptFileTypes: /(\.|\/)(pdf)$/i,
				maxNumberOfFiles: 1,
				maxChunkSize: 10000000, // 10 MB,
				add: function (e, data) { //alert('test 1');
					
					console.log(data);
					
					var that = this;
					$.getJSON('<?php echo base_url() ?>admin/scheme/file_upload/add/<?php echo isset($scheme_data) ? $scheme_data['schemeId'] : ''; ?>', {file: data.files[0].name, size: data.files[0].size, type: data.files[0].type}, function (result) {
						
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
						$.getJSON('<?php echo base_url() ?>admin/scheme/file_upload/add/<?php echo isset($scheme_data) ? $scheme_data['schemeId'] : ''; ?>', {file: data.files[0].name, size: data.files[0].size, type: data.files[0].type})
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
							adp_year: {
								required: true
							},
							adp_number: {
								required: true,
								number:true
							},
							scheme_name:{
								required: true
							},
							scheme_date:{
								required: true
							},
							document_type_id: {
								required: true
							},
							miscellaneous_type: {
								required: function(){
									var document_type_id_valid = $('#document_type_id').val();
									if(document_type_id_valid == 7){
										return true;
									}
									else {
										return false;
									}
										
								}
							}
						},
						messages: {
							adp_year: "Please enter scheme year",
							adp_number: {
								required: "Please enter scheme number",
								number: "Only numbers are allowed"
							},
							scheme_name: "Please enter scheme number",
							scheme_date: "Please enter scheme date",
							document_type_id: "Please select document type",
							miscellaneous_type: "Please select miscellaneous type",
							
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
							//HTMLFormElement.prototype.submit.call($('#fileUploadingForm')[0]);
							submitForm()
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
			
			function submitForm() {
			
				var adp_year = $('#adp_year').val();
				var adp_number = $('#adp_number').val();
				var document_type_id = $('#document_type_id').val();
				var document_type_child_id = $('#document_type_child_id').val();
				var miscellaneous_type = $('#miscellaneous_type').val();
				var parent_adp_number = $('#parent_adp_number').val();
				
				var adp_number_year = adp_number+"-"+adp_year;
				
				if(adp_number_year != parent_adp_number && document_type_id == 1){
					alert("You can't upload PC-1 document in child ADP Number = "+adp_number);
					return false;
				}
				
				if(document_type_id == 17){ // if document type is Procurement / Vendor Document then allow duplication
					submitFormAfterValidation();
					return true;
				}
				
				var post_data = {
					vcd_adp_year: adp_year,
					vcd_adp_number: adp_number,
					vcd_document_type_id: document_type_id,
					vcd_document_type_child_id: document_type_child_id,
					vcd_miscellaneous_type: miscellaneous_type,
					vcd_parent_adp_number: parent_adp_number,
				};
			
				$.ajax({
					url: '<?php echo base_url() ?>admin/scheme/ajax_verify_scheme_details',
					type: 'POST',
					dataType: "JSON",
					data: {post_data: post_data},
					success: function (response) {
						//console.log(response.length);
						if(response.verify_record) {
							alert('This document type already exist');
						}
						else {
							//HTMLFormElement.prototype.submit.call($('#fileUploadingForm')[0]);
							submitFormAfterValidation();
						}
						
					},
					error: function () {
						console.log('Error in retrieving Site.');
					}
				});
			}
			
			function submitFormAfterValidation(){
				HTMLFormElement.prototype.submit.call($('#fileUploadingForm')[0]);
			}
			
			function maskCNIC(){
				$('.input-mask-cnic').inputmask({
					mask: "9999999999999",
				});
				
				$('#adp_year').inputmask({
					mask: "9999-9999",
				});
			}
			
			function copyUid(e){
				var tempItem = document.createElement('input');

				tempItem.setAttribute('type','text');
				tempItem.setAttribute('display','none');
				
				let content = e;
				if (e instanceof HTMLElement) {
						content = e.innerHTML;
				}
				
				tempItem.setAttribute('value',content);
				document.body.appendChild(tempItem);
				
				tempItem.select();
				document.execCommand('Copy');

				tempItem.parentElement.removeChild(tempItem);

			}
			
			<?php if(!isset($scheme_data)) { ?>
				//$("#adp_year, #adp_number").focusout(function () {
				$("body").on('focusout, change', '#adp_year, #adp_number', function () {
					
					$('#scheme_name').val('');
					$('#parent_adp_number').val('');
					$('.scheme_alert').text('').hide();
					
					dateTimePicker();
					
					var adp_year = $('#adp_year').val();
					var adp_number = $('#adp_number').val();
					
					$('#found_file_container').hide();
					var table = $('#found_files');
					table.empty();

					$('#submit_btn').prop('disabled', true);
						
					if(adp_year && adp_number) {
					
						$('.loading-image').show();
					
						$.ajax({
							url: '<?php echo base_url() ?>admin/scheme/ajax_get_scheme_details',
							type: 'POST',
							dataType: "JSON",
							data: {adp_year: adp_year, adp_number: adp_number},
							success: function (response) {
								//console.log(response.length);
								if(response.response_data) {
									
									$('.scheme_alert').text('').hide();
									$('#scheme_name').val(response.response_data.scheme_name);
									$('#data_exists').val(response.response_data.data_exists);
									$('#parent_adp_number').val(response.response_data.parent_adp_number);
									$('#submit_btn').prop('disabled', false);
									
								}
								else {
									$('.scheme_alert').text('Record not found').show();
								}
								
								$('.loading-image').hide();
								
								if(response.scheme_detail.length != 0) {
									
									var html = '<tr><th></th><th>File Name</th><th>Document Type</th></tr>';
									
									//if (response.division.length != 0) {
										$.each(response.scheme_detail, function (i, fb) {
											console.log(fb);
											//select.append('<option value="' + fb.divisionID + '">' + fb.divisionName + '</option>');
											
											html += '<tr>';
											html += '<td><span class="preview"><a href="<?php echo $base_url; ?>admin/scheme/scheme_detail_view/'+fb.schemeId+'" title="'+fb.fileName+'" data-gallery target="_blank"><img src="<?php echo $includes_dir; ?>admin/images/pdf-icon.png" style="height: 25px;"></a></span></td>'
											html += '<td><p class="name"><a style="width: 181px; float: left; overflow: hidden;" href="<?php echo $base_url; ?>admin/scheme/scheme_detail_view/'+fb.schemeId+'" title="'+fb.fileName+'" target="_blank">'+fb.fileName+'</a></p></td>'
											html += '<td>'+fb.documentType+'</td>';
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
					
					
					
					/*$.ajax({
						url: '<?php echo base_url() ?>admin/scheme/ajax_get_uploaded_file',
						type: 'POST',
						dataType: "JSON",
						//data: {employee_cnic: employee_cnic, file_type_id: file_type_id},
						data: {adp_year: adp_year, adp_number: adp_number},
						success: function (response) {
							//console.log(response.length);
							if(response.scheme_detail.length != 0) {
								
								var table = $('#found_files');
			

								table.empty();
								
								var html = '<tr><th></th><th>File Name</th><th>Document Type</th></tr>';
								
								//if (response.division.length != 0) {
									$.each(response.scheme_detail, function (i, fb) {
										console.log(fb);
										//select.append('<option value="' + fb.divisionID + '">' + fb.divisionName + '</option>');
										
										html += '<tr>';
										html += '<td><span class="preview"><a href="<?php echo $base_url; ?>admin/scheme/scheme_detail/'+fb.schemeId+'" title="'+fb.fileName+'" data-gallery target="_blank"><img src="<?php echo $includes_dir; ?>admin/images/pdf-icon.png" style="height: 25px;"></a></span></td>'
										html += '<td><p class="name"><a style="width: 181px; float: left; overflow: hidden;" href="<?php echo $base_url; ?>admin/scheme/scheme_detail/'+fb.schemeId+'" title="'+fb.fileName+'" target="_blank">'+fb.fileName+'</a></p></td>'
										html += '<td>'+fb.documentType+'</td>';
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
					});*/
					
				});
			<?php } ?>
			
			/**************************************************
			// if change document type get document type child
			**************************************************/
			$("#document_type_id").change(function () {
				var document_type_id = $(this).val();
				var document_type = $(this).children('option[value='+document_type_id+']').text();
				$('#document_type').val(document_type);
				
				$('#document_type_child_id').empty();
				$('.document_type_child_div').hide();
				
				$('#miscellaneous_type').val('');
				$('.miscellaneous_type_child').hide();
				
				if(document_type_id == '') {
					return false;
				}
				
				dateTimePicker();

				if(document_type_id == 7){
					$('.miscellaneous_type_child').show();
					return true;
				}	
				
				$.ajax({
					url: '<?php echo base_url(); ?>admin/scheme/get_document_type_child_ajax',
					type: 'POST',
					dataType: "JSON",
					data: {document_type_id: document_type_id},
					success: function (response) {
						//console.log(response.length);
						
						

						//select.empty();
						//select.append('<option value="">Select City</option>');

						if (response.length != 0) {
						
							$('#label_name').text(document_type);
						
							var select = $('#document_type_child_id');
						
							$.each(response, function (i, fb) {
								
								//if() {
								//}
							
								console.log(fb);
								select.append('<option value="' + fb.documentTypeId + '">' + fb.documentType + '</option>');
							});
							
							$('.document_type_child_div').show();
						}
						
					},
					error: function () {
						console.log('Error in retrieving Site.');
					}
				});

			});
			
			
			$('#adp_number').keypress(function(event) {
				var key = window.event ? event.keyCode : event.which;
				if (event.keyCode === 8 || event.keyCode === 46) {
					return true;
				} else if ( key < 48 || key > 57 ) {
					return false;
				} else {
					return true;
				}
			});
			
			$("#miscellaneous_type").keypress(function(event) {
				var key = window.event ? event.keyCode : event.which;
				//alert(key);
				if ((event.keyCode >= 65 && event.keyCode <= 90) || (event.keyCode >= 97 && event.keyCode <= 122) || event.keyCode === 32) {
					return true;
				}
				else {
					return false;
				}
			});
			
			
			$('#scheme_date').unbind('keydown').bind('keydown cut copy paste', function (event) {
				event.preventDefault();
				return false;
			});
			
		</script>
		
		<style>
			#employee_exists .alert-success {
			margin-bottom: 0px;
			padding: 5px 15px;
			margin-top: 3px;
			}
			.pdf-image {
			height: 50px;
			}
			.scheme_alert { 
				color: #a94442;
				margin-bottom: 0;
			}
			.loading-image label img {
				height: 45px;
			}
			.loading-image {
				width: 94% !important;
				background-color: #e6e6e6;
				top: 0px;
				height: 100%;
			}
			.loading-image .control-label  {
				height: 100%;
				width: 100%;
				text-align: center;
				top: 13px;
			}
		</style>
		
				