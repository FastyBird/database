<?php declare(strict_types = 1);

/**
 * DatabaseCheckSubscriber.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:Database!
 * @subpackage     Subscribers
 * @since          0.1.0
 *
 * @date           15.04.20
 */

namespace FastyBird\Database\Subscribers;

use FastyBird\ApplicationEvents\Events as ApplicationEventsEvents;
use FastyBird\Database\Exceptions;
use FastyBird\Database\Helpers;
use Symfony\Component\EventDispatcher;

/**
 * Database check subscriber
 *
 * @package         FastyBird:Database!
 * @subpackage      Subscribers
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 */
class DatabaseCheckSubscriber implements EventDispatcher\EventSubscriberInterface
{

	/** @var Helpers\Database */
	private Helpers\Database $database;

	/**
	 * @return string[]
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			ApplicationEventsEvents\StartupEvent::class  => 'check',
			ApplicationEventsEvents\RequestEvent::class  => 'request',
			ApplicationEventsEvents\ResponseEvent::class => 'response',
		];
	}

	public function __construct(
		Helpers\Database $database
	) {
		$this->database = $database;
	}

	/**
	 * @return void
	 */
	public function check(): void
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

	/**
	 * @return void
	 */
	public function request(): void
	{
		if (!$this->database->ping()) {
			$this->database->reconnect();
		}

		// Make sure we don't work with outdated entities
		$this->database->clear();
	}

	/**
	 * @return void
	 */
	public function response(): void
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
