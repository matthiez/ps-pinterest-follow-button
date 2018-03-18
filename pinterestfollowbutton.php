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
        $this->version = '1.0.3';
        $this->author = 'Andre Matthies';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Pinterest Follow Button');
        $this->description = $this->l('Adds a block with Pinterest Follow Button.');

        $this->__moduleDir = dirname(__FILE__);
    }

    /**
     * @return bool
     * @throws PrestaShopException
     */
    public function install() {
        if (Shop::isFeatureActive()) Shop::setContext(Shop::CONTEXT_ALL);

        if (!parent::install()) return false;

        foreach ($this->config as $k => $v) Configuration::updateValue($k, $v);

        return $this->registerHook('actionAdminControllerSetMedia')
            && $this->registerHook('displayFooter');
    }

    /**
     * @return bool
     */
    public function uninstall() {
        parent::uninstall();

        foreach ($this->config as $k) Configuration::deleteByName($k);

        return true;
    }

    /**
     * @return string
     */
    public function getContent() {
        require_once $this->__moduleDir . '/backendhelperform.php';

        $output = null;

        if (Tools::isSubmit('submit' . $this->name)) {
            foreach (Tools::getValue('config') as $k => $v) Configuration::updateValue($k, $v);
            if ($this->errors) $output .= $this->displayError(implode($this->errors, '<br>'));
            else $output .= $this->displayConfirmation($this->l('Settings updated'));
        }

        return $output . (new BackendHelperForm($this->name))->generate();
    }

    /**
     * @return string
     * @throws PrestaShopException
     */
    public function hookDisplayFooter() {
        $this->context->smarty->assign(Configuration::getMultiple(array_keys($this->config)));
        if (Configuration::get('PINTEREST_FOLLOW_BUTTON')) $this->context->controller->addJS('//assets.pinterest.com/js/pinit.js');
        return $this->display(__FILE__, $this->name . '.tpl');
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
     *
     */
    public function hookActionAdminControllerSetMedia() {
        $this->context->controller->addJqueryPlugin('validate');
        $this->context->controller->addJS($this->__moduleDir . '/views/js/backend.js');
    }
}