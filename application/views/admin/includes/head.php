<title><?php echo $page_title; ?></title>
<!-- start: META -->
<meta charset="utf-8" />
<!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta content="" name="description" />
<meta content="" name="author" />
<!-- end: META -->
<!-- start: MAIN CSS -->
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/fonts/style.css">
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/css/main.css">
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/css/main-responsive.css">
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/iCheck/skins/all.css">
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/bootstrap-colorpalette/css/bootstrap-colorpalette.css">
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/perfect-scrollbar/src/perfect-scrollbar.css">
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/css/theme_light.css" type="text/css" id="skin_color">
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/css/print.css" type="text/css" media="print"/>

<?php // Grocery Crud Files
if (isset($output->css_files)) {
    foreach ($output->css_files as $file):
        ?>
        <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach; ?>
    <?php foreach ($output->js_files as $file): ?>
        <script src="<?php echo $file; ?>"></script>
    <?php
    endforeach;
}
?>


<!--[if IE 7]>
<link rel="stylesheet" href="assets/plugins/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<!-- end: MAIN CSS -->

<!--<link rel="shortcut icon" href="favicon.ico" />-->