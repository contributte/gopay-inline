<?php

namespace Markette\GopayInline\Api\Entity;

use Markette\GopayInline\Api\Objects\Contact;
use Markette\GopayInline\Api\Objects\Eet;
use Markette\GopayInline\Api\Objects\Item;
use Markette\GopayInline\Api\Objects\Parameter;
use Markette\GopayInline\Api\Objects\Payer;
use Markette\GopayInline\Api\Objects\Target;
use Markette\GopayInline\Exception\ValidationException;
use Markette\GopayInline\Utils\Validator;

class PaymentFactory
{

	// Validator's types
	const V_SCHEME = 1;
	const V_PRICES = 2;

	/** @var array */
	public static $required = [
		// 'target', # see at AbstractPaymentService
		'amount',
		'currency',
		'order_number',
		'order_description',
		'items',
		'return_url',
		'notify_url',
	];

	/** @var array */
	public static $optional = [
		'target',
		'payer',
		'additional_params',
		'lang',
		'eet',
	];

	/** @var array */
	public static $validators = [
		self::V_SCHEME => TRUE,
		self::V_PRICES => TRUE,
	];

	/**
	 * @param mixed $data
	 * @param array $validators
	 * @return Payment
	 */
	public static function create($data, $validators = [])
	{
		// Convert to array
		$data = (array) $data;
		$validators = $validators + self::$validators;

		// CHECK REQUIRED DATA ###################

		$res = Validator::validateRequired($data, self::$required);
		if ($res !== TRUE) {
			throw new ValidationException('Missing keys "' . (implode(', ', $res)) . '""');
		}

		// CHECK SCHEME DATA #####################

		$res = Validator::validateOptional($data, array_merge(self::$required, self::$optional));
		if ($res !== TRUE) {
			if ($validators[self::V_SCHEME] === TRUE) {
				throw new ValidationException('Not allowed keys "' . (implode(', ', $res)) . '""');
			}
		}

		// CREATE PAYMENT ########################

		$payment = new Payment();

		// ### PAYER
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

		// ### TARGET
		if (isset($data['target'])) {
			$target = new Target;
			self::map($target, ['type' => 'type', 'goid' => 'goid'], $data['target']);
			$payment->setTarget($target);
		}

		// ### COMMON
		$payment->setAmount($data['amount']);
		$payment->setCurrency($data['currency']);
		$payment->setOrderNumber($data['order_number']);
		$payment->setOrderDescription($data['order_description']);
		$payment->setReturnUrl($data['return_url']);
		$payment->setNotifyUrl($data['notify_url']);

		// ### ITEMS
		foreach ($data['items'] as $param) {
			if (!isset($param['name']) || !$param['name']) {
				if ($validators[self::V_SCHEME] === TRUE) {
					throw new ValidationException('Item\'s name can\'t be empty or null.');
				}
			}
			$item = new Item;
			self::map($item, [
				'name' => 'name',
				'amount' => 'amount',
				'count' => 'count',
				'vat_rate' => 'vatRate',
				'type' => 'type',
			], $param);
			$payment->addItem($item);
		}

		// ### ADDITIONAL PARAMETERS
		if (isset($data['additional_params'])) {
			foreach ($data['additional_params'] as $param) {
				$parameter = new Parameter;
				self::map($parameter, ['name' => 'name', 'value' => 'value'], $param);
				$payment->addParameter($parameter);
			}
		}

		// ### LANG
		if (isset($data['lang'])) {
			$payment->setLang($data['lang']);
		}

		// VALIDATION PRICE & ITEMS PRICE ########
		$itemsPrice = 0;
		$orderPrice = $payment->getAmount();
		foreach ($payment->getItems() as $item) {
			$itemsPrice += $item->amount * $item->count;
		}
		if ($itemsPrice !== $orderPrice) {
			if ($validators[self::V_PRICES] === TRUE) {
				throw new ValidationException(sprintf('Payment price (%s) and items price (%s) do not match', $orderPrice, $itemsPrice));
			}
		}

		// ### EET
		if (isset($data['eet'])) {
			$eet = new Eet();
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
			], $data['eet']);

			$eetSum = $eet->getSum();
			$eetTotal = $eet->getTax()
				+ $eet->getTaxBaseNoVat()
				+ $eet->getTaxBase()
				+ $eet->getTaxBaseReducedRateFirst()
				+ $eet->getTaxReducedRateFirst()
				+ $eet->getTaxBaseReducedRateSecond()
				+ $eet->getTaxReducedRateSecond();

			if ($validators[self::V_PRICES] === TRUE) {
				if (number_format($eetSum, 8) !== number_format($eetTotal, 8)) {
					throw new ValidationException(sprintf('EET sum (%s) and EET tax sum (%s) do not match', $eetSum, $eetTotal));
				}

				if (number_format($eetSum, 8) !== number_format($orderPrice, 8)) {
					throw new ValidationException(sprintf('EET sum (%s) and order sum (%s) do not match', $eetSum, $orderPrice));
				}
			}

			$payment->setEet($eet);
		}

		return $payment;
	}

	/**
	 * @param object $obj
	 * @param array $mapping
	 * @param array $data
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
