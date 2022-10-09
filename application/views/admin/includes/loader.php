<div class="loader-page loaderMain"></div><!--For Page Load-->
<div style="display: none;" class="loader-ajax loaderMain"></div><!--For Ajax Load-->
<script>
    jQuery(window).load(function() {
        jQuery('.loader-page').fadeOut();
    });
    var $loading = jQuery('.loading-div').hide();
    jQuery(document)
            .ajaxStart(function () {
                $loading.show();
                jQuery('.loader-ajax').fadeIn();
            })
            .ajaxStop(function () {
                $loading.hide();
                jQuery('.loader-ajax').fadeOut();
            });
</script>