<?php

namespace Apitte\Console\DI;

use Apitte\Console\Command\RouteDumpCommand;
use Apitte\Core\DI\Plugin\AbstractPlugin;
use Apitte\Core\DI\Plugin\PluginCompiler;

/**
 * Class ConsolePlugin
 *
 * @package Apitte\Console\DI
 */
final class ConsolePlugin extends AbstractPlugin
{

	const PLUGIN_NAME = 'console';

	/**
	 * @param PluginCompiler $compiler
	 */
	public function __construct(PluginCompiler $compiler)
	{
		parent::__construct($compiler);
		$this->name = self::PLUGIN_NAME;
	}

	/**
	 * @return void
	 */
	public function beforePluginCompile()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('console'))
			->setFactory(RouteDumpCommand::class);
	}

}
