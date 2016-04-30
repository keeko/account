<?php
namespace keeko\account\action;

use keeko\framework\domain\payload\Blank;
use keeko\framework\domain\payload\Failed;
use keeko\framework\domain\payload\Success;
use keeko\framework\foundation\AbstractAction;
use keeko\framework\preferences\SystemPreferences;
use Propel\Runtime\Exception\PropelException;
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
			
			if ($post->has('given_name')) {
				$user->setGivenName($post->get('given_name'));
			}
			
			if ($post->has('family_name')) {
				$user->setFamilyName($post->get('family_name'));
			}
			
			if ($post->has('birth')) {
				$user->setBirthday($post->get('birth'));
			}
			
			if ($post->has('sex')) {
				$user->setSex($post->get('sex'));
			}
			
			if ($post->has('nick_name')) {
				$user->setNickName($post->get('nick_name'));
			}
			
			$user->setEmail($post->get('email'));
			
			if ($post->has('display_name')) {
				switch ($post->get('display_name')) {
					case SystemPreferences::DISPLAY_GIVENFAMILYNAME:
						$user->setDisplayName($user->getGivenName() . ' ' . $user->getFamilyName());
						break;
						
					case SystemPreferences::DISPLAY_FAMILYGIVENNAME:
						$user->setDisplayName($user->getFamilyName() . ' ' . $user->getGivenName());
						break;
						
					case SystemPreferences::DISPLAY_NICKNAME:
						$user->setDisplayName($user->getNickName());
						break;
						
					case SystemPreferences::DISPLAY_USERNAME:
						$user->setDisplayName($user->getUserName());
						break;
				}
			}
			
			try {
				$user->save();
				$payload = new Success();
			} catch (PropelException $e) {
				$payload = new Failed();
			}
			
		}
		return $this->responder->run($request, $payload);
	}
}
