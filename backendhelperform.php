<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author    André Matthies
 * @copyright 2018-present André Matthies
 * @license   LICENSE
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class BackendHelperForm extends HelperForm
{
    public function __construct(string $name)
    {
        parent::__construct();

        $default_lang = Configuration::get('PS_LANG_DEFAULT');

        $this->module = $this;

        $this->name = $name;

        $this->name_controller = $name;

        $this->token = Tools::getAdminTokenLite('AdminModules');

        $this->currentIndex = AdminController::$currentIndex . "&configure=$name";

        $this->default_form_language = $default_lang;

        $this->allow_employee_form_lang = $default_lang;

        $this->title = $this->name;

        $this->show_toolbar = true;

        $this->toolbar_scroll = true;

        $this->submit_action = 'submit' . $name;

        $this->toolbar_btn = array(
            'save' =>
                array(
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex
                        . "&configure=$name&save$name&token="
                        . Tools::getAdminTokenLite('AdminModules'),
                ),
            'back' => array(
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        $this->fields_value = array(
            'config[EOO_PINTEREST_FOLLOW_BUTTON]' =>
                Configuration::get('EOO_PINTEREST_FOLLOW_BUTTON'),
            'config[EOO_PINTEREST_FOLLOW_BUTTON_PROFILE_URL]' =>
                Configuration::get('EOO_PINTEREST_FOLLOW_BUTTON_PROFILE_URL'),
            'config[EOO_PINTEREST_FOLLOW_BUTTON_PROFILE_NAME]' =>
                Configuration::get('EOO_PINTEREST_FOLLOW_BUTTON_PROFILE_NAME')
        );

        $this->fields_form = array(array('form' => array(
            'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'switch',
                    'name' => 'config[EOO_PINTEREST_FOLLOW_BUTTON]',
                    'label' => $this->l('Enable Follow Button?'),
                    'hint' =>
                        $this->l('The follow button lets Pinners easily follow your business’s Pinterest page.'),
                    'is_bool' => true,
                    'required' => false,
                    'values' => array(
                        array(
                            'id' => 'eoo_follow_button_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'eoo_follow_button_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        )
                    )
                ),
                array(
                    'type' => 'text',
                    'name' => 'config[EOO_PINTEREST_FOLLOW_BUTTON_PROFILE_URL]',
                    'label' => $this->l('Profile URL'),
                    'hint' => 'The Pinterest URL the follow button should refer to.',
                    'required' => true
                ),
                array(
                    'type' => 'text',
                    'name' => 'config[EOO_PINTEREST_FOLLOW_BUTTON_PROFILE_NAME]',
                    'label' => $this->l('Profile Name'),
                    'hint' => 'The Pinterest username the follow button should refer to.',
                    'required' => false
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            )
        )));
    }
}
