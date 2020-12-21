<?php declare(strict_types = 1);

namespace Tests\Cases;

use FastyBird\Database\DI;
use FastyBird\Database\Helpers;
use FastyBird\Database\Middleware;
use FastyBird\Database\Subscribers;
use Nette;
use Ninjify\Nunjuck\TestCase\BaseTestCase;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class ExtensionTest extends BaseTestCase
{

	public function testCompilersServices(): void
	{
		$container = $this->createContainer();

		Assert::notNull($container->getByType(Middleware\PagingMiddleware::class));

		Assert::notNull($container->getByType(Subscribers\DatabaseCheckSubscriber::class));

		Assert::notNull($container->getByType(Helpers\Database::class));
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

		DI\DatabaseExtension::register($config);

		return $config->createContainer();
	}

}

$test_case = new ExtensionTest();
$test_case->run();
