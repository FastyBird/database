<?php declare(strict_types = 1);

namespace Tests\Cases;

use Doctrine\Common;
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
final class ResponseHandlerTest extends BaseMockeryTestCase
{

	public function testOnResponse(): void
	{
		$manager = Mockery::mock(ORM\EntityManagerInterface::class);
		$manager
			->shouldReceive('isOpen')
			->withNoArgs()
			->andReturn(true)
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

		$databaseHelper = new Helpers\Database($managerRegistry);

		$subscriber = new Subscribers\DatabaseCheckSubscriber($databaseHelper);

		$subscriber->response();

		Assert::true(true);
	}

}

$test_case = new ResponseHandlerTest();
$test_case->run();
