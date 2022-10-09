<!DOCTYPE html>
<!-- Template Name: Clip-One - Responsive Admin Template build with Twitter Bootstrap 3.x Version: 1.4 Author: ClipTheme -->
<!--[if IE 8]><html class="ie8 no-js" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9 no-js" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
    <!--<![endif]-->
    <!-- start: HEAD -->
    <head>
        <!-- statr: INCLUSE HEAD -->
        <?php $this->load->view('admin/includes/head'); ?>
        <!-- end: INCLUSE HEAD -->
	</head>
    <!-- end: HEAD -->
    <!-- start: BODY -->
    <body>
		
        
       
        
        
		<!-- start: MAIN CONTAINER -->
			
			<!-- start: PAGE -->
				<div class="container"> 
					<div class="row">
						<div class="col-md-12">
							<!-- start: BASIC TABLE PANEL -->
							
								<h4 align="center">Section wise report</h4>
								<?php $this->load->view('admin/reports/sectionwise_table'); ?>
							</div>
							<!-- end: BASIC TABLE PANEL -->
						
					</div>
					<!-- end: PAGE CONTENT-->
					
				</div>
				<!-- end: PAGE --> 
			<!-- end: MAIN CONTAINER --> 
			<!-- statr: INCLUSE FOOTER -->


<!-- end: INCLUSE FOOTER --> 
<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/select2/select2.css" />
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.css">
<!-- end: CSS REQUIRED FOR THIS PAGE ONLY --> 

<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 
<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/plugins/select2/select2.min.js"></script>


<script>
	
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
</script>

<style>
		/* column options example */
body{ background:#fff; padding:0; margin:0;
font-family: Arial, Helvetica, sans-serif;}

.pdf_table {
    border-collapse: collapse;
    width: 100%;

	
}

.pdf_table td, .pdf_table th {
    border: 1px solid #ddd;
    padding: 8px;
}

.pdf_table tr:nth-child(even){background-color: #f2f2f2;}

.pdf_table tr:hover {background-color: #ddd;}

.pdf_table th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #4CAF50;
    color: white;
}
		</style>
		
	</body>
	<!-- end: BODY -->
</html>

