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
class Undelete extends Base
{
    /**
     * {@inheritdoc}
     */
    protected $action = 'undelete';

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument($this->adapter->getIdentifierArgument());
        $instance = $this->adapter->undelete($id);

        $this->showMessage($output, $instance, 'The %s <info>%s</info> was undeleted with successfully');
    }
}
