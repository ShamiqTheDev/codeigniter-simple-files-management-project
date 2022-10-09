<style>
    @media print {
	.divHtml {
	display: none;
    }
    .divPrint{
	display:block;
    }
	.breakDivision{page-break-after: always;}		
	}
	@media screen {
    .divPrint{
	display:none;
    }
	}
	
</style>
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
					<?php if ($message) { ?>
						<div id="message"> <?php echo $message; ?> </div>
					<?php } ?>
					<!-- end: Success and error message -->
					<div style="padding-top:10px;"></div>
					
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
							Section wise Report
						</div>
						
						<?php
							$attributes = array('class' => 'form-horizontal', 'role' => 'cat_form', 'id' => 'search_form');
							//echo form_open($base_url.'admin/search/product_search', $attributes);
							echo form_open(current_url(), $attributes);
						?>
						<div class="panel-body">
							<?php //echo "<pre>";print_r($session_section);exit;?>
							<?php if(count($session_section) == 1) { ?>
								<input value="<?php echo $session_section[$uacc_section_fk]['sectionId']; ?>" type="hidden" name="section_id" id="section_id">
								<?php } else { ?>
								<div class="form-group col-md-5">
									<label class="col-sm-5 control-label">Section</label>
									<div class="col-sm-7">
										<select name="section_id" id="section_id" class="form-control">
											<option value="">-----Select Section-----</option>
											<?php
												if($session_section) {
													foreach($session_section as $get_session_section) {
														$select = set_select('section_id', $get_session_section['sectionId']);
														//echo '<option '.$select.' value="'.$get_file_received_from['uacc_id'].'">'.$get_file_received_from['upro_first_name'].' '.$get_file_received_from['upro_last_name'].' ('.$get_file_received_from['sectionName'].')</option>';
														echo '<option '.$select.' value="'.$get_session_section['sectionId'].'">'.$get_session_section['sectionName'].'</option>';
													}
												}
											?>
										</select>
									</div>
								</div>
							<?php } ?>
							
							<div class="form-group col-md-5">
								<label class="col-sm-5 control-label" for="form-field-1">
									File Type
								</label>
								<div class="col-sm-7">
									<select id="ft_file_type_id" name="ft_file_type_id" class="form-control">
										<option value="">Select File Type</option>
										<?php
											if($file_types) {
												foreach($file_types as $file_type) {
													$selected = (isset($_POST["ft_file_type_id"]) && ($_POST["ft_file_type_id"] == $file_type['fileTypeId'])) ? "selected='selected'" : "" ;
													echo '<option value="'.$file_type['fileTypeId'].'"'.$selected.'>'.$file_type['fileType'].'</option>';
												}
											}
										?>
									</select>
								</div>
							</div>
							
							
							<div class="col-md-12">
								<div class="form-group col-md-2  pull-right">
									<div class="col-sm-12 pull-right">
										<input type="hidden" name="uacc_section_fk" id="uacc_section_fk" value="<?php echo $uacc_section_fk?>">
										<button id="search_btn" class="btn btn-info btn-block" type="submit">
											Search <i class="fa fa-search"></i>
										</button>
									</div>
								</div>	
								<input type="hidden" name="generate_file" id="generate_file">
								<?php if (!empty($sectionwise_reporting)) { ?>
								
									<div class="form-group col-md-2 pull-right">
										<div class="col-sm-12 pull-right">
											<button id="pdf_btn" class="btn btn-bricky btn-block" type="button">
												Generate PDF
											</button>
										</div>
									</div>
									
									<div class="form-group col-md-2 pull-right">
										<div class="col-sm-12 pull-right">
											<button id="excel_btn" class="btn btn-success btn-block" type="button">
												Generate Excel
											</button>
										</div>
									</div>
								<?php } ?>
								
							</div>
							
						</div>
						
						<?php echo form_close(); ?>
						<div class="panel-body">
							<?php if (!empty($sectionwise_reporting)) { ?>
								<?php $this->load->view('admin/reports/sectionwise_table'); ?>
							<?php } ?>
						</div>
					</div>
					<!-- end: BASIC TABLE PANEL -->
				</div>
			</div>
			<!-- end: PAGE CONTENT-->
			
		</div>
		<!-- end: PAGE --> 
	</div>
	<div class="divPrint">
		<?php $this->load->view('admin/reports/sectionwise_table'); ?>	
	</div>
	<!-- end: MAIN CONTAINER --> 
	<!-- statr: INCLUSE FOOTER -->
	<?php $this->load->view('admin/includes/footer'); ?>
	<!-- end: INCLUSE FOOTER --> 
	<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
	<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/select2/select2.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/datetimepicker/jquery.datetimepicker.css"/>
	<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/bootstrap-datepicker/datepicker.css">
	<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.css">
	<!-- end: CSS REQUIRED FOR THIS PAGE ONLY --> 
	
	<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 
	<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/plugins/select2/select2.min.js"></script>
	<script src="<?php echo $includes_dir; ?>admin/plugins/datetimepicker/build/jquery.datetimepicker.full.js"></script> 
	<script src="<?php echo $includes_dir; ?>admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	
	
	<script>
		$('.datepicker').datetimepicker({
			timepicker: false,
			format: 'd-m-Y',
			scrollMonth : false,
			scrollInput : false,
		});
		
		jQuery(document).ready(function () {
			Main.init();
		});
	
		$(function () {
			$('#product_table').DataTable({
				"processing": true,
				"serverSide": true,
				"searching": false,
				"autoWidth": false,
				"ajax": {
					"url": "<?php echo $base_url; ?>admin/scaning/get_scan_listing",
					"type": "POST",
					"data": function ( d ) {
						var top_search_like = {
							//prod_product_title: $('#product_title').val()
						};
						
						var top_search = {
							fd_file_type_id: $('#ft_file_type_id').val(),
							fd_general_category_id: $('#gnc_general_category_id').val(),
							fd_employee_cnic: $('#fd_employee_cnic').val(),
							fd_employee_name: $('#fd_employee_name').val(),
						};
						
						d.action_btn = '<?php echo $action_btn; ?>';
						d.top_search_like = top_search_like;
						d.top_search = top_search;
						d.fd_file_typeId = $('#ft_file_type_id').val();
					}
				},
				"order": [[ 0, "desc" ]],
				"columnDefs": [
					//{ "orderable": false, "targets": 0 },
					//{ "orderable": false, "targets": 1 },
					{ "orderable": false, "targets": <?php echo $count_fields_file_type-1;?> },
				
				],
				"columns": [
					<?php echo str_repeat("null,",$count_fields_file_type);?>
				],
				"pageLength": 20,
				"lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
					"initComplete": function(settings, json) {
					//alert( 'DataTables has finished its initialisation.' );
					//$(".group1").colorbox();
				}
			}).on( 'draw', function () {
				$('tr td:nth-child(1), tr td:nth-child(2), tr td:nth-child(3), tr td:nth-child(4), tr td:nth-child(5), tr td:nth-child(6)').each(function (){
					$(this).addClass('left')
				})
				
				if($(".tooltips").length) {$('.tooltips').tooltip();}
			});
		
		});
		
		$('#excel_btn').click(function() {
			$('#generate_file').val('1');
			$('#search_form').submit();
			$('#generate_file').val('');
		});
		
		$('#pdf_btn').click(function() {
			$('#generate_file').val('2');
			$('#search_form').submit();
			$('#generate_file').val('');
		});
		
	</script>
	
	
	
