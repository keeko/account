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

// 			if ($payload instanceof Updated) {
// 				if ($post->has('display_name')) {
// 					switch ($post->get('display_name')) {
// 						case SystemPreferences::DISPLAY_GIVENFAMILYNAME:
// 							$user->setDisplayName($user->getGivenName() . ' ' . $user->getFamilyName());
// 							break;

// 						case SystemPreferences::DISPLAY_FAMILYGIVENNAME:
// 							$user->setDisplayName($user->getFamilyName() . ' ' . $user->getGivenName());
// 							break;

// 						case SystemPreferences::DISPLAY_NICKNAME:
// 							$user->setDisplayName($user->getNickName());
// 							break;

// 						case SystemPreferences::DISPLAY_USERNAME:
// 							$user->setDisplayName($user->getUserName());
// 							break;
// 					}

// 					$user->save();
// 				}
// 			}
		}
		return $this->responder->run($request, $payload);
	}
}
