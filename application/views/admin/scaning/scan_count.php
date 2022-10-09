<style>
.dashboard_box_icon {
	border-radius: 4px;
	color: #fff;
	padding: 10px;
	margin: 0 7px 10px 0;
	overflow: hidden;
}
.dashboard_box_img {
	float: left;
	padding: 10px 6px;
}
.dashboard_box_cont {
	float: right;
	text-align: right;
	font-size: 19px;
	padding: 10px 6px;
}
.blue_bg{padding:2px 15px; background:#3396b3;}
.green_bg{padding:2px 15px; background:#419041;}
.yellow_bg{padding:2px 15px; background:#bd7d21;}
.dashboard_box_cont h4 {
	font-size: 22px;
	padding: 0;
	margin: 0;
	color: #fff;
}
.bg1 {
	background: #5bc0de;
}
.bg2 {
	background: #5cb85c;
}
.bg3 {
	background: #f0ad4e;
}
</style>

<div class="row">
  <div class="col-sm-4">
    <div class="dashboard_box_icon bg1">
      <div class="dashboard_box_img"><img src="<?php echo base_url();?>includes/images/total_icon.png" class="img-responsive"  alt=""></div>
      <div class="dashboard_box_cont">
      <span class="blue_bg">
        <?php
				$total_scanned = 0;
				if($total_number_of_files_to_be_scan['total_file_to_be_scan'] >= 1) {
					$total_scanned = $total_files_scanned['total_file_scanned'].'/'.$total_number_of_files_to_be_scan['total_file_to_be_scan'];
				}
				?>
        <?php echo $total_scanned; ?>
        </span>
        <h4>Total Scanned</h4>
        <?php echo $user_section;?></div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="dashboard_box_icon bg2">
      <div class="dashboard_box_img"><img src="<?php echo base_url();?>includes/images/pending_icon.png" class="img-responsive"  alt=""></div>
      <div class="dashboard_box_cont">
      <span class="green_bg">
        <?php
				$to_be_scanned = 0;
				if($total_number_of_files_to_be_scan['total_file_to_be_scan'] >= 1) {
					$to_be_scanned = $total_number_of_files_to_be_scan['total_file_to_be_scan']-$total_files_scanned['total_file_scanned'];
				}
				?>
        <?php echo $to_be_scanned; ?>
        </span>
        <h4>To be Scanned</h4>
        <?php echo $user_section;?></div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="dashboard_box_icon bg3">
      <div class="dashboard_box_img"><img src="<?php echo base_url();?>includes/images/today_icon.png" class="img-responsive"  alt=""></div>
      <div class="dashboard_box_cont">
       <span class="yellow_bg">
        <?php if($today_scanning['total_file_scanned_today']!=""){echo $today_scanning['total_file_scanned_today'];} else { echo "0"; }?>
</span>
        <h4>Today's Scanned</h4>
        <?php echo $user_section;?></div>
    </div>
  </div>
</div>
