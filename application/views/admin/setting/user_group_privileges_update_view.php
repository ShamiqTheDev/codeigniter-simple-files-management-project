
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
                        <h1 class="col-sm-6">Manage User Account <small></small></h1>
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
                                Manage User Account 
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
                                <div class="form-group">
                                    <div class="col-sm-2 pull-left">
                                        <a href="<?php echo $base_url;?>auth_admin/manage_user_groups" class="btn btn-info ladda-button">Manage User Groups</a>
                                    </div>
                                    <div class="col-sm-2 pull-left">
                                        <a href="<?php echo $base_url;?>auth_admin/update_user_group/<?php echo $group['ugrp_id']; ?>" class="btn btn-info ladda-button">Update User Group</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- end: TEXT FIELDS PANEL -->
                </div>
            </div>
            <!-- end: PAGE CONTENT-->

            <!-- start: PAGE CONTENT -->
            <div class="row">
                <div class="col-md-12">
                    <!-- start: BASIC TABLE PANEL -->
                    <?php echo form_open(current_url()); ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-external-link-square"></i>
                            User Account
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
                                        <th class="tooltip_trigger"
                                            title=""/>
                                        Privilege Name
                                        </th>
                                        
                                        <th class="spacer_150 align_ctr tooltip_trigger"
                                            title=""/>
                                        User Has Privilege
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($privileges as $privilege) { ?>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="update[<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')]; ?>][id]" value="<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')]; ?>"/>
                                                <?php echo "<b>".$privilege[$this->flexi_auth->db_column('user_privileges', 'name')]."</b>"; ?>
                                            </td>
                                            <!--<td><?php //echo $privilege[$this->flexi_auth->db_column('user_privileges', 'description')]; ?></td>-->
                                            <td class="align_ctr">
                                                <?php
                                                // Define form input values.
                                                $current_status = (in_array($privilege[$this->flexi_auth->db_column('user_privileges', 'id')], $group_privileges)) ? 1 : 0;
                                                $new_status = (in_array($privilege[$this->flexi_auth->db_column('user_privileges', 'id')], $group_privileges)) ? 'checked="checked"' : NULL;
                                                ?>
                                                <input type="hidden" name="update[<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')]; ?>][current_status]" value="<?php echo $current_status ?>"/>
                                                <input type="hidden" name="update[<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')]; ?>][new_status]" value="0"/>
                                                <input type="checkbox" id="parent_<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')]; ?>" name="update[<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')]; ?>][new_status]" value="1" class="delete_group" <?php echo $new_status ?>/>
                                            </td>
                                        </tr>
                                        
                                        
                                        <?php if (isset($privilege['child_menu']) && (sizeof($privilege['child_menu']) > 0)) { ?>
                                        <?php foreach ($privilege['child_menu'] as $keys => $value) { ?> 
                                        
                                        <tr>
                                            <td>
                                                <input type="hidden" name="update[<?php echo $value[$this->flexi_auth->db_column('user_privileges', 'id')]; ?>][id]" value="<?php echo $value[$this->flexi_auth->db_column('user_privileges', 'id')]; ?>"/>
                                                <?php echo "&nbsp;&nbsp;&nbsp;".$value[$this->flexi_auth->db_column('user_privileges', 'name')]; ?>
                                            </td>
                                            <td class="align_ctr">
                                                <?php
                                                // Define form input values.
                                                $current_status = (in_array($value[$this->flexi_auth->db_column('user_privileges', 'id')], $group_privileges)) ? 1 : 0;
                                                $new_status = (in_array($value[$this->flexi_auth->db_column('user_privileges', 'id')], $group_privileges)) ? 'checked="checked"' : NULL;
                                                ?>
                                                <input type="hidden" name="update[<?php echo $value[$this->flexi_auth->db_column('user_privileges', 'id')]; ?>][current_status]" value="<?php echo $current_status ?>"/>
                                                <input type="hidden" name="update[<?php echo $value[$this->flexi_auth->db_column('user_privileges', 'id')]; ?>][new_status]" value="0"/>
                                                <input type="checkbox" get_id="<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')]; ?>" class="delete_group child_<?php echo $privilege[$this->flexi_auth->db_column('user_privileges', 'id')]; ?>" name="update[<?php echo $value[$this->flexi_auth->db_column('user_privileges', 'id')]; ?>][new_status]" value="1" class="delete_group" <?php echo $new_status ?>/>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">
                                            <input type="submit" name="update_group_privilege" value="Update Group Privileges" class="btn btn-blue delete"/>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                    <!-- end: BASIC TABLE PANEL -->
                </div>
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

<script>
    jQuery(document).ready(function () {
        Main.init();
        FormValidator.init();
        TableData.init();
        
      jQuery(":checkbox").click(function () {
            var parent_id = jQuery(this).attr('id');
            if(parent_id !== undefined){
                var splited_id = parent_id.split("_");
                var child_class = "child_" + splited_id[1];
                jQuery("." + child_class).prop('checked', this.checked);
                jQuery("#" + parent_id).prop('checked', this.checked);
            }else{
                var get_id = jQuery(this).attr('get_id');
                var child_count = jQuery(".child_"+get_id+":checked").size();
                if(child_count > 0){
                    jQuery("#parent_"+get_id).prop('checked', true);
                }
                else{
                    jQuery("#parent_"+get_id).prop('checked', false);
                }
            }    
        });
    });
</script>
