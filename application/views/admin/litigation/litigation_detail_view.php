<?php 
$petitioner = $data['petitioner']['memberFirstName'].' '.$data['petitioner']['memberLastName'];
$responder = $data['respondent']['memberFirstName'].' '.$data['respondent']['memberLastName'];

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
		<div class="container pdf-container"> 
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
                    <!-- start: TEXT FIELDS PANEL -->
                    <div class="panel panel-default">
						<div class="detail">
							<!--<h4><?php //echo $page_title; ?></h4>-->
							<div class="table-responsive">
								<table class="table table-bordered">
									<tr role="row">
	                                    <td bgcolor="#efefef"><strong>Case No: </strong></td>
	                                    <td width="33%"><?=$data['case']['caseNo']?></td>
										<td width="33%" bgcolor="#efefef" ><strong>Memo: </strong></td>
										<td><?=$data['case']['memo']?></td>
									</tr>
									<tr role="row">
	                                    <td bgcolor="#efefef"><strong> Ground: </strong></td>
	                                    <td width="33%"><?=$data['case']['ground']?></td>
										<td width="33%" bgcolor="#efefef" ><strong> Related To: </strong></td>
										<td><?=$data['case']['relatedTo']?></td>
									</tr>
									<tr role="row">
	                                    <td bgcolor="#efefef"><strong> Case Date: </strong></td>
	                                    <td width="33%"><?=date('d M Y', strtotime($data['case']['caseDate']))?></td>
										<td width="33%" bgcolor="#efefef" ><strong> Hearing Date: </strong></td>
										<td><?=date('d M Y', strtotime($data['case']['hearingDate']))?></td>
									</tr>
									<tr role="row">
	                                    <td bgcolor="#efefef"><strong>Petitioner: </strong></td>
	                                    <td width="33%"><?=$petitioner?></td>
										<td width="33%" bgcolor="#efefef" ><strong>Responder: </strong></td>
										<td><?=$responder?></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
                    <!-- end: TEXT FIELDS PANEL -->
				</div>
				
				<?php if($related_file) { ?>
					<div class="col-sm-12">
						<div class="panel panel-default" id="found-file-container">
							<div class="panel-heading"> <i class="fa fa-external-link-square"></i>Scheme Document</div>
							<div class="panel-body">
								<div id="" class="">
									<table role="" class="table table-striped" id="found_files">
										<tr>
											<th></th>
											<th>File Name</th>
											<!--<th>Document Type</th>-->
											<th>ADP Year</th>
											<th>ADP Number</th>
											<th>Document Date</th>
											<?php if($this->flexi_auth->is_privileged('Delete Scheme')) { ?>
												<th>Action</th>
											<?php } ?>
										</tr>
										<?php
										foreach($related_file as $get_related_file) {
											?>
											<tr>
												<td><span class="preview"><a href="<?php echo $base_url; ?>admin/scheme/scheme_detail_view/<?php echo $get_related_file['schemeId']; ?>" title="<?php echo $get_related_file['fileName']; ?>" data-gallery target="_blank"><img src="<?php echo $includes_dir; ?>admin/images/pdf-icon.png" style="height: 35px;"></a></span></td>
												<td>
													<p class="name">
														<a style="width: 100%; float: left; overflow: hidden;" href="<?php echo $base_url; ?>admin/scheme/scheme_detail_view/<?php echo $get_related_file['schemeId']; ?>" title="<?php echo $get_related_file['fileName']; ?>" target="_blank"><?php echo $get_related_file['documentType']; ?><?php echo ($get_related_file['documentTypeChild']) ? ' ('.$get_related_file['documentTypeChild'].')' : ''; ?></a>
														<span class="created-date"><?php echo 'Size: '.convertToReadableSize($get_related_file['fileSize']); ?></span> | 
														<span class="created-date"><?php echo 'Created Date: '.date('d-m-Y', strtotime($get_related_file['createdDate'])); ?></span>
													</p>
												</td>
												<!--<td><?php echo $get_related_file['documentType']; ?><?php echo ($get_related_file['documentTypeChild']) ? ' ('.$get_related_file['documentTypeChild'].')' : ''; ?></td>-->
												<td><?php echo $get_related_file['adpYear']; ?></td>
												<td><?php echo $get_related_file['adpNumber']; ?></td>
												<td><?php echo ($get_related_file['schemeDate'] == '0000-00-00') ? '' : date('d-m-Y', strtotime($get_related_file['schemeDate']));  ?></td>
												<?php if($this->flexi_auth->is_privileged('Delete Scheme')) { ?>
													<td><button delete_url="<?php echo base_url(); ?>admin/scheme/delete_scheme/<?php echo $get_related_file['fileUploadedId'].'/'.$get_related_file['schemeId'].'/'.$get_related_file['parentAdpNumber']; ?>" class="btn btn-xs btn-bricky text-center delete_scheme" title=""><i class="fa clip-remove"></i></button></td>
												<?php } ?>
											</tr>
											<?php
										}
										?>
									</table>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
				
			</div>
			<!-- end: PAGE CONTENT-->
			
		</div>
		<!-- end: PAGE --> 
	</div>
	
	<div id="delete_scheme_modal" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" style="display: none;">
		<div class="modal-body">
			<p>
				Would you like to delete scheme?
			</p>
		</div>
		<div class="modal-footer">
			<button type="button" data-dismiss="modal" class="btn btn-default">
				Cancel
			</button>
			<a href="" id="scheme_delete_action" type="button" class="btn btn-bricky">
				Delete
			</a>
		</div>
	</div>
	
	<!-- end: MAIN CONTAINER --> 
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
	<script src="<?php echo $includes_dir; ?>admin/js/ui-modals.js"></script>
	<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 
	
	<script>
		
		jQuery(document).ready(function () {
			Main.init();
			UIModals.init();
			$('.pdf-container').css('min-height', '');
			$('.pdf-container').css('min-height', '570px');
		});
		
		$('.delete_scheme').click(function() {
		
			var delete_url = $(this).attr('delete_url');
			$('#delete_scheme_modal').modal('show');
			$('#scheme_delete_action').attr('href', delete_url);
			
		})
		
	</script>
	
	<style>
		
		.pdf_viewer {
			text-align: center;
			margin-top: 15px;
		}
		
		.created-date {
			font-size: 11px;
			color: green;
			font-weight: bold;
		}
		
	</style>
	
	
	
