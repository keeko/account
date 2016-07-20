<?php
namespace keeko\account\action;

use keeko\core\domain\UserDomain;
use keeko\core\model\User;
use keeko\framework\domain\payload\Blank;
use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Account Profile
 *
 * This code is automatically created. Modifications will probably be overwritten.
 *
 * @author gossi
 */
class ProfileAction extends AbstractAction {

	/**
	 * Automatically generated run method
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$page = $this->getServiceContainer()->getKernel()->getApplication()->getPage();
		$page->setTitle($this->getServiceContainer()->getTranslator()->trans('profile'));

		$payload = new Blank();
		if ($request->isMethod('POST')) {
			$post = $request->request;
			$user = $this->getServiceContainer()->getAuthManager()->getUser();
			$domain = new UserDomain($this->getServiceContainer());
			$serializer = User::getSerializer();

			$fields = $serializer->getFields();
			$fields[] = 'display_name_user_select';
			$attribs = [];

			foreach ($fields as $field) {
				if ($post->has($field)) {
					$attribs[$field] = $post->get($field);
				}
			}

			$payload = $domain->update($user->getId(), ['attributes' => $attribs]);
		}
		return $this->responder->run($request, $payload);
	}
}
