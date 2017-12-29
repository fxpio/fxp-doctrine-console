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

use Fxp\Component\DoctrineConsole\Adapter\AdapterInterface;
use Fxp\Component\DoctrineConsole\Exception\RecordNotFoundException;
use Fxp\Component\DoctrineConsole\Helper\ObjectFieldHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
abstract class Base extends Command
{
    /**
     * @var ObjectFieldHelper
     */
    protected $helper;

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var string
     */
    protected $action = 'INVALID_ACTION';

    /**
     * @var bool
     */
    protected $injectFieldOptions = false;

    /**
     * @var array
     */
    protected $configArguments;

    /**
     * @var array
     */
    protected $configOptions;

    /**
     * Constructor.
     *
     * @param ObjectFieldHelper $helper          The doctrine console object field helper
     * @param AdapterInterface  $adapter         The command adapter
     * @param array             $configArguments The config of custom command arguments
     * @param array             $configOptions   The config of custom command options
     */
    public function __construct(ObjectFieldHelper $helper, AdapterInterface $adapter, array $configArguments = array(), array $configOptions = array())
    {
        $this->helper = $helper;
        $this->adapter = $adapter;
        $this->configArguments = $configArguments;
        $this->configOptions = $configOptions;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $adp = $this->adapter;
        $this
            ->setName($adp->getCommandPrefix().':'.$this->action)
            ->setDescription(sprintf($adp->getCommandDescription(), $this->action, $adp->getClass()))
        ;

        foreach ($this->configArguments as $name => $config) {
            $this->addArgument($name, $config['mode'], $config['description'], $config['default']);
        }

        foreach ($this->configOptions as $name => $config) {
            $this->addOption($name, $config['shortcut'], $config['mode'], $config['description'], $config['default']);
        }

        if ('create' !== $this->action && !$this->getDefinition()->hasArgument($adp->getIdentifierArgument())) {
            $this->addArgument($adp->getIdentifierArgument(), InputArgument::REQUIRED, sprintf($adp->getIdentifierArgumentDescription(), $adp->getShortName()));
        }

        if ($this->injectFieldOptions) {
            $this->helper->injectFieldOptions($this->getDefinition(), $this->adapter->getClass());
        }
    }

    /**
     * Validate the instance.
     *
     * @param object|null $instance The instance
     *
     * @throws RecordNotFoundException
     */
    protected function validateInstance($instance)
    {
        if (!is_object($instance)) {
            throw new RecordNotFoundException();
        }
    }

    /**
     * Show the message in the console output.
     *
     * @param OutputInterface $output   The console output
     * @param object          $instance The object instance
     * @param string          $message  The displayed message
     */
    protected function showMessage(OutputInterface $output, $instance, $message)
    {
        $methodGet = $this->adapter->getDisplayNameMethod();

        $output->writeln(array(
            '',
            sprintf($message, strtolower($this->adapter->getShortName()), $instance->$methodGet()),
        ));
    }
}
