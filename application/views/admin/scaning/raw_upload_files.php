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
				<div class="col-sm-12">
					<div class="panel panel-default">
						<div class="panel-heading"> <i class="fa fa-external-link-square"></i>Raw Files</div>
						<div class="panel-body">
							
							<!--<form id="fileupload" action="https://jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">-->
							<div id="fileupload" class="">
								<!-- Redirect browsers with JavaScript disabled to the origin page -->
								<noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
								<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
								<div class="row fileupload-buttonbar">
									<div class="col-lg-12">
										<!-- The fileinput-button span is used to style the file input field as button -->
										<button type="button" class="btn btn-danger delete">
											<i class="glyphicon glyphicon-trash"></i>
											<span>Delete</span>
										</button>
										<input type="checkbox" class="toggle">
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
	<tr>
		<th></th>
		<th>File Name</th>
		<th>Entered Date</th>
		<th>Entered By</th>
		<th>Action</th>
	</tr>
	{% console.log(o); for (var i=0, file; file=o.files[i]; i++) { %}
		<tr class="template-download fade">
		<td>
		<span class="preview">
	<a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img style="height: 36px;" src="<?php echo $includes_dir; ?>admin/images/pdf-icon.png"></a>
</span>
<input type="hidden" value="{%=file.id%}" name="file_uploaded_id[]">
</td>
<td>
	<p class="name">
		{% if (file.url) { %}
		<a style="width: 191px; float: left; overflow: hidden;" href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
		{% } else { %}
		<span>{%=file.name%}</span>
		{% } %}
		</p>
		{% if (file.error) { %}
		<div><span class="label label-danger">Error</span> {%=file.error%}</div>
		{% } %}
		</td>
		<?php /* ?>
		<td>
		<span class="size">{%=o.formatFileSize(file.size)%}</span>
		</td>
		<?php */ ?>
		
		<td>
			{% if (file.file_uploaded_date) { %}
				<span>{%=file.file_uploaded_date%}</span>
			{% } %}
		</td>
		
		<td>
			{% if (file.file_uploaded_by) { %}
				<span>{%=file.file_uploaded_by%}</span>
			{% } %}
		</td>
		
		<td>
		{% if (file.deleteUrl) { %}
		<button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
		<i class="glyphicon glyphicon-trash"></i>
		<span>Delete</span>
		</button>
		<input type="checkbox" name="delete" value="1" class="toggle">
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
			jQuery(document).ready(function () {
				Main.init();
			});
			
			$('#fileupload').fileupload({
				url: '<?php echo base_url() ?>admin/scaning/file_upload/raw/<?php echo isset($scaning) ? $scaning['fileId'].'/'.$scaning['fileTypeId'] : ''; ?>',
				autoUpload:true,
				acceptFileTypes: /(\.|\/)(pdf)$/i
				}).on('fileuploadsubmit', function (e, data) {
				data.formData = data.context.find(':input').serializeArray();
			});
			
		</script>
		
		<style>
			.table.table-striped {
				margin-top: 10px
			}
		</style>
		
				