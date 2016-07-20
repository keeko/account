<?php
namespace keeko\account\action;

use keeko\framework\domain\payload\Blank;
use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Account
 *
 * This code is automatically created. Modifications will probably be overwritten.
 *
 * @author gossi
 */
class AccountAction extends AbstractAction {

	/**
	 * Automatically generated run method
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$prefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();
		$routes = $this->generateRoutes();
		$response = new Response();
		$context = new RequestContext($prefs->getAccountUrl());
		$matcher = new UrlMatcher($routes, $context);
		$payload = [];

		$match = $matcher->match($this->getDestination());
		$route = $match['_route'];
		$module = $this->getModule();
		$auth = $this->getServiceContainer()->getAuthManager();
		$action = null;

		switch ($route) {
			case 'index':
				if ($auth->isRecognized()) {
					$action = $module->loadAction('dashboard', 'html');
				} else {
					$action = $module->loadAction('index', 'html');
				}
				break;

			case 'register':
				$action = $module->loadAction('register', 'html');
				break;

			case 'forgot-password':
			case 'forgot-password-token':
				$action = $module->loadAction('forgot-password', 'html');
				$action->setParams([
					'token' => isset($match['token']) ? $match['token'] : null
				]);
				break;

			case 'login':
				$action = $module->loadAction('login', 'html');
				break;

			case 'activity':
				$action = $module->loadAction('activity', 'html');
				break;

			case 'settings':
				$action = $module->loadAction('settings', 'html');
				$action->setParams([
					'section' => $match['section']
				]);
				break;

			case 'logout':
				$action = $module->loadAction('logout');
				break;
		}

		$kernel = $this->getServiceContainer()->getKernel();
		$response = $kernel->handle($action, $request);

		if ($response instanceof RedirectResponse) {
			return $response;
		}

		$payload = [
			'main' => $response->getContent(),
		];

		return $this->responder->run($request, new Blank($payload));
	}

	private function generateRoutes() {
		$translator = $this->getServiceContainer()->getTranslator();
		$routes = new RouteCollection();
		$routes->add('index', new Route('/'));
		$routes->add('register', new Route('/' . $translator->trans('slug.register')));
		$routes->add('login', new Route('/' . $translator->trans('slug.login')));
		$routes->add('logout', new Route('/' . $translator->trans('slug.logout')));
		$routes->add('forgot-password', new Route('/' . $translator->trans('slug.forgot-password')));
		$routes->add('forgot-password-token', new Route('/' . $translator->trans('slug.forgot-password') . '/{token}'));

		$routes->add('activity', new Route('/' . $translator->trans('slug.activity')));
		$routes->add('settings', new Route('/' . $translator->trans('slug.settings') . '/{section}'));

		return $routes;
	}
}
