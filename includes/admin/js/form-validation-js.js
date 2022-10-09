var FormValidator = function () {
    // function to initiate category    
    var categoryForm = function () {
        var form1 = $('#cat_form');
        var errorHandler1 = $('.errorHandler', form1);
        var successHandler1 = $('.successHandler', form1);
        $('#cat_form').validate({
            errorElement: "span", // contain the error msg in a span tag
            errorClass: 'help-block',
            errorPlacement: function (error, element) {
                    error.insertAfter(element);
                    // for other inputs, just perform default behavior
            },
            ignore: "",
            rules: {
                cat_title: {
                    minlength: 2,
                    required: true
                }
            },
            messages: {
                cat_title: "Please specify Category Title"
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                successHandler1.hide();
                errorHandler1.show();
            },
            highlight: function (element) {
                $(element).closest('.help-block').removeClass('valid');
                // display OK icon
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
                // add the Bootstrap error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error');
                // set error class to the control group
            },
            success: function (label, element) {
                label.addClass('help-block valid');
                // mark the current input as valid and display OK icon
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
            },
            submitHandler: function (form) {
                successHandler1.show();
                errorHandler1.hide();
                // submit form
                //$('#form').submit();
                HTMLFormElement.prototype.submit.call($('#cat_form')[0]);
            }
        });
    };
    
    return {
        //main function to initiate pages
        init: function () {
            categoryForm();
        }
    };
}();