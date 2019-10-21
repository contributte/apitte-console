<?php declare(strict_types = 1);

/**
 * Test: Command/RouteDumpCommand
 */

require_once __DIR__ . '/../../bootstrap.php';

use Apitte\Console\Command\RouteDumpCommand;
use Apitte\Core\Schema\Endpoint;
use Apitte\Core\Schema\EndpointHandler;
use Apitte\Core\Schema\EndpointParameter;
use Apitte\Core\Schema\Schema;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Tests\Fixtures\DummyOutput;
use Tester\Assert;

// No endpoints
test(function (): void {
	$schema = new Schema();
	$command = new RouteDumpCommand($schema);

	$input = new ArgvInput();
	$output = new DummyOutput();

	$command->run($input, $output);

	Assert::equal('No endpoints found', trim($output->fetch()));
});

// Some endpoints
test(function (): void {
	$schema = new Schema();
	$handler = new EndpointHandler('class', 'method');
	$endpoint = new Endpoint($handler);
	$endpoint->addMethod(Endpoint::METHOD_GET);
	$endpoint->setMask('/example/{id}');
	$endpoint->addParameter(new EndpointParameter('id'));
	$schema->addEndpoint($endpoint);

	$command = new RouteDumpCommand($schema);

	$input = new ArgvInput();
	$output = new DummyOutput();

	$command->run($input, $output);

	$result = <<<EOD
All registered endpoints
========================

+--------+---------------+-----------------+-------------+
| Method | Path          | Handler         | Parameters  |
+--------+---------------+-----------------+-------------+
| GET    | /example/{id} | class::method() | id (string) |
+--------+---------------+-----------------+-------------+
EOD;

	Assert::equal($result, trim($output->fetch()));
});
