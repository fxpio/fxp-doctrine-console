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
class Update extends Base
{
    /**
     * {@inheritdoc}
     */
    protected $action = 'update';

    /**
     * {@inheritdoc}
     */
    protected $injectFieldOptions = true;

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument($this->adapter->getIdentifierArgument());
        $instance = $this->adapter->get($id);

        $this->validateInstance($instance);
        $this->helper->injectNewValues($input, $instance);
        $this->adapter->update($instance);

        $this->showMessage($output, $instance, 'The %s <info>%s</info> was updated with successfully');
    }
}
