<?php
namespace keeko\account\widget;

use keeko\framework\service\ServiceContainer;

class WidgetFactory {
	
	/** @var ServiceContainer */
	private $service;
	
	public function __construct(ServiceContainer $service) {
		$this->service = $service;
	}
	
	public function createLoginWidget() {
		return new LoginWidget($this->service);
	}
}