
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
                <div class="col-md-12">
                    <!-- start: BASIC TABLE PANEL -->
                    <?php
                    $attributes = array('class' => 'form-horizontal', 'role' => 'cat_form', 'id' => 'search_form');
                    echo form_open(current_url(), $attributes);
                    ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-external-link-square"></i>
                                User Account
                                <!--<div class="panel-tools">
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
                                </div>-->
                            </div>
                            <div class="panel-body">
                                <table class="table table-striped table-bordered table-hover table-full-width" id="sample_1">
                                    <thead>
                                        <tr>
                                            <th class="spacer_200">Email</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th class="spacer_125 align_ctr tooltip_trigger"
                                                title="Indicates the user group the user belongs to.">
                                                User Group
                                            </th>
                                            <th class="spacer_125 align_ctr tooltip_trigger" 
                                                title="Indicates whether the users account is currently set as 'active'.">
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <?php if (!empty($users)) { ?>
                                        <tbody>
                                            <?php foreach ($users as $user) { ?>
                                                <tr>
                                                    <td>
                                                        <a href="<?php echo $base_url; ?>auth_admin/update_user_account/<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')]; ?>">
                                                            <?php echo $user[$this->flexi_auth->db_column('user_acc', 'email')]; ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <?php echo $user['upro_first_name']; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $user['upro_last_name']; ?>
                                                    </td>
                                                    <td class="align_ctr">
                                                        <?php echo $user[$this->flexi_auth->db_column('user_group', 'name')]; ?>
                                                    </td>
                                                    <td class="align_ctr">
                                                        <?php echo ($user[$this->flexi_auth->db_column('user_acc', 'active')] == 1) ? 'Active' : 'Inactive'; ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5">
                                                    <input type="submit" name="delete_unactivated" value="Delete Listed Users" class="btn btn-blue"/>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    <?php } else { ?>
                                        <tbody>
                                            <tr>
                                                <td colspan="5" class="highlight_red">
                                                    No users are available.
                                                </td>
                                            </tr>
                                        </tbody>
                                    <?php } ?>
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
    });
</script>
