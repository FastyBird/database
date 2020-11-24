<?php declare(strict_types = 1);

/**
 * ServerStartHandler.php
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

namespace FastyBird\Database\Events;

use FastyBird\Database\Exceptions;
use FastyBird\Database\Helpers;
use Nette;
use Throwable;

/**
 * Http server start handler
 *
 * @package         FastyBird:Database!
 * @subpackage      Events
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 */
class ServerStartHandler
{

	use Nette\SmartObject;

	/** @var Helpers\Database */
	private $database;

	public function __construct(
		Helpers\Database $database
	) {
		$this->database = $database;
	}

	/**
	 * @return void
	 *
	 * @throws Throwable
	 */
	public function __invoke(): void
	{
		// Check if ping to DB is possible...
		if (!$this->database->ping()) {
			// ...if not, try to reconnect
			$this->database->reconnect();

			// ...and ping again
			if (!$this->database->ping()) {
				throw new Exceptions\InvalidStateException('Connection to database could not be established');
			}
		}
	}

}
