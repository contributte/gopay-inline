<?php

namespace Markette\GopayInline\Service;

use Markette\GopayInline\Api\Lists\Scope;
use Markette\GopayInline\Exception\HttpException;

class AuthenticationService extends AbstractService
{

	/**
	 * @param string $scope
	 * @return bool
	 */
	public function verifyCredentials($scope = Scope::PAYMENT_ALL)
	{
		try {
			$this->doAuthorization($scope);
		} catch (HttpException $e) {
			return FALSE;
		}
		return TRUE;
	}

}