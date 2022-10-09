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
			
			<div class="row">
				<div class="col-md-12">
					<!-- start: DYNAMIC TABLE PANEL -->
					<div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-external-link-square"></i>
                            Search Files
						</div>
						
							<?php
							$attributes = array('class' => 'form-horizontal', 'role' => '', 'id' => 'receipt_submit_form');
							echo form_open_multipart(current_url(), $attributes);
							?>
						<div class="panel-body">	
								<div class="panel-body">

									<div class="form-group col-md-4">
										<label class="col-sm-5 control-label" for="form-field-1" style='font-size: 12px !important;'>
											Employee Name
										</label>
										<div class="col-sm-7">
											<input type="text" id="fd_employee_name" name="fd_employee_name" value="<?php echo set_value('fd_employee_name'); ?>" class="form-control avoid_space"/>
										</div>
									</div>

									<div class="form-group col-md-4">
										<label class="col-sm-5 control-label" for="form-field-1" style='font-size: 12px !important;'>
											File No
										</label>
										<div class="col-sm-7">
											<input type="text" id="fd_file_number" name="fd_file_number" value="<?php echo set_value('fd_file_number'); ?>" class="form-control avoid_space"/>
										</div>
									</div>

									<div class="form-group col-md-4">
										<label class="col-sm-5 control-label" for="form-field-1" style='font-size: 12px !important;'>
											Department File No
										</label>
										<div class="col-sm-7">
											<input type="text" id="fd_dept_file_number" name="fd_dept_file_number" value="<?php echo set_value('fd_dept_file_number'); ?>" class="form-control avoid_space"/>
										</div>
									</div>
									
								

								

								
								
								<div class="form-group col-md-4">
									<label class="col-sm-5 control-label" for="form-field-1" style='font-size: 12px !important;'>
										File Existence
									</label>
									<div class="col-sm-7">
										<select id="current_status" name="current_status" class="form-control">
											<option value="">Select File Existence</option>
											<?php
											if($send_user) {
												foreach($send_user as $key_send_user => $get_send_user) {
													echo '<optgroup label="'.$key_send_user.'">';
														
													foreach($get_send_user as $get_user) {
														//$designation = ($get_user['designationName']) ? ' ('.$get_user['designationName'].')' : '';
														$selected = (isset($_POST["current_status"]) && ($_POST["current_status"] == $get_user['uacc_id'])) ? "selected='selected'" : "" ;
														echo '<option '.$selected.' class="item" value="'.$get_user['uacc_id'].'">'.$get_user['upro_first_name'].' '.$get_user['upro_last_name'].'</option>';
													}

													echo '</optgroup>';
												}
											}
											?>
										</select>
									</div>
								</div>


								<div class="form-group col-md-4">
									<label class="col-sm-5 control-label" for="form-field-1" style='font-size: 12px !important;'>
										Subject
									</label>
									<div class="col-sm-7">
										<input type="text" id="fd_description" name="fd_description" value="<?php echo set_value('fd_description'); ?>" class="form-control avoid_space"/>
									</div>
								</div>
								

								<div class="form-group col-md-4">
									<label class="col-sm-5 control-label" for="form-field-1">
										File Type
									</label>
									<div class="col-sm-7">
										<select id="fd_file_type_id" name="fd_file_type_id" class="form-control">
											<option value="">Select File Type</option>
											<?php
												if($file_types) {
													foreach($file_types as $file_type) {
														$selected = (isset($_POST["fd_file_type_id"]) && ($_POST["fd_file_type_id"] == $file_type['fileTypeId'])) ? "selected='selected'" : "" ;
														echo '<option value="'.$file_type['fileTypeId'].'"'.$selected.'>'.$file_type['fileType'].'</option>';
													}
												}
											?>
										</select>
									</div>
								</div>


								<!-- <div class="form-group col-md-4">
									<label class="col-sm-5 control-label" for="form-field-1" style='font-size: 12px !important;'>
										Forward Date
									</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input name="forward_date" id="forward_date" class="form-control forward_date" type="text" autocomplete="off" value="<?php echo set_value('forward_date'); ?>">
											<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
										</div>	
									</div>
								</div> -->



								<!-- <div class="form-group">
								<label class="col-sm-3 control-label">
									Forward Date
								</label>
								<div class="col-sm-9">
									<div class="input-group">
										<input name="forward_date" id="forward_date" class="form-control forward_date" type="text" autocomplete="off">
										<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
									</div>
								</div>
							</div> -->
								
									<!--<div class="form-group col-md-4">
									<label class="col-sm-4 control-label" for="form-field-1" style='font-size: 12px !important;'>
										Category
									</label>
									<div class="col-sm-8">
										<select id="fd_category_id" name="fd_category_id" class="form-control">
											<option value="">Category</option>
											<?php
												if($category) {
													foreach($category as $get_subject_category) {
														$selected = (isset($_POST["fd_category_id"]) && ($_POST["fd_category_id"] == $get_subject_category['categoryId'])) ? "selected='selected'" : "" ;
														echo '<option value="'.$get_subject_category['categoryId'].'"'.$selected.'>'.$get_subject_category['categoryName'].'</option>';
													}
												}
											?>
										</select>
									</div>
								</div>-->
								<!--<div class="form-group col-md-4">
									<label class="col-sm-5 control-label" for="form-field-1" style='font-size: 11px !important;'>
										Sender
									</label>
									<div class="col-sm-7">
										<input type="text" id="cd_contact_name" name="cd_contact_name" value="<?php echo set_value('cd_contact_name'); ?>" class="form-control"/>
									</div>
								</div>-->
									<div class="form-group col-sm-5 pull-right">
										<div class="col-sm-6 pull-right">
											<button id="search_btn" class="btn btn-info btn-block top_btn" type="submit" style="padding: 6px 0px !important;">
												Search <i class="fa fa-search"></i>
											</button>
										</div>
									</div>
									<?php //if($error_message!='') { 

									?>
									<div class="form-group col-md-12">
										<label class="col-sm-4 control-label" id="error_message" style='color:red; margin-left:30px !important; display: none;'>Please Enter atleast one value to proceed</label>
									</div>
									<?php //} ?>
								</div>
								</div>	
							</div>
							</div>
							</div>
						<div class="row datatable_listing">
							<div class="col-md-12">
							<?php if($fileno !='' || $dept_fileno != '' || $filetypeids !='' || $description !='' || $employee_name !='' ||$default_listing) { 
								//echo "<pre>";print_r($this->input->post());exit;
								?>
							<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-external-link-square"></i>
								File Tracking
							</div>
							
							<div class="panel-body">
								<input type="hidden" name="roll_back_recipt_id" id="roll_back_recipt_id">
								<table class="table table-striped table-bordered table-hover table-full-width" id="receipt_table">
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
								<input type="hidden" name="view_type" id="view_type" value="<?php echo $view_type; ?>" />
							<?php echo form_close(); ?>
							
                            <hr />
                            
							
							
						</div>
					</div>
					<!-- end: DYNAMIC TABLE PANEL -->
				</div>
			</div>
			<?php } ?>
		</div>
		<!-- end: PAGE --> 
	</div>
</div>
<!-- end: MAIN CONTAINER --> 
<!-- statr: INCLUSE FOOTER -->
<?php $this->load->view('admin/includes/footer'); ?>
<?php $this->load->view('admin/includes/loader'); ?>
<!-- end: INCLUSE FOOTER --> 
<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/select2/select2.css" />
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.css">
<!-- <link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/bootstrap-datepicker/datepicker.css"> -->
<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/datetimepicker/jquery.datetimepicker.css"/>
<!-- end: CSS REQUIRED FOR THIS PAGE ONLY --> 

<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 
<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/plugins/select2/select2.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- <script src="<?php echo $includes_dir; ?>admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> -->
<script src="<?php echo $includes_dir; ?>admin/plugins/datetimepicker/build/jquery.datetimepicker.full.js"></script> 
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 

<script>
	$('.datepicker').datetimepicker({
			timepicker: false,
			format: 'd-m-Y',
			scrollMonth : false,
			scrollInput : false,
		});

	$('.forward_date').datetimepicker({
		timepicker: false,
		format: 'd-m-Y',
		scrollMonth : false,
		scrollInput : false,
		maxDate : new Date
	});

	jQuery(document).ready(function () {
		Main.init();
		
	});


	$('.avoid_space').keypress(function( e ) {
		if(e.which === 32) 
	        return false;
	});
	

	$('#search_btn').on('click', function(event) {       

    	var file_no = $('#fd_file_number').val();
    	var dept_file_no = $('#fd_dept_file_number').val();

		var file_type = $('#fd_file_type_id').val();
		var subject = $('#fd_description').val();
		var employee_name = $('#fd_employee_name').val();

		var file_existence = $('#current_status').val();

		if (file_no == '' && dept_file_no == '' && file_type == '' && subject == '' && employee_name == '' && file_existence == ''){
        	$('#error_message').show();
			$('.datatable_listing').hide();        	
        	event.preventDefault();
    	}
    });


	function datatabledraw() {
	
	//$(function () {
		$('#receipt_table').DataTable({
			"processing": <?php echo $datatable_setting['processing']; ?>,
			"serverSide": true,
			"searching": <?php echo $datatable_setting['searching']; ?>,
			"autoWidth": <?php echo $datatable_setting['autoWidth']; ?>,
			"lengthChange": <?php echo $datatable_setting['lengthChange']; ?>,
			//"ordering": false,
			"ajax": {
				"url": "<?php echo $base_url; ?>admin/files/get_listing",
				"type": "POST",
				"data": function ( d ) {
					var top_search_like = {
							<?php
							if($view_type == 'inbox') {
								?>
								fs_sent_to: '<?php echo $this->flexi_auth->get_user_id(); ?>',
								fs_sent_cc: '<?php echo $this->flexi_auth->get_user_id(); ?>',
								
								<?php
							}else if($view_type == 'file_tracking'){ ?>
								fs_created_date : $('#fs_created_date').val(),
								fs_forward_date : $('#forward_date').val(),
								fd_description: $('#fd_description').val(),
								fd_employee_name: $('#fd_employee_name').val(),
							<?php } ?>
							
						};
					
					var top_search = {
							<?php
							if($view_type == 'inbox' || $view_type == 'sent') {
								?>
								fs_sent_status: 'sent',
								//fs_email_version: 'latest',
								<?php
							}
							else if($view_type == 'roll back') {
								?>
								fs_sent_status: 'roll back',
								<?php
							}
							else if($view_type == 'file_tracking'){?>
								fd_file_number: $('#fd_file_number').val(),
								fd_old_file_number: $('#fd_dept_file_number').val(),
								
								fd_file_type_id: $('#fd_file_type_id').val(),
								fs_forward_date : $('#forward_date').val(),
								fd_file_existence_id:$('#current_status').val(),
								
								<?php
							}
							else {
								?>
								fd_file_status:'<?php echo $view_type; ?>',
								fd_created_by: '<?php echo $this->flexi_auth->get_user_id(); ?>',
								<?php
							}
							?>
							<?php if($view_type == 'sent') {
								?>
								fs_created_by: '<?php echo $this->flexi_auth->get_user_id(); ?>',
								<?php
							}
							?>
						};
					
					d.action_btn = '<?php echo $action_btn; ?>';
					d.top_search_like = top_search_like;
					d.top_search = top_search;
					//d.dt_datatable = '<?php echo json_encode($dt_datatable); ?>';
					d.dt_datatable = <?php echo json_encode($dt_datatable); ?>;
					d.view_type = '<?php echo $view_type; ?>';
					//d.roll_back_recipt_id = $('#roll_back_recipt_id').val();
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
				var color = $(nRow).find('span:eq(0)').text();
				//console.log(color);
				if (color != "") {
					//$(nRow).find('td:eq(3)').addClass('color');
					//$(nRow).find('tr').removeClass('odd');
					//$(nRow).css('cssText','background-color:'+color+'!important');
				}
				<?php
				if($view_type == 'inbox') {
					?>
					var is_read = $(nRow).find('.p_square').attr('is-read');
					
					if (is_read == "0") {
						$(nRow).children('td').addClass('is-not-read');
					}
					<?php
				}
				?>
				
				//console.log(iDisplayIndexFull);
				/*if(aData[3]=="Appointments"){
					$(nRow).css('background-color', '#ffd2c8');
				}*/   
			},
			"pageLength": <?php echo $datatable_setting['pageLength']; ?>,
			"lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
			"initComplete": function(settings, json) {
				//alert( 'DataTables has finished its initialisation.' ); 
				//$(".group1").colorbox();
			}
		}).on( 'draw', function () {
		
			$('#roll_back_recipt_id').val('');
		
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
	
	datatabledraw();
	
	$('table').on('click', '.roll-back-receipt', function() {
		var action_recipt_id = $(this).parent('a').attr('action-recipt-id');
		$('#roll_back_recipt_id').val(action_recipt_id);
	
		var receipt_datatable = $('#receipt_table').DataTable();
		receipt_datatable.destroy();
		datatabledraw();
	})
	
	$("#check_all_box").click(function () {	
		if($(this).prop('checked')) {
			$('.checked_box').prop('checked', true);
		}
		else {
			$('.checked_box').prop('checked', false);
		}
	});
	
	
	$('body').on('click', '.checked_box', function() {
		var check_box_length = $(".checked_box").length;
		var check_box_length_checked = $(".checked_box:checked").length;
		
		if(check_box_length_checked == 1)
		{
			$('#receipt_put_file').prop('disabled', false);
			$('#send_receipt').prop('disabled', false);
			$('#send_back_receipt').prop('disabled', false);
		}
		else{
			$('#receipt_put_file').prop('disabled', true);
			$('#send_receipt').prop('disabled', true);
			$('#send_back_receipt').prop('disabled', true);
		}
		
		if(check_box_length == check_box_length_checked) {
			$('#check_all_box').prop('checked', true);
		}
		else {
			$('#check_all_box').prop('checked', false);
		}
	});
	
	$('.receipt_submit').click(function() {
		var action_form = $(this).attr('action-form');
		action_form = '<?php echo base_url(); ?>admin/files/'+action_form;
		
		var checked_box_length = $(".checked_box:checked").length;
		
		if(checked_box_length > 0) {
			$('#receipt_submit_form').attr('action', action_form);
			$('#receipt_submit_form').submit();
		}
	})
	
</script>

<style>
	.panel-heading{ padding-left:40px !important; background-image:none; border-radius:0; box-shadow:none; }
	.panel-body {padding: 5px; margin-top:5px; }
	.panel-default>.panel-heading{color: #fff;font-weight: bold;background:#282828;}
	
	.p_square{width: 10px;
    height: 10px;
    margin: 2px 8px;
    float: left;}
	.s_square{ width: 40px;height: 20px;margin: 0px 10px;float: left;}	
	
	.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{padding:2px;}
	.btn {padding: 3px 8px;}
	
	.input-sm{ margin-top:-60px;}
	#sample_1_length{ display:none;}
	
	.table-hover a{ color:#0071d4;}
	table.dataTable thead > tr > th{ padding-right:15px; padding-top:8px; padding-bottom:8px; }
	table.dataTable tr > td{ padding-top:8px; padding-bottom:8px; }
	
	table.dataTable thead .sorting, table.dataTable thead .sorting_asc, table.dataTable thead .sorting_desc, table.dataTable thead .sorting_asc_disabled, table.dataTable thead .sorting_desc_disabled{ font-size:12px;}
	.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td{ font-size:12px;}
	div.dataTables_paginate{ font-size:12px;}
	div.dataTables_info{ font-size:12px;}
	/*.top_btn{background: #428bca;color: #fffefe;border-radius: 0;font-size: 12px;border: solid 1px #2e5d86;}*/
	.listing_table{ font-size:12px;}
	.is-not-read {
		font-weight: 600 !important;
	}
</style>
