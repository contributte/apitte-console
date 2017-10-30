<?php

namespace Apitte\Console\Command;

use Apitte\Core\Schema\Endpoint;
use Apitte\Core\Schema\EndpointParameter;
use Apitte\Core\Schema\Schema;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class RouteDumpCommand
 */
class RouteDumpCommand extends Command
{

	const TABLE_HEADER = ['Method', 'Path', 'Handler', 'Parameters'];

	/**
	 * @var \Apitte\Core\Schema\Schema
	 */
	private $schema;

	protected function configure()
	{
		$this->setName('route:dump');
		$this->setAliases(['route:list', 'endpoint:dump', 'endpoint:list', 'schema:dump']);
		$this->setDescription('Lists all endpoints registered in application');
	}

	/**
	 * RouteDumpCommand constructor.
	 *
	 * @param \Apitte\Core\Schema\Schema $schema
	 */
	public function __construct(Schema $schema)
	{
		parent::__construct();

		$this->schema = $schema;
	}

	/**
	 * @param \Symfony\Component\Console\Input\InputInterface $input
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 * @return int
	 * @throws \ReflectionException
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$io = new SymfonyStyle($input, $output);

		$io->title('All registered endpoints');

		$this->printEndpointsTable($output);

		return 0;
	}

	/**
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 */
	public function printEndpointsTable(OutputInterface $output)
	{
		$table = new Table($output);
		$table->setHeaders(self::TABLE_HEADER);

		/** @var \Apitte\Core\Schema\Endpoint[][] $endpointsByHandler */
		$endpointsByHandler = [];

		foreach ($this->schema->getEndpoints() as $endpoint) {
			$endpointsByHandler[$endpoint->getHandler()->getClass()][] = $endpoint;
		}

		foreach ($endpointsByHandler as $handler) {

			\usort($handler, function (Endpoint $first, Endpoint $second) {
				return \strlen($first->getMask()) - \strlen($second->getMask());
			});

			foreach ($handler as $endpoint) {
				$table->addRow([
					$endpoint->getMethods()[0],
					$endpoint->getMask(),
					\sprintf(
						'%s::%s()',
						$endpoint->getHandler()->getClass(),
						$endpoint->getHandler()->getMethod()
					),
					$this->formatParameters($endpoint->getParameters()),
				]);
			}

			if ($handler !== \end($endpointsByHandler)) {
				$table->addRow(new TableSeparator());
			}
		}

		$table->render();
	}

	/**
	 * @param \Apitte\Core\Schema\EndpointParameter[] $parameters
	 * @return string
	 */
	private function formatParameters(array $parameters)
	{
		$params = \array_map(function (EndpointParameter $parameter) {
			return \sprintf('%s (%s)', $parameter->getName(), $parameter->getType());
		}, $parameters);

		return \implode(', ', $params);
	}

}
