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
 * @license   LICENSE.md
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class EooPinterestfollowbutton extends Module
{
    protected $errors = array();

    protected $config = array(
        'EOO_PINTEREST_FOLLOW_BUTTON' => '',
        'EOO_PINTEREST_FOLLOW_BUTTON_PROFILE_URL' => '',
        'EOO_PINTEREST_FOLLOW_BUTTON_PROFILE_NAME' => ''
    );

    /**
     * PinterestFollowButton constructor.
     */
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
    public function install(): bool
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
            && $this->registerHook('displayFooter');
    }

    public function uninstall(): bool
    {
        parent::uninstall();

        foreach ($this->config as $k) {
            Configuration::deleteByName($k);
        }

        return true;
    }

    public function getContent(): string
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
    public function hookDisplayFooter(): string
    {
        $this->context->smarty->assign(Configuration::getMultiple(array_keys($this->config)));

        return $this->display(__FILE__, $this->name . '.tpl');
    }

    public function hookDisplayLeftColumn(): string
    {
        return $this->hookDisplayFooter();
    }

    public function hookDisplayRightColumn(): string
    {
        return $this->hookDisplayFooter();
    }

    public function hookDisplayTop(): string
    {
        return $this->hookDisplayFooter();
    }

    public function hookDisplayHome(): string
    {
        return $this->hookDisplayFooter();
    }

    public function hookActionAdminControllerSetMedia(): void
    {
        $this->context->controller->addJqueryPlugin('validate');
        $this->context->controller->addJS($this->__moduleDir . '/views/js/backend.js');
    }

    public function hookActionFrontControllerSetMedia(): void
    {
        if (true === (bool)Configuration::get('EOO_EOO_PINTEREST_FOLLOW_BUTTON')) {
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
