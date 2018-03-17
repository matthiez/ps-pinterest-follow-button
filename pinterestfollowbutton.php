<?php if (!defined('_PS_VERSION_')) exit;

/**
 * Class PinterestFollowButton
 */
class PinterestFollowButton extends Module
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var array
     */
    protected $config = [
        'PINTEREST_FOLLOW_BUTTON' => '',
        'PINTEREST_FOLLOW_BUTTON_PROFILE_URL' => '',
        'PINTEREST_FOLLOW_BUTTON_PROFILE_NAME' => ''
    ];

    /**
     * PinterestFollowButton constructor.
     */
    public function __construct() {
        $this->name = 'pinterestfollowbutton';
        $this->tab = 'front_office_features';
        $this->version = '1.0.2';
        $this->author = 'Andre Matthies';
        $this->need_instance = 0;
        $this->bootstrap = true;
        
        parent::__construct();
        
        $this->displayName = $this->l('Pinterest Follow Button');
        $this->description = $this->l('Adds a block with Pinterest Follow Button.');
    }

    /**
     * @return bool
     * @throws PrestaShopException
     */
    public function install() {
        if (Shop::isFeatureActive()) Shop::setContext(Shop::CONTEXT_ALL);
        return parent::install()
            && $this->installConfig()
            && $this->registerHook('displayFooter');
    }

    /**
     * @return bool
     */
    public function uninstall() {
        return parent::uninstall()
            && $this->removeConfig();
    }

    /**
     * @return bool
     */
    private function installConfig() {
        foreach ($this->config as $k => $v) Configuration::updateValue($k, $v);
        return true;
    }

    /**
     * @return bool
     */
    private function removeConfig() {
        foreach ($this->config as $k => $v) Configuration::deleteByName($k);
        return true;
    }

    /**
     * @return array
     * @throws PrestaShopException
     */
    public function getConfig() {
        return Configuration::getMultiple(array_keys($this->config));
    }

    /**
     * @return string
     */
    public function getContent() {
        $output = null;
        if (Tools::isSubmit('submitpinterestfollowbutton')) {
            foreach (Tools::getValue('config') as $key => $value) Configuration::updateValue($key, $value);
            if ($this->errors) $output .= $this->displayError(implode($this->errors, '<br/>'));
            else $output .= $this->displayConfirmation($this->l('Settings updated'));
        }
        return $output . $this->displayForm();
    }

    /**
     * @return string
     */
    public function displayForm() {
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = [
            'save' =>
                [
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                        '&token=' . Tools::getAdminTokenLite('AdminModules'),
                ],
            'back' => [
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            ]
        ];
        $helper->fields_value['config[PINTEREST_FOLLOW_BUTTON]'] = Configuration::get('PINTEREST_FOLLOW_BUTTON');
        $helper->fields_value['config[PINTEREST_FOLLOW_BUTTON_PROFILE_URL]'] = Configuration::get('PINTEREST_FOLLOW_BUTTON_PROFILE_URL');
        $helper->fields_value['config[PINTEREST_FOLLOW_BUTTON_PROFILE_NAME]'] = Configuration::get('PINTEREST_FOLLOW_BUTTON_PROFILE_NAME');
        return $helper->generateForm([ [ 'form' => [
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
                        'required' => false
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
            ] ] ]);
    }

    /**
     * @return string
     */
    public function hookDisplayLeftColumn() {
        return $this->hookDisplayFooter();
    }

    /**
     * @return string
     */
    public function hookDisplayRightColumn() {
        return $this->hookDisplayFooter();
    }

    /**
     * @return string
     */
    public function hookDisplayTop() {
        return $this->hookDisplayFooter();
    }

    /**
     * @return string
     */
    public function hookDisplayHome() {
        return $this->hookDisplayFooter();
    }

    /**
     * @return string
     * @throws PrestaShopException
     */
    public function hookDisplayFooter() {
        $this->context->smarty->assign($this->getConfig());
        if (Configuration::get('PINTEREST_FOLLOW_BUTTON')) $this->context->controller->addJS('//assets.pinterest.com/js/pinit.js');
        return $this->display(__FILE__, 'pinterestfollowbutton.tpl');
    }
}