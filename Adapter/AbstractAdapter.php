<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\DoctrineConsole\Adapter;

/**
 * Abstract command adapter.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
abstract class AbstractAdapter implements AdapterInterface
{
    /**
     * @var string
     */
    protected $commandPrefix;

    /**
     * @var string
     */
    protected $commandDescription;

    /**
     * @var string
     */
    protected $identifierField;

    /**
     * @var string
     */
    protected $identifierArgument;

    /**
     * @var string
     */
    protected $identifierArgumentDescription;

    /**
     * @var string
     */
    protected $displayNameMethod;

    /**
     * Set the prefix of command name.
     *
     * @param string $commandPrefix The prefix command name
     */
    public function setCommandPrefix($commandPrefix)
    {
        $this->commandPrefix = $commandPrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function getCommandPrefix()
    {
        return $this->commandPrefix;
    }

    /**
     * Set the command description.
     *
     * @param string $commandDescription The command description
     */
    public function setCommandDescription($commandDescription)
    {
        $this->commandDescription = str_replace('{s}', '%s', $commandDescription);
    }

    /**
     * {@inheritdoc}
     */
    public function getCommandDescription()
    {
        return $this->commandDescription;
    }

    /**
     * Set the identifier field of object.
     *
     * @param string $identifierField The identifier field of object
     */
    public function setIdentifierField($identifierField)
    {
        $this->identifierField = $identifierField;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifierField()
    {
        return $this->identifierField;
    }

    /**
     * Set the command argument name of identifier.
     *
     * @param string $identifierArgument The command argument name of identifier
     */
    public function setIdentifierArgument($identifierArgument)
    {
        $this->identifierArgument = $identifierArgument;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifierArgument()
    {
        return $this->identifierArgument;
    }

    /**
     * Set the command argument description of identifier.
     *
     * @param string $identifierArgumentDescription The description of the identifier argument
     */
    public function setIdentifierArgumentDescription($identifierArgumentDescription)
    {
        $this->identifierArgumentDescription = str_replace('{s}', '%s', $identifierArgumentDescription);
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifierArgumentDescription()
    {
        return $this->identifierArgumentDescription;
    }

    /**
     * Set the method name for display the object in console.
     *
     * @param string $displayNameMethod The method name for display the object in console
     */
    public function setDisplayNameMethod($displayNameMethod)
    {
        $this->displayNameMethod = $displayNameMethod;
    }

    /**
     * {@inheritdoc}
     */
    public function getDisplayNameMethod()
    {
        return $this->displayNameMethod;
    }
}
