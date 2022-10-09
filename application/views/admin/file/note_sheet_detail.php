<?php
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
				<?php if($file['fileStatus']=='created'){ ?>
				
					<a href="#" class="receipt_submit btn btn-primary top_btn">
						Edit File
					</a>
				<?php } ?>
				<?php if($file['fileStatus']=='created' || $send_inbox_button == 'inbox'){ ?>
					<a href="<?php echo $base_url; ?>admin/files/note_sheet_send/<?php echo $file_id; ?>/<?php echo $note_sheet_id; ?>" links="<?php echo $base_url; ?>admin/files/note_sheet_send/<?php echo $file_id; ?>/<?php echo $note_sheet_id; ?>" class="file_submit btn btn-primary top_btn">
						Send
					</a>
					
				<?php } ?>
				<?php if($file['fileExistenceId'] == $file['createdBy'] ){ ?>
					<a href="<?php echo $base_url; ?>admin/files/note_sheet/<?php echo $file_id; ?>/<?php echo $note_sheet_id; ?>" links="<?php echo $base_url; ?>admin/files/note_sheet/<?php echo $file_id; ?>/<?php echo $note_sheet_id; ?>" class="file_submit btn btn-primary top_btn">
						Correspondence
					</a>
					<div style="margin-bottom:10px;"></div>
				<?php } ?>
				</div>
				
				<div class="col-md-6">
					<!-- start: PROGRESS BARS PANEL -->
					<div class="panel panel-default">
						<div class="panel-heading">File Details</div>
							<div class="panel-body details-info">
								<div class="form-group">
									<div class="form-validation">
										<label class="col-sm-2 control-label">
											File No:
										</label>
										<div class="col-sm-4 file-detail">										
											<?php echo $file['fileNumber']; ?>
										</div>
									</div>
									<div class="form-validation">
										<label class="col-sm-2 control-label">
											Old File No:
										</label>
										<div class="col-sm-4 file-detail">										
											<?php echo $file['oldFileNumber']; ?>
										</div>
									</div>
								</div>
								
								<div class="form-group">
									<div class="form-validation">
										<label class="col-sm-2 control-label">
											File Type:
										</label>
										<div class="col-sm-4 file-detail">
											<?php //echo $file['fileTypeId']; ?>
											<?php echo $file_type_name; ?>
											
										</div>
									</div>
									<div class="form-validation">
										<label class="col-sm-2 control-label">
											CNIC:
										</label>
										<div class="col-sm-4 file-detail">
											<?php echo $file['employeeCNIC']; ?>
										</div>
									</div>
								</div>
								
								<div class="form-group">
									<div class="form-validation">
										<label class="col-sm-2 control-label">
											Employee Name:
										</label>
										<div class="col-sm-4 file-detail">
											<?php echo $file['employeeName']; ?>
										</div>
									</div>
									<div class="form-validation">
										<label class="col-sm-2 control-label">
											Subject:
										</label>
										<div class="col-sm-4 file-detail">
											<?php echo $file['description']; ?>
										</div>
									</div>
								</div>
								
								<div class="form-group">
									<div class="form-validation">
										<label class="col-sm-2 control-label">
											Category:
										</label>
										<div class="col-sm-4 file-detail">
											<?php //echo $file['generalCategoryId']; ?>
											<?php echo $category_name; ?>
										</div>
									</div>
								</div>
							</div>
						<?php if($uri_3 == 'receipt_put_file'){ ?> 
						<?php
							$attributes = array('class' => 'form-horizontal', 'role' => '', 'id' => 'fileUploadingForm');
							echo form_open_multipart(current_url(), $attributes);
						?>
						<div class="panel-heading">Put In File</div>
						<div class="panel-body">
							<table id="files_table" class="table table-striped table-bordered table-hover" id="" style="font-size:12px;">
                                <thead>
                                    <tr bgcolor="ebedfb" style="color:#000;" >
                                        <th class="hidden-xs"></th>
                                        <th class="hidden-xs">File Number</th>
                                        <th class="hidden-xs">Description</th>
                                        <th class="hidden-xs">Created Date</th>
                                    </tr>
								</thead>
                                <tbody>
								<?php if($filesInfo) { foreach($filesInfo as $key =>  $val){
										if(isset($receipt['fileNumber']) && !empty($receipt['fileNumber']))
										{
											if($receipt['fileNumber'] != $val['fileNumber'])
											{
												//continue;
											}
										}
								?>
                                <tr>
									<td><input type="radio" name="file_no" id="file_no" value="<?php echo $val['fileNumber'] ?>" <?php echo $receipt['fileNumber'] == $val['fileNumber'] ? "checked" : "" ?>></td>
									<td><?php echo $val['fileNumber'] ?></td>
									<td><?php echo $val['description'] ?></td>
									<td><?php echo date("d M Y" , strtotime($val['createdDate'])) ?></td>
                                </tr>
								<?php }} ?>
							  </tbody>
							</table>
							<p class="text-right">
								<!-- Contextual button for informational alert messages -->
								<input type="hidden" value="<?php echo $receipt['receiptDetailId']; ?>" name="receipt_id"/>
								<button type="submit" name="attach" value="attach" class="btn btn-sm btn-success" <?php //echo $hide; ?> >
									Attach
								</button>
								
							</p>
						</div>
						<?php echo form_close(); ?>
						<?php } else {?>
						<div class="panel-heading">Movement Details</div>
						<div class="panel-body">
							<table id="movement_table" class="table table-bordered table-hover table-full-width dataTable no-footer" id="" style="font-size:12px;">
                                <thead>
                                    <tr bgcolor="ebedfb" style="color:#000;" >
                                        <th class="hidden-xs">Sent By</th>
                                        <th class="hidden-xs">Sent On</th>
                                        <th class="hidden-xs">Sent To</th>
                                        <th class="hidden-xs">Action</th>
                                        <th width="100px" class="hidden-xs">Remarks</th>
									</tr>
								</thead>
                                <tbody>
								<?php if($movementInfo){foreach($movementInfo as $key => $val){  ?>
                                <tr>
									<td><?php echo $val['by_full_name'] ?></td>
									<td><?php echo date("d M Y" , strtotime($val['createdDate'])) ?></td>
									<td><?php echo $val['to_full_name'] ?></td>
									<td><?php echo $val['sendAction']; ?></td>
									<td width="100px"><?php echo $val['remarks'] ?></td>
                                </tr>
								<?php }} ?>
                               </tbody>
							</table>
						</div>
						<?php } ?>
					</div>
					<!-- end: PROGRESS BARS PANEL -->
				</div>
				
				<div class="col-md-6">
					<!-- start: NOTIFICATION PANEL -->
					<div class="panel panel-default" style="">
						<?php /* ?>
						<div class="panel-heading" style="background-color: #e3e8db;">
							<a href="<?php echo base_url(); ?>admin/files/note_sheet/<?php echo $file_id; ?>/green" style="color: #000; color: #000; border: 1px solid #b6c4a9; background-color: #e5f3d6; padding: 4px; font-weight: normal;">Add Green Note</a>
							<a href="<?php echo base_url(); ?>admin/files/note_sheet/<?php echo $file_id; ?>/yellow"  style="color: #000; color: #000; border: 1px solid #b6c4a9; background-color: #e5f3d6; padding: 4px; font-weight: normal;">Add yellow Note</a>
						</div>
						<?php */ ?>
						<div class="panel-heading">
								<h4 class="panel-title" style="font-size: 13px; font-weight: bold;">
									List of Correspondences and Issues
								</h4>
								</div>
						<div class="panel-body">
							<?php /*
							if($note_sheet) {
								
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
										<table id="uploaded-files-table" class="table table-striped table-bordered table-hover" id="" style="font-size:12px;">
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
									
									?>
									
									<?php if($get_note_sheet['noteSheetStatus']) { ?>
										<div class="col-md-6" style="padding: 0; text-align: left;">
											<div class="signature_date"><?php echo date('d-m-Y h:i A', strtotime($get_note_sheet['createdDate'])); ?></div>
										</div>
										
										<div class="col-md-6" style="padding: 0; text-align: right;">
											<div class="signature_name"><?php echo $get_note_sheet['upro_first_name'].' '.$get_note_sheet['upro_last_name']; ?></div>
											<div class="signature_desg"><?php echo $get_note_sheet['designationName']; ?></div>
										</div>
									<?php } ?>
									
									<?php
									
									if(count($note_sheet) > $i)
										echo '<hr / style="border-top: 1px solid #3071a9;">';
									
									//echo "</br></br>";
									$i++;
								}
							} */
							?>
							
							<table id="toc_table" class="table table-striped table-bordered table-hover" id="" style="font-size:12px;">
								<thead>
									<tr bgcolor="ebedfb" style="color:#000;" >
										<th class="hidden-xs" style="width: 20%;">Receipt/Issue No</th>
										<th class="hidden-xs">Subject</th>
										<th class="hidden-xs">Category</th>
										<th class="hidden-xs">Put up Date</th>
										<th class="hidden-xs">Note Sheet</th>
										<th class="hidden-xs">Action</th>
										<th class="hidden-xs"></th>
									</tr>
								</thead>
								<tbody>
									<?php
									if($receipt_detail) {
										foreach($receipt_detail as $key =>  $get_receipt_detail) {
											?>
											<tr>
												<td><?php echo $get_receipt_detail['receiptNo'] ?></td>
												<td><?php echo $get_receipt_detail['subject'] ?></td>
												<td><?php echo $get_receipt_detail['categoryName'] ?></td>
												<td><?php echo date("d-m-Y H-i" , strtotime($get_receipt_detail['attachedDate'])) ?></td>
												<td>
												<?php
												if($get_receipt_detail['attachment']) {
													foreach($get_receipt_detail['attachment'] as $get_attachment) {
														echo '<a target="_blank" href="'.base_url().$get_attachment['filePath'].'">Note Sheet</a>';
													}
												}
												?>	
												</td>
												<td>
													<a target="_blank" href="<?php echo base_url(); ?>admin/receipts/receipt_view/sent/<?php echo $get_receipt_detail['receiptDetailId']; ?>">View</a>
												</td>
												<td>
													<?php
													if($get_receipt_detail['receiptDetailId'] == $file['pob_id']) {
														echo '<strong>PUC</strong>';
													}
													?>
												</td>
											</tr>
											<?php
										}
									}
									?>
								</tbody>
							</table>
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
		<?php echo form_close(); ?>
		<!-- end: PAGE --> 
	</div>
</div>
<!-- end: MAIN CONTAINER --> 
<!-- statr: INCLUSE FOOTER -->
<?php $this->load->view('admin/includes/footer'); ?>
<!-- end: INCLUSE FOOTER --> 
<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/fancybox-master/dist/jquery.fancybox.min.css">
<!-- end: CSS REQUIRED FOR THIS PAGE ONLY --> 

<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/fancybox-master/dist/jquery.fancybox.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/fancybox-master/dist/jquery.fancybox.min.js"></script>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 

<script>
	
	jQuery(document).ready(function () {
		Main.init();
	});
	
	$('#movement_table').DataTable({
		"searching": false,
		"order": [[ 1, "desc" ]],
		"columns": [
			null,
			null,
			null,
			null,
			null,
		],
		"pageLength": 10,
	});
	
	$('#files_table').DataTable({			
		"order": [[ 1, "desc" ]],
		"columns": [
			{ "orderable": false, "targets": '0'},
			null,
			null,
			null,
		],
		"pageLength": 10,
	})
</script>

<style>
.panel-heading{ padding-left:10px !important; background-image:none; border-radius:0; box-shadow:none; }
	.form-group { margin:3px !important;background: #f6f6f6;padding: 2px 4px;}
	select.form-control, input.form-control { height:28px !important; padding-left:0;border: solid 1px #8e8e8e;font-size: 12px;padding-left: 5px;color: #000;}
	.form-horizontal .control-label {padding: 5px 0!important; margin:0 !important;color:#000; font-weight: bold !important;font-size: 12px;text-align: left;}
	select.form-control { font-size: 12px;color:#000; font-weight:normal !important;}
	.panel-body {padding: 5px;}
	.panel-default>.panel-heading{color: #fff;font-weight: bold;background:#282828; }
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
    font-size: 11px;}
	.file-detail {padding:4px 18px;font-size:12px; color:#000;font-weight: normal; }
	/*
	.form-horizontal .control-label { padding-top:0 !important;}
	.form-group { margin-bottom:5px !important;}
	select.form-control, input.form-control { height:25px !important;}
	.form-horizontal .control-label { padding:0 !important; margin:0 !important;}
	select.form-control { font-size: 11px;}*/
	/*.top_btn{background: #428bca;color: #fffefe;border-radius: 0;font-size: 12px;border: solid 1px #2e5d86;}*/
	.signature_name { font-size: 18px; text-decoration: underline; color: #428bca; }
	.signature_date { padding-top: 6px; }
</style>
