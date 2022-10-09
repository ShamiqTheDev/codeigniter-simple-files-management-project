<!-- start: FOOTER -->
<div class="footer clearfix">
    <div class="footer-inner">
        <!--2014 &copy; clip-one by cliptheme.-->
        <?php echo ($this->session->userdata('footer_message')) ? $this->session->userdata('footer_message') : '@copyright '.date("Y").', sapphire.co'; ?>
    </div>
    <div class="footer-items">
        <span class="go-top"><i class="clip-chevron-up"></i></span>
    </div>
</div>
<!-- end: FOOTER -->
<div id="event-management" class="modal fade" tabindex="-1" data-width="760" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title">Event Management</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-light-grey">
                    Close
                </button>
                <button type="button" class="btn btn-danger remove-event no-display">
                    <i class='fa fa-trash-o'></i> Delete Event
                </button>
                <button type='submit' class='btn btn-success save-event'>
                    <i class='fa fa-check'></i> Save
                </button>
            </div>
        </div>
    </div>
</div>

<!-- statr: INCLUSE FOOT -->
<?php $this->load->view('admin/includes/foot'); ?>
<!-- end: INCLUSE FOOT -->

<script>
    
    // Fade out status messages, but ensure error messages stay visable.
    if ($('.status_msg').length > 0) {
        $('#message').delay(2500).fadeTo(1000,0.01).slideUp(500);
    }
	$('.slidedown_default_menu').children('ul').slideDown();
    
</script>

</body>
<!-- end: BODY -->
</html>