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
			<!--<button type="button" id="edit_receipt" action-form="edit_receipt" class="btn btn-primary top_btn">
				Edit Receipt
			</button>-->
			
			<div class="row">
				
				<div class="col-md-12">
					<div class="form-group">
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
					<div style="margin-bottom:10px;"></div>
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
					<div class="col-md-12">
					<div class="col-md-6">
					<button type="submit" class="btn btn-primary top_btn">
					Save
					</button>
					<div style="margin-bottom:10px;"></div>
					</div>
					<div class="col-md-6">
					
					<a href="<?php echo $base_url; ?>admin/receipts/browse_diarise/<?php echo $receipt['receiptDetailId']; ?>" links="<?php echo $base_url; ?>admin/receipts/browse_diarise/<?php echo $receipt['receiptDetailId']; ?>" class="btn btn-primary top_btn">
					ToC
					</a>
					
					<a href="<?php echo $base_url; ?>admin/receipts/receipt_send/<?php echo $receipt['receiptDetailId']; ?>" links="<?php echo $base_url; ?>admin/receipts/browse_diarise/<?php echo $receipt['receiptDetailId']; ?>" class="btn btn-primary top_btn">
					Recent
					</a>
					
					<a href="<?php echo $base_url; ?>admin/receipts/receipt_send/<?php echo $receipt['receiptDetailId']; ?>" links="<?php echo $base_url; ?>admin/receipts/browse_diarise/<?php echo $receipt['receiptDetailId']; ?>" class="btn btn-primary top_btn">
					All
					</a>
					<div style="margin-bottom:10px;"></div>
					</div>
					</div>
				<?php */ ?>
				
				<div class="col-md-6">
					<!-- start: PROGRESS BARS PANEL -->
					<div class="panel panel-default">
						<div class="panel-body" style="background-color: <?php echo $note_color; ?>; min-height: 600px;">
							<div style="text-align: center; padding: 4px 0;"><?php  echo (isset($note_sheet) && $note_sheet) ? 'Version '.current($note_sheet['history'])['noteSheetVersion'] : ''; ?></div>
							<div style="background-color: <?php echo $note_color; ?>; border-top: 1px solid #d5d69e; padding-top: 10px;"><?php echo (isset($note_sheet) && $note_sheet) ? $note_sheet['noteSheetContent'] : ''; ?></div>
							<input type="hidden" name="note_sheet_version" id="note_sheet_version" value="<?php echo (isset($note_sheet) && $note_sheet) ? current($note_sheet['history'])['noteSheetVersion'] : 0; ?>">
							
							<table id="files_table" class="table table-striped table-bordered table-hover" id="" style="font-size:11px;">
								<thead>
									<tr class="note-sheet-btn">
										<td>
											<a href="<?php echo $base_url; ?>admin/files/note_sheet/<?php echo $file_id.'/'.$note_type.'/'.$note_sheet['noteSheetId']; ?>" class="btn btn-primary top_btn">Edit</a>
										</td>
										<td>
											<a href="<?php echo $base_url; ?>admin/files/note_sheet_discard/<?php echo $file_id; ?>/<?php echo $note_sheet['noteSheetId']; ?>/<?php echo ($files_detail['fileStatus'] == 'created') ? 'correspondence' : 'note_sheet_detail'; ?>" class="btn btn-primary top_btn">Discard</a>
										</td>
										<td>
											<?php /* ?><a href="<?php echo $base_url; ?>admin/files/note_sheet_confirm/<?php echo $file_id; ?>/green/<?php echo $note_sheet['noteSheetId']; ?>" class="btn btn-primary top_btn">Confirm</a><?php */ ?>
											<a href="#confirm_box" data-toggle="modal" class="btn btn-primary top_btn">Confirm</a>
										</td>
									</tr>
									<tr bgcolor="ebedfb" style="color:#000;" >
										<th class="hidden-xs">Version</th>
										<th class="hidden-xs">Created On</th>
										<th class="hidden-xs">Created By</th>
									</tr>
								</thead>
								<tbody>
									<?php
										if($note_sheet['history']) {
											foreach($note_sheet['history'] as $key =>  $get_history) {
											?>
											<tr>
												<td><?php echo $get_history['noteSheetVersion'] ?></td>
												<td><?php echo date("d-m-Y H-i" , strtotime($get_history['updateDate'])) ?></td>
												<td><?php echo $get_history['upro_first_name'].' '.$get_history['upro_first_name'].(($get_history['designationName']) ? ' ('.$get_history['designationName'].')' : '') ?></td>
											</tr>
											<?php
											}
										}
									?>
								</tbody>
							</table>
						</div>
					</div>
					<!-- end: PROGRESS BARS PANEL -->
				</div>
				<div class="col-md-6">
					<!-- start: NOTIFICATION PANEL -->
					<div class="panel panel-default" style="">
						<div class="panel-body" style="min-height: 600px">
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
													<td><div class="checkbox-table"><label><input type="checkbox" disabled name="receipt_id[]" class="grey checked_box" value="<?php echo $get_receipt_detail['receiptDetailId'] ?>"></label></div></td>
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
								
								<?php /* ?>
									<div class="col-md-12 text-center">
									<div class="receipt">
									<a data-toggle="modal" id="modal_ajax_demo_btn" class="demo btn btn-primary top_btn">Attach</a>
									</div>
									</div>
								<?php */ ?>
								
								<?php
								}
								else {
								?>
								<embed id="pdf_preview" src="<?php echo base_url(); ?>/includes/admin/pdf/sample.pdf#page=1&toolbar=1&navpanes=1&scrollbar=0&statusbar=0&pagemode=none&viewrect=0,0" type="application/pdf" width="100%" height="570px">
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

<div id="confirm_box" class="modal fade" tabindex="-1" data-width="400" style="display: none;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			&times;
		</button>
		<h4 class="modal-title">Confirm Yellow Note</h4>
	</div>
	<div class="modal-body">
		<div class="row">
			<div class="col-md-12">
				Are you sure to confirm this yellow note to green note ?
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn btn-light-grey">
			Cancel
		</button>
		<a href="<?php echo $base_url; ?>admin/files/note_sheet_confirm/<?php echo $file_id; ?>/green/<?php echo $note_sheet['noteSheetId']; ?>" class="btn btn-blue">OK</a>
	</div>
</div>
<!-- statr: INCLUSE FOOTER -->
<?php $this->load->view('admin/includes/footer'); ?>
<!-- end: INCLUSE FOOTER -->

<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
<link href="<?php echo $includes_dir; ?>admin/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $includes_dir; ?>admin/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
<!-- end: CSS REQUIRED FOR THIS PAGE ONLY --> 

<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
<script src="<?php echo $includes_dir; ?>admin/plugins/bootstrap-modal/js/bootstrap-modal.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/bootstrap-modal/js/bootstrap-modalmanager.js"></script>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 

<script>
	
	jQuery(document).ready(function () {
		Main.init();
	});
	
</script>

<style>
	#files_table th { color: #007AFF; }
	#files_table th, #files_table td { padding: 4px; }
	#files_table { border: none; }
	.note-sheet-btn td { border: none !important; }
	.note-sheet-btn a { padding: 2px 10px; background-color: #efeeee; border: 1px solid #cccccc; color: #000; }
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
