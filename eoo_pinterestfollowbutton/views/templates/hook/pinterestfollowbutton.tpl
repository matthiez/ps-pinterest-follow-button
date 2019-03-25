{*
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
*  @license   LICENSE.md
*/
*}

{if
$EOO_PINTEREST_FOLLOW_BUTTON
and EOO_PINTEREST_FOLLOW_BUTTON_PROFILE_URL|count_characters
and EOO_PINTEREST_FOLLOW_BUTTON_PROFILE_NAM|count_characters
}
    <div id='pinterest-follow-button'>
        <a data-pin-do='buttonFollow'
           href='{$EOO_PINTEREST_FOLLOW_BUTTON_PROFILE_URL|escape:'htmlall':'utf-8'}'>
            {$EOO_PINTEREST_FOLLOW_BUTTON_PROFILE_NAME|escape:'htmlall':'utf-8'}
        </a>
    </div>
{/if}