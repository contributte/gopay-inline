<?php

declare(strict_types=1);

namespace Contributte\GopayInline\Api\Entity;


use Contributte\GopayInline\Api\Objects\Contact;
use Contributte\GopayInline\Api\Objects\Eet;
use Contributte\GopayInline\Api\Objects\Item;
use Contributte\GopayInline\Api\Objects\Parameter;
use Contributte\GopayInline\Api\Objects\Payer;
use Contributte\GopayInline\Api\Objects\Target;
use Contributte\GopayInline\Exception\ValidationException;
use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;

final class PaymentFactory
{
	public const V_SCHEME = 1;

	public const V_PRICES = 2;

	/** @var true[] (int => true) */
	public static $validators = [
		self::V_SCHEME => true,
		self::V_PRICES => true,
	];


	public static function getConfigSchema(): Schema
	{
		return Expect::structure([
			'amount' => Expect::type('float|int')->castTo('float')->required(),
			'currency' => Expect::string()->required(),
			'order_number' => Expect::type('string|int')->castTo('string')->required(),
			'items' => Expect::arrayOf(Expect::structure([
				'name' => Expect::string()->required(),
				'amount' => Expect::type('int|float')->castTo('float')->required(),
				'count' => Expect::int()->default(1),
				'type' => Expect::string(),
				'vat_rate' => Expect::int(),
			])->castTo('array'))->castTo('array'),
			'callback' => Expect::structure([
				'return_url' => Expect::string()->required(),
				'notify_url' => Expect::string()->required(),
			])->castTo('array')->required(),
			'payer' => Expect::structure([
				'default_payment_instrument' => Expect::string()->required(),
				'allowed_payment_instruments' => Expect::arrayOf(Expect::string()),
				'contact' => Expect::structure([
					'first_name' => Expect::string(),
					'last_name' => Expect::string(),
					'email' => Expect::string(),
					'phone_number' => Expect::string(),
					'city' => Expect::string(),
					'street' => Expect::string(),
					'postal_code' => Expect::string(),
					'country_code' => Expect::string(),
				])->castTo('array')->required(),
			])->castTo('array')->required(),
			'lang' => Expect::string()->pattern('[A-Z]{2}'),
			'target' => Expect::structure([
				'type' => Expect::string(),
				'goid' => Expect::float(),
			])->castTo('array'),
			'order_description' => Expect::string(),
			'additional_params' => Expect::arrayOf(Expect::structure([
				'name' => Expect::string()->required(),
				'value' => Expect::mixed(),
			])->castTo('array'))->castTo('array'),
			'eet' => Expect::structure([
				'mena' => Expect::string(),
				'celk_trzba' => Expect::float(),
				'zakl_dan1' => Expect::float(),
				'zakl_nepodl_dph' => Expect::float(),
				'dan1' => Expect::float(),
				'zakl_dan2' => Expect::float(),
				'dan2' => Expect::float(),
				'zakl_dan3' => Expect::float(),
				'dan3' => Expect::float(),
				'urceno_cerp_zuct' => Expect::float(),
				'cerp_zuct' => Expect::float(),
			])->castTo('array'),
			'preauthorization' => Expect::bool(),
		])->castTo('array')->otherItems();
	}


	/**
	 * @param mixed[] $data
	 * @param mixed[] $validators
	 * @return Payment
	 */
	public static function create(array $data, array $validators = []): Payment
	{
		$validators = $validators + self::$validators;
		$data = (new Processor)->process(self::getConfigSchema(), $data);

		$payment = new Payment;
		if (isset($data['payer'])) {
			$payer = new Payer;
			self::map($payer, [
				'allowed_payment_instruments' => 'allowedPaymentInstruments',
				'default_payment_instrument' => 'defaultPaymentInstrument',
				'allowed_swifts' => 'allowedSwifts',
				'default_swift' => 'defaultSwift',
			], $data['payer']);
			$payment->setPayer($payer);

			if (isset($data['payer']['contact'])) {
				$contact = new Contact;
				self::map($contact, [
					'first_name' => 'firstname',
					'last_name' => 'lastname',
					'email' => 'email',
					'phone_number' => 'phone',
					'city' => 'city',
					'street' => 'street',
					'postal_code' => 'zip',
					'country_code' => 'country',
				], $data['payer']['contact']);
				$payer->contact = $contact;
			}
		}
		if (isset($data['target']['goid'])) {
			$target = new Target;
			self::map($target, ['type' => 'type', 'goid' => 'goid'], $data['target']);
			$payment->setTarget($target);
		}

		$payment->setAmount((float) $data['amount']);
		$payment->setCurrency($data['currency']);
		$payment->setOrderNumber((string) $data['order_number']);
		if (isset($data['order_description'])) {
			$payment->setOrderDescription($data['order_description']);
		}
		$payment->setReturnUrl($data['callback']['return_url']);
		$payment->setNotifyUrl($data['callback']['notify_url']);

		foreach ($data['items'] as $orderItem) {
			$item = new Item;
			self::map($item, [
				'name' => 'name',
				'amount' => 'amount',
				'count' => 'count',
				'vat_rate' => 'vatRate',
				'type' => 'type',
			], $orderItem);
			$payment->addItem($item);
		}
		if (isset($data['additional_params'])) {
			foreach ($data['additional_params'] as $orderItem) {
				$parameter = new Parameter;
				self::map($parameter, ['name' => 'name', 'value' => 'value'], $orderItem);
				$payment->addParameter($parameter);
			}
		}
		if (isset($data['lang'])) {
			$payment->setLang($data['lang']);
		}

		$itemsPrice = 0;
		$orderPrice = $payment->getAmount();
		foreach ($payment->getItems() as $item) {
			$itemsPrice += $item->amount;
		}
		if ($itemsPrice !== $orderPrice && $validators[self::V_PRICES] === true) {
			throw new ValidationException(sprintf('Payment price (%s) and items price (%s) do not match', $orderPrice, $itemsPrice));
		}
		if (isset($data['eet']['mena'])) {
			$eet = new Eet;
			self::map($eet, [
				'mena' => 'currency',
				'celk_trzba' => 'sum',
				'zakl_dan1' => 'taxBase',
				'zakl_nepodl_dph' => 'taxBaseNoVat',
				'dan1' => 'tax',
				'zakl_dan2' => 'taxBaseReducedRateFirst',
				'dan2' => 'taxReducedRateFirst',
				'zakl_dan3' => 'taxBaseReducedRateSecond',
				'dan3' => 'taxReducedRateSecond',
				'urceno_cerp_zuct' => 'subsequentDrawing',
				'cerp_zuct' => 'subsequentlyDrawn',
			], $data['eet']);

			$eetSum = $eet->getSum();
			$eetTotal = $eet->getTax()
				+ $eet->getTaxBaseNoVat()
				+ $eet->getTaxBase()
				+ $eet->getTaxBaseReducedRateFirst()
				+ $eet->getTaxReducedRateFirst()
				+ $eet->getTaxBaseReducedRateSecond()
				+ $eet->getTaxReducedRateSecond()
				+ $eet->getSubsequentDrawing()
				+ $eet->getSubsequentlyDrawn();

			if ($validators[self::V_PRICES] === true) {
				if (number_format($eetSum, 8) !== number_format($eetTotal, 8)) {
					throw new ValidationException(sprintf('EET sum (%s) and EET tax sum (%s) do not match', $eetSum, $eetTotal));
				}
				if (number_format($eetSum, 8) !== number_format($orderPrice, 8)) {
					throw new ValidationException(sprintf('EET sum (%s) and order sum (%s) do not match', $eetSum, $orderPrice));
				}
			}

			$payment->setEet($eet);
		}
		if (isset($data['preauthorization'])) {
			$payment->setPreauthorization($data['preauthorization']);
		}

		return $payment;
	}


	/**
	 * @param object $obj
	 * @param mixed[] $mapping
	 * @param mixed[] $data
	 * @return object
	 */
	public static function map($obj, array $mapping, array $data)
	{
		foreach ($mapping as $from => $to) {
			if (isset($data[$from])) {
				$obj->{$to} = $data[$from];
			}
		}

		return $obj;
	}
}
