<?php
namespace keeko\account\widget;

use keeko\framework\utils\TwigRenderTrait;
use keeko\framework\service\ServiceContainer;

class LoginWidget {

	use TwigRenderTrait;
	
	/** @var ServiceContainer */
	private $service;
	
	public function __construct(ServiceContainer $service) {
		$this->service = $service;
	}
	
	protected function getServiceContainer() {
		return $this->service;
	}
	
	public function build(array $data = []) {
		$prefs = $this->service->getPreferenceLoader()->getSystemPreferences();
		$data = array_merge($data, [
			'login_label' => $prefs->getUserLogin()
		]);
		
		return $this->render('/keeko/account/templates/login-form.twig', $data);
	}
}