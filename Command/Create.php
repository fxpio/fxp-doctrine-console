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
class Create extends Base
{
    /**
     * {@inheritdoc}
     */
    protected $action = 'create';

    /**
     * {@inheritdoc}
     */
    protected $injectFieldOptions = true;

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $instance = $this->adapter->newInstance(array());

        $this->validateInstance($instance);
        $this->helper->injectNewValues($input, $instance);
        $this->adapter->create($instance);

        $this->showMessage($output, $instance, 'The %s <info>%s</info> was created with successfully');
    }
}
