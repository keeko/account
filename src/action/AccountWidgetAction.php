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
class AccountWidgetAction extends AbstractAction {

	/**
	 * Automatically generated run method
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$prefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();
		$translator = $this->getServiceContainer()->getTranslator();

		return $this->responder->run($request, new Success([
			'account_url' => $prefs->getAccountUrl(),
			'destination' => $prefs->getAccountUrl() . '/' . $translator->trans('slug.login'),
			'redirect' => $request->getUri(),
			'login_label' => $prefs->getUserLogin()
		]));
	}
}
