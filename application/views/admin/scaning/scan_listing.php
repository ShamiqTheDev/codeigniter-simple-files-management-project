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
	.loading-image {
    position: absolute;
    width: 50%;
    background-color: #e6e6e6;
    line-height: 40px;
    opacity: 0.5;
    margin-left: 140px;
    display: none;
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
                            Search Files
						</div>
                        <?php
							$attributes = array('class' => 'form-horizontal', 'role' => 'cat_form', 'id' => 'search_form');
							//echo form_open($base_url.'admin/search/product_search', $attributes);
							echo form_open(current_url(), $attributes);
						?>
						<div class="panel-body">
							<?php if(in_array('File Type', $show_field)) { ?>
								<div class="form-group col-md-4">
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
							<?php } else { ?>
								<input type="hidden" id="ft_file_type_id" name="ft_file_type_id" value="<?php echo $file_type_index; ?>" />
							<?php } ?>
							
							<?php if(in_array('Section', $show_field)) { ?>
								<div class="form-group col-md-4">
									<label class="col-sm-5 control-label" for="form-field-1">
										Section
									</label>
									<div class="col-sm-7">
										<select id="ft_section_id" name="ft_section_id" class="form-control">
											<option value="">Select Section</option>
											<?php
												if($session_section) {
													foreach($session_section as $get_session_section) {
														$selected = (isset($_POST["ft_section_id"]) && ($_POST["ft_section_id"] == $get_session_section['sectionId'])) ? "selected='selected'" : "" ;
														echo '<option value="'.$get_session_section['sectionId'].'"'.$selected.'>'.$get_session_section['sectionName'].'</option>';
													}
												}
											?>
										</select>
									</div>
								</div>
							<?php } else { ?>
								<input type="hidden" id="ft_section_id" name="ft_section_id" value="<?php echo $section_id; ?>" />
							<?php } ?>
							<div class="form-group col-md-4" id="old_file_number_display" style="display:<?php echo in_array('Old File Number', $show_field) ? 'block' : 'none'; ?>">
								<label class="col-sm-5 control-label" for="form-field-1">
									Old File Number
								</label>
								<div class="col-sm-7">
									<input type="text" id="fd_old_file_number" name="fd_old_file_number" value="<?php echo set_value('fd_old_file_number'); ?>" class="form-control"/>
								</div>
							</div>
							<?php if(!empty($created_by)){ ?>
								<input type="hidden" id="fd_created_by" name="fd_created_by" value="<?php echo isset($created_by) ? $created_by : ''; ?>" />
							<?php } else { ?>
							<div class="form-group col-md-4" id="entered_by_display" style="display:<?php echo in_array('Entered By', $show_field) ? 'block' : 'none'; ?>">
									<label class="col-sm-5 control-label" for="form-field-1">
										Entered By
									</label>
									<div class="col-sm-7">
										<select id="fd_created_by" name="fd_created_by" class="form-control">
											<option value="">Select Entered By</option>
											<?php
												if($entered_by) {
													foreach($entered_by as $get_entered_by) {
														$selected = (isset($_POST["fd_created_by"]) && ($_POST["fd_created_by"] == $get_entered_by['uacc_id'])) ? "selected='selected'" : "" ;
														echo '<option value="'.$get_entered_by['uacc_id'].'"'.$selected.'>'.$get_entered_by['entered_by_name'].'</option>';
													}
												}
											?>
										</select>
									</div>
							</div>
							<?php } ?>
							
							<div class="form-group col-md-4" id="cnic_display" style="display:<?php echo in_array('CNIC', $show_field) ? 'block' : 'none'; ?>">
								<label class="col-sm-5 control-label" for="form-field-1">
									Employee CNIC
								</label>
								<div class="col-sm-7">
									<input type="text" id="fd_employee_cnic" name="fd_employee_cnic" value="<?php echo set_value('fd_employee_cnic'); ?>" class="form-control input-mask-cnic"/>
								</div>
							</div>
							
							<div class="form-group col-md-4" id="employee_name_display" style="display:<?php echo in_array('Employee Name', $show_field) ? 'block' : 'none'; ?>">
								<label class="col-sm-5 control-label" for="form-field-1">
									Employee Name
								</label>
								<div class="col-sm-7">
									<input type="text" id="fd_employee_name" name="fd_employee_name" value="<?php echo set_value('fd_employee_name'); ?>" class="form-control"/>
								</div>
							</div>
							<div class="form-group col-md-4" id="employee_subject_display" style="display:<?php echo in_array('Subject', $show_field) ? 'block' : 'none'; ?>">
								<label class="col-sm-5 control-label" for="form-field-1">
									Subject
								</label>
								<div class="col-sm-7">
									<input type="text" id="fd_subject" name="fd_subject" value="<?php echo set_value('fd_subject'); ?>" class="form-control"/>
								</div>
							</div>
							<div class="form-group col-md-4" id="category_name_display" style="display:<?php echo in_array('Category Name', $show_field) ? 'block' : 'none'; ?>">
								<label class="col-sm-5 control-label" for="form-field-1">
									Category Name
								</label>
								<div class="col-sm-7">
									<select id="gnc_general_category_id" name="gnc_general_category_id" class="form-control">
										<option value="">Select General Category Name</option>
										<?php
											if($general_category) {
												foreach($general_category as $get_general_category) {
													
													$style = "style='color:#000000;background-color:#FFFFFF'";
													$current_user_id = $this->flexi_auth->get_user_id();
													
													if($get_general_category['isExtraCategory'] == 1 && in_array($current_user_id,$this->config->item("showExtraCategoriesToIds"))){ 
														$style = "style='color:#FFFFFF;background-color:#00641D'";
														
													}
													else if($get_general_category['isExtraCategory'] == 1 && !in_array($current_user_id,$this->config->item("showExtraCategoriesToIds"))){
														continue;
													}
													
													$selected = (isset($_POST["gnc_general_category_id"]) && ($_POST["gnc_general_category_id"] == $get_general_category['generalCategoryId'])) ? "selected='selected'" : "" ;
													echo '<option value="'.$get_general_category['generalCategoryId'].'"'.$selected.' '.$style.'>'.$get_general_category['generalCategoryName'].'</option>';
												}
											}
										?>
									</select>
								</div>
								<div class="loading-image-area loading-image" style="margin-top: -5px;">
									<label class="col-sm-6 control-label" for="form-field-1"><img src="<?php echo $includes_dir; ?>admin/images/rounded-light.gif"></label>
								</div>
							</div>
							
							
							
							<div class="form-group col-md-4" id="app_name_display" style="display:<?php echo in_array('App Name', $show_field) ? 'block' : 'none'; ?>">
									<label class="col-sm-5 control-label" for="form-field-1">
										App Name
									</label>
									<div class="col-sm-7">
										<select id="fd_app_name" name="fd_app_name" class="form-control">
											<option value="">Select App Name</option>
											<?php
												if($appName) {
													foreach($appName as $get_app_name) {
														$selected = (isset($_POST["fd_app_name"]) && ($_POST["fd_app_name"] == $get_app_name)) ? "selected='selected'" : "" ;
														echo '<option value="'.$get_app_name.'"'.$selected.'>'.$get_app_name.'</option>';
													}
												}
											?>
										</select>
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
								Listing
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
	<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 
	
	<script>
	
		jQuery(document).ready(function () {
			Main.init();
			maskCNIC();
			FormValidator.init();
		});
		
		function maskCNIC(){
			$('.input-mask-cnic').mask('9999999999999');
		}
		
		$("#ft_file_type_id").on('change', function() {
				var file_type = $("#ft_file_type_id option:selected").val();
				$('#fd_employee_cnic').val('');
				$('#fd_employee_name').val('');
				$('#fd_subject').val('');
				$('#fd_old_file_number').val('');
				if(file_type == "1"){
					$('#cnic_display').css('display','block');
					$('#employee_name_display').css('display','block');
					//$('#entered_by_display').css('display','block');
					$('#app_name_display').css('display','block');
					$('#employee_subject_display').css('display','none');
					//$('#old_file_number_display').css('display','block');
					<?php if (array_search('1', array_column($general_category_all, 'fileTypeId'))){ ?>
						$('#category_name_display').css('display','block');
						search_category_name();
					<?php }else{ ?>
						$('#general_cat_name').css('display','none');
					<?php } ?>
					
					
					//search_category_name();
				}
				else if(file_type == "2" ){
					$('#cnic_display').css('display','block');
					$('#employee_name_display').css('display','block');
					$('#entered_by_display').css('display','block');
					$('#app_name_display').css('display','none');
					$('#employee_subject_display').css('display','none');
					$('#old_file_number_display').css('display','block');
					$('#category_name_display').css('display','none');
					
				}
				else {
					$('#employee_subject_display').css('display','block');
					$('#cnic_display').css('display','none');
					$('#employee_name_display').css('display','none');
					$('#entered_by_display').css('display','block');
					$('#app_name_display').css('display','block');
					$('#category_name_display').css('display','block');
					$('#old_file_number_display').css('display','block');
					search_category_name();
				}
				if(file_type==''){
					$('#cnic_display').css('display','none');
					$('#employee_name_display').css('display','none');
					$('#entered_by_display').css('display','none');
					$('#app_name_display').css('display','none');
					$('#category_name_display').css('display','none');
					$('#employee_subject_display').css('display','none');
					$('#old_file_number_display').css('display','none');
				}
				
			});
			var file_type = $("#ft_file_type_id").val();
			<?php if (!$file_type_name){ ?>
			if(file_type!=''){
				if(file_type == "1"){
					$('#cnic_display').css('display','block');
					$('#employee_name_display').css('display','block');
					$('#entered_by_display').css('display','block');
					$('#app_name_display').css('display','block');
					$('#employee_subject_display').css('display','none');
					$('#old_file_number_display').css('display','block');
					<?php if (array_search('1', array_column($general_category_all, 'fileTypeId'))){ ?>
						$('#category_name_display').css('display','block');
						search_category_name();
					<?php }else{ ?>
						$('#general_cat_name').css('display','none');
					<?php } ?>
									
				}
				else if(file_type == "2" ){
					$('#cnic_display').css('display','block');
					$('#employee_name_display').css('display','block');
					$('#entered_by_display').css('display','block');
					$('#app_name_display').css('display','none');
					$('#employee_subject_display').css('display','none');
					$('#old_file_number_display').css('display','block');
					$('#category_name_display').css('display','none');
					
				}
				else {
					$('#employee_subject_display').css('display','block');
					$('#cnic_display').css('display','none');
					$('#employee_name_display').css('display','none');
					$('#entered_by_display').css('display','block');
					$('#app_name_display').css('display','block');
					$('#category_name_display').css('display','block');
					$('#old_file_number_display').css('display','block');
				}
			}
			<?php } ?>
		$(function () {
			$('#scanning_table').DataTable({
				"processing": true,
				"serverSide": true,
				"searching": false,
				"autoWidth": true,
				
				"ajax": {
					"url": "<?php echo $base_url; ?>admin/scaning/get_scan_listing",
					"type": "POST",
					"data": function ( d ) {
						var top_search_like = {
								fd_employee_cnic: $('#fd_employee_cnic').val(),
								fd_employee_name: $('#fd_employee_name').val(),
								fd_subject: $('#fd_subject').val(),
								fd_old_file_number: $('#fd_old_file_number').val(),
							};
						
						var top_search = {
								fd_general_category_id: $('#gnc_general_category_id').val(),
								fd_file_type_id: $('#ft_file_type_id').val(),
								fd_created_by: $('#fd_created_by').val(),
								fd_section_id: $('#ft_section_id').val(),
								fd_app_name: $('#fd_app_name').val(),
								//fd_app_name: $('#fd_app_name').val(),
							};
						
						d.action_btn = '<?php echo $action_btn; ?>';
						d.top_search_like = top_search_like;
						d.top_search = top_search;
						d.file_type_ids = $('#ft_file_type_id').val();
						d.dt_column = '<?php echo $dt_column; ?>';
						d.db_column = '<?php echo $db_column; ?>';
						//d.section_name = '<?php echo $user_section; ?>';
					}
				},
				"order": [[ 0, "desc" ]],
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
		function search_category_name(){
			var file_type = $("#ft_file_type_id").val();
			$(".loading-image-area").show();
			$('#gnc_general_category_id').html('<option value=""> Select Category Name </option>');
			$.ajax({
            url: '<?php echo $base_url; ?>admin/scaning/search_category_name',
            type: 'POST',
			dataType: 'JSON',
            data: {file_type_id:file_type},
		    success: function(response) {
				var category_name = $('#gnc_general_category_id');
                console.log(response);	
				if(response != ''){
					$.each(response.general_category, function (i, fb) {
							console.log(fb.isExtraCategory);
							
							<?php 
								$style = 'style="color:#000000;background-color:#FFFFFF"';
								$current_user_id = $this->flexi_auth->get_user_id();
								$extra = in_array($current_user_id,$this->config->item("showExtraCategoriesToIds"));
								//echo $extra;
							?>
							<?php if(in_array($current_user_id,$this->config->item("showExtraCategoriesToIds"))) { ?>
							if(fb.isExtraCategory==1){ 
								category_name.append('<option value="' + fb.generalCategoryId + '"style="color:#FFFFFF;background-color:#00641D">' + fb.generalCategoryName + '</option>');
							}
							<?php } ?>
							if(fb.isExtraCategory!=1){
								category_name.append('<option value="' + fb.generalCategoryId + '"style="color:#000000;background-color:#FFFFFF"">' + fb.generalCategoryName + '</option>');
							}
							
						});
			    $(".loading-image-area").hide();
				}
            },
            error: function () {
                console.log('Error in retrieving Site.');
            }
        });
		}
	</script>
	
	<style>
		.valid {
			display: none;
		}
	</style>
	
	
	
