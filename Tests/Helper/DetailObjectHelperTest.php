<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\DoctrineConsole\Tests\Helper;

use PHPUnit\Framework\TestCase;
use Sonatra\Component\DoctrineConsole\Helper\DetailObjectHelper;
use Sonatra\Component\DoctrineConsole\Tests\Helper\Fixtures\InstanceMock;
use Symfony\Component\Console\Output\StreamOutput;

/**
 * Detail Object Helper Tests.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class DetailObjectHelperTest extends TestCase
{
    /**
     * @var StreamOutput|null
     */
    protected $output;

    /**
     * @return array
     */
    public function getHumanizeInputs()
    {
        return array(
            array('fooBar', 'Foo bar'),
            array('FooBar', 'Foo bar'),
        );
    }

    /**
     * @dataProvider getHumanizeInputs
     *
     * @param string $input
     * @param string $valid
     */
    public function testHumanize($input, $valid)
    {
        $this->assertSame($valid, DetailObjectHelper::humanize($input));
    }

    public function testDisplay()
    {
        $instance = new InstanceMock();
        $this->output = new StreamOutput(fopen('php://memory', 'w', false), StreamOutput::VERBOSITY_NORMAL, false);

        DetailObjectHelper::display($this->output, $instance);
        $validDate = $instance->getValidationDate()->format(\DateTime::ISO8601);
        $valid = array(
            ' Name             : Foo bar                  ',
            ' Has children     : False                    ',
            ' Is valid         : True                     ',
            ' Validation date  : '.$validDate.' ',
            ' Number of tests  : 42                       ',
            ' Roles            : foo                      ',
            '                    bar                      ',
            ' List of integer  : 1                        ',
            '                    2                        ',
            ' List of datetime : '.$validDate.' ',
            '                    '.$validDate.' ',
            ' Owner            : format error             ',
            '',
        );
        $this->assertSame(implode(PHP_EOL, $valid), $this->getDisplay());
    }

    /**
     * Gets the display returned by the last execution of the command.
     *
     * @return string The display
     */
    protected function getDisplay()
    {
        rewind($this->output->getStream());

        return stream_get_contents($this->output->getStream());
    }
}
