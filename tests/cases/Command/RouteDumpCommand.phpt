<?php

/**
 * Test: Command/RouteDumpCommand
 */

require_once __DIR__ . '/../../bootstrap.php';

use Apitte\Console\Command\RouteDumpCommand;
use Apitte\Core\Schema\Schema;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Tests\Fixtures\DummyOutput;
use Tester\Assert;

// No endpoints
test(function () {
	$schema = new Schema();
	$command = new RouteDumpCommand($schema);

	$input = new ArgvInput();
	$output = new DummyOutput();

	$command->run($input, $output);

	Assert::equal('No endpoints found', trim($output->fetch()));
});
