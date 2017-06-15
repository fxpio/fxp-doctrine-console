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

use PHPUnit\Framework\TestCase;
use Sonatra\Component\DoctrineConsole\Exception\RecordNotFoundException;

/**
 * Record Not Found Exception Tests.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
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
