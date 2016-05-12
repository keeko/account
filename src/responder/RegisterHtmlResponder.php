<?php
namespace keeko\account\responder;

use keeko\framework\domain\payload\Blank;
use keeko\framework\domain\payload\PayloadInterface;
use keeko\framework\foundation\AbstractPayloadResponder;
use Symfony\Component\HttpFoundation\Request;
use keeko\framework\domain\payload\Created;
use keeko\framework\preferences\SystemPreferences;
use Symfony\Component\HttpFoundation\Response;

/**
 * Automatically generated HtmlResponder for Registration
 * 
 * @author gossi
 */
class RegisterHtmlResponder extends AbstractPayloadResponder {

	/**
	 */
	protected function getPayloadMethods() {
		return [
			'keeko\framework\domain\payload\Blank' => 'form',
			'keeko\framework\domain\payload\NotValid' => 'form',
			'keeko\framework\domain\payload\Created' => 'created',
		];
	}
	
	protected function form(Request $request, PayloadInterface $payload) {
		$prefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();

		$data = array_merge($payload->get(), [
			'prefs' => [
				'login' => $prefs->getUserLogin(),
				'user_names' => $prefs->getUserNames(),
				'email' => $prefs->getUserEmail(),
				'sex' => $prefs->getUserSex(),
				'birth' => $prefs->getUserBirth(),
				'nick_name' => $prefs->getUserNickname(),
				'display_name' => $prefs->getUserDisplayName(),
				'display_option_given_family' => $prefs->getUserDisplayOptionGivenFamilyName(),
				'display_option_family_given' => $prefs->getUserDisplayOptionFamilyGivenName(),
				'display_option_username' => $prefs->getUserDisplayOptionUsername(),
				'display_option_nickname' => $prefs->getUserDisplayOptionNickname(),
				'display_given_family' => SystemPreferences::DISPLAY_GIVENFAMILYNAME,
				'display_family_given' => SystemPreferences::DISPLAY_FAMILYGIVENNAME,
				'display_nickname' => SystemPreferences::DISPLAY_NICKNAME,
				'display_username' => SystemPreferences::DISPLAY_USERNAME
			],
			'target' => $this->getServiceContainer()->getKernel()->getApplication()->getFullUrl()
		]);
		
		return new Response($this->render('/keeko/account/templates/register.twig', $data));
	}
	
	protected function created(Request $request, Created $payload) {
		return new Response($this->render('/keeko/account/templates/registered.twig', $payload->get()));
	}
}