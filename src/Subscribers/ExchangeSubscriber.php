<?php declare(strict_types = 1);

/**
 * ExchangeSubscriber.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:Database!
 * @subpackage     Subscribers
 * @since          0.1.0
 *
 * @date           21.12.20
 */

namespace FastyBird\Database\Subscribers;

use Doctrine\Persistence;
use FastyBird\ApplicationExchange\Events as ApplicationExchangeEvents;
use Symfony\Component\EventDispatcher;

/**
 * Exchange bus clear subscriber
 *
 * @package         FastyBird:Database!
 * @subpackage      Subscribers
 *
 * @author          Adam Kadlec <adam.kadlec@fastybird.com>
 */
class ExchangeSubscriber implements EventDispatcher\EventSubscriberInterface
{

	/** @var Persistence\ManagerRegistry */
	private Persistence\ManagerRegistry $managerRegistry;

	/**
	 * @return string[]
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			ApplicationExchangeEvents\MessageConsumedEvent::class  => 'clear',
		];
	}

	public function __construct(
		Persistence\ManagerRegistry $managerRegistry
	) {
		$this->managerRegistry = $managerRegistry;
	}

	/**
	 * @return void
	 */
	public function clear(): void
	{
		$em = $this->managerRegistry->getManager();

		// Flushing and then clearing Doctrine's entity manager allows
		// for more memory to be released by PHP
		$em->flush();
		$em->clear();

		// Just in case PHP would choose not to run garbage collection,
		// we run it manually at the end of each batch so that memory is
		// regularly released
		gc_collect_cycles();
	}

}
