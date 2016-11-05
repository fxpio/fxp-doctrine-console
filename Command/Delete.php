<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\DoctrineConsole\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class Delete extends Base
{
    /**
     * {@inheritdoc}
     */
    protected $action = 'delete';

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $methodGet = $this->adapter->getDisplayNameMethod();
        $id = $input->getArgument($this->adapter->getIdentifierArgument());
        $instance = $this->adapter->get($id);

        $this->adapter->delete($instance);

        $output->writeln(array(
            '',
            sprintf('Deleted the %s: <info>%s</info>', $this->adapter->getShortName(), $instance->$methodGet()),
        ));
    }
}
