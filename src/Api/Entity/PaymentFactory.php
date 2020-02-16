<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Api\Entity;

use Contributte\GopayInline\Api\Objects\Contact;
use Contributte\GopayInline\Api\Objects\Eet;
use Contributte\GopayInline\Api\Objects\Item;
use Contributte\GopayInline\Api\Objects\Parameter;
use Contributte\GopayInline\Api\Objects\Payer;
use Contributte\GopayInline\Api\Objects\Target;
use Contributte\GopayInline\Exception\ValidationException;
use Contributte\GopayInline\Utils\Validator;
use Money\Currency;
use Money\Money;

class PaymentFactory
{

	// Validator's types
	public const V_SCHEME = 1;
	public const V_PRICES = 2;

	/** @var string[] */
	public static $required = [
		'amount',
		'order_number',
		'items',
		'callback',
	];

	/** @var string[] */
	public static $requiredCallback = [
		'return_url',
		'notify_url',
	];

	/** @var string[] */
	public static $optional = [
		'target', // see at AbstractPaymentService
		'payer',
		'order_description',
		'additional_params',
		'lang',
		'eet',
		'preauthorization',
	];

	/** @var array<int, bool> */
	public static $validators = [
		self::V_SCHEME => true,
		self::V_PRICES => true,
	];

	/**
	 * @param mixed[] $data
	 * @param mixed[] $validators
	 */
	public static function create(array $data, array $validators = []): Payment
	{
		// Convert to array
		$data = (array) $data;
		$validators += self::$validators;

		// CHECK REQUIRED DATA ###################

		$res = Validator::validateRequired($data, self::$required);
		if ($res !== true) {
			throw new ValidationException('Missing keys "' . (implode(', ', $res)) . '"');
		}

		$res = Validator::validateRequired($data['callback'], self::$requiredCallback);
		if ($res !== true) {
			throw new ValidationException('Missing keys "' . (implode(', ', $res)) . '" in callback definition');
		}

		// CHECK SCHEME DATA #####################

		$res = Validator::validateOptional($data, array_merge(self::$required, self::$optional));
		if ($res !== true) {
			if ($validators[self::V_SCHEME] === true) {
				throw new ValidationException('Not allowed keys "' . (implode(', ', $res)) . '""');
			}
		}

		// CREATE PAYMENT ########################

		$payment = new Payment();

		// ### PAYER
		if (isset($data['payer'])) {
			$payer = new Payer();
			self::map($payer, [
				'allowed_payment_instruments' => 'allowedPaymentInstruments',
				'default_payment_instrument' => 'defaultPaymentInstrument',
				'allowed_swifts' => 'allowedSwifts',
				'default_swift' => 'defaultSwift',
			], $data['payer']);
			$payment->setPayer($payer);

			if (isset($data['payer']['contact'])) {
				$contact = new Contact();
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
			$target = new Target();
			self::map($target, ['type' => 'type', 'goid' => 'goid'], $data['target']);
			$payment->setTarget($target);
		}

		// ### COMMON
		$payment->setAmount($data['amount']);
		$payment->setOrderNumber($data['order_number']);
		if (array_key_exists('order_description', $data)) {
			$payment->setOrderDescription($data['order_description']);
		}
		$payment->setReturnUrl($data['callback']['return_url']);
		$payment->setNotifyUrl($data['callback']['notify_url']);

		// ### ITEMS
		foreach ($data['items'] as $param) {
			if (!isset($param['name']) || !$param['name']) {
				if ($validators[self::V_SCHEME] === true) {
					throw new ValidationException('Item\'s name can\'t be empty or null.');
				}
			}

			$item = new Item();
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
				$parameter = new Parameter();
				self::map($parameter, ['name' => 'name', 'value' => 'value'], $param);
				$payment->addParameter($parameter);
			}
		}

		// ### LANG
		if (isset($data['lang'])) {
			$payment->setLang($data['lang']);
		}

		// VALIDATION PRICE & ITEMS PRICE ########
		$itemsPrice = new Money(0, new Currency($payment->getCurrency()));

		$orderPrice = $payment->getAmount();
		foreach ($payment->getItems() as $item) {
			$itemsPrice = $itemsPrice->add($item->getAmount()->multiply($item->count));
		}

		if (!$itemsPrice->equals($orderPrice)) {
			if ($validators[self::V_PRICES] === true) {
				throw new ValidationException(sprintf('Payment price (%s) and items price (%s) do not match', $orderPrice->getAmount(), $itemsPrice->getAmount()));
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
				'urceno_cerp_zuct' => 'subsequentDrawing',
				'cerp_zuct' => 'subsequentlyDrawn',
			], $data['eet']);

			$eetSum = $eet->getSum();
			$eetTotal = $eet->getTotal();

			if ($validators[self::V_PRICES] === true) {
				if (!$eetSum->equals($eetTotal)) {
					throw new ValidationException(sprintf('EET sum (%s) and EET tax sum (%s) do not match', $eetSum->getAmount(), $eetTotal->getAmount()));
				}

				if (!$eetSum->equals($orderPrice)) {
					throw new ValidationException(sprintf('EET sum (%s) and order sum (%s) do not match', $eetSum->getAmount(), $orderPrice->getAmount()));
				}
			}

			$payment->setEet($eet);
		}

		// ### PREAUTHORIZATION
		if (isset($data['preauthorization'])) {
			$payment->setPreauthorization($data['preauthorization']);
		}

		return $payment;
	}

	/**
	 * @param mixed[] $mapping
	 * @param mixed[] $data
	 */
	public static function map(object $obj, array $mapping, array $data): object
	{
		foreach ($mapping as $from => $to) {
			if (isset($data[$from])) {
				$obj->{$to} = $data[$from];
			}
		}

		return $obj;
	}

}
