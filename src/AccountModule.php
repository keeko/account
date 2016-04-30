<?php
namespace keeko\account;

use keeko\framework\foundation\AbstractModule;
use keeko\account\widget\WidgetFactory;

/**
 * Keeko Account
 * 
 * @license MIT
 * @author gossi
 */
class AccountModule extends AbstractModule {
	
	const EXT_SETTINGS = 'keeko.account.settings';
	
	/** @var WidgetFactory */
	private $widgetFactory;
	
	public function getWidgetFactory() {
		if ($this->widgetFactory == null) {
			$this->widgetFactory = new WidgetFactory($this->getServiceContainer());
		}
		
		return $this->widgetFactory;
	}

	/**
	 */
	public function install() {
	}

	/**
	 */
	public function uninstall() {
	}

	/**
	 * @param mixed $from
	 * @param mixed $to
	 */
	public function update($from, $to) {
	}
}
