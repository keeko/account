<?php
namespace keeko\account\action;

use keeko\account\AccountModule;
use keeko\framework\domain\payload\Blank;
use keeko\framework\foundation\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Account Settings
 * 
 * This code is automatically created. Modifications will probably be overwritten.
 * 
 * @author gossi
 */
class SettingsAction extends AbstractAction {

	protected function configureParams(OptionsResolver $resolver) {
		$resolver->setRequired(['section']);
	}
	
	/**
	 * Automatically generated run method
	 * 
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$prefs = $this->getServiceContainer()->getPreferenceLoader()->getSystemPreferences();
		$translator = $this->getServiceContainer()->getTranslator();
		$reg = $this->getServiceContainer()->getExtensionRegistry();
		$settings = $reg->getExtensions(AccountModule::EXT_SETTINGS);
		$section = $this->getParam('section');
		$routes = [];

		// build settings routes
		foreach ($settings as $ext) {
			$routes[$translator->trans($ext['slug'])] = $ext;
		}
			
		if (!isset($routes[$section])) {
			$url = $prefs->getAccountUrl() . $translator->trans('slug.settings');
			throw new ResourceNotFoundException(sprintf('No route found for %s/%s', $url, $section));
		}
			
		$ext = $routes[$section];
		$kernel = $this->getServiceContainer()->getKernel();
		$module = $this->getServiceContainer()->getModuleManager()->load($ext['module']);
		$action = $module->loadAction($ext['action'], 'html');
		$response = $kernel->handle($action, $request);
		
		if ($response instanceof RedirectResponse) {
			return $response;
		}
		
		return $this->responder->run($request, new Blank([
			'settings' => $settings, 
			'section' => $response->getContent()
		]));
	}
}
