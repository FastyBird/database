<?php declare(strict_types = 1);

namespace Tests\Cases;

use FastyBird\NodeDatabase\DI;
use FastyBird\NodeDatabase\Events;
use FastyBird\NodeDatabase\Middleware;
use FastyBird\NodeLibs\Boot;
use Ninjify\Nunjuck\TestCase\BaseTestCase;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

final class ExtensionTest extends BaseTestCase
{

	public function testCompilersServices(): void
	{
		$configurator = Boot\Bootstrap::boot();
		$configurator->addParameters([
			'origin'   => 'com.fastybird.node',
			'rabbitmq' => [
				'queueName' => 'testingQueueName',
			],
		]);

		$configurator->addConfig(__DIR__ . DS . '..' . DS . '..' . DS . '..' . DS . 'common.neon');

		DI\NodeDatabaseExtension::register($configurator);

		$container = $configurator->createContainer();

		Assert::notNull($container->getByType(Middleware\JsonApiMiddleware::class));
		Assert::notNull($container->getByType(Middleware\DbErrorMiddleware::class));

		Assert::notNull($container->getByType(Events\AfterConsumeHandler::class));
		Assert::notNull($container->getByType(Events\RequestHandler::class));
		Assert::notNull($container->getByType(Events\ResponseHandler::class));
		Assert::notNull($container->getByType(Events\ServerStartHandler::class));
	}

}

$test_case = new ExtensionTest();
$test_case->run();
