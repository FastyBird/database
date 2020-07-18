<?php declare(strict_types = 1);

/**
 * TEntity.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:NodeDatabase!
 * @subpackage     Entities
 * @since          0.1.0
 *
 * @date           25.05.20
 */

namespace FastyBird\NodeDatabase\Entities;

use Ramsey\Uuid;

/**
 * Entity base trait
 *
 * @package        FastyBird:NodeDatabase!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 *
 * @property-read Uuid\UuidInterface $id
 */
trait TEntity
{

	/**
	 * {@inheritDoc}
	 */
	public function getId(): Uuid\UuidInterface
	{
		return $this->id;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRawId(): Uuid\UuidInterface
	{
		return $this->getId();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getPlainId(): string
	{
		return $this->id->toString();
	}

}
