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
					<div class="page-header row">
                        
						<div class="col-md-2 pull-right">
                            <a class="btn btn-teal btn-block" href="<?php echo $base_url; ?>admin/general/scheme_document_type">
								Add 						
							</a>
						</div>
					</div>
					<!-- end: PAGE TITLE & BREADCRUMB width="1090" height="300" --> 
				</div>
			</div>
			<!-- end: PAGE HEADER -->
			<div class="row">
                <div class="col-md-12">
                    <!-- start: BASIC TABLE PANEL -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-external-link-square"></i>
                            Document Type
						</div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered table-hover" id="sample_1">
                                <thead>
                                    <tr>
                                        <th class="hidden-xs" width="65%">Document Type</th>
                                        <th class="hidden-xs" width="65%">Document Type Parent</th>
										<th class="hidden-xs center">Action</th>
									</tr>
								</thead>
                                <tbody>
									<?php
										if($document_type)
										{
											foreach($document_type as $key => $get_document_type)
											{
											?>
											<tr>
												
												<td><?php echo $get_document_type['documentType']?></td>
												<td><?php echo $get_document_type['documentTypeParent']?></td>
												<td nowrap class="center">
													<div class="visible-md visible-lg hidden-sm hidden-xs">
														<a href="<?php echo $base_url; ?>admin/general/scheme_document_type/<?php echo $get_document_type['documentTypeId']?>" class="btn btn-xs btn-teal tooltips" data-placement="top" data-original-title="Edit"><i class="fa fa-edit"></i></a>
													</div>
												</td>
											</tr>
											<?php }
										}?>
								</tbody>
							</table>
						</div>
					</div>
                    <!-- end: BASIC TABLE PANEL -->
				</div>
			</div>
			<!-- end: PAGE CONTENT-->
			
		</div>
		<!-- end: PAGE --> 
	</div>
</div>
<!-- end: MAIN CONTAINER --> 
<!-- statr: INCLUSE FOOTER -->
<?php $this->load->view('admin/includes/footer'); ?>
<!-- end: INCLUSE FOOTER -->

<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/select2/select2.css" />
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.css">
<!-- end: CSS REQUIRED FOR THIS PAGE ONLY --> 

<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 
<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/plugins/select2/select2.min.js"></script>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 

<script>
	jQuery(document).ready(function () {
		Main.init();
	});
</script>



