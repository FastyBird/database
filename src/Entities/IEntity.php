<?php declare(strict_types = 1);

/**
 * IEntity.php
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

use IPub\DoctrineCrud;
use Ramsey\Uuid;

/**
 * Node base entity interface
 *
 * @package        FastyBird:NodeDatabase!
 * @subpackage     Entities
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
interface IEntity extends DoctrineCrud\Entities\IIdentifiedEntity
{

	/**
	 * @return Uuid\UuidInterface
	 */
	public function getId(): Uuid\UuidInterface;

	/**
	 * @return string
	 */
	public function getPlainId(): string;

}
