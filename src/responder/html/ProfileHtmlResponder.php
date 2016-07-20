<?php
namespace keeko\account\responder\html;

use keeko\framework\domain\payload\PayloadInterface;
use keeko\framework\foundation\AbstractResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use keeko\framework\preferences\SystemPreferences;
use keeko\framework\domain\payload\Success;
use keeko\framework\domain\payload\Updated;

/**
 * Automatically generated HtmlResponder for Account Profile
 *
 * @author gossi
 */
class ProfileHtmlResponder extends AbstractResponder {

	/**
	 * Automatically generated run method
	 *
	 * @param Request $request
	 * @param PayloadInterface $payload
	 * @return Response
	 */
	public function run(Request $request, PayloadInterface $payload = null) {
		$prefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();

		$data = [
			'prefs' => [
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
			'nickname_label' => $prefs->getUserNames() != SystemPreferences::VALUE_NONE ? 'nick_name' : 'name',
			'target' => $request->getUri(),
			'submitted' => $request->isMethod('POST'),
			'success' => $payload instanceof Updated
		];
		return new Response($this->render('/keeko/account/templates/profile.twig', $data));
	}
}
