<?php declare(strict_types = 1);

/**
 * RequestHandler.php
 *
 * @license        More in license.md
 * @copyright      https://fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:Database!
 * @subpackage     Events
 * @since          0.1.0
 *
 * @date           15.04.20
 */

namespace FastyBird\Database\Events;

use Doctrine\DBAL;
use Doctrine\ORM;
use Doctrine\Persistence;
use Nette;
use Throwable;

/**
 * After http request processed handler
 *
 * @package         FastyBird:Database!
 * @subpackage      Events
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 */
class RequestHandler
{

	use Nette\SmartObject;

	/** @var Persistence\ManagerRegistry */
	private $managerRegistry;

	public function __construct(
		Persistence\ManagerRegistry $managerRegistry
	) {
		$this->managerRegistry = $managerRegistry;
	}

	/**
	 * @return void
	 *
	 * @throws Throwable
	 */
	public function __invoke(): void
	{
		$em = $this->managerRegistry->getManager();

		if ($em instanceof ORM\EntityManagerInterface && !$em->isOpen()) {
			$this->managerRegistry->resetManager();

			$em = $this->managerRegistry->getManager();
		}

		if ($em instanceof ORM\EntityManagerInterface) {
			$connection = $em->getConnection();

			try {
				$connection->executeQuery($connection->getDatabasePlatform()->getDummySelectSQL(), [], []);

			} catch (DBAL\Exception $e) {
				$connection->close();
				$connection->connect();
			}

			// Make sure we don't work with outdated entities
			$em->clear();
		}
	}

}
