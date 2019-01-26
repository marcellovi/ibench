/**
 * Author : Adegboye Joshua
 * Author URL : https://masterjosh.com
 * Author Email : joshuaeasy4u@gmail.com
 */
jQuery( function( $ ) {
    'use strict';

    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
                $("#payment-button-amount").hide();
                $("#payment-button-sending").show();
            }, false);
        });
    }, false);

    /**
     * Object to handle Pago functions.
     */
    var mj_wirecard = {
        /**
         * Initialize.
         */
        init: function() {
            $("#cc-exp-y").on('change', function(e){
                var min = parseInt($(this).data('min'));
                var max = parseInt($(this).data('max'));
                var val = parseInt($(this).val());
                if(val < min)
                {
                    $(this).val(min);
                    return false;
                } else if (max !== "") {
                    if(val > max)
                    {
                        $(this).val(max);
                        return false;
                    }
                }
            });

            $("#cc-exp-m").on('input', function () {
                $(this).val($(this).val().replace(/[^0-9.]/g, ''));
                if ($(this).val().length === 2) {
                    $("#cc-exp-y").focus();
                }
            });

            $("#cc-number").on('input', function () {
                $(this).val($(this).val().replace(/[^0-9.]/g, ''));
                if ($(this).val().length >= 4) {
                    var $cc_type = GetCardType($(this).val());
                    // $("#card_type").val($cc_type);
                    $(this).removeClass("visa discover amex mastercard").addClass($cc_type);
                }

                if ($(this).val().length === 16) {
                    $("#cc-exp-m").focus();
                }
            });
        }
    };
    function GetCardType(number) {
        var re = new RegExp("^4");
        if (number.match(re) != null)
            return "visa";

        re = new RegExp("^(34|37)");
        if (number.match(re) != null)
            return "amex";

        re = new RegExp("^5[1-5]");
        if (number.match(re) != null)
            return "mastercard";

        re = new RegExp("^6011");
        if (number.match(re) != null)
            return "discover";

        return "";
    };

    mj_wirecard.init();

});