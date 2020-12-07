<?php declare(strict_types = 1);

/**
 * DatabaseExtension.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:Database!
 * @subpackage     DI
 * @since          0.1.0
 *
 * @date           27.05.20
 */

namespace FastyBird\Database\DI;

use FastyBird\Database\Events;
use FastyBird\Database\Helpers;
use FastyBird\Database\Middleware;
use FastyBird\WebServer\Commands as WebServerCommands;
use Nette;
use Nette\DI;

/**
 * Database utils extension container
 *
 * @package        FastyBird:Database!
 * @subpackage     DI
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
class DatabaseExtension extends DI\CompilerExtension
{

	/**
	 * @param Nette\Configurator $config
	 * @param string $extensionName
	 *
	 * @return void
	 */
	public static function register(
		Nette\Configurator $config,
		string $extensionName = 'fbDatabase'
	): void {
		$config->onCompile[] = function (
			Nette\Configurator $config,
			DI\Compiler $compiler
		) use ($extensionName): void {
			$compiler->addExtension($extensionName, new DatabaseExtension());
		};
	}

	/**
	 * {@inheritDoc}
	 */
	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition(null)
			->setType(Middleware\PagingMiddleware::class)
			->setTags([
				'middleware' => [
					'priority' => 10,
				],
			]);

		$builder->addDefinition($this->prefix('event.serverStart'))
			->setType(Events\ServerStartHandler::class);

		$builder->addDefinition($this->prefix('event.request'))
			->setType(Events\RequestHandler::class);

		$builder->addDefinition($this->prefix('event.response'))
			->setType(Events\ResponseHandler::class);

		$builder->addDefinition($this->prefix('helpers.database'))
			->setType(Helpers\Database::class);
	}

	/**
	 * {@inheritDoc}
	 */
	public function beforeCompile(): void
	{
		parent::beforeCompile();

		$builder = $this->getContainerBuilder();

		/**
		 * SERVER EVENTS
		 */

		$serverCommandServiceName = $builder->getByType(WebServerCommands\HttpServerCommand::class);

		if ($serverCommandServiceName !== null) {
			/** @var DI\Definitions\ServiceDefinition $serverCommandService */
			$serverCommandService = $builder->getDefinition($serverCommandServiceName);

			$serverCommandService
				->addSetup('$onServerStart[]', ['@' . $this->prefix('event.serverStart')])
				->addSetup('$onRequest[]', ['@' . $this->prefix('event.request')])
				->addSetup('$onResponse[]', ['@' . $this->prefix('event.response')]);
		}
	}

}
