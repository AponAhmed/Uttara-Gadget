
var total;
var iconHtm;

function getRandom() {
    return Math.ceil(Math.random() * 20)
}

jQuery(document).ready(function () {
    excEvt();

});

function uploadImage(_this) {

    var fd = new FormData();
    var files = jQuery(_this)[0].files[0];
    fd.append('exchange-image', files);
    fd.append('action', 'upload_divices_image');
    //Add New Block in Queue
    let uid = "as" + Date.now();
    jQuery("#uploadNew").before(`<div class='uploading image-item' id='${uid}'>Loading...</div>`);
    jQuery.ajax({
        url: contactAjaxObj.ajaxurl,
        type: "POST",
        data: fd,
        processData: false,
        contentType: false,
        success: function (data, textStatus, jqXHR) {
            data = JSON.parse(data);
            if (data.type == 'error') {
                jQuery(".errMsg").html(data.message).css("color", "red");
            } else {
                //console.log(uid);
                jQuery("#" + uid).html(`
                <span onclick="jQuery(this).parent().remove()">&times;</span>
                <img src="${data.src}" alt='media-image'>
                <input type="hidden" name="exch-images[]" value="${data.name}">
                `);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            //if fails     
        }
    });
}

function excEvt() {
    jQuery("#exchangeForm").on('submit', function (event) {
        event.preventDefault();
        let frm = event.target;
        let loader = "<span class='spinLoaderexch'><i></i><i></i><i></i><i></i><i></i><i></i></span>";
        jQuery("#exchangeSubmit").html("Please Wait...");
        jQuery("#exchangeSubmit").attr('type', 'button');//To Prevent Resend when already Processing
        var data = {
            action: "exchange_request",
            data: jQuery("#exchangeForm").serialize(),
        };
        jQuery.post(contactAjaxObj.ajaxurl, data, function (response) {
            jQuery(".spinLoaderexch").remove();
            var obj = JSON.parse(response);
            if (obj.error === false) {
                jQuery('#exchangeForm')[0].reset();
                jQuery(".errMsg").html(obj.message).css("color", "green");
                jQuery("#exchangeSubmit").html(" Requested !");
                jQuery(".image-item").remove();
                setTimeout(function () {
                    jQuery("#exchangeSubmit").html(" Submit Another ");
                }, 2000);
            } else {
                jQuery(".errMsg").html(obj.message).css("color", "red");
                jQuery("#exchangeSubmit").attr('type', 'submit');
            }

        });
    });
}

function createSum() {
    var randomNum1 = getRandom(),
        randomNum2 = getRandom();
    total = randomNum1 + randomNum2;
    jQuery("#question").text(randomNum1 + " + " + randomNum2 + "=");
    jQuery('#success, #fail').hide();
    jQuery('#message').show()
}

function checkInput() {
    var input = jQuery("#ans").val(),
        slideSpeed = 200,
        hasInput = !!input,
        valid = hasInput & input == total;
    if (valid) {
        jQuery(".question").css("border-color", "green")
    } else {
        jQuery(".question").css("border-color", "red")
    }
    jQuery('button[type=submit]').prop('disabled', !valid)
}

function contactForm_init() {
    jQuery("#reff").val(document.referrer);
    createSum();
    jQuery("#ans").keyup(checkInput);
    jQuery("#ans").change(checkInput);
    var loader = "<span class='spinLoader'><i></i><i></i><i></i><i></i><i></i><i></i></span>";
    var loaderBig = "<span class='bodyLoader'></span>";
    jQuery("#contactForm").submit(function (e) {
        jQuery("#submitBtn").attr('type', 'button');//To Prevent Resend when already Processing
        jQuery("#submitBtn").html(" Sending...");
        jQuery("#submitBtn").after(loader);
        e.preventDefault();
        var data = {
            action: "contactActionAjax",
            data: jQuery("#contactForm").serialize(),
        };
        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        jQuery.post(contactAjaxObj.ajaxurl, data, function (response) {
            jQuery(".spinLoader").remove();
            console.log(response);
            var obj = JSON.parse(response);
            if (obj.error === false) {
                jQuery('#contactForm')[0].reset();
                jQuery(".contactMsg").html(obj.message).css("color", "green");
                jQuery("#submitBtn").html(" Sent !");
                setTimeout(function () {
                    jQuery("#submitBtn").html(" Send ");
                }, 2000)
            } else {
                jQuery(".contactMsg").html(obj.message).css("color", "red");
                jQuery("#submitBtn").attr('type', 'submit');
            }
        });
    })
}

function trigFloated(_this) {
    let act = jQuery(_this);
    if (act.attr('data-action') == 'open') {
        iconHtm = act.html();
        jQuery('.floated-contact-form-wrap').addClass('open');
        act.attr('data-action', 'close');
        act.html('<span class="close-floated"></span>');
    } else {
        jQuery('.floated-contact-form-wrap').removeClass('open');
        act.attr('data-action', 'open');
        act.html(iconHtm);
    }
}