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

use Fxp\Component\DoctrineConsole\Exception\RecordNotFoundException;
use PHPUnit\Framework\TestCase;

/**
 * Record Not Found Exception Tests.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class RecordNotFoundExceptionTest extends TestCase
{
    public function testRecordNotFoundException()
    {
        $e = new RecordNotFoundException();
        $expected = 'The record does not exist for this identifier';

        $this->assertSame($expected, $e->getMessage());
    }
}
