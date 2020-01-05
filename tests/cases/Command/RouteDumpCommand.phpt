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
use Symfony\Component\Console\Output\BufferedOutput;
use Tester\Assert;

// No endpoints
test(function (): void {
	$schema = new Schema();
	$command = new RouteDumpCommand($schema);

	$input = new ArgvInput();
	$output = new BufferedOutput();

	$command->run($input, $output);

	Assert::equal('No endpoints found', trim($output->fetch()));
});

// Some endpoints
test(function (): void {
	$schema = new Schema();

	$handler1 = new EndpointHandler('class1', 'method1');
	$endpoint1 = new Endpoint($handler1);
	$endpoint1->addMethod(Endpoint::METHOD_GET);
	$endpoint1->setMask('/example/foo');
	$schema->addEndpoint($endpoint1);

	$handler2 = new EndpointHandler('class1', 'method2');
	$endpoint2 = new Endpoint($handler2);
	$endpoint2->addMethod(Endpoint::METHOD_GET);
	$endpoint2->setMask('/example/{id}');
	$endpoint2->addParameter(new EndpointParameter('id'));
	$schema->addEndpoint($endpoint2);

	$handler3 = new EndpointHandler('class2', 'method1');
	$endpoint3 = new Endpoint($handler3);
	$endpoint3->addMethod(Endpoint::METHOD_GET);
	$endpoint3->setMask('/lorem-ipsum');
	$schema->addEndpoint($endpoint3);

	$command = new RouteDumpCommand($schema);

	$input = new ArgvInput();
	$output = new BufferedOutput();

	$command->run($input, $output);

	$result = <<<EOD
All registered endpoints
========================

+--------+---------------+-------------------+-------------+
| Method | Path          | Handler           | Parameters  |
+--------+---------------+-------------------+-------------+
| GET    | /example/foo  | class1::method1() |             |
| GET    | /example/{id} | class1::method2() | id (string) |
+--------+---------------+-------------------+-------------+
| GET    | /lorem-ipsum  | class2::method1() |             |
+--------+---------------+-------------------+-------------+
EOD;

	Assert::equal($result, trim($output->fetch()));
});
