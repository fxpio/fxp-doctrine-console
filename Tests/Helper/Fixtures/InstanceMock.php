<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\DoctrineConsole\Tests\Helper\Fixtures;

/**
 * Mock instance.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class InstanceMock
{
    protected $name;
    protected $children;
    protected $valid;
    protected $validationDate;
    protected $numberOfTests;
    protected $roles;
    protected $listOfInteger;
    protected $listOfDatetime;
    protected $owner;

    public function __construct()
    {
        $this->name = 'Foo bar';
        $this->children = false;
        $this->valid = true;
        $this->validationDate = new \DateTime();
        $this->numberOfTests = 42;
        $this->roles = ['foo', 'bar'];
        $this->listOfInteger = [1, 2];
        $this->listOfDatetime = [clone $this->validationDate, clone $this->validationDate];
        $this->owner = new \stdClass();
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return $this->children;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * @return \DateTime
     */
    public function getValidationDate()
    {
        return $this->validationDate;
    }

    /**
     * @return int
     */
    public function getNumberOfTests()
    {
        return $this->numberOfTests;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return string[]
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return int[]
     */
    public function getListOfInteger()
    {
        return $this->listOfInteger;
    }

    /**
     * @return \DateTime[]
     */
    public function getListOfDatetime()
    {
        return $this->listOfDatetime;
    }

    public function setOwner(\stdClass $owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return \stdClass
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
