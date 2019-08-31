<?php

/**
 * Test: Api\Objects\Item
 */

use Contributte\GopayInline\Api\Objects\Item;
use Tester\Assert;

require __DIR__ . '/../../../../bootstrap.php';

// Item default count for BC
test(function () {
	$item = new Item();
	$item->amount = 100;
	$itemArray = $item->toArray();
	Assert::equal($itemArray['count'], 1);
});
