<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Service;

use Contributte\GopayInline\Api\Entity\Payment;
use Contributte\GopayInline\Api\Lists\TargetType;
use Contributte\GopayInline\Api\Objects\Target;

abstract class AbstractPaymentService extends AbstractService
{

	/**
	 * Add required target field
	 */
	protected function preConfigure(Payment $payment): void
	{
		if (!$payment->hasTarget()) {
			$target = new Target();
			$target->goid = $this->client->getGoId();
			$target->type = TargetType::ACCOUNT;

			$payment->setTarget($target);
		}
	}

}
