<style>
 .imgPanel{
	float:left;
	width:100%;
	overflow-y: auto;
	height: 750px;
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
 <form> 
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
        <div class="page-header">
          <h1>Bulk Scaning </h1>
        </div>
        <!-- end: PAGE TITLE & BREADCRUMB width="1090" height="300" --> 
      </div>
    </div>
    <!-- end: PAGE HEADER -->
    
    <div class="row">
      <div class="col-sm-6">
        <div class="panel panel-default">
          <div class="panel-heading"> <i class="fa fa-external-link-square"></i> File upload</div>
          <div class="panel-body">
			<img id="scanImage" class="scanImage" src="<?php echo base_url(); ?>/includes/admin/images/notification.jpg" style="width:100%; height:80%;" alt="your image will be uploaded here" /> 
		  </div>
		</div>
	  </div>
      <div class="col-sm-6"> 
        <!-- start: DATE/TIME PICKER PANEL -->
        <div class="panel panel-default">
        <div class="panel-heading"> <i class="fa fa-external-link-square"></i> Document Detail</div>
        <div class="panel-body">
        
    	 <div class="row" >
				<div class="col-sm-12">
					<div class="form-group">
						  <label>File Number:</label>
						  <label>0930190</label>
					</div>
			  </div>
          </div>	
          <div class="row">
				<div class="col-sm-6">
					<div class="form-group">
					  <label>Document Category:</label>
					  <label>General</label>            
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
					  <label>Receiving Mode:</label>
					  <label>By TCS</label>
					</div>
				</div>
          </div>
          <div class="row">
            <div class="col-sm-6">
				<div class="form-group">
				  <label>Receiving Date:</label>
				  <label>30-05-2018</label>
				</div>
            </div>
            <div class="col-sm-6">
				<div class="form-group">
				  <label>Issue Date:</label>
				  <label>30-05-2018</label>
				</div>
            </div>
          </div>
          
          <div class="row" >
            <div class="col-sm-12">
				<div class="form-group">
				  <label>Document Subject:</label>
				  <label>Eid Holidays has been announced for Health Department</label>
				</div>
			</div>
          </div>
          
          <div class="row">
            <div class="col-sm-6">
				<div class="form-group">
				  <label>Document Status:</label>
				  <label>Pending</label>
				</div>
            </div>
            <div class="col-sm-6">
				<div class="form-group">
				  <label>Document Sort No:</label>
				  <label>213548545</label>
				</div>
          </div>
          </div>
          </div>
          </div>
          <!-- end: DATE/TIME PICKER PANEL --> 
          
          <!-- start: MASKED INPUT PANEL -->
          <div class="panel panel-default">
            <div class="panel-heading"> <i class="fa fa-external-link-square"></i> Contact Detail</div>
            <div class="panel-body">
              <div class="row" >
                <div class="col-sm-6">
                   <div class="form-group">
                  <label>Sender Name:</label>
                  <label>Zia-ur-rahman</label>
                </div>
                </div>
                
                <div class="col-sm-6">
                   <div class="form-group">
                  <label>Sender Department:</label>
                  <label>Health Deparmtne</label>
                </div>
                </div>
              </div>
              <div class="row" >
                <div class="col-sm-6">
                   <div class="form-group">
                  <label>City Name:</label>
				  <label>Karachi</label>
                  </div>
                  </div>
               
                <div class="col-sm-6">
                     <div class="form-group">
                    <label>Designation:</label>
                 	<label>BPS-17</label>
                    </div>
                </div>
              </div>
              
              
              <div class="row">
                <div class="col-sm-12">
                
                     <div class="form-group">
                    <label>Address:</label>
					<label>House # B-9 Pakistan Chowk, Pakistan Community, Pakistan City, Karachi Sindh</label>
                </div>
              </div>
              </div>
              
              <div class="row" >
                <div class="col-sm-6">
                
                     <div class="form-group">
                    <label>Phone No:</label>
					<label>03318220080</label>
                </div>
                </div>
                
                <div class="col-sm-6">
                     <div class="form-group">
                    <label>Mobile No:</label>
                 	<label>0234578985</label>
                </div>
              </div>
              
              </div>
              
              <div class="row" >
                <div class="col-sm-6">
                   <div class="form-group">
                  <label>Fax No:</label>
                  <label>96587854854</label>
                  </div>
                </div>
                <div class="col-sm-6">
                   <div class="form-group">
                  <label>Email: </label>
				  <label>panhwerwaseem@gmail.com</label>
                  </div>
                </div>
              </div>
              
              
              <div class="col-sm-12">
              <button class="btn btn-primary pull-right">create</button>
            </div>
            </div>
            
            
          </div>
          <!-- end: MASKED INPUT PANEL -->
          
        
            
         
        </form>
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
<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/select2/select2.css" />
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/datatables/media/css/DT_bootstrap.css" />
<!-- fancy box -->
<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/fancybox/jquery.fancybox.css?v=2.1.5" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo $includes_dir; ?>admin/plugins/datetimepicker/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="<?php echo $includes_dir; ?>admin/plugins/bootstrap-datepicker/datepicker.css">
<!-- end: CSS REQUIRED FOR THIS PAGE ONLY --> 

<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 
<script src="<?php echo $includes_dir; ?>admin/plugins/jquery-validation/dist/jquery.validate.min.js"></script> 
<script src="<?php echo $includes_dir; ?>admin/js/form-validation-js.js"></script> 
<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/plugins/select2/select2.min.js"></script> 
<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/plugins/datatables/media/js/jquery.dataTables.min.js"></script> 
<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/plugins/datatables/media/js/DT_bootstrap.js"></script> 
<script src="<?php echo $includes_dir; ?>admin/js/table-data.js"></script> 
<!-- fancy box --> 
<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/plugins/fancybox/jquery.fancybox.js?v=2.1.5"></script> 
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 

<!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 
<script src="<?php echo $includes_dir; ?>admin/plugins/jquery-validation/dist/jquery.validate.min.js"></script> 
<script src="<?php echo $includes_dir; ?>admin/plugins/datetimepicker/build/jquery.datetimepicker.full.js"></script> 
<!--<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/js/jzoom.js"></script>--> 
<script type="text/javascript" src="<?php echo $includes_dir; ?>admin/js/imagezoom.js"></script> 
<script src="<?php echo $includes_dir; ?>admin/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> 
<!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY --> 

<script>
        $('.datepicker').datetimepicker({
            timepicker: false,
            format: 'd-m-Y',
        });
        jQuery(document).ready(function () {
        
        Main.init();
        FormValidator.init();
        //PagesGallery.init();
        //TableData.init();
		$(".clip-chevron-left").trigger("click");
    }); 
        
		$('.date-picker').datepicker({
            autoclose: true
        });
		function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#scanImage')
                    .attr('src', e.target.result)
                    .width('100%')
                    .height('90%');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }



    </script> 



