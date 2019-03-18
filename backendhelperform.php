<?php if (!defined('_PS_VERSION_')) exit;

/**
 * Class BackendHelperForm
 */
class BackendHelperForm extends HelperForm
{
    /**
     * BackendHelperForm constructor.
     * @param $name
     */
    public function __construct($name) {
        parent::__construct();

        $default_lang = Configuration::get('PS_LANG_DEFAULT');

        $this->module = $this;

        $this->name_controller = $name;

        $this->token = Tools::getAdminTokenLite('AdminModules');

        $this->currentIndex = AdminController::$currentIndex . '&configure=' . $name;

        $this->default_form_language = $default_lang;

        $this->allow_employee_form_lang = $default_lang;

        $this->title = $this->displayName;

        $this->show_toolbar = true;

        $this->toolbar_scroll = true;

        $this->submit_action = 'submit' . $name;

        $this->toolbar_btn = [
            'save' =>
                [
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex . '&configure=' . $name . '&save' . $name .
                        '&token=' . Tools::getAdminTokenLite('AdminModules'),
                ],
            'back' => [
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            ]
        ];

        $this->fields_value = [
            'config[PINTEREST_FOLLOW_BUTTON]' => Configuration::get('PINTEREST_FOLLOW_BUTTON'),
            'config[PINTEREST_FOLLOW_BUTTON_PROFILE_URL]' => Configuration::get('PINTEREST_FOLLOW_BUTTON_PROFILE_URL'),
            'config[PINTEREST_FOLLOW_BUTTON_PROFILE_NAME]' => Configuration::get('PINTEREST_FOLLOW_BUTTON_PROFILE_NAME')
        ];

        $this->fields_form = [['form' => [
            'legend' => [
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs'
            ],
            'input' => [
                [
                    'type' => 'switch',
                    'name' => 'config[PINTEREST_FOLLOW_BUTTON]',
                    'label' => $this->l('Enable Follow Button?'),
                    'hint' => $this->l('The follow button lets Pinners easily follow your business’s Pinterest page.'),
                    'is_bool' => true,
                    'required' => false,
                    'values' => [
                        [
                            'id' => 'follow_button_on',
                            'value' => 1,
                            'label' => $this->l('Yes')
                        ],
                        [
                            'id' => 'follow_button_off',
                            'value' => 0,
                            'label' => $this->l('No')
                        ]
                    ]
                ],
                [
                    'type' => 'text',
                    'name' => 'config[PINTEREST_FOLLOW_BUTTON_PROFILE_URL]',
                    'label' => $this->l('Profile URL'),
                    'hint' => 'The Pinterest URL the follow button should refer to.',
                    'required' => true
                ],
                [
                    'type' => 'text',
                    'name' => 'config[PINTEREST_FOLLOW_BUTTON_PROFILE_NAME]',
                    'label' => $this->l('Profile Name'),
                    'hint' => 'The Pinterest username the follow button should refer to.',
                    'required' => false
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ]
        ]]];
    }
}