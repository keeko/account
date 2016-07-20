<?php
namespace keeko\account\action;

use keeko\framework\domain\payload\Blank;
use keeko\framework\domain\payload\Failed;
use keeko\framework\domain\payload\Success;
use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Change Password
 *
 * This code is automatically created. Modifications will probably be overwritten.
 *
 * @author gossi
 */
class ChangePasswordAction extends AbstractAction {

	/**
	 * Automatically generated run method
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$page = $this->getServiceContainer()->getKernel()->getApplication()->getPage();
		$page->setTitle($this->getServiceContainer()->getTranslator()->trans('change_password'));

		$payload = new Blank();
		if ($request->isMethod('POST')) {
			$post = $request->request;
			$auth = $this->getServiceContainer()->getAuthManager();
			$user = $auth->getUser();
			$payload = new Failed();

			if ($post->has('password') && $auth->verifyUser($user, $post->get('password'))) {
				$pwA = $post->get('new_password');
				$pwB = $post->get('new_password_confirm');

				if ($pwA == $pwB) {
					$user->setPassword($auth->encryptPassword($pwA));
					$user->save();
					$payload = new Success();
				}
			}
		}

		return $this->responder->run($request, $payload);
	}
}
