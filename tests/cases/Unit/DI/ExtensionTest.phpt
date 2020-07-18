<?php declare(strict_types = 1);

namespace Tests\Cases;

use FastyBird\NodeDatabase\DI;
use FastyBird\NodeDatabase\Events;
use FastyBird\NodeDatabase\Middleware;
use Nette;
use Ninjify\Nunjuck\TestCase\BaseTestCase;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

final class ExtensionTest extends BaseTestCase
{

	public function testCompilersServices(): void
	{
		$container = $this->createContainer();

		Assert::notNull($container->getByType(Middleware\JsonApiMiddleware::class));
		Assert::notNull($container->getByType(Middleware\DbErrorMiddleware::class));

		Assert::notNull($container->getByType(Events\AfterConsumeHandler::class));
		Assert::notNull($container->getByType(Events\RequestHandler::class));
		Assert::notNull($container->getByType(Events\ResponseHandler::class));
		Assert::notNull($container->getByType(Events\ServerStartHandler::class));
	}

	/**
	 * @return Nette\DI\Container
	 */
	protected function createContainer(): Nette\DI\Container
	{
		$rootDir = __DIR__ . '/../../';

		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		$config->addParameters(['container' => ['class' => 'SystemContainer_' . md5((string) time())]]);
		$config->addParameters(['appDir' => $rootDir, 'wwwDir' => $rootDir]);

		$config->addConfig(__DIR__ . '/../../../common.neon');

		DI\NodeDatabaseExtension::register($config);

		return $config->createContainer();
	}

}

$test_case = new ExtensionTest();
$test_case->run();
