<?php

namespace Markette\GopayInline\Api\Entity;

use Markette\GopayInline\Api\Objects\Contact;
use Markette\GopayInline\Api\Objects\Item;
use Markette\GopayInline\Api\Objects\Parameter;
use Markette\GopayInline\Api\Objects\Payer;
use Markette\GopayInline\Api\Objects\Target;
use Markette\GopayInline\Exception\ValidationException;
use Markette\GopayInline\Utils\Helpers;
use Markette\GopayInline\Utils\Validator;

class PaymentFactory
{

    /** @var array */
    static $required = [
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
    static $optional = [
        'target',
        'payer',
        'additional_params',
        'lang',
    ];

    /**
     * @param mixed $data
     * @return Payment
     */
    public static function create($data)
    {
        // Convert to array
        $data = (array)$data;

        // CHECK REQUIRED DATA ###################

        if (($res = Validator::validateRequired($data, self::$required)) !== TRUE) {
            throw new ValidationException('Missing keys "' . (implode(', ', $res)) . '""');
        }

        // CHECK SCHEME DATA #####################

        if (($res = Validator::validateOptional($data, array_merge(self::$required, self::$optional))) !== TRUE) {
            throw new ValidationException('Not allowed keys "' . (implode(', ', $res)) . '""');
        }

        // CREATE PAYMENT ########################

        $payment = new Payment();

        // ### PAYER
        if (isset($data['payer'])) {
            $payment->setPayer($payer = Helpers::map(new Payer, [
                'allowed_payment_instruments' => 'allowedPaymentInstruments',
                'default_payment_instrument' => 'defaultPaymentInstrument',
                'allowed_swifts' => 'allowedSwifts',
                'default_swift' => 'defaultSwift',
            ], $data['payer']));

            if (isset($data['payer']['contact'])) {
                $payer->contact = Helpers::map(new Contact, [
                    'first_name' => 'firstname',
                    'last_name' => 'lastname',
                    'email' => 'email',
                    'phone_number' => 'phone',
                    'city' => 'city',
                    'street' => 'street',
                    'postal_code' => 'zip',
                    'country_code' => 'country',
                ], $data['payer']['contact']);
            }
        }

        // ### TARGET
        if (isset($data['target'])) {
            $payment->setTarget(Helpers::map(new Target, [
                'type' => 'type',
                'goid' => 'goid',
            ], $data['target']));
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
            $payment->addItem(Helpers::map(new Item, [
                'name' => 'name',
                'amount' => 'amount',
                'count' => 'count'
            ], $param));
        }

        // ### ADDITIONAL PARAMETERS
        if (isset($data['additional_params'])) {
            foreach ($data['additional_params'] as $param) {
                $payment->addParameter(Helpers::map(new Parameter, [
                    'name' => 'name',
                    'value' => 'value',
                ], $param));
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
            throw new ValidationException(sprintf('Payment price (%s) and items price (%s) dont match', $orderPrice, $itemsPrice));
        }

        return $payment;
    }
}
