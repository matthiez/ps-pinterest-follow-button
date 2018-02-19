<?php

if (!defined('_PS_VERSION_')) {
	exit;
}

class shmoPinterestFollowButton extends Module {

	protected $errors = array();

	protected $config = array(
 		'SHMO_PINTEREST_FOLLOW_BUTTON' => '',
		'SHMO_PINTEREST_FOLLOW_BUTTON_PROFILE_URL' => '',
		'SHMO_PINTEREST_FOLLOW_BUTTON_PROFILE_NAME' => ''
		);

	public function __construct() {
		$this->name = 'shmopinterestfollowbutton';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'Shopmods';
		$this->need_instance = 0;
		$this->bootstrap = true;
	 	parent::__construct();
		$this->displayName = $this->l('Pinterest Follow Button by Shopmods');
		$this->description = $this->l('Adds a block with Pinterest Follow Button.');
		$this->confirmUninstall = $this->l('Are you sure you want to delete Pinterest Follow Button by Shopmods?');
	}

	public function install() {
		if (Shop::isFeatureActive()) {
			Shop::setContext(Shop::CONTEXT_ALL);
		}
		if (!parent::install() 
		OR !$this->installConfig()
		OR !$this->registerHook('displayHeader')
		OR !$this->registerHook('displayTop')
		OR !$this->registerHook('displayHome')
		OR !$this->registerHook('displayLeftColumn')
		OR !$this->registerHook('displayRightColumn')
		OR !$this->registerHook('displayFooter')) {
			return false;
		}
		return true;
	}
	
	public function uninstall() {
		if (!parent::uninstall()
		OR	!$this->removeConfig()) {
			return false;
		}
		return true;
	}

	private function installConfig() {
		foreach ($this->config as $keyname => $value) {
			Configuration::updateValue(strtoupper($keyname), $value);
		}
		return true;
	}

	private function removeConfig() {
		foreach ($this->config as $keyname => $value) {
			Configuration::deleteByName(strtoupper($keyname));
		}
		return true;
	}

	public function getConfig() {
		$config_keys = array_keys($this->config);
		return Configuration::getMultiple($config_keys);
	}
	
 	public function getContent() {
		$output = null;
		if (Tools::isSubmit('submitshmopinterestfollowbutton')) {
 			foreach (Tools::getValue('config') as $key => $value) {
				Configuration::updateValue($key, $value);
			}
			if ($this->errors) {
				$output .= $this->displayError(implode($this->errors, '<br/>'));
			}
			else {
				$output .= $this->displayConfirmation($this->l('Settings updated'));
			}
		}
		$vars = array();
		$vars['config'] = $this->getConfig();
		return $output.$this->displayForm($vars);
	}
	
	public function displayForm($vars) {
		extract($vars);
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
		$shmoPntrstFllwBtnForm[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('Settings'),
				'icon' => 'icon-cogs'
			),
			'input' => array(
				array(
					'type' => 'switch',
					'name' => 'config[SHMO_PINTEREST_FOLLOW_BUTTON]',
					'label' => $this->l('Enable Follow Button?'),
					'hint' => $this->l('The follow button lets Pinners easily follow your business’s Pinterest page.'),
					'is_bool' => true,
					'required' => false,
					'values' => array(
						array(
							'id' => 'follow_button_on',
							'value' => 1,
							'label' => $this->l('Yes')
						),
						array(
							'id' => 'follow_button_off',
							'value' => 0,
							'label' => $this->l('No')
						)
					)
				),
				array(
					'type' => 'text',
					'name' => 'config[SHMO_PINTEREST_FOLLOW_BUTTON_PROFILE_URL]',
					'label' => $this->l('Profile URL'),
					'hint' => 'The Pinterest URL the follow button should refer to.',
					'required' => false
				),
				array(
					'type' => 'text',
					'name' => 'config[SHMO_PINTEREST_FOLLOW_BUTTON_PROFILE_NAME]',
					'label' => $this->l('Profile Name'),
					'hint' => 'The Pinterest username the follow button should refer to.',
					'required' => false
				),
			),
			'submit' => array(
				'title' => $this->l('Save'),
				'class' => 'btn btn-default pull-right'
			)
		);
		$helper = new HelperForm();
		$helper->module = $this;
		$helper->name_controller = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$helper->default_form_language = $default_lang;
		$helper->allow_employee_form_lang = $default_lang;
		$helper->title = $this->displayName;
		$helper->show_toolbar = true;
		$helper->toolbar_scroll = true;
		$helper->submit_action = 'submit'.$this->name;
		$helper->toolbar_btn = array(
			'save' =>
			array(
				'desc' => $this->l('Save'),
				'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
				'&token='.Tools::getAdminTokenLite('AdminModules'),
			),
			'back' => array(
				'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
				'desc' => $this->l('Back to list')
			)
		);
		$helper->fields_value['config[SHMO_PINTEREST_FOLLOW_BUTTON]'] = Configuration::get('SHMO_PINTEREST_FOLLOW_BUTTON');
		$helper->fields_value['config[SHMO_PINTEREST_FOLLOW_BUTTON_PROFILE_URL]'] = Configuration::get('SHMO_PINTEREST_FOLLOW_BUTTON_PROFILE_URL');
		$helper->fields_value['config[SHMO_PINTEREST_FOLLOW_BUTTON_PROFILE_NAME]'] = Configuration::get('SHMO_PINTEREST_FOLLOW_BUTTON_PROFILE_NAME');
		return $helper->generateForm($shmoPntrstFllwBtnForm);
	}

	public function hookDisplayLeftColumn($params) {
		$config = $this->getConfig();
		$this->context->smarty->assign(array(
			'shmoPntrstFllwBtn' => $config
		));
		if (Configuration::get('SHMO_PINTEREST_FOLLOW_BUTTON')) {
			$this->context->controller->addJS('//assets.pinterest.com/js/pinit.js');
		}
		return $this->display(__FILE__, 'shmopinterestfollowbutton.tpl');
	}
	
	public function hookDisplayRightColumn($params) {
		return $this->hookDisplayLeftColumn($params);
	}
	
	public function hookDisplayTop($params) {
		return $this->hookDisplayLeftColumn($params);
	}
	
	public function hookDisplayHome($params) {
		return $this->hookDisplayLeftColumn($params);
	}
	
	public function hookDisplayFooter($params) {
		return $this->hookDisplayLeftColumn($params);
	}

}