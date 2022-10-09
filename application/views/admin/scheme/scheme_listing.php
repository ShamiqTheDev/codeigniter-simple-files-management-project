<?php 
if($request_type == "operator"){
	$scheme_listing_func = "get_scheme_listing";
}
else{
	$scheme_listing_func = "get_scheme_listing_service";
}
?>

<style>
	a.demo_img {
	display: block;
	/*width:300px;*/
	position:relative;
	line-height:25px;
	}
	
	a.demo_img>div {
	position:absolute;
	padding:0;
	margin:0;
	left: -40px; /* change this value to one that works best for you */
	top: -420px; /* change this value to one that works best for you */
	background: transparent url(https://image.ibb.co/bxXeho/arrow_down_grey.png) center 115px no-repeat;
	margin-left: 24px;
	opacity:0;
	height: 0;
	overflow: hidden;
	
	/* Enable transitions */
	-webkit-transition: all .3s ease .15s;
	-moz-transition: all .3s ease .15s;
	-o-transition: all .3s ease .15s;
	-ms-transition: all .3s ease .15s;
	transition: all .3s ease .15s;
	}
	
	a.demo_img>div embed {
	padding:8px;
	margin-left:4px;
	border:1px solid #BCBDC0;
	background-color:#BCBDC0;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	
	-webkit-box-sizing:border-box; 
	-moz-box-sizing:border-box; 
	box-sizing:border-box;
	}
	a.demo_img:hover>div {
	
	opacity:1;
	height: 1000px;
	padding: 8px;   
	
	z-index:1;
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
                        <div class="panel-heading">
                            <i class="fa fa-external-link-square"></i>
                            Search Scheme
						</div>
                        <?php
							$attributes = array('class' => 'form-horizontal', 'role' => 'cat_form', 'id' => 'search_form');
							//echo form_open($base_url.'admin/search/product_search', $attributes);
							echo form_open(current_url(), $attributes);
						?>
							
							<div class="panel-body">
							<input type="hidden" id="fd_created_by" name="fd_created_by" value="<?php echo isset($created_by) ? $created_by : ''; ?>" />
							
								<div class="form-group col-md-4">
								
									<?php
									
									$start_year = '2008';
									$current_year = (date('m') > 6) ? date('Y') : date('Y', strtotime('-1 year'));
									
									for($i = $start_year; $i <= $current_year; $i++) {
										$next_year = $i+1;
										$year_array[] = $i .'-'. $next_year;
									}									
									
									?>
								
									<label class="col-sm-5 control-label" for="form-field-1">
										ADP Year
									</label>
									<div class="col-sm-7">
									
										<select name="sc_adp_year" id="sc_adp_year" class="form-control">
											<?php
												if($year_array) {
													foreach($year_array as $get_year) {
														//$select = (isset($_POST)) ? set_select('sc_adp_year', $get_year) : ((date('Y').'-'.date('Y', strtotime('+1 year')) == $get_year) ? 'selected="selected"' : 'zia');
														$select = ($_POST) ? set_select('sc_adp_year', $get_year) : (($current_year.'-'.($current_year + 1) == $get_year) ? 'selected="selected"' : '');
														echo '<option '.$select.' value="'.$get_year.'">'.$get_year.'</option>';
													}
												}
											?>
										</select>
									
										<?php /* ?>
										<input type="text" id="sc_adp_year" name="sc_adp_year" value="<?php echo set_value('sc_adp_year'); ?>" class="form-control"/>
										<?php */ ?>
									</div>
								</div>
							
						
							
								<div class="form-group col-md-4">
									<label class="col-sm-5 control-label" for="form-field-1">
										ADP Number
									</label>
									<div class="col-sm-7">
										<input type="text" id="sc_adp_number" name="sc_adp_number" value="<?php echo set_value('sc_adp_number'); ?>" class="form-control"/>
									</div>
								</div>
							
							
							
							<div class="form-group col-md-12">
								<div class="col-sm-2 pull-right">
									<button id="search_btn" class="btn btn-info btn-block" type="submit">
										Search <i class="fa fa-search"></i>
									</button>
								</div>
							</div>
						</div>
							
                        <?php echo form_close(); ?>
					</div>
                    <!-- end: TEXT FIELDS PANEL -->
				</div>
			</div>
			<?php if($_POST || $default_listing) { ?>
				<!-- start: PAGE CONTENT -->
				<div class="row">
					<div class="col-md-12">
						<!-- start: BASIC TABLE PANEL -->
						<div class="panel panel-default">
							<div class="panel-heading">
								<i class="fa fa-external-link-square"></i>
								<div style="display:inline">Listing</div>
								<?php
								if(isset($scheme_data_count)){
								?>
								<div style="float:right"><strong>Total Scanned Document</strong> = <?php echo $scheme_data_count?></div>
								<?php 
								}
								?>
							</div>
							<div class="panel-body">
								<table class="table table-striped table-bordered table-hover" id="scanning_table">
									<thead>
										<tr>
											<?php
												foreach($show_table_th as $get_heading){
													echo '<th>'.$get_heading.'</th>';
												}
											?>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
							</div>
						</div>
						<!-- end: BASIC TABLE PANEL -->
					</div>
				</div>
				<!-- end: PAGE CONTENT-->
			<?php } ?>
		</div>
		<!-- end: PAGE --> 
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
	<script src="<?php echo $includes_dir; ?>admin/plugins/jquery.maskedinput/src/jquery.maskedinput.js"></script>
	<script src="<?php echo $includes_dir; ?>admin/plugins/jquery-maskmoney/jquery.maskMoney.js"></script>
	<script src="<?php echo $includes_dir; ?>admin/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
	<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/plugins/Inputmask-4.x/dist/jquery.inputmask.bundle.js" charset="utf-8"></script>
	<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 
	
	
	<script>
	
		jQuery(document).ready(function () {
			Main.init();
			maskCNIC();
			FormValidator.init();
		});
		
		function maskCNIC(){
			$('.input-mask-cnic').inputmask({
				mask: "9999999999999",
			});
			
			$('#sc_adp_year').inputmask({
				mask: "9999-9999",
			});
		}
		
		$(function () {
			$('#scanning_table').DataTable({
				"processing": true,
				"serverSide": true,
				"searching": false,
				"autoWidth": true,
				"ordering": false,
				"ajax": {
					"url": "<?php echo $base_url; ?>admin/scheme/<?php echo $scheme_listing_func;?>",
					"type": "POST",
					"data": function ( d ) {
						var top_search_like = {
								//fd_employee_cnic: $('#fd_employee_cnic').val(),
								//fd_employee_name: $('#fd_employee_name').val(),
								//fd_subject: $('#fd_subject').val(),
							};
						
						var top_search = {
								scd_adp_year: $('#sc_adp_year').val(),
								scd_adp_number: $('#sc_adp_number').val(),
								//fd_created_by: $('#fd_created_by').val(),
								//fd_section_id: $('#ft_section_id').val(),
							};
						
						//d.action_btn = '<?php echo $action_btn; ?>';
						d.top_search_like = top_search_like;
						d.top_search = top_search;
						d.dt_column = '<?php echo $dt_column; ?>';
						d.request_type = '<?php echo $request_type; ?>';
						d.scheme_data = '<?php echo $scheme_data?>';
						d.adp_year = $('#sc_adp_year').val();
						d.adp_number = $('#sc_adp_number').val();
						
						//d.section_name = '<?php echo $user_section; ?>';
					}
				},
				//"order": [[ 4, "asc" ]],
				"columnDefs": [
                //{ "orderable": false, "targets": 0 },
                //{ "orderable": false, "targets": 1 },
                { "orderable": false, "targets": <?php echo count($show_table_th)-1;?> },
                
				],
				"columns": [
					<?php echo str_repeat("null,", count($show_table_th));?>
				],
				"pageLength": 20,
				"lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
				"initComplete": function(settings, json) {
					//alert( 'DataTables has finished its initialisation.' );
					//console.log(json.totalCount);
					//$(".group1").colorbox();
				}
				}).on( 'draw', function () {
				$('tr td:nth-child(1), tr td:nth-child(2), tr td:nth-child(3), tr td:nth-child(4), tr td:nth-child(5), tr td:nth-child(6)').each(function (){
					$(this).addClass('left')
				})
				
				if($(".tooltips").length) {$('.tooltips').tooltip();}
			});
			
		});
		
		
		var FormValidator = function () {
				
			// function to initiate category    
			var fileUploadingForm = function () {
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
							required: false
						},
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
					fileUploadingForm();
				}
			};
		}();
		
		$('#sc_adp_number').keypress(function(event) {
			var key = window.event ? event.keyCode : event.which;
			if (event.keyCode === 8 || event.keyCode === 46) {
				return true;
			} else if ( key < 48 || key > 57 ) {
				return false;
			} else {
				return true;
			}
		});
		
		
		/*$('#ft_file_type_id').change(function() {
		
			$('#fd_employee_cnic').val('');
			$('#fd_employee_name').val('');
			$('#gnc_general_category_id option[value=""]').prop('selected', true);
			
			var file_type_id = $(this).val();
			
			if(file_type_id == "1"){
				$('#cnic_display').css('display','block');
				$('#employee_name_display').css('display','block');
				$('#category_name_display').css('display','none');
			}
			else if(file_type_id == "2" ){
				$('#cnic_display').css('display','block');
				$('#employee_name_display').css('display','block');
				$('#category_name_display').css('display','none');
			}
			else{
				$('#cnic_display').css('display','none');
				$('#employee_name_display').css('display','none');
				$('#category_name_display').css('display','block');
			}
			
		});*/
	</script>
	
	<style>
		.valid {
			display: none;
		}
	</style>
	
	
	
