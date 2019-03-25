/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 *  @author    André Matthies
 *  @copyright 2018-present André Matthies
 *  @license   LICENSE
 */

$(document).ready(function () {
    $("#configuration_form").validate({
        rules: {
            "config[EOO_PINTEREST_FOLLOW_BUTTON_PROFILE_URL]": {
                required: true
            }
        }
    });
});