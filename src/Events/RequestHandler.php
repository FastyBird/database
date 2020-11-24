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

namespace FastyBird\Database\Events;

use FastyBird\Database\Helpers;
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
		if (!$this->database->ping()) {
			$this->database->reconnect();
		}

		// Make sure we don't work with outdated entities
		$this->database->clear();
	}

}
