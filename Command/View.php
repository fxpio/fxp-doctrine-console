<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\DoctrineConsole\Command;

use Fxp\Component\DoctrineConsole\Helper\DetailObjectHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class View extends Base
{
    /**
     * {@inheritdoc}
     */
    protected $action = 'view';

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument($this->adapter->getIdentifierArgument());
        $instance = $this->adapter->get($id);

        $output->writeln(array(
            '',
            '<info>Details of '.$this->adapter->getShortName().':</info>',
            '',
        ));

        DetailObjectHelper::display($output, $instance);
        $output->writeln('');
    }
}
