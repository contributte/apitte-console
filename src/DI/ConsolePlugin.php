<?php declare(strict_types = 1);

namespace Apitte\Console\DI;

use Apitte\Console\Command\RouteDumpCommand;
use Apitte\Core\DI\Plugin\AbstractPlugin;
use Apitte\Core\DI\Plugin\PluginCompiler;

final class ConsolePlugin extends AbstractPlugin
{

	public const PLUGIN_NAME = 'console';

	public function __construct(PluginCompiler $compiler)
	{
		parent::__construct($compiler);
		$this->name = self::PLUGIN_NAME;
	}

	public function beforePluginCompile(): void
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('console'))
			->setFactory(RouteDumpCommand::class);
	}

}
