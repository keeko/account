<?php
namespace keeko\account\responder\html;

use keeko\framework\domain\payload\PayloadInterface;
use keeko\framework\foundation\AbstractResponder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use keeko\framework\domain\payload\Success;

/**
 * Automatically generated HtmlResponder for Change Password
 *
 * @author gossi
 */
class ChangePasswordHtmlResponder extends AbstractResponder {

	/**
	 * Automatically generated run method
	 *
	 * @param Request $request
	 * @param PayloadInterface $payload
	 * @return Response
	 */
	public function run(Request $request, PayloadInterface $payload = null) {
		return new Response($this->render('/keeko/account/templates/change-password.twig', [
			'target' => $request->getUri(),
			'submitted' => $request->isMethod('POST'),
			'success' => $payload instanceof Success
		]));
	}
}
