<?php
namespace keeko\account\responder;

use keeko\framework\domain\payload\PayloadInterface;
use keeko\framework\foundation\AbstractResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Automatically generated HtmlResponder for User Sessions
 * 
 * @author gossi
 */
class SessionsHtmlResponder extends AbstractResponder {

	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @param PayloadInterface $payload
	 * @return Response
	 */
	public function run(Request $request, PayloadInterface $payload = null) {
		$session = $this->getServiceContainer()->getAuthManager()->getSession();
		return new Response($this->render('/keeko/account/templates/sessions.twig', [
			'target' => $this->getServiceContainer()->getKernel()->getApplication()->getFullUrl(),
			'icons' => $this->getIcons(),
			'active_session' => $session
		]));
	}
	
	private function getIcons() {
		return [
			// devices
			'desktop' => 'desktop',
			'smartphone' => 'mobile',
			'feature phone' => 'mobile',
			'phablet' => 'mobile',
			'tablet' => 'tablet',
			'tv' => 'television',
			'car browser' => 'car',
			'camera' => 'camera',
			'console' => 'gamepad',
			'portable media player' => 'play',
			'smart display' => 'television',
			
			// os
			'android' => 'android',
			'chrome os' => 'chrome',
			'firefox os' => 'firefox',
			'google tv' => 'android',
			
			'mac' => 'apple',
			'apple tv' => 'apple',
			'ios' => 'apple',
			
			'arch linux' => 'linux',
			'centos' => 'linux',
			'debian' => 'linux',
			'fedora' => 'linux',
			'freebsd' => 'linux',
			'gentoo' => 'linux',
			'knoppix' => 'linux',
			'kubuntu' => 'linux',
			'gnu/linux' => 'linux',
			'lubuntu' => 'linux',
			'vectorlinux' => 'linux',
			'mandriva' => 'linux',
			'mint' => 'linux',
			'netbsd' => 'linux',
			'openbsd' => 'linux',
			'red hat' => 'linux',
			'suse' => 'linux',
			'slackware' => 'linux',
			'ubuntu' => 'linux',
			'xubuntu' => 'linux',
			
			'windows' => 'windows',
			'windows ce' => 'windows',
			'windows mobile' => 'windows',
			'windows phone' => 'windows',
			'windows rt' => 'windows',
			
			
			
			// browser
			'firefox' => 'firefox',
			'iceweasel' => 'firefox',
			
			'chrome' => 'chrome',
			'chrome frame' => 'chrome',
			'chrome mobile ios' => 'chrome',
			'chrome mobile' => 'chrome',
			'chromium' => 'chrome',
			
			'opera' => 'opera',
			'opera mini' => 'opera',
			'opera mobile' => 'opera',
			'opera next' => 'opera',
			
			'safari' => 'safari',
			'mobile safari' => 'safari',
			
			'microsoft edge' => 'edge',
			'internet explorer' => 'internet-explorer',
			'ie mobile' => 'internet-explorer',
			
			'android browser' => 'android'
		];
	}
}
