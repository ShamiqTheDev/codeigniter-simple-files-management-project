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
                            <?php echo $page_title?>
						</div>
						<div class="panel-body">
						
							<?php
							$attributes = array('class' => 'form-horizontal', 'role' => '', 'id' => 'receipt_submit_form');
							echo form_open_multipart(current_url(), $attributes);
							?>
							
								<!-- <input type="hidden" name="roll_back_recipt_id" id="roll_back_recipt_id"> -->
								<table class="table table-striped table-bordered table-hover table-full-width" id="cases_table">
									<thead>
										<tr bgcolor="ebedfb" style="color:#000;">
											<?php
											if($dt_datatable) {
												foreach($dt_datatable as $get_dt_datatable) {
													$th_html = '<th>'.$get_dt_datatable['th_table'].'</th>';	
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
							
						</div>
					</div>
					<!-- end: DYNAMIC TABLE PANEL -->
				</div>
			</div>
			
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
<!-- end: CSS REQUIRED FOR THIS PAGE ONLY --> 

<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 
<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/plugins/select2/select2.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 

<script>
	
	jQuery(document).ready(function () {
		Main.init();
		
	});

	// $('.input-sm').keypress(function( e ) {
 //    	if(e.which === 32) 
 //        	return false;
	// })​​​​​;​
	
	function datatabledraw() {
	
	//$(function () {
		$('#cases_table').DataTable({
			"processing": <?php echo $datatable_setting['processing']; ?>,
			"serverSide": true,
			"searching": <?php echo $datatable_setting['searching']; ?>,
			"autoWidth": <?php echo $datatable_setting['autoWidth']; ?>,
			"lengthChange": <?php echo $datatable_setting['lengthChange']; ?>,
			"language": { search: '', searchPlaceholder: "Search..." },
			"ajax": {
				"url": "<?php echo $base_url; ?>admin/litigation/get_listing",
				"type": "POST",
				"data": function ( d ) {
					var top_search_like = {
							<?php
							if($view_type == 'inbox') {
								?>
								fs_sent_to_user_group: <?php echo $this->session->userdata('user_job_group'); ?>,
								//fs_sent_to: '<?php echo $this->flexi_auth->get_user_id(); ?>',
								//fs_sent_cc: '<?php echo $this->flexi_auth->get_user_id(); ?>',
								<?php
							}
							?>
						};
					
					var top_search = {
							<?php
							if($view_type == 'inbox') {
								?>
								fs_sent_status: 'sent',
								fs_is_responded: '0',
								<?php
							}
							else if($view_type == 'sent') {
								?>
								fs_sent_status: 'sent',
								fs_sent_by_user_group: <?php echo $this->session->userdata('user_job_group'); ?>,
								//fs_created_by: '<?php echo $this->flexi_auth->get_user_id(); ?>',
								<?php
							}
							else if($view_type == 'roll back') {
								?>
								fs_sent_status: 'roll back',
								<?php
							}
							else {
								?>
								fd_file_status:'<?php echo $view_type; ?>',
								fd_created_by: '<?php echo $this->flexi_auth->get_user_id(); ?>',
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
			//$('#cases_table').DataTable();
		//})
		
	//});
	
	}
		
	datatabledraw();
	
	$('table').on('click', '.roll-back-receipt', function() {
		var action_recipt_id = $(this).parent('a').attr('action-recipt-id');
		$('#roll_back_recipt_id').val(action_recipt_id);

			
		var receipt_datatable = $('#cases_table').DataTable();
		//alert(receipt_datatable);
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
		
		if(check_box_length_checked > 0)
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



	function hard_copy_received(fileId = null) {
			var hard_copy_receive = '1';
			$("#cases_table_processing").css("display",'block');
		$.ajax({
            url: '<?php echo $base_url; ?>admin/files/hard_copy_received/',
            type: 'POST',
			dataType: 'JSON',
            data: {hardCopyReceived:hard_copy_receive,file_id:fileId},
		    success: function(response) {

				if(response != ''){
					
			    $("#cases_table_processing").css("display",'none');
				$("#hard_copy_"+fileId).html('Hard Copy Received');
				$("#hard_copy_"+fileId).removeClass('btn-primary');
				$("#hard_copy_"+fileId).addClass('btn-success');
				$("#hard_copy_"+fileId).attr('disabled', true);
				
					alert("Hard Copy Received");
				}
				return true;
            },
            error: function () {
                console.log('Error in retrieving Site.');
				return false;
            }
        }); 
	}


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

	#cases_table tr td {
    	height: 30px;
	}	

	.panel-heading{ padding-left:40px !important; background-image:none; border-radius:0; box-shadow:none; }
	.panel-body {padding: 5px; margin-top:5px;}
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
	table.dataTable thead > tr > th{ padding-right:15px;}
	
	table.dataTable thead .sorting, table.dataTable thead .sorting_asc, table.dataTable thead .sorting_desc, table.dataTable thead .sorting_asc_disabled, table.dataTable thead .sorting_desc_disabled{ font-size:12px;}
	.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td{ font-size:12px;}
	div.dataTables_paginate{ font-size:12px;}
	div.dataTables_info{ font-size:12px;}
	/*.top_btn{background: #428bca;color: #fffefe;border-radius: 0;font-size: 12px;border: solid 1px #2e5d86;}*/
	.listing_table{ font-size:12px;}
	.is-not-read {
		font-weight: 600 !important;
	}
	
	.action-button {
		position: absolute;
	}
	.dataTables_filter input {
		margin-top: 0px;
	}
</style>
