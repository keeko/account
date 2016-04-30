<?php
namespace keeko\account\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use keeko\framework\domain\payload\Success;

/**
 * User Widget
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
class UserWidgetAction extends AbstractAction {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$prefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();
		$app = $this->getServiceContainer()->getKernel()->getApplication();
		$translator = $this->getServiceContainer()->getTranslator();
		$loginSlug = $translator->trans('slug.login', [], 'keeko.user');

		return $this->responder->run($request, new Success([
			'account_url' => $prefs->getAccountUrl(),
			'destination' => $prefs->getAccountUrl() . $loginSlug,
			'referrer' => $app->getFullUrl(),
			'login_label' => $prefs->getUserLogin()
		]));
	}
}
