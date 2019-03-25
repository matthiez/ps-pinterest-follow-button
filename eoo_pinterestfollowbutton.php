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

class Eoo_Pinterestfollowbutton extends Module
{
    protected $errors = array();

    protected $config = array(
        'EOO_PINTEREST_FOLLOW_BUTTON' => '1',
        'EOO_PINTEREST_FOLLOW_BUTTON_PROFILE_URL' => 'https://www.pinterest.com/pinterest/',
        'EOO_PINTEREST_FOLLOW_BUTTON_PROFILE_NAME' => ''
    );

    public function __construct()
    {
        $this->name = 'eoo_pinterestfollowbutton';
        $this->tab = 'front_office_features';
        $this->version = '1.0.4';
        $this->author = 'Andre Matthies';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->displayName = $this->l('Pinterest Follow Button');
        $this->description = $this->l('Adds a block with Pinterest Follow Button.');
        $this->__moduleDir = dirname(__FILE__);

        parent::__construct();
    }

    /**
     * @throws PrestaShopException
     */
    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!parent::install()) {
            return false;
        }

        foreach ($this->config as $k => $v) {
            Configuration::updateValue($k, $v);
        }

        return $this->registerHook('actionAdminControllerSetMedia')
            && $this->registerHook('actionFrontControllerSetMedia')
            && $this->registerHook('displayFooter');
    }

    public function uninstall()
    {
        parent::uninstall();

        foreach ($this->config as $k) {
            Configuration::deleteByName($k);
        }

        return true;
    }

    public function getContent()
    {
        require_once $this->__moduleDir . '/backendhelperform.php';

        $output = null;

        if (Tools::isSubmit('submit' . $this->name)) {
            foreach (Tools::getValue('config') as $k => $v) {
                Configuration::updateValue($k, $v);
            }

            $output .= $this->errors
                ? $this->displayError(implode($this->errors, '<br>'))
                : $this->displayConfirmation($this->l('Settings updated'));
        }

        return $output . (new BackendHelperForm($this->name))->generate();
    }

    /**
     * @throws PrestaShopException
     */
    public function hookDisplayFooter()
    {
        $this->context->smarty->assign(Configuration::getMultiple(array_keys($this->config)));

        return $this->display(__FILE__, $this->name . '.tpl');
    }

    public function hookDisplayLeftColumn()
    {
        return $this->hookDisplayFooter();
    }

    public function hookDisplayRightColumn()
    {
        return $this->hookDisplayFooter();
    }

    public function hookDisplayTop()
    {
        return $this->hookDisplayFooter();
    }

    public function hookDisplayHome()
    {
        return $this->hookDisplayFooter();
    }

    public function hookActionAdminControllerSetMedia()
    {
        $this->context->controller->addJqueryPlugin('validate');
        $this->context->controller->addJS($this->__moduleDir . '/views/js/backend.js');
    }

    public function hookActionFrontControllerSetMedia()
    {
        if (true === (bool)Configuration::get('EOO_PINTEREST_FOLLOW_BUTTON')) {
            $this->context->controller->registerJavascript(
                'eoo-pinterest-pinit',
                "https://assets.pinterest.com/js/pinit_main.js",
                array(
                    'server' => 'remote',
                    'position' => 'bottom',
                    'priority' => 10,
                    'attribute' => 'async defer'
                )
            );
        }
    }
}
