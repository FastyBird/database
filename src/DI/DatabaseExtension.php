<?php declare(strict_types = 1);

/**
 * DatabaseExtension.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:Database!
 * @subpackage     DI
 * @since          0.1.0
 *
 * @date           27.05.20
 */

namespace FastyBird\Database\DI;

use FastyBird\Database\Helpers;
use FastyBird\Database\Middleware;
use FastyBird\Database\Subscribers;
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

		$builder->addDefinition($this->prefix('subscribers.webServer'))
			->setType(Subscribers\WebServerSubscriber::class);

		$builder->addDefinition($this->prefix('subscribers.exchange'))
			->setType(Subscribers\ExchangeSubscriber::class);

		$builder->addDefinition($this->prefix('helpers.database'))
			->setType(Helpers\Database::class);
	}

}
