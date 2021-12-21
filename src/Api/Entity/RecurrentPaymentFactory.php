<?php declare(strict_types = 1);

namespace Contributte\GopayInline\Api\Entity;

use Contributte\GopayInline\Api\Objects\Contact;
use Contributte\GopayInline\Api\Objects\Eet;
use Contributte\GopayInline\Api\Objects\Item;
use Contributte\GopayInline\Api\Objects\Parameter;
use Contributte\GopayInline\Api\Objects\Payer;
use Contributte\GopayInline\Api\Objects\Recurrence;
use Contributte\GopayInline\Api\Objects\Target;
use Contributte\GopayInline\Exception\ValidationException;
use Contributte\GopayInline\Utils\Validator;
use Money\Currency;
use Money\Money;

class RecurrentPaymentFactory
{

	// Validator's types
	public const V_SCHEME = 1;
	public const V_PRICES = 2;

	/** @var string[] */
	public static $required = [
		// 'target', # see at AbstractPaymentService
		'amount',
		'order_number',
		'order_description',
		'items',
		'recurrence',
		'callback',
	];

	/** @var string[] */
	public static $requiredCallback = [
		'return_url',
		'notify_url',
	];

	/** @var string[] */
	public static $optional = [
		'target',
		'payer',
		'additional_params',
		'lang',
		'eet',
	];

	/** @var array<int, bool> */
	public static $validators = [
		self::V_SCHEME => true,
		self::V_PRICES => true,
	];

	/**
	 * @param mixed $data
	 * @param mixed[] $validators
	 */
	public static function create($data, array $validators = []): RecurrentPayment
	{
		// Convert to array
		$data = (array) $data;
		$validators += self::$validators;

		// CHECK REQUIRED DATA ###################

		$res = Validator::validateRequired($data, self::$required);
		if ($res !== true) {
			throw new ValidationException('Missing keys "' . (implode(', ', $res)) . '""');
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

		// CREATE RECURRENT PAYMENT ########################

		$recurrentPayment = new RecurrentPayment();

		// ### PAYER
		if (isset($data['payer'])) {
			$payer = new Payer();
			self::map($payer, [
				'allowed_payment_instruments' => 'allowedPaymentInstruments',
				'default_payment_instrument' => 'defaultPaymentInstrument',
				'allowed_swifts' => 'allowedSwifts',
				'default_swift' => 'defaultSwift',
			], $data['payer']);
			$recurrentPayment->setPayer($payer);

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
			$recurrentPayment->setTarget($target);
		}

		// ### COMMON
		$recurrentPayment->setAmount($data['amount']);
		$recurrentPayment->setOrderNumber($data['order_number']);
		$recurrentPayment->setOrderDescription($data['order_description']);
		$recurrentPayment->setReturnUrl($data['callback']['return_url']);
		$recurrentPayment->setNotifyUrl($data['callback']['notify_url']);

		// ### ITEMS
		foreach ($data['items'] as $param) {
			/** @phpstan-ignore-next-line */
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
			$recurrentPayment->addItem($item);
		}

		// ### RECURRENCE
		if (isset($data['recurrence'])) {
			$recurrence = new Recurrence();
			self::map($recurrence, ['recurrence_cycle' => 'cycle', 'recurrence_period' => 'period', 'recurrence_date_to' => 'dateTo'], $data['recurrence']);
			$recurrentPayment->setRecurrence($recurrence);
		}

		// ### ADDITIONAL PARAMETERS
		if (isset($data['additional_params'])) {
			foreach ($data['additional_params'] as $param) {
				$parameter = new Parameter();
				self::map($parameter, ['name' => 'name', 'value' => 'value'], $param);
				$recurrentPayment->addParameter($parameter);
			}
		}

		// ### LANG
		if (isset($data['lang'])) {
			$recurrentPayment->setLang($data['lang']);
		}

		// VALIDATION PRICE & ITEMS PRICE ########
		$itemsPrice = new Money(0, new Currency($recurrentPayment->getCurrency()));

		$orderPrice = $recurrentPayment->getAmount();
		foreach ($recurrentPayment->getItems() as $item) {
			$itemsPrice = $itemsPrice->add($item->getAmount());
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
			$eetTotal = $eet->getTotal();

			if ($validators[self::V_PRICES] === true) {
				if (!$eetSum->equals($eetTotal)) {
					throw new ValidationException(sprintf('EET sum (%s) and EET tax sum (%s) do not match', $eetSum->getAmount(), $eetTotal->getAmount()));
				}

				if (!$eetSum->equals($orderPrice)) {
					throw new ValidationException(sprintf('EET sum (%s) and order sum (%s) do not match', $eetSum->getAmount(), $orderPrice->getAmount()));
				}
			}

			$recurrentPayment->setEet($eet);
		}

		return $recurrentPayment;
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
