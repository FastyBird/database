<?php declare(strict_types = 1);

/**
 * InvalidStateException.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:NodeDatabase!
 * @subpackage     Exceptions
 * @since          0.1.0
 *
 * @date           25.05.20
 */

namespace FastyBird\NodeDatabase\Exceptions;

use FastyBird\NodeLibs\Exceptions as NodeLibsExceptions;

class InvalidStateException extends NodeLibsExceptions\InvalidStateException implements IException
{

}
