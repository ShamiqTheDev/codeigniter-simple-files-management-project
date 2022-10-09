<?php
	//echo $receipt['receiptDetailId']; die();
/*$hide = "";

if(isset($receipt['fileNumber']) && !empty($receipt['fileNumber']))
{
	$hide = "style = 'display:none;' ";
}*/
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
		//$attributes = array('class' => 'form-horizontal', 'role' => '', 'id' => 'note_sheet_form');
		//echo form_open_multipart(current_url(), $attributes);
		?>
		<div class="form-horizontal">
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
				<!--<button type="button" id="edit_receipt" action-form="edit_receipt" class="receipt_submit btn btn-primary top_btn">
					Edit Receipt
				</button>-->
				
				<div class="row">
					
					<div class="col-md-12">
						<div class="col-md-6">
						<?php /*if($note_sheet_color == 'green'){
									$style="display:inline;";		
								  }
								  else{
									$style="display:none;";
								  }*/
							?>
							<?php /* ?>
							<button type="submit" class="receipt_submit btn btn-primary top_btn">
								Save
							</button>
							<?php */ ?>
							<?php /* ?>
							<a href="<?php echo $base_url; ?>admin/files/create/<?php echo $file_id; ?>" links="<?php echo $base_url; ?>admin/files/create/<?php echo $file_id; ?>" class="edit_file btn btn-primary top_btn">
								Edit
							</a>
							<?php */ ?>
							<?php if($receipt_detail) { ?>
								<button type="button" class="note_sheet_send btn btn-primary top_btn" style="<?php echo $style; ?>">
									Send
								</button>
							<?php } ?>
							<div style="margin-bottom:10px;"></div>
						</div>
						<div class="col-md-6">
							<?php /* ?>
							<a href="<?php echo $base_url; ?>admin/files/note_sheet/<?php echo $file_id.'/'.$note_type.'/'; echo $note_sheet['noteSheetId']; ?>/toc" class="receipt_submit btn btn-primary top_btn">
								ToC
							</a>
							
							<a href="<?php echo $base_url; ?>admin/receipts/receipt_send/<?php echo $receipt['receiptDetailId']; ?>" links="<?php echo $base_url; ?>admin/receipts/browse_diarise/<?php echo $receipt['receiptDetailId']; ?>" class="receipt_submit btn btn-primary top_btn">
								Recent
							</a>
							
							<a href="<?php echo $base_url; ?>admin/receipts/receipt_send/<?php echo $receipt['receiptDetailId']; ?>" links="<?php echo $base_url; ?>admin/receipts/browse_diarise/<?php echo $receipt['receiptDetailId']; ?>" class="receipt_submit btn btn-primary top_btn">
								All
							</a>
							<?php */ ?>
							<div style="margin-bottom:10px;"></div>
						</div>
					</div>
					
					<div class="col-md-12">
						<div class="form-group">
							<div class="form-validation">
								<label class="col-sm-2 control-label" style="width: 5%;">
									File No:
								</label>
								<div class="col-sm-4" style="padding: 4px 0 0 0; width: 15%;">
									<?php echo $files_detail['fileNumber']; ?>
								</div>
							</div>
							
							<div class="form-validation">
								<label class="col-sm-2 control-label" style="width: 5%;">
									Subject:
								</label>
								<div class="col-sm-6" style="padding: 4px 0 0 0;">
									<?php echo shorter($files_detail['description'], '50'); ?>
								</div>
							</div>
						</div>
					</div>
					
					<?php
					// Start function
					function shorter($text, $chars_limit)
					{
						// Check if length is larger than the character limit
						if (strlen($text) > $chars_limit)
						{
							// If so, cut the string at the character limit
							$new_text = substr($text, 0, $chars_limit);
							// Trim off white space
							$new_text = trim($new_text);
							// Add at end of text ...
							return $new_text . "...";
						}
						// If not just return the text as is
						else
						{
						return $text;
						}
					}
					?>
					
					<?php /* ?>
					<div class="col-md-6">
						<!-- start: PROGRESS BARS PANEL -->
						<div class="panel panel-default">
							<div class="panel-body" style="min-height: 600px;">
								<?php /* ?>
								<span style="color: #007AFF;"><?php  echo (isset($note_sheet) && $note_sheet && $note_sheet['noteSheetType'] != 'green') ? 'Last Saved '.date('d-m-Y H:i:s', strtotime(current($note_sheet['history'])['updateDate'])) : ''; ?></span>
								<textarea name="note_sheet_content" id="note_sheet_content" class="ckeditor11 form-control" rows="5" style="background-color: <?php echo $note_color; ?>;"><?php echo (isset($note_sheet) && $note_sheet) ? $note_sheet['noteSheetContent'] : ''; ?></textarea>
								<input type="hidden" name="note_sheet_content_update" value="<?php echo (isset($note_sheet) && $note_sheet) ? $note_sheet['noteSheetContent'] : ''; ?>">
								<input type="hidden" name="note_sheet_version" id="note_sheet_version" value="<?php echo (isset($note_sheet) && $note_sheet) ? current($note_sheet['history'])['noteSheetVersion'] : 0; ?>">
								<?php */ ?>
								<?php
								//if(isset($note_sheet) && $note_sheet['noteSheetType'] == 'green') {
								/*
									?>
									<div class="fileUpload btn btn-primary top_btn">
										<span>Attach</span>
										<input type="file" class="upload" name="note_sheet_file[]" id="note_sheet_file" />
									</div>
									
									<table id="uploaded-files-table" class="table table-striped table-bordered table-hover" id="" style="font-size:11px;">
										<tbody>
											<?php
											if($attachment_files) {
												foreach($attachment_files as $key =>  $get_file) {
													?>
													<tr>
														<td style="width: 7%;"><a href="<?php echo base_url(); ?>admin/files/delete_note_sheet_file/<?php echo $file_id.'/'.$note_type.'/'.$note_sheet['noteSheetId'].'/'.$get_file['fileUploadedId']; ?>" class="btn btn-xs btn-primary text-center" title=""><i class="fa clip-remove"></i></a></td>
														<td><a data-fancybox data-type="iframe" data-src="<?php echo base_url().$get_file['filePath'] ?>" href="javascript:;"><?php echo $get_file['fileName'] ?></a></td>
													</tr>
													<?php
												}
											}
											?>
										</tbody>
									</table>
									<?php
								//}
								?>
							</div>
						</div>
						<!-- end: PROGRESS BARS PANEL -->
					</div>
					<?php */ ?>
					<div class="col-md-12">
						<!-- start: NOTIFICATION PANEL -->
						<div class="panel panel-default">
							
								<div class="panel-heading">
								<h4 class="panel-title" style="font-size: 13px; font-weight: bold;">
									List of Correspondences and Issues
								</h4>
								</div>
								<div class="panel-body">
									<table id="toc_table" class="table table-striped table-bordered table-hover" id="" style="font-size:12px;">
										<thead>
											<tr bgcolor="ebedfb" style="color:#000;" >
												<th class="hidden-xs" style="text-align: center;"></th>
												<th class="hidden-xs" style="width: 20%;">Receipt/Issue No</th>
												<th class="hidden-xs">Subject</th>
												<th class="hidden-xs">Category</th>
												<th class="hidden-xs">Put up Date</th>
												<th class="hidden-xs">Note Sheet</th>
												<th class="hidden-xs">Action</th>
											</tr>
										</thead>
										<tbody>
											<?php
											if($receipt_detail) {
												foreach($receipt_detail as $key =>  $get_receipt_detail) {
													?>
													<tr>
														<?php $checked = ($get_receipt_detail['receiptDetailId'] == $files_detail['pob_id']) ? 'checked="checked"' : ''; ?>
														<td><div class="checkbox-table"><label><input <?php echo $checked; ?> type="radio" name="check" class="checked_box_receipt" value="<?php echo $get_receipt_detail['receiptDetailId'] ?>" id="<?php echo $get_receipt_detail['receiptDetailId']; ?>"></label></div></td>
														<td><?php echo $get_receipt_detail['receiptNo'] ?></td>
														<td><?php echo $get_receipt_detail['subject'] ?></td>
														<td><?php echo $get_receipt_detail['categoryName'] ?></td>
														<td><?php echo date("d-m-Y H-i" , strtotime($get_receipt_detail['attachedDate'])) ?></td>
														<td>
														<?php
														if($get_receipt_detail['attachment']) {
															foreach($get_receipt_detail['attachment'] as $get_attachment) {
																echo '<a href="'.base_url().$get_attachment['filePath'].'">Note Sheet</a>';
															}
														}
														else {
															$attributes = array('class' => 'form-horizontal upload-'.$get_receipt_detail['receiptDetailId'], 'role' => '', 'id' => '');
															echo form_open_multipart(base_url().'admin/files/upload_attachment/'.$get_receipt_detail['receiptDetailId'], $attributes);
																?>
																<input type="hidden" name="file_id" value="<?php echo $files_detail['fileId']; ?>">
																<div class="fileUpload btn btn-primary top_btn">
																	<span>Upload</span>
																	<input type="file" class="upload note_sheet_file" name="note_sheet_file[]" id="<?php echo $get_receipt_detail['receiptDetailId']; ?>" />
																</div>
																<?php
															echo form_close();
														}
														?>	
														</td>
														<td>
															<a target="_blank" href="<?php echo base_url(); ?>admin/receipts/receipt_view/sent/<?php echo $get_receipt_detail['receiptDetailId']; ?>">View</a>
														</td>
													</tr>
													<?php
												}
											}
											?>
										</tbody>
									</table>
									
									<div class="col-md-12 text-center">
										<div class="receipt">
											<a data-toggle="modal" id="modal_ajax_demo_btn" class="demo btn btn-primary top_btn">Attach</a>
										</div>
									</div>
								</div>	
								<div class="panel-heading">
									<h4 class="panel-title" style="font-size: 13px; font-weight: bold;">
										Automation Doc
									</h4>
								</div>
								<div class="panel-body">
									<table id="toc_table" class="table table-striped table-bordered table-hover" id="" style="font-size:12px;">
										<thead>
											<tr bgcolor="ebedfb" style="color:#000;" >
												<th class="hidden-xs" style="width: 20%;">File Name</th>
												<th class="hidden-xs">Type</th>
												<th class="hidden-xs">Created Date</th>
											</tr>
										</thead>
										<!--<div class="col-md-12 text-center" style="padding: 2px 0px 10px;">Automation Doc</div>-->
									
										<tbody>
									<?php if(isset($releted_files) && $releted_files) { ?>
									
										
										
												<?php
												foreach($releted_files as $key =>  $get_releted_files) {
													?>
													<tr>	
														<td><a target="_blank" href="<?php echo base_url().$get_releted_files['filePath']; ?>"><?php echo $get_releted_files['OriginalFileName'] ?></a></td>
														<td><?php echo $get_releted_files['fileType'] ?></td>
														<td><?php echo $get_releted_files['createdDate'] ?></td>
													</tr>
													<?php
												}
												?>
											
										
									
									<?php } ?>
										</tbody>
									</table>
								</div>
						
						</div>
						<!-- end: NOTIFICATION PANEL -->
					</div>
					
				</div>
				
			</div>
		<?php //echo form_close(); ?>
		</div>
		<!-- end: PAGE --> 
	</div>
</div>
<!-- end: MAIN CONTAINER -->


<div id="receipt-modal" class="modal fade" tabindex="-1" style="display: none;">
	<?php
	$attributes = array('class' => 'form-horizontal', 'role' => '', 'id' => 'note_sheet_form');
	echo form_open_multipart(current_url(), $attributes);
	?>
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3>Receipt</h3>
		</div>
		<div class="modal-body">
			<table id="receipt_table" class="table table-striped table-bordered table-hover" id="" style="font-size:12px;">
				<thead>
					<tr bgcolor="ebedfb" style="color:#000;">
						<th></th>
						<th>Receipt Number</th>
						<th>Subject</th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
			
			<div class="text-center">
				<button type="submit" class="btn btn-primary">Attach</button>
			</div>
		</div>
		<input type="hidden" name="file_number" value="<?php echo $files_detail['fileNumber']; ?>">
		<input type="hidden" name="attach_receipt_file" value="true">
	<?php echo form_close(); ?>
</div>

<?php
$attributes = array('class' => '', 'role' => '', 'id' => 'note_sheet_send_form');
echo form_open_multipart(base_url().'admin/files/note_sheet_send/'.$file_id, $attributes);
	echo '<input type="hidden" name="attach_receipt_id" id="attach_receipt_id" value="">';
echo form_close();
?>


<!-- statr: INCLUSE FOOTER -->
<?php $this->load->view('admin/includes/footer'); ?>
<!-- end: INCLUSE FOOTER -->

<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/ckeditor/contents.css">
<link href="<?php echo $includes_dir; ?>admin/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $includes_dir; ?>admin/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/fancybox-master/dist/jquery.fancybox.min.css">
<!-- end: CSS REQUIRED FOR THIS PAGE ONLY --> 

<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
<script src="//cdn.ckeditor.com/4.6.2/full-all/ckeditor.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/ckeditor/adapters/jquery.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/bootstrap-modal/js/bootstrap-modal.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/bootstrap-modal/js/bootstrap-modalmanager.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
<?php /* ?><script src="<?php echo $includes_dir; ?>admin/js/ui-modals.js"></script><?php */ ?>
<script src="<?php echo $includes_dir; ?>admin/plugins/fancybox-master/dist/jquery.fancybox.min.js"></script>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 

<script>
	
	jQuery(document).ready(function () {
		Main.init();
		//UIModals.init();
		
		/*CKEDITOR.replace('note_sheet_content');
		
		CKEDITOR.on('instanceReady', function(e) { 
			e.editor.document.getBody().setStyle('background-color', '<?php echo $note_color; ?>');
		});*/
	});
	
	
	var data_table = '';
	var $modal = $('#receipt-modal');
	$('.receipt .demo').on('click', function () {
	
		if(data_table != '') {
			data_table.destroy();
		}
		
		// create the backdrop and wait for next modal to be triggered
		//$('body').modalmanager('loading');
		/*setTimeout(function () {
			$modal.load('<?php echo base_url(); ?>admin/files/get_receipt_attach', '', function () {
				$modal.modal();
				datatabledraw();
			});
		}, 1000);*/
		$modal.modal();
		datatabledraw();
		//data_table.destroy();
		
	});
	
	
	$('.note_sheet_send').click(function() {
		//var form_action = '<?php echo base_url(); ?>admin/files/note_sheet_send/<?php echo $file_id.'/'; echo (isset($note_sheet) && $note_sheet) ? $note_sheet['noteSheetId'] : ''; ?>'
		//$('#note_sheet_form').attr('action', form_action);
		$('#note_sheet_send_form').submit();
	})
	
	
	$('.checked_box_receipt').click(function() {
		var receipt_id = $(this).attr('id');
		$('#attach_receipt_id').val(receipt_id);
		//alert(receipt_id);
	})
	
	
	/*$('#note_sheet_file').change(function() {
		var form_action = '<?php echo base_url(); ?>admin/files/upload_attachment/<?php echo $file_id.'/'; echo (isset($note_sheet) && $note_sheet) ? $note_sheet['noteSheetId'] : ''; ?>'
		$('#note_sheet_form').attr('action', form_action);
		$('#note_sheet_form').submit();
	})*/
	
	$('.note_sheet_file').change(function() {
		var receipt_num = $(this).attr('id');
		//alert(receipt_num);
		//var form_action = '<?php echo base_url(); ?>admin/files/upload_attachment/<?php echo $file_id.'/'; echo (isset($note_sheet) && $note_sheet) ? $note_sheet['noteSheetId'] : ''; ?>'
		//$('#note_sheet_form').attr('action', form_action);
		$('.upload-'+receipt_num).submit();
	})	
	
	function datatabledraw() { //alert('OK');
	//$(function () {
		data_table = $('#receipt_table').DataTable({
			"processing": true,
			"serverSide": true,
			"searching": false,
			"autoWidth": false,
			"lengthChange": false,
			"ajax": {
				"url": "<?php echo $base_url; ?>admin/files/get_receipt_ajax",
				"type": "POST",
				"data": function ( d ) {
					var top_search_like = {};
					
					var top_search = {
							rd_file_number: '0',
							rd_receipt_Existance_id: '<?php echo $this->flexi_auth->get_user_id(); ?>',
						};
					
					d.top_search_like = top_search_like;
					d.top_search = top_search;
				}
			},
			"order": [[ 1, "desc" ]],
			"columnDefs": [
				{ "orderable": false, "targets": 0, "width": "5%"},
				{ "orderable": true, "targets": 1, "width": "10%"},
				{ "orderable": true, "targets": 2, "width": "20%"},
			],
			"columns": [
				null,
				null,
				null,
			],
			fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {   
			},
			"pageLength": 20,
			"lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
			"initComplete": function(settings, json) {
			}
		}).on( 'draw', function () {
			if($(".tooltips").length) {$('.tooltips').tooltip();}
		});
	//});
	}
	
</script>

<style>
	
	.panel-heading{ padding-left:40px !important; background-image:none; border-radius:0; box-shadow:none; }
	.panel-body {padding: 5px; margin-top:5px; }
	.panel-default>.panel-heading{color: #fff;font-weight: bold;background:#282828;}

	#toc_table th, #toc_table td{ padding: 5px; border: 1px solid #B1B1B1; }
	#receipt_table th, #receipt_table td { padding: 5px; }
	
	.fileUpload {
    position: relative;
    overflow: hidden;
    margin: 10px;
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
	
	
	.form-group { margin:3px !important;background: #f6f6f6;padding: 2px 4px;}
	select.form-control, input.form-control { height:28px !important; padding-left:0;border: solid 1px #8e8e8e;font-size: 12px;padding-left: 5px;color: #000;}
	.form-horizontal .control-label {padding: 5px 0!important; margin:0 !important;color:#000; font-weight: bold !important;font-size: 12px;text-align: left;}
	select.form-control { font-size: 12px;color:#000; font-weight:normal !important;}
	.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control{font-size: 12px;padding-left: 5px;}
	 .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10,{ padding-left:5px; padding-right:5px;}
	.has-error .form-control{border-color: red;}
	.has-error .help-block, .has-error .control-label, .has-error .radio, .has-error .checkbox, .has-error .radio-inline, .has-error .checkbox-inline{color: red; font-size:12px;}
	div.dataTables_paginate{ font-size:12px;}
	div.dataTables_info{ font-size:12px;}
	div.dataTables_length{ display:none;}
	
	.label_detail{
	padding: 5px 0!important;
    margin: 0 !important;
    color: #000;
    font-size: 12px;}
	
	/*
	.form-horizontal .control-label { padding-top:0 !important;}
	.form-group { margin-bottom:5px !important;}
	select.form-control, input.form-control { height:25px !important;}
	.form-horizontal .control-label { padding:0 !important; margin:0 !important;}
	select.form-control { font-size: 11px;}*/
	/*.top_btn{background: #428bca;color: #fffefe;border-radius: 0;font-size: 12px;border: solid 1px #2e5d86;}*/

</style>
