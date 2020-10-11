<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Service;


use Contributte\GopayInline\Api\Lists\Scope;
use Contributte\GopayInline\Exception\HttpException;

class AuthenticationService extends AbstractService
{

	/**
	 * @param string $scope
	 * @return bool
	 */
	public function verify($scope = Scope::PAYMENT_ALL)
	{
		try {
			$this->doAuthorization($scope);
		} catch (HttpException $e) {
			return false;
		}

		return true;
	}

}
