<?php
namespace keeko\account\action;

use keeko\core\model\SessionQuery;
use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * User Sessions
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
class SessionsAction extends AbstractAction {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		if ($request->isMethod('POST')) {
			$post = $request->request;
			if ($post->has('token')) {
				SessionQuery::create()->findOneByToken($post->get('token'))->delete();
				$service = $this->getServiceContainer();
				$accountUrl = $service->getPreferenceLoader()->getSystemPreferences()->getAccountUrl();
				$translator = $service->getTranslator();
				$url = sprintf('%s%s/%s',
					$accountUrl,
					$translator->trans('slug.settings'),
					$translator->trans('slug.settings.sessions')
				);
				return new RedirectResponse($url);
			}
		}
		
		return $this->responder->run($request);
	}
}
