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
			
			<?php
			$attributes = array('class' => 'form-horizontal', 'role' => '', 'id' => 'receipt_submit_form');
			echo form_open_multipart(current_url(), $attributes);
			?>
			
			<div class="row">
				<div class="col-md-12">
					<!-- start: DYNAMIC TABLE PANEL -->
					<div class="panel panel-default">
						<div class="panel-heading">
                            <i class="fa fa-external-link-square"></i>
                            <?php echo $page_title?>
						</div>
						<div class="panel-body">
						
							<div class="col-md-12">
								<div class="form-group col-md-4">
									<label class="col-sm-4 control-label">Generated Date</label>
									<div class="col-sm-8">
										<input type="text" id="generated_date" name="generated_date" value="<?php echo set_value('generated_date'); ?>" class="form-control datepicker"/>
									</div>
								</div>
								
								<div class="form-group col-md-4">
									<label class="col-sm-4 control-label">Document Number</label>
									<div class="col-sm-8">
										<input type="text" id="document_number" name="document_number" value="<?php echo set_value('document_number'); ?>" class="form-control"/>
									</div>
								</div>
								
								<div class="form-group col-md-4">
									<label class="col-sm-4 control-label">Personnel Id</label>
									<div class="col-sm-8">
										<input type="text" id="personnel_id" name="personnel_id" value="<?php echo set_value('personnel_id'); ?>" class="form-control"/>
									</div>
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group col-md-4">
									<label class="col-sm-4 control-label">CNIC</label>
									<div class="col-sm-8">
										<input type="text" id="cnic" name="cnic" value="<?php echo set_value('cnic'); ?>" class="form-control input-mask-cnic"/>
									</div>
								</div>
								
								<div class="form-group col-md-4">
									<label class="col-sm-4 control-label">Document Type</label>
									<div class="col-sm-8">
										<?php
										$docs_type = array('Offer Letter', 'PER','Posting Order', 'Show cause notice', 'Notice', 'Police Report', 'Other');
										?>
										<select name="document_type" id="document_type" class="form-control">
											<option value="">-Select Document Type-</option>
											<?php
											if($docs_type) {
												foreach($docs_type as $get_docs_type) {
													$selected = (isset($_POST["document_type"]) && ($_POST["document_type"] == $get_docs_type)) ? "selected='selected'" : "" ;
													echo '<option value="'.$get_docs_type.'"'.$selected.'>'.$get_docs_type.'</option>';
												}
											}
											?>
										</select>
									</div>
								</div>
							</div>
						
							<div class="form-group col-md-12">
								<div class="col-sm-2 pull-right">
									<button id="search_btn" class="btn btn-info btn-block" type="submit">
										Search <i class="fa fa-search"></i>
									</button>
								</div>
							</div>
							
							<?php if($_POST) { ?>
								<table class="table table-bordered table-hover table-full-width" id="receipt_table">
									<thead>
										<tr bgcolor="ebedfb" style="color:#000;">
											<?php
											if($dt_datatable) {
												foreach($dt_datatable as $get_dt_datatable) {
													if($get_dt_datatable['dt_column'] == 'checkBox') {
														$th_html = '<th><div class="checkbox-table">';
														$th_html .= '<label><input type="checkbox" id="check_all_box"></label>';
														$th_html .= '</div></th>';
													}
													else {
														$th_html = '<th>'.$get_dt_datatable['th_table'].'</th>';
													}
													
													echo $th_html;
												}
											}
											?>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
							<?php } ?>
							
						</div>
					</div>
					<!-- end: DYNAMIC TABLE PANEL -->
				</div>
			</div>
			<?php echo form_close(); ?>
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
<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/datetimepicker/jquery.datetimepicker.css"/>
<!-- end: CSS REQUIRED FOR THIS PAGE ONLY --> 

<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 
<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/plugins/select2/select2.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/jquery.maskedinput/src/jquery.maskedinput.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/jquery-maskmoney/jquery.maskMoney.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/datetimepicker/build/jquery.datetimepicker.full.js"></script> 
<script src="<?php echo $includes_dir; ?>admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 

<script>
	
	jQuery(document).ready(function () {
		Main.init();
		maskCNIC();
	});
	
	function maskCNIC(){
		$('.input-mask-cnic').mask('9999999999999');
	}
	
	$('.datepicker').datetimepicker({
		timepicker: false,
		format: 'Y-m-d',
		scrollMonth : false,
		scrollInput : false,
	});
	
	function datatabledraw() {
	
	//$(function () {
		$('#receipt_table').DataTable({
			"processing": <?php echo $datatable_setting['processing']; ?>,
			"serverSide": true,
			"searching": <?php echo $datatable_setting['searching']; ?>,
			"autoWidth": <?php echo $datatable_setting['autoWidth']; ?>,
			"lengthChange": <?php echo $datatable_setting['lengthChange']; ?>,
			"ajax": {
				"url": "<?php echo $base_url; ?>admin/bar_codes/get_listing",
				"type": "POST",
				"data": function ( d ) {
					var top_search_like = {
							gb_generated_date: $('#generated_date').val(),
						};
					
					var top_search = {
							gb_document_type: $('#document_type').val(),
							gb_document_number: $('#document_number').val(),
							gb_cnic: $('#cnic').val(),
							gb_personnel_id: $('#personnel_id').val(),
						};
					
					d.action_btn = '<?php echo $action_btn; ?>';
					d.top_search_like = top_search_like;
					d.top_search = top_search;
					d.dt_datatable = '<?php echo json_encode($dt_datatable); ?>';
				}
			},
			"order": [[ <?php echo $datatable_setting['order']['column']; ?>, "<?php echo $datatable_setting['order']['value']; ?>" ]],
			"columnDefs": [
				<?php
				if($dt_datatable) {
					foreach($dt_datatable as $key => $get_dt_datatable) {						
						echo '{ "orderable": '.$get_dt_datatable['td_orderable'].', "targets": '.$key.', "width": "'.$get_dt_datatable['td_width'].'", className: "'.$get_dt_datatable['className'].'"},';
					}
				}
				?>
			],
			/*"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
					$('tr').css('background-color', 'Yellow');				
			}*/
			"columns": [
				<?php echo str_repeat("null,", count($dt_datatable));?>
			],
			fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
				
			},
			"pageLength": <?php echo $datatable_setting['pageLength']; ?>,
			"lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
			"initComplete": function(settings, json) {
				//alert( 'DataTables has finished its initialisation.' ); 
				//$(".group1").colorbox();
			}
		}).on( 'draw', function () {
			
			$('tr td:nth-child(1), tr td:nth-child(2), tr td:nth-child(3), tr td:nth-child(4), tr td:nth-child(5), tr td:nth-child(6)').each(function (){
				//$(this).css('background-color', $('#color_by_input').val());
			})
			/*$('tr').each(function (){
				$(this).css('background-color', $('#color_by_input').val());
			})*/
			/*$('tr').each(function (){
				$(this).css('background-color', $('#color_by_input').val());
			})*/
			
			if($(".tooltips").length) {$('.tooltips').tooltip();}
		});
		
		
		//$('table').on('click', '.roll-back-receipt', function() {
			//$('#receipt_table').DataTable();
		//})
		
	//});
	
	}
	
	<?php if($_POST) { ?>
		datatabledraw();
	<?php } ?>
		
</script>


