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

final class RouteDumpCommand extends Command
{

	const TABLE_HEADER = ['Method', 'Path', 'Handler', 'Parameters'];

	/** @var Schema */
	private $schema;

	/**
	 * @param Schema $schema
	 */
	public function __construct(Schema $schema)
	{
		parent::__construct();

		$this->schema = $schema;
	}

	/**
	 * @return void
	 */
	protected function configure()
	{
		$this->setName('apitte:route:dump');
		$this->setDescription('Lists all endpoints registered in application');
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$io = new SymfonyStyle($input, $output);

		$io->title('All registered endpoints');

		$table = new Table($output);
		$table->setHeaders(self::TABLE_HEADER);

		/** @var Endpoint[][] $endpointsByHandler */
		$endpointsByHandler = [];

		foreach ($this->schema->getEndpoints() as $endpoint) {
			$endpointsByHandler[$endpoint->getHandler()->getClass()][] = $endpoint;
		}

		foreach ($endpointsByHandler as $handler) {

			usort($handler, function (Endpoint $first, Endpoint $second) {
				return strlen($first->getMask()) - strlen($second->getMask());
			});

			foreach ($handler as $endpoint) {
				$table->addRow([
					implode('|', $endpoint->getMethods()),
					$endpoint->getMask(),
					sprintf(
						'%s::%s()',
						$endpoint->getHandler()->getClass(),
						$endpoint->getHandler()->getMethod()
					),
					$this->formatParameters($endpoint->getParameters()),
				]);
			}

			if ($handler !== end($endpointsByHandler)) {
				$table->addRow(new TableSeparator());
			}
		}

		$table->render();
	}

	/**
	 * @param EndpointParameter[] $parameters
	 * @return string
	 */
	private function formatParameters(array $parameters)
	{
		$params = array_map(function (EndpointParameter $parameter) {
			return sprintf('%s (%s)', $parameter->getName(), $parameter->getType());
		}, $parameters);

		return implode(', ', $params);
	}

}
