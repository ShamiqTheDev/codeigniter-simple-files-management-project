<!-- start: MAIN JAVASCRIPTS -->
<!--[if lt IE 9]>
<script src="assets/plugins/respond.min.js"></script>
<script src="assets/plugins/excanvas.min.js"></script>
<script type="text/javascript" src="assets/plugins/jQuery-lib/1.10.2/jquery.min.js"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
<?php
if (!isset($output->js_files)) { ?>
<script src="<?php echo $includes_dir; ?>admin/plugins/jQuery-lib/2.0.3/jquery.min.js"></script>
<?php } ?>
<!--<![endif]-->
<script src="<?php echo $includes_dir; ?>admin/plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/blockUI/jquery.blockUI.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/iCheck/jquery.icheck.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/perfect-scrollbar/src/jquery.mousewheel.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/perfect-scrollbar/src/perfect-scrollbar.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/less/less-1.5.0.min.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/jquery-cookie/jquery.cookie.js"></script>
<script src="<?php echo $includes_dir; ?>admin/plugins/bootstrap-colorpalette/js/bootstrap-colorpalette.js"></script>
<script src="<?php echo $includes_dir; ?>admin/js/main.js"></script>
<!-- end: MAIN JAVASCRIPTS -->