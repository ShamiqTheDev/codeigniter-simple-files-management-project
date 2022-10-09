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
								<!--
									<table class="table table-bordered">
									<tr role="row" >
									<td style="width: 50%"><strong>File </strong> Altaf Hussain<strong> S/o </strong> Abdul Razaque</td>
									<td><strong>CNIC </strong>4151215548798</td>
									</tr>
									<tr role="row" >
									<td colspan="2" style="padding: 0 !important;">
									<table style="width: 100%">
									<tr>
									<td style="width: 33.33%; border-right: 1px solid #ddd;"><strong>BPS </strong> 01</td>
									<td style="width: 33.33%; border-right: 1px solid #ddd;"><strong>Section </strong> Section-I</td>
									<td style="width: 33.33%"><strong>File Type </strong> Personnel</td>
									</tr>
									</table>
									</td>
									</tr>
									<tr role="row" >
									
									</tr>
									</table>
								-->
								<div class="pdf_viewer">
									<embed src="<?php echo base_url(); ?><?php echo  $scheme['filePath']; ?>#page=1&toolbar=1&navpanes=1&scrollbar=0&statusbar=0&pagemode=none&viewrect=0,0" type="application/pdf" width="1000px" height="400px">
								</div>
								<table class="table table-bordered">
								
									<tr role="row">
										<td colspan="5"><strong>Scheme Name:</strong> <?php echo $scheme['schemeName']; ?></td>
									</tr>
								
									<tr role="row">
										<td style="width: 20%"><strong>ADP Year: </strong><?php echo $scheme['adpYear']; ?></td>
										<td style="width: 15%"><strong>ADP Number: </strong><?php echo $scheme['adpNumber']; ?></td>
										<td style="width: 15%"><strong>Size: </strong><?php echo convertToReadableSize($scheme['fileSize']); ?></td>
										<td style="width: 15%"><strong>Date: </strong><?php echo ($scheme['schemeDate'] == '0000-00-00') ? '' : date('d-m-Y', strtotime($scheme['schemeDate'])); ?></td>
										<td style="width: 35%"><strong>Document Type: </strong><?php echo $scheme['documentType']; ?><?php echo ($scheme['documentTypeChild']) ? ' ('.$scheme['documentTypeChild'].')' : ''; ?></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
                    <!-- end: TEXT FIELDS PANEL -->
				</div>
				
				<?php /*if($related_file) { ?>
					<div class="col-sm-12">
						<div class="panel panel-default" id="found-file-container">
							<div class="panel-heading"> <i class="fa fa-external-link-square"></i>Related File</div>
							<div class="panel-body">
								<div id="" class="">
									<table role="" class="table table-striped" id="found_files">
										<tr>
											<th></th>
											<th>File Name</th>
											<th>Document Type</th>
										</tr>
										<?php
										foreach($related_file as $get_related_file) {
											?>
											<tr>
												<td><span class="preview"><a href="<?php echo $base_url; ?>admin/scheme/scheme_detail/<?php echo $get_related_file['schemeId']; ?>" title="<?php echo $get_related_file['fileName']; ?>" data-gallery target="_blank"><img src="<?php echo $includes_dir; ?>admin/images/pdf-icon.png" style="height: 35px;"></a></span></td>
												<td><p class="name"><a style="width: 181px; float: left; overflow: hidden;" href="<?php echo $base_url; ?>admin/scheme/scheme_detail/<?php echo $get_related_file['schemeId']; ?>" title="<?php echo $get_related_file['fileName']; ?>" target="_blank"><?php echo $get_related_file['fileName']; ?></a></p></td>
												<td><?php echo $get_related_file['documentType']; ?></td>
											</tr>
											<?php
										}
										?>
									</table>
								</div>
							</div>
						</div>
					</div>
				<?php } */?>
				
			</div>
			<!-- end: PAGE CONTENT-->
			
		</div>
		<!-- end: PAGE --> 
	</div>
	<!-- end: MAIN CONTAINER --> 
	<!-- statr: INCLUSE FOOTER -->
	<?php $this->load->view('admin/includes/footer'); ?>
	<!-- end: INCLUSE FOOTER --> 
	
	<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
	<!-- end: CSS REQUIRED FOR THIS PAGE ONLY --> 
	
	<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 
	<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 
	
	<script>
		
		jQuery(document).ready(function () {
			Main.init();
			$('.pdf-container').css('min-height', '');
			$('.pdf-container').css('min-height', '570px');
		});
		
	</script>
	
	<style>
		
		.pdf_viewer {
			text-align: center;
			margin-top: 15px;
		}
		
	</style>
	
	
	
