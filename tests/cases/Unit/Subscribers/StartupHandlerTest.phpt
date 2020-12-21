<?php declare(strict_types = 1);

namespace Tests\Cases;

use Doctrine\Common;
use Doctrine\DBAL;
use Doctrine\ORM;
use FastyBird\Database\Helpers;
use FastyBird\Database\Subscribers;
use Mockery;
use Ninjify\Nunjuck\TestCase\BaseMockeryTestCase;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class StartupHandlerTest extends BaseMockeryTestCase
{

	public function testServerAfterStart(): void
	{
		$databasePlatform = Mockery::mock(DBAL\Platforms\AbstractPlatform::class);
		$databasePlatform
			->shouldReceive('getDummySelectSQL')
			->withNoArgs()
			->andReturn(sprintf('SELECT %s', 1))
			->times(1);

		$connection = Mockery::mock(DBAL\Connection::class);
		$connection
			->shouldReceive('getDatabasePlatform')
			->withNoArgs()
			->andReturn($databasePlatform)
			->times(1)
			->getMock()
			->shouldReceive('executeQuery')
			->withArgs([sprintf('SELECT %s', 1), [], []])
			->andReturnNull()
			->times(1);

		$manager = Mockery::mock(ORM\EntityManagerInterface::class);
		$manager
			->shouldReceive('isOpen')
			->withNoArgs()
			->andReturn(true)
			->times(1)
			->getMock()
			->shouldReceive('getConnection')
			->withNoArgs()
			->andReturn($connection)
			->times(1);

		$managerRegistry = Mockery::mock(Common\Persistence\ManagerRegistry::class);
		$managerRegistry
			->shouldReceive('getManager')
			->withNoArgs()
			->andReturn($manager)
			->times(1);

		$databaseHelper = new Helpers\Database($managerRegistry);

		$subscriber = new Subscribers\DatabaseCheckSubscriber($databaseHelper);

		$subscriber->check();

		Assert::true(true);
	}

}

$test_case = new StartupHandlerTest();
$test_case->run();
