<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\DoctrineConsole\Tests\Exception;

use Sonatra\Component\DoctrineConsole\Exception\ValidationException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Validation Exception Tests.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ValidationExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testViolationExceptionWithEmptyViolation()
    {
        $e = new ValidationException(new ConstraintViolationList(array()));

        $this->assertSame('', $e->getMessage());
    }

    public function testViolationException()
    {
        $object = new \stdClass();
        $object->foo = 'bar';
        $object->bar = 'foo';

        $e = new ValidationException(new ConstraintViolationList(array(
            new ConstraintViolation('Message 1', 'Message 1', array(), $object, null, null),
            new ConstraintViolation('Message 2', 'Message 2', array(), $object, 'foo', null),
            new ConstraintViolation('Message 3', 'Message 3', array(), $object, 'bar', null),
        )));

        $expected = <<<'EOT'

- errors:
  - Message 1
- children:
  - foo
    - errors:
      - Message 2
  - bar
    - errors:
      - Message 3
EOT;

        $this->assertSame(str_replace(array("\r", "\n"), PHP_EOL, $expected), $e->getMessage());
    }
}
