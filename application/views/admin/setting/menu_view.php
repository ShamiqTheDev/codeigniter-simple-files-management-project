<!-- start: MAIN CONTAINER -->
<div class="main-container">
    <div class="navbar-content">
        <!-- start: SIDEBAR -->
        <?php $this->load->view('admin/includes/sidebar'); ?>
        <!-- end: SIDEBAR -->
    </div>
    <!-- start: PAGE -->
    <div class="main-content">

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
                    <?php if (!empty($message)) { ?>
                        <div id="message">
                            <?php echo $message; ?>
                        </div>
                    <?php } ?>
                    <!-- end: Success and error message -->
                    <div class="page-header row">
                        <h1 class="col-sm-6"><?php echo ($page_title == "Manage Privilege") ? "Manage Privilege" : "Manage Menu"; ?> <small></small></h1>
                    </div>
                    <!-- end: PAGE TITLE & BREADCRUMB -->
                </div>
            </div>
            <!-- end: PAGE HEADER -->

            <!-- start: PAGE CONTENT -->
            <div class="row">
                <div class="col-sm-12">
                    <!-- start: TEXT FIELDS PANEL -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-external-link-square"></i>
                            <?php echo ($page_title == "Manage Privilege") ? "Add Privilege" : "Add Menu"; ?>
                            <!-- <div class="panel-tools">
                                <a class="btn btn-xs btn-link panel-collapse collapses" href="#">
                                </a>
                                <a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <a class="btn btn-xs btn-link panel-refresh" href="#">
                                    <i class="fa fa-refresh"></i>
                                </a>
                                <a class="btn btn-xs btn-link panel-expand" href="#">
                                    <i class="fa fa-resize-full"></i>
                                </a>
                                <a class="btn btn-xs btn-link panel-close" href="#">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div> -->

                        </div>
                        <?php if ($this->flexi_auth->is_privileged($add)) { ?>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-sm-2 pull-left">
                                        <a href="<?php echo $base_url . $add_link; ?>" class="btn btn-info ladda-button"><?php echo ($page_title == "Manage Privilege") ? "Add Privilege" : "Add Menu"; ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <!-- end: TEXT FIELDS PANEL -->
                </div>
            </div>
            <!-- end: PAGE CONTENT-->

            <!-- start: PAGE CONTENT -->
            <div class="row">
                <div class="col-md-12">
                    <!-- start: BASIC TABLE PANEL -->
                    <?php
                    $attributes = array('class' => 'form-horizontal', 'role' => 'cat_form', 'id' => 'items_form');
                    echo form_open(current_url(), $attributes);
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-external-link-square"></i>
                            <?php echo ($page_title == "Manage Privilege") ? "Privilege List" : "Menu List"; ?>
                            <!-- <div class="panel-tools">
                                <a class="btn btn-xs btn-link panel-collapse collapses" href="#">
                                </a>
                                <a class="btn btn-xs btn-link panel-config" href="#panel-config" data-toggle="modal">
                                    <i class="fa fa-wrench"></i>
                                </a>
                                <a class="btn btn-xs btn-link panel-refresh" href="#">
                                    <i class="fa fa-refresh"></i>
                                </a>
                                <a class="btn btn-xs btn-link panel-expand" href="#">
                                    <i class="fa fa-resize-full"></i>
                                </a>
                                <a class="btn btn-xs btn-link panel-close" href="#">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div> -->

                        </div>
                        <div class="panel-body">                                
                            <table class="table table-striped table-bordered table-hover table-full-width" id="">
                                <thead>
                                    <tr>
                                        <th width="5%">
                                            Delete
                                        </th>
                                        <th class="tooltip_trigger" 
                                            title="">
                                                <?php echo ($page_title == "Manage Privilege") ? "Privilege Name" : "Menu Name"; ?>
                                        </th>
                                        <th class="spacer_100 align_ctr tooltip_trigger" 
                                            title="">
                                                <?php echo ($page_title == "Manage Privilege") ? "Privilege Status" : "Menu Status"; ?>
                                        </th>
                                        <?php if ($page_title != "Manage Privilege") { ?>
                                            <th class="spacer_50 tooltip_trigger center" 
                                                title="">
                                                Action
                                            </th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php //echo "<pre>"; print_r($get_menu); exit;?>
                                    <?php
                                    foreach ($get_menus as $key => $val) {
                                        $privilege_child = 0; // if privilege have no child then it should not show the parent also.
                                        ?>
                                        <tr class="<?php echo $val['mu_id']; ?>">
                                            <td class="center">
                                                <?php if ($page_title != "Manage Privilege") { ?>
                                                    <input type="checkbox" id="parent_<?php echo $val['mu_id']; ?>" class="delete_group" name="delete_item[<?php echo $val['mu_id']; ?>]" /></td>
                                            <?php } ?>
                                            <td>
                                                <?php if ($page_title == "Manage Privilege") {
                                                    ?>
                                                    <b><?php echo $val['mu_title']; ?></b>
                                                <?php } else { ?>
                                                    <a href="<?php echo ($this->flexi_auth->is_privileged($update)) ? base_url() . $update_link . $val['mu_id'] : "#"; ?>">
                                                        <u><b><?php echo $val['mu_title']; ?></b></u>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if ($page_title != "Manage Privilege") { ?>
                                                    <?php echo ($val['mu_active'] == 1) ? "Enable" : "Disable"; ?>
                                                <?php } ?>
                                            </td>
                                            <?php if ($this->flexi_auth->is_privileged($update)) { ?>
                                                <?php if ($page_title != "Manage Privilege") { ?>
                                                    <td class="center">
                                                        <a href="<?php echo base_url() . $update_link . $val['mu_id']; ?>" class="edit_btn btn btn-xs btn-teal tooltips" data-placement="top" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                                    </td>
                                                <?php } ?>
                                            <?php } ?>

                                        </tr>
                                        <?php if (isset($val['child_menu']) && (sizeof($val['child_menu']) > 0)) { ?>
                                            <?php foreach ($val['child_menu'] as $keys => $value) { ?>    
                                                <?php
                                                if ($page_title == "Manage Privilege" && $value['mu_main_menu'] == 1 || $page_title != "Manage Privilege" && $value['mu_main_menu'] == 0) {
                                                    continue;
                                                } else {
                                                    $privilege_child++;
                                                }
                                                ?>
                                                <tr>
                                                    <td class="center"><input type="checkbox" class="delete_group child_<?php echo $val['mu_id']; ?>" name="delete_item[<?php echo $value['mu_id']; ?>]" /></td>
                                                    <td>
                                                        <?php if ($this->flexi_auth->is_privileged($update)) { ?>
                                                            &nbsp;&nbsp;&nbsp;
                                                            <a href="<?php echo base_url() . $update_link . $value['mu_id']; ?>">
                                                                <?php echo $value['mu_title']; ?>
                                                            </a>
                                                        <?php } else { ?>
                                                            <?php echo $value['mu_title']; ?>  
                                                        <?php } ?>
                                                    </td>
                                                    <td><?php echo ($value['mu_active'] == 1) ? "Enable" : "Disable"; ?></td>
                                                    <!--<td><?php //echo ($value['mu_main_menu'] == 1) ? "Yes" : "No";                                                                      ?></td>-->
                                                    <?php if ($this->flexi_auth->is_privileged($update)) { ?>
                                                        <?php if ($page_title != "Manage Privilege") { ?>
                                                            <td class="center">
                                                                <a href="<?php echo base_url() . $update_link . $value['mu_id']; ?>" class="edit_btn btn btn-xs btn-teal tooltips" data-placement="top" data-original-title="Edit"><i class="fa fa-edit"></i></a>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>

                                        <?php
                                        if ($privilege_child == 0 && $page_title == "Manage Privilege") { // to hide the parent menu if there is no child in the privileg
                                            $classes = $val['mu_id'];
                                            ?>
                                        <script src="<?php echo $includes_dir; ?>admin/plugins/jQuery-lib/2.0.3/jquery.min.js"></script>    
                                        <?php
                                        echo "<script>jQuery(document).ready(function(){jQuery('." . $classes . "').hide();})</script>";
                                    }
                                }
                                ?>
                                </tbody>
                                <tfoot>
                                <td colspan="3">
                                    <?php
                                    if ($this->flexi_auth->is_privileged($delete)) {
                                        $disable = (!$this->flexi_auth->is_privileged($update) && !$this->flexi_auth->is_privileged($delete)) ? 'disabled="disabled"' : NULL;
                                        ?>
                                        <input type="button" value="Delete checked <?php echo ($page_title == "Manage Privilege") ? "Privilege" : "Menu"; ?>" class="btn btn-red delete" <?php echo $disable; ?>/>
                                    <?php } ?>
                                </td>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- start: PANEL CONFIGURATION MODAL FORM -->
                    <div class="modal fade" id="panel-config" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        &times;
                                    </button>
                                    <h4 class="modal-title">Delete Confirmation</h4>
                                </div>
                                <div class="modal-body">
                                    Are you sure, you want to delete checked  <?php echo ($page_title == "Manage Privilege") ? "Privilege" : "Menu"; ?>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                        Close
                                    </button>
                                    <button type="button" onclick="form_submit()" class="btn btn-red btn-primary">
                                        Delete
                                    </button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                    <!-- /.modal -->
                    <?php echo form_close(); ?>
                    <!-- end: BASIC TABLE PANEL -->


                </div>
                <!-- end: PAGE CONTENT-->
            </div>
        </div>
        <!-- end: PAGE -->
    </div>
    <!-- end: MAIN CONTAINER -->

    <!-- statr: INCLUSE FOOTER -->
    <?php $this->load->view('admin/includes/footer'); ?>
    <!-- end: INCLUSE FOOTER -->

    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
    <link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/select2/select2.css" />
    <link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/datatables/media/css/DT_bootstrap.css" />
    <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->

    <!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
    <script src="<?php echo $includes_dir; ?>admin/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="<?php echo $includes_dir; ?>admin/js/form-validation-js.js"></script>
    <script type="text/javascript" src="<?php echo $includes_dir; ?>admin/plugins/select2/select2.min.js"></script>
    <script type="text/javascript" src="<?php echo $includes_dir; ?>admin/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?php echo $includes_dir; ?>admin/plugins/datatables/media/js/DT_bootstrap.js"></script>
    <script src="<?php echo $includes_dir; ?>admin/js/table-data.js"></script>
    <!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->

    <!-- Modal CSS AND JS  -->
    <link href="<?php echo $includes_dir; ?>admin/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo $includes_dir; ?>admin/plugins/bootstrap-modal/js/bootstrap-modal.js"></script>
    <script src="<?php echo $includes_dir; ?>admin/plugins/bootstrap-modal/js/bootstrap-modalmanager.js"></script>
    <script src="<?php echo $includes_dir; ?>admin/js/ui-modals.js"></script>

    <script>
                                        jQuery(document).ready(function () {
                                            Main.init();
                                            FormValidator.init();
                                            TableData.init();
                                            jQuery(":checkbox").click(function () {
                                                var parent_id = jQuery(this).attr('id');
                                                if (typeof parent_id != 'undefined') {
                                                    var splited_id = parent_id.split("_");
                                                    var child_class = "child_" + splited_id[1];
                                                    jQuery("." + child_class).prop('checked', this.checked).attr("disabled", true);
                                                    if (!(this.checked)) {
                                                        jQuery("." + child_class).removeAttr("disabled");
                                                    }
                                                }

                                            });

                                            jQuery(".delete").click(function () {
                                                jQuery('#panel-config').modal('show');
                                            });

                                        });

                                        function form_submit() {
                                            document.getElementById("items_form").submit();
                                        }
    </script>
