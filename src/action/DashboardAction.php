<?php
namespace keeko\account\action;

use keeko\account\AccountModule;
use keeko\core\model\ActivityQuery;
use keeko\framework\domain\payload\Blank;
use keeko\framework\foundation\AbstractAction;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Account Dashboard
 *
 * This code is automatically created. Modifications will probably be overwritten.
 *
 * @author gossi
 */
class DashboardAction extends AbstractAction {

	/**
	 * Automatically generated run method
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$page = $this->getServiceContainer()->getKernel()->getApplication()->getPage();
		$page->setTitle($this->getServiceContainer()->getTranslator()->trans('dashboard'));
		$user = $this->getServiceContainer()->getAuthManager()->getUser();
		$activities = ActivityQuery::create()->filterByActor($user)->limit(5)->orderById(Criteria::DESC)->find();
		$reg = $this->getServiceContainer()->getExtensionRegistry();
		return $this->responder->run($request, new Blank([
			'settings' => $reg->getExtensions(AccountModule::EXT_SETTINGS),
			'activities' => $activities
		]));
	}
}
