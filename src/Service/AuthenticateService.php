<?php

namespace Markette\GopayInline\Service;

use Markette\GopayInline\Api\Lists\Scope;
use Markette\GopayInline\Exception\AuthorizationException;

class AuthenticateService extends AbstractService
{

	/**
	 * @param string $scope
	 * @return bool
	 */
	public function verifyCredentials($scope = Scope::PAYMENT_ALL)
	{
		try {
			$this->doAuthorization($scope);
		} catch (AuthorizationException $e) {
			return FALSE;
		}
		return TRUE;
	}

}