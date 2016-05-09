<?php
namespace keeko\account\action;

use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use keeko\framework\preferences\SystemPreferences;
use keeko\core\model\User;
use keeko\core\domain\UserDomain;
use keeko\framework\domain\payload\Blank;
use keeko\framework\domain\payload\NotValid;

/**
 * Registration
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
class RegisterAction extends AbstractAction {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		if ($request->isMethod('POST')) {
			$post = $request->request;
			$prefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();
			$translator = $this->getServiceContainer()->getTranslator();
			
			// check errors first
			$errors = [];
			
			// username
			if ($prefs->getUserLogin() == SystemPreferences::LOGIN_USERNAME && !$post->has('user_name')) {
				$errors['user_name'] = $translator->trans('error.required', [
					'field' => $translator->trans('user_name')
				]);
			}
			
			// email
			$emailRequired = $prefs->getUserEmail()
				|| $prefs->getUserLogin() == SystemPreferences::LOGIN_EMAIL
				|| $prefs->getUserLogin() == SystemPreferences::LOGIN_USERNAME_EMAIL;
			
			if ($emailRequired && !$post->has('email')) {
				$errors['email'] = $translator->trans('error.required', [
					'field' => $translator->trans('email')
				]);
			}
			
			// given name
			if ($prefs->getUserNames() == SystemPreferences::VALUE_REQUIRED && !$post->has('given_name')) {
				$errors['given_name'] = $translator->trans('error.required', [
					'field' => $translator->trans('given_name')
				]);
			}
			
			// family name
			if ($prefs->getUserNames() == SystemPreferences::VALUE_REQUIRED && !$post->has('family_name')) {
				$errors['family_name'] = $translator->trans('error.required', [
					'field' => $translator->trans('family_name')
				]);
			}
			
			// birth
			if ($prefs->getUserBirth() == SystemPreferences::VALUE_REQUIRED && !$post->has('birth')) {
				$errors['birth'] = $translator->trans('error.required', [
					'field' => $translator->trans('birth')
				]);
			}
			
			// sex
			if ($prefs->getUserSex() == SystemPreferences::VALUE_REQUIRED && !$post->has('sex')) {
				$errors['sex'] = $translator->trans('error.required', [
					'field' => $translator->trans('sex')
				]);
			}
			
			// passwords
			if (!$post->has('password') && !$post->has('password_confirm')) {
				$errors['password'] = $translator->trans('error.required', [
					'field' => $translator->trans('password')
				]);
			}
			
			if ($post->get('password') != $post->get('password_confirm')) {
				$errors['password'] = $translator->trans('error.password_nomatch');
			}

			if (count($errors) == 0) {
				$serializer = User::getSerializer();
				$fields = $serializer->getFields();
				$attribs = [];
				
				foreach ($fields as $field) {
					if ($post->has($field)) {
						$attribs[$field] = $post->get($field);
					}
				}
				
				$domain = new UserDomain($this->getServiceContainer());
				$payload = $domain->create(['attributes' => $attribs]);
			} else {
				$post->remove('password');
				$post->remove('password_confirm');
				$payload = new NotValid(['errors' => $errors, 'fields' => $post->all()]);
			}
		} else {
			$payload = new Blank();
		}
		
		return $this->responder->run($request, $payload);
	}
}
