
<!-- start: MAIN CONTAINER -->
<div class="main-container">
    <div class="navbar-content">
        <!-- start: SIDEBAR -->
        <?php $this->load->view('admin/includes/sidebar'); ?>
        <!-- end: SIDEBAR -->
    </div>
    <!-- start: PAGE -->
    <div class="main-content">
        <!-- start: PANEL CONFIGURATION MODAL FORM -->
        <div class="modal fade" id="panel-config" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                            &times;
                        </button>
                        <h4 class="modal-title">Panel Configuration</h4>
                    </div>
                    <div class="modal-body">
                        Here will be a configuration form
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Close
                        </button>
                        <button type="button" class="btn btn-primary">
                            Save changes
                        </button>
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
                        <?php echo $this->breadcrumbs->show();  ?>
                    </ol>
                    <!-- start: Success and error message -->
                    <?php if (!empty($message)) { ?>
                        <div id="message">
                            <?php echo $message; ?>
                        </div>
                    <?php } ?>
                    <!-- end: Success and error message -->
                    <!--<div class="page-header">
                        <h1>Dashboard <small>overview &amp; stats </small></h1>
                    </div>-->
					<div style="margin-top:10px;">
						
						
						<?php
						if ($this->flexi_auth->is_privileged('Show Bulk Scanning Count'))
							$this->load->view('admin/scaning/scan_count');
						?>
						
						
						<?php
						if(($this->session->userdata('pass_expire_remaining_days') <= 10) && ($this->session->userdata('pass_expire_remaining_days') > 0)) {
							?>
							<div id="message" style="color: #a94442; border-color: #a94442;">Your password will expire in <?php echo $this->session->userdata('pass_expire_remaining_days'); ?> days. Please update your password</div>
							<?php
						}
						?>
					</div>
                    <!-- end: PAGE TITLE & BREADCRUMB -->
					<!-- start: Email Alerts -->
					<?php if ($this->flexi_auth->is_privileged('Show Inbox Alerts')) {?>
					<div class="row">
					<?php if($inbox_emails && !empty($inbox_emails)){ ?>
					
						<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="clip-bubble-4"></i>
									Alerts
								</div>
								<div class="panel-body panel-scroll ps-container ps-active-y" style="height:250px">
									<table class="table table-striped table-bordered table-hover table-full-width" id="receipt_table">
									<thead>
										<tr>
											<th>Subject</th>
											<th>Sent By</th>
											<th>Days Remaining</th>
										</tr>
									</thead>
									<?php foreach($inbox_emails as $key => $get_inbox_data){ ?>
									<tr>
										<td width="250px;">
											<?php if($get_inbox_data['sentModule']=='receipt'){ ?>
												<span class="desc" style="opacity: 1; text-decoration: none;"><a href="<?php echo base_url(); ?>admin/receipts/receipt_view/inbox/<?php echo $get_inbox_data['receiptDetailId'] ?>" target="_blank"><?php echo $get_inbox_data['subject']; ?></a></span>
											<?php }else if($get_inbox_data['sentModule']=='file'){ ?>
												<span class="desc" style="opacity: 1; text-decoration: none;"><a href="<?php echo base_url(); ?>admin/files/note_sheet_detail/inbox/<?php echo $get_inbox_data['fileId']; ?>" target="_blank"><?php echo $get_inbox_data['description']; ?></a></span>
											<?php } ?>
										</td>
										<td width="150px;">
											<?php if($get_inbox_data['designationId']!=''){ ?>
												<span class="desc" style="opacity: 1; text-decoration: none;"><?php echo $get_inbox_data['full_name']. "(".$get_inbox_data['designationName'].")" ; ?></span>
											<?php }else{ ?>
												<span class="desc" style="opacity: 1; text-decoration: none;"><?php echo $get_inbox_data['full_name']; ?></span>
											<?php } ?>
										</td>
										<td width="100px;">
											<?php
											
											$due_date = date_create(date('Y-m-d', strtotime($get_inbox_data['dueDate'])));
											$current_date = date_create(date('Y-m-d'));
											//$diff=date_diff($date1,$date2);
											$diff = date_diff($current_date, $due_date);
											//$diff=date_diff(, );
											$days = $diff->format("%R%a");
											
											//$dateDiff = date('d-m-Y', strtotime($get_inbox_data['dueDate'])) - date('d-m-Y') ; 
											if($days < 10 && $days >= 0){
												$label = "label-danger";
											}else if($days >= 10){
												$label = "label-success";
											}else if($days < 0){
												$label = "label-warning";
											}
											?>
											<span class="label <?php echo $label; ?>" style="opacity: 1;"> <?php echo ($days>=0) ? (($days == 0) ? "1" : $days) ." Days Remaining" : "Expired" ; ?> </span>
										</td>
									</tr>
									<?php } ?>
									</table>
								<div class="ps-scrollbar-x-rail" style="width: 416px; display: none; left: 0px; bottom: 3px;"><div class="ps-scrollbar-x" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; height: 250px; display: inherit; right: 3px;"><div class="ps-scrollbar-y" style="top: 0px; height: 215px;"></div></div></div>
							</div>
							</div>
				
					<?php }else{ ?>
							<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="clip-bubble-4"></i>
									Alerts
								</div>
								<div class="panel-body panel-scroll ps-container ps-active-y" style="height:250px">
									<h4 class="text-center" style="font-size:15px;font-weight:bold;color:#303030;fill:#303030;margin-top: 23%;">No data available</h4>
								<div class="ps-scrollbar-x-rail" style="width: 416px; display: none; left: 0px; bottom: 3px;"><div class="ps-scrollbar-x" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; height: 250px; display: inherit; right: 3px;"><div class="ps-scrollbar-y" style="top: 0px; height: 215px;"></div></div></div>
							</div>
							</div>
							
					<?php } ?>
					<?php } ?>
					<?php if ($this->flexi_auth->is_privileged('Show Today Receipts Scanned')) {?>
					
						<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="clip-bubble-4"></i>
									Today's Receipts Scanned
								</div>
								<div class="panel-body panel-scroll ps-container ps-active-y" style="height:250px">
								<?php if($today_receipt_scanned && !empty($today_receipt_scanned)){ ?>
									<table class="table table-striped table-bordered table-hover table-full-width" id="category_table">
									<thead>
										<tr>
											<th>Category</th>
											<th>Count</th>
										</tr>
									</thead>
									<?php foreach($today_receipt_scanned as $key => $get_today_receipt){ ?>
									<tr>
										<td width="250px;">
											<?php echo $key;  ?>
										</td>
										<td width="150px;">
											<?php echo $get_today_receipt['categoryCount'];  ?>
										</td>
										
									</tr>
									<?php } ?>
									</table>
									<?php }else{ ?>
										<h4 class="text-center" style="font-size:15px;font-weight:bold;color:#303030;fill:#303030;margin-top: 23%;">No data available</h4>
									<?php } ?>
								<div class="ps-scrollbar-x-rail" style="width: 416px; display: none; left: 0px; bottom: 3px;"><div class="ps-scrollbar-x" style="left: 0px; width: 0px;"></div></div><div class="ps-scrollbar-y-rail" style="top: 0px; height: 250px; display: inherit; right: 3px;"><div class="ps-scrollbar-y" style="top: 0px; height: 215px;"></div></div></div>
							</div>
							</div>
					
					
					<?php } ?>
					</div>
					<!-- end: Email Alerts-->
					<?php if ($this->flexi_auth->is_privileged('Show Bulk Scanning Count')){?>
					<div class="row">
						
						<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="clip-pie"></i>
									Month wise Scanned Files
								</div>
								<div class="panel-body">
									<div class="flot-medium-container">
										<div id="" style="min-width: 310px; margin: 0 auto">
                                            <div class="col-md-3" style="float:right;">
												<select name="scanned_year" id="scanned_year" class="form-control">
													<?php foreach ($scanned_year as $year=>$value) { 
													$selected = ($year == date("Y")) ? "selected='selected'" : "";	
													?>
                                                        <option value="<?php echo $year; ?>" <?php echo $selected?> ><?php echo $year; ?></option>
                                                    <?php } ?>
													
                                                </select>
                                            </div>
                                        </div>
										<div id="container_chart_month" style="min-width: 310px; height:320px; margin: 0 auto; font-weight:bold; color:#808080"></div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="clip-pie"></i>
									Today's Section wise Scanned Files
								</div>
								<div class="panel-body">
									<div class="flot-medium-container">
										<div id="container_pie_todays_section" style="min-width: 310px; height:350px; margin: 0 auto;font-weight:bold; color:#808080"></div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="clip-pie"></i>
									File Type wise Scanned Files
								</div>
								<div class="panel-body">
									<div class="flot-medium-container">
										<div id="container_donut_file_type" style="min-width: 310px; height:350px; margin: 0 auto;font-weight:bold; color:#808080"></div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-sm-6">
							<div class="panel panel-default">
								<div class="panel-heading">
									<i class="clip-pie"></i>
									Section wise Scanned Files
								</div>
								<div class="panel-body">
									<div class="flot-medium-container">
										<div id="container_chart_section" style="min-width: 310px; height:350px; margin: 0 auto;font-weight:bold; color:#808080"></div>
									</div>
								</div>
							</div>
						</div>
						
					</div>
					<?php } ?>
                </div>
            </div>
            
        </div>
    </div>
    <!-- end: PAGE -->
</div>
<!-- end: MAIN CONTAINER -->

<!-- statr: INCLUSE FOOTER -->
<?php $this->load->view('admin/includes/footer'); ?>
<!-- end: INCLUSE FOOTER -->

<!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/fullcalendar/fullcalendar/fullcalendar.css">
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/select2/select2.css" />
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/datatables/dataTables.bootstrap.css">
<!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->

<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->

<script src="<?php echo $includes_dir; ?>admin/plugins/select2/select2.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/datatables/media/js/DT_bootstrap.js"></script>
<script src="<?php echo $includes_dir; ?>admin/js/table-data.js"></script>


<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/js/highcharts_v5.0.9.js"></script>
<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/js/highcharts_data.js"></script>
<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/js/highcharts_exporting.js"></script>
<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/js/highcharts_drilldown.js"></script>
<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/js/no-data-to-display.js"></script>
<?php /* ?><script src="<?php echo $includes_dir; ?>admin/js/highcharts.js"></script><?php */ ?>
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->


<script>
	var base_url = '<?php echo base_url(); ?>';
	
    jQuery(document).ready(function () {
		Main.init();
        TableData.init();
        jQuery("#dashboard_paginate").hide();
    });
</script>
<script>

	function getAjaxData(container_id,point_name,point_id,point_url,graph,drilldown){
		
		$("#"+container_id).text("Loading...");
		
		$.ajax({
			url: "<?php echo base_url(); ?>admin/ajax_dashboard/" + point_url,
			type: "post", 
			dataType: "json",
			data: {
					name: point_name, id: point_id, graph_type: graph, more_drilldown:drilldown
				},
			success: function(response) {
				console.log(response);
				if(graph == "bar"){
					getBarGraph(container_id,response);
				}
				else if(graph == "pie" || graph == "donut"){
					getPieGraph(container_id,response,drilldown);
				}
			},
			error: function(response) {
				//Do Something to handle error
				console.log(response);
			}
		});
	}
	
	$("#scanned_year").change(function () {
		getMonthGraph();
	});
	
	function getMonthGraph(){
		var month_container_id = "container_chart_month";
		var year = $("#scanned_year").val();
		var url_name = "getMonthWiseEntries";
		var drilldown = 1;
		var graph_type = "bar";
		
		getAjaxData(month_container_id,year,year,url_name,graph_type,drilldown);
	}
	
	function getSectionGraph(){
		var section_container_id = "container_chart_section";
		var day_month_year = "00|00|0000"; // assign 0 values means no date filter
		var url_name = "getSectionEntries";
		var drilldown = 1;
		var graph_type = "bar";
		
		getAjaxData(section_container_id,day_month_year,day_month_year,url_name,graph_type,drilldown);
	}
	
	function getTodaysSectionEntries(){
		var today_entries_container_id = "container_pie_todays_section";
		
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();

		var day_month_year = dd+"|"+mm+"|"+yyyy; 
		var drilldown = 0;
		var graph_type = "pie";
		
		var url_name = "getSectionEntries";
		
		getAjaxData(today_entries_container_id,day_month_year,day_month_year,url_name,graph_type,drilldown);
	}
	
	function getFileTypeEntries(){
		var file_type_container_id = "container_donut_file_type";
		var section_day_month_year = "0|00|00|0000"; // assign 0 values means no date filter
		var url_name = "getFileTypeEntries";
		var drilldown = 0;
		var graph_type = "donut";
		
		getAjaxData(file_type_container_id,section_day_month_year,section_day_month_year,url_name,graph_type,drilldown);
	}
	
	<?php if ($this->flexi_auth->is_privileged('Show Bulk Scanning Count')){?>
		getMonthGraph();
		getTodaysSectionEntries();
		getFileTypeEntries();
		getSectionGraph();
	<?php } ?>

</script>
<script src="<?php echo $includes_dir; ?>admin/js/highcharts_custom.js"></script>

<style>
.hovering{
	cursor:pointer;
	text-decoration:underline;
	
}
</style>