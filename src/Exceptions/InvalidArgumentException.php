<?php declare(strict_types = 1);

/**
 * InvalidArgumentException.php
 *
 * @license        More in license.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:NodeDatabase!
 * @subpackage     Exceptions
 * @since          0.1.0
 *
 * @date           10.03.20
 */

namespace FastyBird\NodeDatabase\Exceptions;

use FastyBird\NodeLibs\Exceptions as NodeLibsExceptions;

class InvalidArgumentException extends NodeLibsExceptions\InvalidArgumentException implements IException
{

}
