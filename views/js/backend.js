$(document).ready(function () {
    $("#configuration_form").validate({
        rules: {
            "config[PINTEREST_FOLLOW_BUTTON_PROFILE_URL]": {
                required: true
            }
        }
    });
});