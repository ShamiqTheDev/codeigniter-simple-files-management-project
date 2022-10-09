<?php
$hide = "";

if(isset($receipt['fileNumber']) && !empty($receipt['fileNumber']))
{
	$hide = "style = 'display:none;' ";
}
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
			<!--<button type="button" id="edit_receipt" action-form="edit_receipt" class="receipt_submit btn btn-primary top_btn">
				Edit Receipt
			</button>-->
			
			<div class="row">
				
				<div class="col-md-12">
					<div class="form-group">
						<div style="margin-top:3px;"></div>
						<div class="form-validation">
							<label class="col-sm-2 control-label" style="width: 4%;">
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
				
				
				<div class="col-md-12">
					<div class="col-md-6">
						<a href="<?php echo $base_url; ?>admin/files/create/<?php echo $file_id; ?>" links="<?php echo $base_url; ?>admin/files/create/<?php echo $file_id; ?>" class="edit_file btn btn-primary top_btn">
							Edit
						</a>
						<?php if($note_sheet && $note_sheet['green_note_active']) { ?>
							<button type="button" class="note_sheet_send btn btn-primary top_btn" style="<?php echo $style; ?>">
								Send
							</button>
						<?php } ?>
						<div style="margin-bottom:10px;"></div>
					</div>
					<div class="col-md-6">
						<?php /* ?>
						<a href="<?php echo $base_url; ?>admin/receipts/browse_diarise/<?php echo $receipt['receiptDetailId']; ?>" links="<?php echo $base_url; ?>admin/receipts/browse_diarise/<?php echo $receipt['receiptDetailId']; ?>" class="receipt_submit btn btn-primary top_btn">
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
				
				<div class="col-md-6">
					<!-- start: PROGRESS BARS PANEL -->
					<div class="panel panel-default">
						<div class="panel-heading" style="background-color: #e3e8db;">
							<a href="<?php echo base_url(); ?>admin/files/note_sheet/<?php echo $file_id; ?>/green" style="color: #000; color: #000; border: 1px solid #b6c4a9; background-color: #e5f3d6; padding: 4px; font-weight: normal;">Add Green Note</a>
							<a href="<?php echo base_url(); ?>admin/files/note_sheet/<?php echo $file_id; ?>/yellow"  style="color: #000; color: #000; border: 1px solid #b6c4a9; background-color: #e5f3d6; padding: 4px; font-weight: normal;">Add yellow Note</a>
						</div>
						<div class="panel-body" style="background-color: #d8ffce; min-height: 600px;">
							<div class="panel-body" style="background-color: #d4f8ca; min-height: 600px;">
								<?php
								if($note_sheet) {
									//echo '<pre>'; print_r($note_sheet); die();
								
									if(isset($note_sheet['green_note_active']))
										unset($note_sheet['green_note_active']);
								
									$i = 1;
									foreach($note_sheet as $key => $get_note_sheet){									
										?>
										<div class="col-md-10" style="padding: 0;">
											<h4 style='color:#008000;'><u>Note # "<?php echo $i; ?>"</u></h4>
										</div>
										<div class="col-md-2" style="padding: 13px 0 0 0; text-align: right; font-weight: bold;">
											<?php if($get_note_sheet['noteSheetStatus'] == 'created') { ?>
												<a href="<?php echo base_url(); ?>admin/files/note_sheet/<?php echo $file_id.'/'.$get_note_sheet['noteSheetType'].'/'.$get_note_sheet['noteSheetId']; ?>" style="color: #007AFF; font-size: 14px;">Edit</a>
											<?php } ?>
										</div>
										<div style="clear: both;"><?php echo $get_note_sheet['noteSheetContent']; ?></div>
										
										<?php									
										if($get_note_sheet['files']) {
											?>
											<table id="uploaded-files-table" class="table table-striped table-bordered table-hover" id="" style="font-size:11px;">
												<tbody>
													<?php
													foreach($get_note_sheet['files'] as $key =>  $get_file) {
														?>
														<tr>
															<td><a data-fancybox data-type="iframe" data-src="<?php echo base_url().$get_file['filePath'] ?>" href="javascript:;"><?php echo $get_file['fileName'] ?></a></td>
														</tr>
														<?php
													}
													?>
												</tbody>
											</table>
											<?php
										}
										
										if(count($note_sheet) > $i)
											echo '<hr / style="border-top: 1px solid #3071a9;">';
										
										//echo "</br></br>";
										$i++;
									}
								}
								?>
							</div>
						</div>
					</div>
					<!-- end: PROGRESS BARS PANEL -->
				</div>
				<div class="col-md-6">
					<!-- start: NOTIFICATION PANEL -->
					<div class="panel panel-default" style="">
						
						<div class="panel-body" style="background-color: #e5e5e4; min-height: 635px">
							<?php
							//if($table_content == 'toc') {
							if(TRUE) {
								?>
								<div class="col-md-12 text-center" style="padding: 2px 0px 10px;">List of Correspondences and Issues</div>
								<table id="toc_table" class="table table-striped table-bordered table-hover" id="" style="font-size:11px;">
									<thead>
										<tr bgcolor="ebedfb" style="color:#000;" >
											<th class="hidden-xs" style="text-align: center;"><div class="checkbox-table"><label><input type="checkbox" disabled name="" class="grey checked_box" value=""></label></div></th>
											<th class="hidden-xs" style="width: 20%;">Receipt/Issue No</th>
											<th class="hidden-xs">Subject</th>
											<th class="hidden-xs">Type</th>
											<th class="hidden-xs">Attached On</th>
											<th class="hidden-xs">Pages</th>
											<th class="hidden-xs">Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if($receipt_detail) {
											foreach($receipt_detail as $key =>  $get_receipt_detail) {
												?>
												<tr>
													<td><div class="checkbox-table"><label><input type="checkbox" name="receipt_id[]" disabled class="grey checked_box" value="<?php echo $get_receipt_detail['receiptDetailId'] ?>"></label></div></td>
													<td><?php echo $get_receipt_detail['receiptNo'] ?></td>
													<td><?php echo $get_receipt_detail['subject'] ?></td>
													<td><?php echo $get_receipt_detail['documentType'] ?></td>
													<td><?php echo date("d-m-Y H-i" , strtotime($get_receipt_detail['attachedDate'])) ?></td>
													<td></td>
													<td></td>
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
								
								<?php
							}
							else {
								?>
								<embed id="pdf_preview" src="<?php echo base_url(); ?>/includes/admin/pdf/sample.pdf#page=1&toolbar=1&navpanes=1&scrollbar=0&statusbar=0&pagemode=none&viewrect=0,0" type="application/pdf" width="100%" height="600px">
								<?php
							}
							?>
						</div>
					</div>
					<!-- end: NOTIFICATION PANEL -->
				</div>
			</div>
			
		</div>
		<?php echo form_close(); ?>
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
			<table id="receipt_table" class="table table-striped table-bordered table-hover" id="" style="font-size:11px;">
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



<!-- statr: INCLUSE FOOTER -->
<?php $this->load->view('admin/includes/footer'); ?>
<!-- end: INCLUSE FOOTER --> 

<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
<link href="<?php echo $includes_dir; ?>admin/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $includes_dir; ?>admin/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/fancybox-master/dist/jquery.fancybox.min.css">
<!-- end: CSS REQUIRED FOR THIS PAGE ONLY --> 

<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
<script src="<?php echo $includes_dir; ?>admin/plugins/bootstrap-modal/js/bootstrap-modal.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/bootstrap-modal/js/bootstrap-modalmanager.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/fancybox-master/dist/jquery.fancybox.min.js"></script>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 

<script>
	
	jQuery(document).ready(function () {
		Main.init();
	});
	
	$('#movement_table , #files_table').DataTable({
		
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
							//rd_file_number: '0',
							//rs_is_responded: '0',
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
	
	$('.note_sheet_send').click(function() {
		var form_action = '<?php echo base_url(); ?>admin/files/note_sheet_send/<?php echo $file_id.'/'; echo (isset($note_sheet) && $note_sheet) ? $note_sheet['noteSheetId'] : ''; ?>'
		$('#note_sheet_form').attr('action', form_action);
		$('#note_sheet_form').submit();
	})
</script>

<style>
.panel-heading{ padding-left:10px !important; background-image:none; border-radius:0; box-shadow:none; }
	.form-group { margin:3px !important;background: #f6f6f6;padding: 2px 4px;}
	select.form-control, input.form-control { height:28px !important; padding-left:0;border: solid 1px #8e8e8e;font-size: 11px;padding-left: 5px;color: #000;}
	.form-horizontal .control-label {padding: 5px 0!important; margin:0 !important;color:#000; font-weight: bold !important;font-size: 11px;text-align: left;}
	select.form-control { font-size: 11px;color:#000; font-weight:normal !important;}
	.panel-body {padding: 5px;}
	.panel-default>.panel-heading{color: #fff;font-weight: bold;background:#282828;}
	.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control{font-size: 12px;padding-left: 5px;}
	 .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10,{ padding-left:5px; padding-right:5px;}
	.has-error .form-control{border-color: red;}
	.has-error .help-block, .has-error .control-label, .has-error .radio, .has-error .checkbox, .has-error .radio-inline, .has-error .checkbox-inline{color: red; font-size:12px;}
	div.dataTables_paginate{ font-size:11px;}
	div.dataTables_info{ font-size:11px;}
	div.dataTables_length{ display:none;}
	
	.label_detail{
	padding: 5px 0!important;
    margin: 0 !important;
    color: #000;
    font-size: 11px;}
	
	/*
	.form-horizontal .control-label { padding-top:0 !important;}
	.form-group { margin-bottom:5px !important;}
	select.form-control, input.form-control { height:25px !important;}
	.form-horizontal .control-label { padding:0 !important; margin:0 !important;}
	select.form-control { font-size: 11px;}*/
	.top_btn{background: #428bca;color: #fffefe;border-radius: 0;font-size: 12px;border: solid 1px #2e5d86;}
</style>
