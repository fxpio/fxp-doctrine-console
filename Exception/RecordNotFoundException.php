<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\DoctrineConsole\Exception;

/**
 * Record not found exception.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class RecordNotFoundException extends \RuntimeException implements ExceptionInterface
{
    /**
     * Constructor.
     *
     * @param string          $message  The message
     * @param int             $code     The Exception code
     * @param \Exception|null $previous The previous exception used for the exception chaining
     */
    public function __construct($message = 'The record does not exist for this identifier', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
