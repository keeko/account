<?php
namespace keeko\account\responder\html;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use keeko\framework\foundation\AbstractPayloadResponder;
use keeko\framework\domain\payload\Created;
use keeko\framework\domain\payload\Found;
use keeko\framework\domain\payload\Blank;
use keeko\framework\domain\payload\Updated;
use Symfony\Component\HttpFoundation\RedirectResponse;
use keeko\framework\domain\payload\Failed;

/**
 * Automatically generated HtmlResponder for Forgot Password
 *
 * @author gossi
 */
class ForgotPasswordHtmlResponder extends AbstractPayloadResponder {

	/**
	 */
	protected function getPayloadMethods() {
		return [
			'keeko\framework\domain\payload\Created' => 'tokenCreated',
			'keeko\framework\domain\payload\Found' => 'changePassword',
			'keeko\framework\domain\payload\Blank' => 'identifyUser',
			'keeko\framework\domain\payload\Updated' => 'updated',
			'keeko\framework\domain\payload\Failed' => 'failed',
		];
	}

	protected function tokenCreated(Request $request, Created $payload) {
		return new Response($this->render('/keeko/account/templates/forgot-password-token-created.twig', $payload->get()));
	}

	protected function changePassword(Request $request, Found $payload) {
		return new Response($this->render('/keeko/account/templates/forgot-password-change-password.twig', $payload->get()));
	}

	protected function failed(Request $request, Failed $payload) {
		$prefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();
		$translator = $this->getServiceContainer()->getTranslator();
		$data = array_merge($payload->get(), [
			'link' => $prefs->getAccountUrl() . $translator->trans('slug.forgot-password')
		]);
		return new Response($this->render('/keeko/account/templates/forgot-password-failed.twig', $data));
	}

	protected function identifyUser(Request $request, Blank $payload) {
		$prefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();
		$data = array_merge($payload->get(), [
			'login_label' => $prefs->getUserLogin()
		]);
		return new Response($this->render('/keeko/account/templates/forgot-password-identify-user.twig', $data));
	}

	protected function updated(Request $request, Updated $payload) {
		$prefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();
		return new RedirectResponse($prefs->getAccountUrl());
	}
}
