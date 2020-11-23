<?php declare(strict_types = 1);

namespace Tests\Cases;

use Doctrine\Common;
use Doctrine\DBAL;
use Doctrine\ORM;
use FastyBird\Database\Events;
use Mockery;
use Ninjify\Nunjuck\TestCase\BaseMockeryTestCase;
use Tester\Assert;

require_once __DIR__ . '/../../../bootstrap.php';

/**
 * @testCase
 */
final class RequestHandlerTest extends BaseMockeryTestCase
{

	public function testOnRequest(): void
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
			->times(1)
			->getMock()
			->shouldReceive('clear')
			->withNoArgs()
			->times(1);

		$managerRegistry = Mockery::mock(Common\Persistence\ManagerRegistry::class);
		$managerRegistry
			->shouldReceive('getManager')
			->withNoArgs()
			->andReturn($manager)
			->times(1);

		$subscriber = new Events\RequestHandler($managerRegistry);

		$subscriber->__invoke();

		Assert::true(true);
	}

}

$test_case = new RequestHandlerTest();
$test_case->run();
