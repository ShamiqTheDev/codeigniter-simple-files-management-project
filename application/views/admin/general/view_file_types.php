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
                            <a class="btn btn-teal btn-block" href="<?php echo $base_url; ?>admin/general/file_type">
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
                            File Types
						</div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered table-hover" id="sample_1">
                                <thead>
                                    <tr>
										
                                     
                                        <th class="hidden-xs" width="65%">File Type</th>
										<th class="hidden-xs center">Action</th>
									</tr>
								</thead>
                                <tbody>
								<?php
									if($file_types)
									{
										foreach($file_types as $key => $get_file_type)
										{
									?>
									<tr>
										
										<td><?php echo $get_file_type['fileType']?></td>
										<td nowrap class="center">
											<div class="visible-md visible-lg hidden-sm hidden-xs">
												<a href="<?php echo $base_url; ?>admin/general/file_type/<?php echo $get_file_type['fileTypeId']?>" class="btn btn-xs btn-teal tooltips" data-placement="top" data-original-title="Edit"><i class="fa fa-edit"></i></a>
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
	<!-- end: MAIN CONTAINER --> 
	<!-- statr: INCLUSE FOOTER -->
	<?php $this->load->view('admin/includes/footer'); ?>
	<!-- end: INCLUSE FOOTER --> 
	<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
	<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/select2/select2.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/datetimepicker/jquery.datetimepicker.css"/>
	<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/bootstrap-datepicker/datepicker.css">
	<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.css">
	
	<!-- Generic page styles -->
	<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/css/style.css">
	<!-- blueimp Gallery styles -->
	<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/css/blueimp-gallery.min.css">
	<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
	<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/css/jquery.fileupload.css">
	<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/css/jquery.fileupload-ui.css">
	<!-- CSS adjustments for browsers with JavaScript disabled -->
	<noscript><link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/css/jquery.fileupload-noscript.css"></noscript>
	<noscript><link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/jQuery-File-Upload/css/jquery.fileupload-ui-noscript.css"></noscript>
	<!-- end: CSS REQUIRED FOR THIS PAGE ONLY --> 
	
	<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 
	<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
	<script src="<?php echo $includes_dir; ?>admin/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/plugins/select2/select2.min.js"></script>
	<script src="<?php echo $includes_dir; ?>admin/plugins/datetimepicker/build/jquery.datetimepicker.full.js"></script> 
	<script src="<?php echo $includes_dir; ?>admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> 
	<script src="<?php echo $includes_dir; ?>admin/js/script.js"></script> 
	<script src="<?php echo $includes_dir; ?>admin/plugins/jquery.maskedinput/src/jquery.maskedinput.js"></script>
	<script src="<?php echo $includes_dir; ?>admin/plugins/jquery-maskmoney/jquery.maskMoney.js"></script>	
	
	
	<script>
        $('.datepicker').datetimepicker({
			timepicker: false,
			format: 'd-m-Y',
			scrollMonth : false,
			scrollInput : false,
		});
        jQuery(document).ready(function () {
			Main.init();
			maskCNIC();
			FormValidator.init();
		});
	
		function maskCNIC(){
			$('.input-mask-cnic').mask('9999999999999');
		}
	   var FormValidator = function () {
			
		// function to initiate category    
		var search_form = function () {
        var form1 = $('#search_form');
        var errorHandler1 = $('.errorHandler', form1);
        var successHandler1 = $('.successHandler', form1);
        $('#search_form').validate({
			
            errorElement: "span", // contain the error msg in a span tag
            errorClass: 'help-block',
            errorPlacement: function (error, element) {
                    error.insertAfter(element);
                    // for other inputs, just perform default behavior
            },
            ignore: "",
            rules: {
				ft_file_type_id: {
                    required: true
                }
            },
            messages: {
				ft_file_type_id: "Please select file type",
				
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                successHandler1.hide();
                errorHandler1.show();
            },
            highlight: function (element) {
                $(element).closest('.help-block').removeClass('valid');
                // display OK icon
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
                // add the Bootstrap error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error');
                // set error class to the control group
            },
            success: function (label, element) {
                label.addClass('help-block valid');
                // mark the current input as valid and display OK icon
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
            },
            submitHandler: function (form) {
                successHandler1.show();
                errorHandler1.hide();
                // submit form
                //$('#form').submit();
               HTMLFormElement.prototype.submit.call($('#search_form')[0]);
            }
        });
    };
    
    return {
        //main function to initiate pages
        init: function () {
            search_form();
        }
    };
}();
	$("#ft_file_type_id").on('change', function() {
			var file_type = $("#ft_file_type_id option:selected").text();
			if(file_type == "General (Non-Personnel File)"){
				$('#general_category_name_display').css('display','block');
				$('#cnic_display').css('display','none');
			}
			else{
				$('#general_category_name_display').css('display','none');
				$('#cnic_display').css('display','block');
			}
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
	
	
	
