<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\DoctrineConsole\Exception;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidatorException as BaseValidatorException;

/**
 * Validator exception.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class ValidationException extends BaseValidatorException implements ExceptionInterface
{
    /**
     * Constructor.
     *
     * @param ConstraintViolationListInterface $violations The constraint violation list
     * @param int                              $code       The Exception code
     * @param \Exception|null                  $previous   The previous exception used for the exception chaining
     */
    public function __construct(ConstraintViolationListInterface $violations, $code = 0, \Exception $previous = null)
    {
        parent::__construct($this->buildMessage($violations), $code, $previous);
    }

    /**
     * Build the message.
     *
     * @param ConstraintViolationListInterface $violations The constraint violation list
     *
     * @return string
     */
    private function buildMessage(ConstraintViolationListInterface $violations)
    {
        $msg = '';
        $errors = array();
        $childrenErrors = array();

        /* @var ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            if (null === $violation->getPropertyPath()) {
                $errors[] = $violation->getMessage();
            } else {
                $childrenErrors[$violation->getPropertyPath()][] = $violation->getMessage();
            }
        }

        $msg = $this->buildGlobalErrors($errors, $msg);
        $msg = $this->buildChildrenErrors($childrenErrors, $msg);

        return $msg;
    }

    /**
     * Build the global errors and returns the exception message.
     *
     * @param string[] $errors The error messages
     * @param string   $msg    The exception message
     *
     * @return string
     */
    private function buildGlobalErrors(array $errors, $msg)
    {
        if (!empty($errors)) {
            $msg .= sprintf('%s- errors:', PHP_EOL);

            foreach ($errors as $error) {
                $msg .= sprintf('%s  - %s', PHP_EOL, $error);
            }
        }

        return $msg;
    }

    /**
     * Build the children errors and returns the exception message.
     *
     * @param array  $childrenErrors The children error messages
     * @param string $msg            The exception message
     *
     * @return string
     */
    private function buildChildrenErrors(array $childrenErrors, $msg)
    {
        if (!empty($childrenErrors)) {
            $msg .= PHP_EOL.'- children:';

            foreach ($childrenErrors as $child => $errors) {
                $msg .= sprintf('%s  - %s', PHP_EOL, $child);
                $msg .= sprintf('%s    - errors:', PHP_EOL);

                foreach ($errors as $error) {
                    $msg .= sprintf('%s      - %s', PHP_EOL, $error);
                }
            }
        }

        return $msg;
    }
}
