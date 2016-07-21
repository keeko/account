<?php
namespace keeko\account\action;

use keeko\core\domain\UserDomain;
use keeko\core\model\User;
use keeko\framework\domain\payload\Blank;
use keeko\framework\domain\payload\Created;
use keeko\framework\domain\payload\NotValid;
use keeko\framework\foundation\AbstractAction;
use keeko\framework\preferences\SystemPreferences;
use keeko\framework\utils\TwigRenderTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Registration
 *
 * This code is automatically created. Modifications will probably be overwritten.
 *
 * @author gossi
 */
class RegisterAction extends AbstractAction {

	use TwigRenderTrait;

	/**
	 * Automatically generated run method
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$page = $this->getServiceContainer()->getKernel()->getApplication()->getPage();
		$page->setTitle($this->getServiceContainer()->getTranslator()->trans('registration'));

		if ($request->isMethod('POST')) {
			$post = $request->request;
			$prefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();
			$translator = $this->getServiceContainer()->getTranslator();

			// check errors first
			$errors = [];

			// username
			if ($prefs->getUserLogin() == SystemPreferences::LOGIN_USERNAME && !$post->has('user-name')) {
				$errors[] = $translator->trans('error.required', [
					'field' => $translator->trans('user_name')
				]);
			}

			// email
			$emailRequired = $prefs->getUserEmail()
				|| $prefs->getUserLogin() == SystemPreferences::LOGIN_EMAIL
				|| $prefs->getUserLogin() == SystemPreferences::LOGIN_USERNAME_EMAIL;

			if ($emailRequired && !$post->has('email')) {
				$errors[] = $translator->trans('error.required', [
					'field' => $translator->trans('email')
				]);
			}

			// given name
			if ($prefs->getUserNames() == SystemPreferences::VALUE_REQUIRED && !$post->has('given-name')) {
				$errors[] = $translator->trans('error.required', [
					'field' => $translator->trans('given_name')
				]);
			}

			// family name
			if ($prefs->getUserNames() == SystemPreferences::VALUE_REQUIRED && !$post->has('family-name')) {
				$errors[] = $translator->trans('error.required', [
					'field' => $translator->trans('family_name')
				]);
			}

			// birth
			if ($prefs->getUserBirth() == SystemPreferences::VALUE_REQUIRED && !$post->has('birth')) {
				$errors[] = $translator->trans('error.required', [
					'field' => $translator->trans('birth')
				]);
			}

			// sex
			if ($prefs->getUserSex() == SystemPreferences::VALUE_REQUIRED && !$post->has('sex')) {
				$errors[] = $translator->trans('error.required', [
					'field' => $translator->trans('sex')
				]);
			}

			// passwords
			if (!$post->has('password') && !$post->has('password_confirm')) {
				$errors[] = $translator->trans('error.required', [
					'field' => $translator->trans('password')
				]);
			}

			if ($post->get('password') != $post->get('password_confirm')) {
				$errors[] = $translator->trans('error.password_nomatch');
			}


			$vars = ['failures' => $errors, 'fields' => $post->all()];
			if (count($errors) == 0) {
				$serializer = User::getSerializer();
				$fields = $serializer->getFields();
				$attribs = [
					'password' => $post->get('password')
				];

				foreach ($fields as $field) {
					if ($post->has($field)) {
						$attribs[$field] = $post->get($field);
					}
				}

				$domain = new UserDomain($this->getServiceContainer());
				$payload = $domain->create(['attributes' => $attribs]);
				if ($payload instanceof NotValid) {
					$payload = new NotValid(array_merge($payload->get(), $vars));
				} else if ($payload instanceof Created) {
					$user = $payload->getModel();

					// send mail
					$prefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();
					$localeService = $this->getServiceContainer()->getLocaleService();
					$file = $localeService->findLocaleFile('/keeko/account/locales/{locale}/mail/registration.twig');
					$body = $this->render($file, [
						'user' => $user->getDisplayName(),
						'username' => $user->getUserName(),
						'plattform' => $prefs->getPlattformName(),
					]);

					$mailer = $this->getServiceContainer()->getMailer();
					$message = $this->getServiceContainer()->createMessage();
					$message->setTo($user->getEmail());
					$message->setSubject($translator->trans('registration.subject', [
						'plattform' => $prefs->getPlattformName()
					]));
					$message->setBody($body);
					$mailer->send($message);
				}
			} else {
				$post->remove('password');
				$post->remove('password_confirm');
				$payload = new NotValid($vars);
			}
		} else {
			$payload = new Blank();
		}

		return $this->responder->run($request, $payload);
	}
}
