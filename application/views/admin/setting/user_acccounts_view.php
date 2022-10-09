
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
                        <h1 class="col-sm-6">Manage Users Account <small></small></h1>
                        <!-- start: ADD NEW CATEGORY -->
                        <div class="col-md-2 pull-right">
                            <a href="<?php echo base_url(); ?>auth_admin/register_account" class="btn btn-teal" >Add new User</a>
                        </div>
                        <!-- end: ADD NEW CATEGORY -->
                    </div>
                    <!-- end: PAGE TITLE & BREADCRUMB -->
                </div>
            </div>
            <!-- end: PAGE HEADER -->
            
            <!-- start: PAGE CONTENT -->
            <div class="row">
                <div class="col-sm-12">
                    <!-- start: TEXT FIELDS PANEL -->
                    <?php
                    $attributes = array('class' => 'form-horizontal', 'role' => 'cat_form', 'id' => 'search_form');
                    echo form_open(current_url(), $attributes);
                    ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-external-link-square"></i>
                                Search
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
                                    <label class="col-sm-3 control-label" for="form-field-1">
                                        Search Users
                                    </label>
                                    <div class="col-sm-7">
                                        <?php
                                        $input_data = array(
                                                'type'          => 'text',
                                                'name'          => 'search_query',
                                                'id'            => 'search',
                                                'value'         => set_value('search_users', $search_query),
                                                'class'         => 'form-control',
                                                'placeholder'   => ''
                                        );

                                        echo form_input($input_data);
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-2 pull-right">
                                        <a href="<?php echo $base_url; ?>auth_admin/manage_user_accounts" class="btn btn-info ladda-button">Reset</a>
                                    </div>
                                    <div class="col-sm-2 pull-right">
                                        <input type="hidden" name="search_users" value="Search"/>
                                        <button id="search_btn" class="btn btn-info btn-block" type="submit">
                                            Search <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php echo form_close(); ?>
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
                                <table class="table table-striped table-bordered table-hover table-full-width" id="sample_1">
                                    <thead>
                                        <tr>
                                            <th class="spacer_200">Email</th>
                                            <th class="spacer_200">Username</th>
                                            <th>First Name</th>
                                            <th>Last Name</th>
                                            <th class="spacer_100 align_ctr tooltip_trigger"
                                                title="Indicates the user group the user belongs to.">
                                                User Group
                                            </th>
											<th>Section</th>
											
                                            <th class="spacer_100 align_ctr tooltip_trigger"
                                                title="If checked, the users account will be locked and they will not be able to login.">
                                                Account Suspended
                                            </th>
                                            <th class="spacer_100 align_ctr tooltip_trigger" 
                                                title="If checked, the row will be deleted upon the form being updated.">
                                                Delete
                                            </th>
                                        </tr>
                                    </thead>
                                    <?php if (!empty($users)) { //print_r($users);exit;?>
                                        <tbody>
                                            <?php foreach ($users as $user) { ?>
                                                <tr>
                                                    <td>
                                                        <a href="<?php echo $base_url . 'auth_admin/update_user_account/' . $user[$this->flexi_auth->db_column('user_acc', 'id')]; ?>">
                                                            <?php echo $user[$this->flexi_auth->db_column('user_acc', 'email')]; ?>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <?php echo $user[$this->flexi_auth->db_column('user_acc', 'username')]; ?>
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
														<div class="tagsinput">
															<?php
															if($user['section_name']) {
																$section_name = explode(',', $user['section_name']);
																foreach($section_name as $get_section_name) {
																	?>
																		<span class="tag"><?php echo $get_section_name; //$user['uacc_section_fk'];//$user[$this->flexi_auth->db_column('section', 'sectionName')]; ?></span>
																	<?php
																}
															}
															?>			
														</div>
                                                    </td>
													
                                                    <td class="align_ctr">
                                                        <input type="hidden" name="current_status[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')]; ?>]" value="<?php echo $user[$this->flexi_auth->db_column('user_acc', 'suspend')]; ?>"/>
                                                        <!-- A hidden 'suspend_status[]' input is included to detect unchecked checkboxes on submit -->
                                                        <input type="hidden" name="suspend_status[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')]; ?>]" value="0"/>

                                                        <?php if ($this->flexi_auth->is_privileged($update_user)) { ?>
                                                            <input type="checkbox" class="delete_group" name="suspend_status[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')]; ?>]" value="1" <?php echo ($user[$this->flexi_auth->db_column('user_acc', 'suspend')] == 1) ? 'checked="checked"' : ""; ?>/>
                                                        <?php } else { ?>
                                                            <input type="checkbox" disabled="disabled"/>
                                                            <small>Not Privileged</small>
                                                            <input type="hidden" name="suspend_status[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')]; ?>]" value="0"/>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="align_ctr">
                                                        <?php if ($this->flexi_auth->is_privileged($delete_user)) { ?>
                                                        <input type="checkbox" class="delete_group" name="delete_user[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')]; ?>]" value="1"/>
                                                        <?php } else { ?>
                                                            <input type="checkbox" disabled="disabled"/>
                                                            <small>Not Privileged</small>
                                                            <input type="hidden" name="delete_user[<?php echo $user[$this->flexi_auth->db_column('user_acc', 'id')]; ?>]" value="0"/>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="8">
                                                    <?php $disable = (!$this->flexi_auth->is_privileged('Update Users') && !$this->flexi_auth->is_privileged('Delete Users')) ? 'disabled="disabled"' : NULL; ?>
                                                    <input type="submit" name="update_users" value="Update / Delete Users" class="btn btn-blue delete" <?php echo $disable; ?>/>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    <?php } else { ?>
                                        <tbody>
                                            <tr>
                                                <td colspan="8" class="highlight_red">
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

<style>
	div.tagsinput span.tag {
		border: 1px solid #a5d24a;
		-moz-border-radius: 2px;
		-webkit-border-radius: 2px;
		display: block;
		float: left;
		padding: 2px;
		text-decoration: none;
		background: #cde69c;
		color: #638421;
		margin-right: 5px;
		margin-bottom: 5px;
		font-family: helvetica;
		font-size: 13px;
	}
</style>