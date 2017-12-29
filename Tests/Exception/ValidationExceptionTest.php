<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\DoctrineConsole\Tests\Exception;

use Fxp\Component\DoctrineConsole\Exception\ValidationException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Validation Exception Tests.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class ValidationExceptionTest extends TestCase
{
    public function testViolationExceptionWithEmptyViolation()
    {
        $e = new ValidationException(new ConstraintViolationList([]));

        $this->assertSame('', $e->getMessage());
    }

    public function testViolationException()
    {
        $object = new \stdClass();
        $object->foo = 'bar';
        $object->bar = 'foo';

        $e = new ValidationException(new ConstraintViolationList([
            new ConstraintViolation('Message 1', 'Message 1', [], $object, null, null),
            new ConstraintViolation('Message 2', 'Message 2', [], $object, 'foo', null),
            new ConstraintViolation('Message 3', 'Message 3', [], $object, 'bar', null),
        ]));

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

        $this->assertSame(str_replace(["\r", "\n"], PHP_EOL, $expected), $e->getMessage());
    }
}
