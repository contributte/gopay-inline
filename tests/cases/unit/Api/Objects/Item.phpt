<?php declare(strict_types = 1);

/**
 * Test: Api\Objects\Item
 */

use Contributte\GopayInline\Api\Objects\Item;
use Money\Money;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// Item default count for BC
test(function (): void {
	$item = new Item();
	$item->amount = Money::CZK(100);
	$itemArray = $item->toArray();
	Assert::equal($itemArray['count'], 1);
});
