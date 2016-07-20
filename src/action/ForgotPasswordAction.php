<?php
namespace keeko\account\action;

use \DateTime;
use keeko\framework\domain\payload\Blank;
use keeko\framework\domain\payload\Created;
use keeko\framework\domain\payload\Found;
use keeko\framework\foundation\AbstractAction;
use keeko\framework\security\AuthManager;
use keeko\framework\utils\TwigRenderTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use keeko\framework\domain\payload\Updated;
use keeko\core\model\UserQuery;
use keeko\framework\domain\payload\Failed;

/**
 * Forgot Password
 *
 * This code is automatically created. Modifications will probably be overwritten.
 *
 * @author gossi
 */
class ForgotPasswordAction extends AbstractAction {

	use TwigRenderTrait;

	protected function configureParams(OptionsResolver $resolver) {
		$resolver->setDefined(['token']);
	}

	/**
	 * Automatically generated run method
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$token = $this->getParam('token');
		$auth = $this->getServiceContainer()->getAuthManager();
		$translator = $this->getServiceContainer()->getTranslator();

		$page = $this->getServiceContainer()->getKernel()->getApplication()->getPage();
		$page->setTitle($translator->trans('forgot_password'));

		if ($request->isMethod('POST')) {
			// reset password
			if (!empty($token)) {
				$post = $request->request;
				$user = $auth->getUser();

				$pwA = $post->get('new_password');
				$pwB = $post->get('new_password_confirm');

				if ($pwA == $pwB) {
					$user->setPassword($auth->encryptPassword($pwA));
					$user->setPasswordRecoverToken(null);
					$user->setPasswordRecoverTime(null);
					$user->save();
					$payload = new Updated();
				} else {
					$payload = new Found([
						'token' => $token,
						'destination' => $this->getTargetLocation($token),
						'error' => $translator->trans('change_password_nomatch')
					]);
				}

			}

			// generate token when user is identified
			else {
				$login = $request->request->get('login');
				$user = $auth->findUser($login);

				if ($user === null) {
					$message = $translator->trans('error.user_not_found', [], 'keeko.account');
					$payload = new Blank([
						'error' => $message,
						'login' => $login
					]);
				} else {
					$token = AuthManager::generateToken();
					$user->setPasswordRecoverToken($token);
					$user->setPasswordRecoverTime(new DateTime());
					$user->save();

					// send mail
					$prefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();
					$localeService = $this->getServiceContainer()->getLocaleService();
					$file = $localeService->findLocaleFile('/keeko/account/locales/{locale}/mail/forgot-password.twig');
					$body = $this->render($file, [
						'user' => $user->getDisplayName(),
						'plattform' => $prefs->getPlattformName(),
						'link' => $this->getTargetLocation($token)
					]);

					$mailer = $this->getServiceContainer()->getMailer();
					$message = $this->getServiceContainer()->createMessage();
					$message->setTo($user->getEmail());
					$message->setSubject($translator->trans('forgot_password.subject'));
					$message->setBody($body);
					$mailer->send($message);

					$payload = new Created(['mail' => $user->getEmail()]);
				}
			}
		}

		// recover pw formular with token
		else if (!empty($token)) {
			$user = UserQuery::create()->findOneByPasswordRecoverToken($token);
			if ($user !== null) {
				$now = new DateTime();
				$time = $user->getPasswordRecoverTime();
				$diff = $now->diff($time);

				if ($diff->h >= 1) {
					$payload = new Failed(['error' => $translator->trans('error.token_timeout')]);
				} else {
					$payload = new Found([
						'token' => $token,
						'destination' => $this->getTargetLocation($token)
					]);
				}
			} else {
				$payload = new Failed(['error' => $translator->trans('error.token_invalid')]);
			}
		}

		// anyway, identify user
		else {
			$payload = new Blank();
		}

		return $this->responder->run($request, $payload);
	}

	private function getTargetLocation($token) {
		$prefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();
		$translator = $this->getServiceContainer()->getTranslator();
		return sprintf('%s%s/%s',
			$prefs->getAccountUrl(),
			$translator->trans('slug.forgot-password'),
			$token
		);
	}
}
