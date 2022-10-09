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
                            <?php echo $page_title?>
						</div>
                        <div class="panel-body">
							<?php
								$attributes = array('role' => 'cat_form', 'id' => 'search_form');
								//echo form_open($base_url.'admin/search/product_search', $attributes);
								echo form_open(current_url(), $attributes);
							?>
							<?php if($section_name) { ?>
							<h2 class="text-center"><?php echo $section_name ?></h2>
							<?php if(!isset($set_manage_count) && !$set_manage_count) { ?>
								<div class="col-sm-2 pull-right">
									<a href="<?php echo base_url().'admin/general/set_manage_section_count/'.$section_id_post_session; ?>" class="btn btn-dark-beige pull-right col-sm-12">Correct Count</a>
								</div>
							<?php } ?>
							<?php 
							} else {  ?>
							<div class="row">
								<div class="form-group">
									<div class="col-sm-3"></div>
									<div class="col-sm-1 control-label"><label>Section</label></div>
									<div class="col-sm-4">
										<select name="section_id" id="section_id" class="form-control"  >
											<option value="">-----Select Section-----</option>
											<?php
											foreach ($session_section as $get_all_sections) {
												$select = ($get_all_sections['sectionId'] == set_value('section_id')) ? 'selected="selected"' : set_select('section_id', $get_all_sections['sectionId']);
												echo '<option '.$select.' value="'.$get_all_sections['sectionId'].'">'.$get_all_sections['sectionName'].'</option>';				
											} 
											?>
										</select>
									</div>
									<div class="col-sm-1"><input type="button" id="search_btn" class="btn btn-primary pull-right" value="Search" onclick="this.form.submit();" /></div>
									<?php if($section_id_post_session != 0) { ?>
										<div class="col-sm-2">
											<a href="<?php echo base_url().'admin/general/set_manage_section_count/'.$section_id_post_session; ?>" class="btn btn-dark-beige pull-right col-sm-12">Correct Count</a>
										</div>
									<?php } ?>
									<div class="col-sm-1"></div>
								</div>
								
							</div>
							<?php
							}  
							?>
							
							<?php 
							if($section_id_post_session != 0){
							?>
                            <div class="row" style="padding-top:50px;"></div>
							<div class="row">
								<?php
								foreach($file_types as $key => $file_type) { 
									//foreach ($manage_count_table_data as $get_manage_count) { 
									//if($file_type['fileTypeId'] == $get_manage_count['fileTypeId']){
								?>
									<div class="col-sm-12">
										<div class="col-sm-<?php echo (isset($set_manage_count) && $set_manage_count) ? '2' : '3'; ?>">
											<div class="form-group">
												<label>File Type</label>
												<div class="input-group col-sm-12">
													<select name="file_type_id[<?php echo $key+1; ?>]" id="file_type_id_<?php echo $key+1; ?>" class="form-control">
														<?php
															echo '<option selected value="'.$file_type['fileTypeId'].'">'.$file_type['fileType'].'</option>';
														?>
													</select>
												</div>
											</div>
										</div>
										<?php if(!isset($set_manage_count) && !$set_manage_count) { ?>
											<div class="col-sm-3">
												<div class="form-group">
													<label>Start Date</label>
													<div class="input-group col-sm-12">
														<input type="text" id="start_date_<?php echo $key+1; ?>" name="start_date[<?php echo $key+1; ?>]" value="<?php echo (isset($manage_count_table_data) && isset($manage_count_table_data[$file_type['fileTypeId']]['startDate']) && $manage_count_table_data[$file_type['fileTypeId']]['startDate'] != '0000-00-00') ? date(('d-m-Y'),strtotime($manage_count_table_data[$file_type['fileTypeId']]['startDate'])) : ""; ?>" class="form-control datepicker" autocomplete="off"/>
													</div>
												 </div>
											</div>
											<div class="col-sm-3">
												<div class="form-group">
													<label>End Date</label>
													<div class="input-group col-sm-12">
														<input type="text" id="end_date_<?php echo $key+1; ?>" name="end_date[<?php echo $key+1; ?>]" value="<?php echo (isset($manage_count_table_data) && isset($manage_count_table_data[$file_type['fileTypeId']]['endDate']) && $manage_count_table_data[$file_type['fileTypeId']]['endDate'] != '0000-00-00') ? date(('d-m-Y'),strtotime($manage_count_table_data[$file_type['fileTypeId']]['endDate'])) : ""; ?>" class="form-control datepicker" autocomplete="off"/>
													</div>
												 </div>
											</div>
										<?php } else { ?>
											<input type="hidden" name="start_date[<?php echo $key+1; ?>]" id="start_date_<?php echo $key+1; ?>" value="<?php echo (isset($manage_count_table_data) && isset($manage_count_table_data[$file_type['fileTypeId']]['startDate']) && $manage_count_table_data[$file_type['fileTypeId']]['startDate'] != '0000-00-00') ? date(('d-m-Y'),strtotime($manage_count_table_data[$file_type['fileTypeId']]['startDate'])) : ""; ?>" />
											<input type="hidden" name="end_date[<?php echo $key+1; ?>]" id="end_date_<?php echo $key+1; ?>" value="<?php echo (isset($manage_count_table_data) && isset($manage_count_table_data[$file_type['fileTypeId']]['endDate']) && $manage_count_table_data[$file_type['fileTypeId']]['endDate'] != '0000-00-00') ? date(('d-m-Y'),strtotime($manage_count_table_data[$file_type['fileTypeId']]['endDate'])) : ""; ?>" />
										<?php } ?>
											
										<?php if(isset($set_manage_count) && $set_manage_count) { ?>
											<div class="col-sm-2">
												<div class="form-group">
													<label>Entered Records</label>
													<div class="input-group col-sm-12">
														<input readonly type="text" rownum="<?php echo $key+1; ?>" id="entered_count_<?php echo $key+1; ?>" name="entered_count[<?php echo $key+1; ?>]" value="<?php echo $manage_count_table_data[$file_type['fileTypeId']]['entered_count']; ?>" class="form-control subtract_count" onblur=""/>
													</div>
												 </div>
											</div>
											<div class="col-sm-1">
												<div class="form-group">
													<div style="font-size: 20px; margin-top: 22px; padding: 0 25px;">+</div>
												 </div>
											</div>
										<?php } ?>
											
										<div class="col-sm-<?php echo (isset($set_manage_count) && $set_manage_count) ? '2' : '3'; ?>">
											<div class="form-group">
												<label>Total Count</label>
												<div class="input-group col-sm-12">
													<input <?php echo (isset($set_manage_count) && $set_manage_count) ? 'readonly' : '' ?> type="number" rownum="<?php echo $key+1; ?>" id="file_count_<?php echo $key+1; ?>" name="file_count[<?php echo $key+1; ?>]" value="<?php echo (isset($set_manage_count) && $set_manage_count) ? $manage_count_table_data[$file_type['fileTypeId']]['totalFileCount']-$manage_count_table_data[$file_type['fileTypeId']]['entered_count'] : $manage_count_table_data[$file_type['fileTypeId']]['totalFileCount']; ?>" class="form-control file-count subtract_count <?php echo (!isset($set_manage_count) && !$set_manage_count) ? 'calculate_total_count' : '';  ?>" onblur="<?php echo (!isset($set_manage_count) && !$set_manage_count) ? 'calculate_total_count()' : '';  ?>"/>
													
													<input type="hidden" name="file_count_old[<?php echo $key+1; ?>]" id="file_count_old_<?php echo $key+1; ?>" value="<?php echo $manage_count_table_data[$file_type['fileTypeId']]['totalFileCount']; ?>" />
												</div>
											 </div>
										</div>
										
										<?php if(isset($set_manage_count) && $set_manage_count) { ?>
											<div class="col-sm-1">
												<div class="form-group">
													<div style="font-size: 20px; margin-top: 22px; padding: 0 25px;">-</div>
												 </div>
											</div>
											<div class="col-sm-1">
												<div class="form-group">
													<label>Count</label>
													<div class="input-group col-sm-12">
														<input type="number" rownum="<?php echo $key+1; ?>" id="file_count_subtract_<?php echo $key+1; ?>" name="file_count_subtract[<?php echo $key+1; ?>]" value="0" class="form-control subtract_count valid-tooltip" onblur="" title="" data-placement="top"/>
													</div>
												 </div>
											</div>
											<div class="col-sm-1">
												<div class="form-group">
													<div style="font-size: 20px; margin-top: 22px; padding: 0 25px;">=</div>
												 </div>
											</div>
											<div class="col-sm-2">
												<div class="form-group">
													<label>Total Count</label>
													<div class="input-group col-sm-12">
														<input readonly rownum="<?php echo $key+1; ?>" type="text" id="total_file_count_<?php echo $key+1; ?>" name="total_file_count[<?php echo $key+1; ?>]" value="<?php echo $manage_count_table_data[$file_type['fileTypeId']]['totalFileCount']; ?>" class="form-control calculate_total_count" onblur="calculate_total_count()"/>
													</div>
												 </div>
											</div>
										<?php } ?>
									</div>
									<?php 
									//if($manage_count_table_data){  ?> 	
										<input type="hidden" name="manage_section_count[<?php echo $key+1; ?>]" id="manage_section_count" value="<?php echo ($manage_count_table_data) ? $manage_count_table_data[$file_type['fileTypeId']]['manageSectionCountId'] : ''; ?>" />
									<?php
									//}
									} 
									?>
							</div>
							<div class="row"><hr/></div>
							<div class="row">
								<div class="col-md-10"></div>
								<div class="col-md-2"><label style="font-weight:600;">Total Count</label>
								<span class="total_count" style="margin-left:20px; font-weight:600;" id="total_count" >0</span>
								</div>
							</div>
							
							<div class="col-sm-12">
								<input type="hidden" id="section_id_post_session" name="section_id_post_session" value="<?php echo $section_id_post_session; ?>" />
								<input type="submit" id="submit_btn" class="btn btn-primary pull-right" value="Submit" />
							</div>
							<?php
							}
							?>
							<?php echo form_close(); ?>
						
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
		calculate_total_count();
        jQuery(document).ready(function () {
			Main.init();
			//FormValidator.init();
		});
		function calculate_total_count(){
			var total_count = 0;
			$('.calculate_total_count').each(function() {
				
				//alert($(this).val());
				
				if($(this).val() != '' || $(this).val() != 0 ){
					total_count = parseInt(total_count) + parseInt($(this).val());
				}
			});
			$("#total_count").text(total_count);
		}
		
		//var FormValidator = function () {
			
		// function to initiate category    
		//var search_form = function () {
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
				<?php
				
				if($section_id_post_session != 0){
					foreach($file_types as $key => $file_type) {
					
						?>
						<?php /* ?>
						'start_date[<?php echo $key+1; ?>]': {
							required : function(element) {
								if($("#start_date_<?php echo $key+1; ?>").val() != "") {
										return true;
									} 
									else{
										return false;
									}
								}
						
							required: true
						},
						
						'end_date[<?php echo $key+1; ?>]': {
							required: true
						},
						<?php */ ?>
						'file_count[<?php echo $key+1; ?>]': {
							required : function(element) {
							
									var check_value = true
							
									$(".file-count").each(function( i ) {
									
										
										//alert(i);
										if($(this).val() != '') {
											$('.form-group').removeClass('has-error');
											$('.form-group').addClass('has-success');
											$('.help-block').addClass('valid');
											$('.help-block').hide();
											check_value = false
										}
										
										
										
									});
									
									return check_value;
								}
						},
						<?php
					
					}
				}
				
				?>
				
            },
            messages: {
				<?php
				
				if($section_id_post_session != 0){
					foreach($file_types as $key => $file_type) {
					
						?>
						<?php /* ?>
						'start_date[<?php echo $key+1; ?>]': "Please enter start date",
						'end_date[<?php echo $key+1; ?>]': "Please enter end date",
						<?php */ ?>
						'file_count[<?php echo $key+1; ?>]': "Please enter count",
						<?php
					
					}
				}
				
				?>
				
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
    //};
    
    /*return {
        //main function to initiate pages
        init: function () {
            search_form();
        }
    };
}();*/

	$('.subtract_count').keyup(function() {
		
		var row_num = $(this).attr('rownum');
		$('#file_count_subtract_'+row_num).tooltip('destroy');
		
		var entered_count = parseInt($('#entered_count_'+row_num).val());
		var file_count = parseInt($('#file_count_'+row_num).val());
		var file_count_subtract = parseInt($('#file_count_subtract_'+row_num).val());
		
		if(file_count >= file_count_subtract) {
			var total = entered_count + file_count - file_count_subtract;
			$('#total_file_count_'+row_num).val(total);
		}
		else {
			$('#file_count_subtract_'+row_num).tooltip({title: "Value Must be in Between (0,"+file_count+')'});
			$('#file_count_subtract_'+row_num).tooltip('show');
			$('#file_count_subtract_'+row_num).val('');
			$('#total_file_count_'+row_num).val(file_count+entered_count);
			//$('[data-toggle="tooltip"]').tooltip();
		}
		
		calculate_total_count()
		
	})

	</script>
	
	<style>
		
		.valid-tooltip + .tooltip > .tooltip-inner {background-color: #f00; width: 200px;}
		
	</style>
	
	
	
