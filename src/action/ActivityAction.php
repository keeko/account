<?php
namespace keeko\account\action;

use keeko\core\model\ActivityQuery;
use keeko\framework\domain\payload\Blank;
use keeko\framework\foundation\AbstractAction;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Activity Log
 *
 * This code is automatically created. Modifications will probably be overwritten.
 *
 * @author Thomas Gossmann
 */
class ActivityAction extends AbstractAction {

	/**
	 * Automatically generated run method
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function run(Request $request) {
		$user = $this->getServiceContainer()->getAuthManager()->getUser();
		$activities = ActivityQuery::create()->filterByActor($user)->orderById(Criteria::DESC)->find();

		return $this->responder->run($request, new Blank([
			'activities' => $activities
		]));
	}
}
