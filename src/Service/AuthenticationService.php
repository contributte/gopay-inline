<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Service;

use Contributte\GopayInline\Api\Lists\Scope;
use Contributte\GopayInline\Exception\HttpException;

class AuthenticationService extends AbstractService
{

	public function verify(string $scope = Scope::PAYMENT_ALL): bool
	{
		try {
			$this->doAuthorization($scope);
		} catch (HttpException $e) {
			return false;
		}

		return true;
	}

}
