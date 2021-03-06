<?php
namespace keeko\account\action;

use keeko\framework\domain\payload\Blank;
use keeko\framework\foundation\AbstractAction;
use keeko\framework\security\AuthManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Account Login
 *
 * This code is automatically created. Modifications will probably be overwritten.
 *
 * @author gossi
 */
class LoginAction extends AbstractAction {

	/**
	 * Automatically generated run method
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$login = '';
		$error = '';
		$redirect = $request->headers->get('referer');
		$prefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();

		// try login
		if ($request->isMethod('POST')) {
			$login = $request->request->get('login');
			$password = $request->request->get('password');
			$redirect = rawurldecode($request->request->get('redirect'));

			$auth = $this->getServiceContainer()->getAuthManager();
			if ($auth->login($login, $password)) {
				$token = $auth->getSession()->getToken();
				$foward = $redirect ?: $prefs->getAccountUrl();
				$response = new RedirectResponse($foward);
				$this->getServiceContainer()->getKernel()->setCookie($response, AuthManager::COOKIE_TOKEN_NAME, $token);
				return $response;
			}

			$error = 'Invalid credentials';
		}

		$translator = $this->getServiceContainer()->getTranslator();
		$module = $this->getModule();
		$form = $module->getWidgetFactory()->createLoginWidget();
		$form = $form->build([
			'error' => $error,
			'destination' => $prefs->getAccountUrl() . '/' . $translator->trans('slug.login'),
			'redirect' => $redirect,
			'login' => $login
		]);

		return $this->responder->run($request, new Blank([
			'form' => $form
		]));
	}
}
