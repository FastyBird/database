<?php declare(strict_types = 1);

/**
 * InvalidStateException.php
 *
 * @license        More in LICENSE.md
 * @copyright      https://www.fastybird.com
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 * @package        FastyBird:Database!
 * @subpackage     Exceptions
 * @since          0.1.0
 *
 * @date           24.11.20
 */

namespace FastyBird\Database\Exceptions;

use RuntimeException;

class InvalidStateException extends RuntimeException implements IException
{

}
