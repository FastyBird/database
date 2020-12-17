<?php declare(strict_types = 1);

/**
 * RequestHandler.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:Database!
 * @subpackage     Events
 * @since          0.1.0
 *
 * @date           15.04.20
 */

namespace FastyBird\Database\Helpers;

use Doctrine\DBAL;
use Doctrine\ORM;
use Doctrine\Persistence;
use FastyBird\Database\Exceptions;
use Nette;
use Throwable;

/**
 * Database connection helpers
 *
 * @package         FastyBird:Database!
 * @subpackage      Helpers
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 */
class Database
{

	use Nette\SmartObject;

	/** @var Persistence\ManagerRegistry */
	private Persistence\ManagerRegistry $managerRegistry;

	public function __construct(
		Persistence\ManagerRegistry $managerRegistry
	) {
		$this->managerRegistry = $managerRegistry;
	}

	/**
	 * @return bool
	 *
	 * @throws Throwable
	 */
	public function ping(): bool
	{
		$connection = $this->getConnection();

		if ($connection !== null) {
			try {
				$connection->executeQuery($connection->getDatabasePlatform()
					->getDummySelectSQL(), [], []);

			} catch (DBAL\Exception $e) {
				return false;
			}

			return true;
		}

		throw new Exceptions\InvalidStateException('Database connection not found');
	}

	/**
	 * @return DBAL\Connection|null
	 */
	private function getConnection(): ?DBAL\Connection
	{
		$em = $this->getEntityManager();

		if ($em instanceof ORM\EntityManagerInterface) {
			return $em->getConnection();
		}

		return null;
	}

	/**
	 * @return ORM\EntityManagerInterface|null
	 */
	private function getEntityManager(): ?ORM\EntityManagerInterface
	{
		$em = $this->managerRegistry->getManager();

		if ($em instanceof ORM\EntityManagerInterface) {
			if (!$em->isOpen()) {
				$this->managerRegistry->resetManager();

				$em = $this->managerRegistry->getManager();
			}

			if ($em instanceof ORM\EntityManagerInterface) {
				return $em;
			}
		}

		return null;
	}

	/**
	 * @return void
	 *
	 * @throws Throwable
	 */
	public function reconnect(): void
	{
		$connection = $this->getConnection();

		if ($connection !== null) {
			$connection->close();
			$connection->connect();

			return;
		}

		throw new Exceptions\InvalidStateException('Invalid database connection');
	}

	/**
	 * @return void
	 */
	public function clear(): void
	{
		$em = $this->getEntityManager();

		if ($em instanceof ORM\EntityManagerInterface) {
			$em->clear();

			return;
		}

		throw new Exceptions\InvalidStateException('Invalid entity manager');
	}

}
