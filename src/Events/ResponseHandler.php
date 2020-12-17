<?php declare(strict_types = 1);

/**
 * ResponseHandler.php
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

/**
 * Before http request processed handler
 *
 * @package         FastyBird:Database!
 * @subpackage      Events
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 */
class ResponseHandler
{

	use Nette\SmartObject;

	/** @var Helpers\Database */
	private Helpers\Database $database;

	public function __construct(
		Helpers\Database $database
	) {
		$this->database = $database;
	}

	/**
	 * @return void
	 */
	public function __invoke(): void
	{
		// Clearing Doctrine's entity manager allows
		// for more memory to be released by PHP
		$this->database->clear();

		// Just in case PHP would choose not to run garbage collection,
		// we run it manually at the end of each batch so that memory is
		// regularly released
		gc_collect_cycles();
	}

}
