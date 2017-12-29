<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\DoctrineConsole\Adapter;

use Fxp\Component\DoctrineConsole\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Command Adapter for service manager.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class ServiceManagerAdapter extends AbstractAdapter
{
    /**
     * @var object
     */
    protected $manager;

    /**
     * @var string
     */
    protected $classname;

    /**
     * @var string
     */
    protected $shortName;

    /**
     * @var string|null
     */
    protected $newInstanceMethod;

    /**
     * @var string|null
     */
    protected $createMethod;

    /**
     * @var string|null
     */
    protected $getMethod;

    /**
     * @var string|null
     */
    protected $updateMethod;

    /**
     * @var string|null
     */
    protected $deleteMethod;

    /**
     * @var string|null
     */
    protected $undeleteMethod;

    /**
     * @var ValidatorInterface|null
     */
    protected $validator;

    /**
     * Constructor.
     *
     * @param object             $manager   The manager
     * @param ValidatorInterface $validator The validator
     */
    public function __construct($manager, ValidatorInterface $validator = null)
    {
        $this->manager = $manager;
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function newInstance(array $options = [])
    {
        $this->validate('newInstance');

        return $this->manager->{$this->newInstanceMethod}();
    }

    /**
     * {@inheritdoc}
     */
    public function create($instance)
    {
        $this->validate('create');
        $this->validateObject($instance);
        $this->manager->{$this->createMethod}($instance);
    }

    /**
     * {@inheritdoc}
     */
    public function get($identifier)
    {
        $this->validate('get');

        $instance = $this->manager->{$this->getMethod}([$this->getIdentifierField() => $identifier]);

        if (null === $instance) {
            throw new \InvalidArgumentException(sprintf('The %s with the identifier "%s" does not exist', $this->getShortName(), $identifier));
        }

        return $instance;
    }

    /**
     * {@inheritdoc}
     */
    public function update($instance)
    {
        $this->validate('update');
        $this->validateObject($instance);
        $this->manager->{$this->updateMethod}($instance);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($instance)
    {
        $this->validate('delete');
        $this->manager->{$this->deleteMethod}($instance);
    }

    /**
     * {@inheritdoc}
     */
    public function undelete($identifier)
    {
        $this->validate('undelete');

        return $this->manager->{$this->undeleteMethod}($identifier);
    }

    /**
     * Set the class name of object.
     *
     * @param string $classname The class name of object
     */
    public function setClass($classname)
    {
        $this->classname = $classname;
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        return $this->classname;
    }

    /**
     * Set the short name of object.
     *
     * @param string $shortName The short name of object
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }

    /**
     * {@inheritdoc}
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Set the method name of service manager to create the instance without saving it.
     *
     * @param string $newInstanceMethod The method name to create the instance without saving
     */
    public function setNewInstanceMethod($newInstanceMethod)
    {
        $this->newInstanceMethod = $newInstanceMethod;
    }

    /**
     * Set the method name of service manager to create the instance.
     *
     * @param string $createMethod The method name to create the instance
     */
    public function setCreateMethod($createMethod)
    {
        $this->createMethod = $createMethod;
    }

    /**
     * Set the method name of service manager to get the instance.
     *
     * @param string $getMethod The method name to get the instance
     */
    public function setGetMethod($getMethod)
    {
        $this->getMethod = $getMethod;
    }

    /**
     * Set the method name of service manager to update the instance.
     *
     * @param string $updateMethod The method name to update the instance
     */
    public function setUpdateMethod($updateMethod)
    {
        $this->updateMethod = $updateMethod;
    }

    /**
     * Set the method name of service manager to delete the instance.
     *
     * @param string $deleteMethod The method name to delete the instance
     */
    public function setDeleteMethod($deleteMethod)
    {
        $this->deleteMethod = $deleteMethod;
    }

    /**
     * Set the method name of service manager to undelete the instance.
     *
     * @param string $undeleteMethod The method name to undelete the instance
     */
    public function setUndeleteMethod($undeleteMethod)
    {
        $this->undeleteMethod = $undeleteMethod;
    }

    /**
     * Validate the adapter method.
     *
     * @param string $method The method name
     *
     * @throws \RuntimeException When the method does not supported
     */
    private function validate($method)
    {
        $actionMethod = $method.'Method';
        $ref = new \ReflectionClass($this->manager);

        if (null === $this->$actionMethod || !$ref->hasMethod($this->$actionMethod)) {
            throw new \RuntimeException(sprintf('The "%s" method for "%s" adapter is does not supported', $method, $this->getClass()));
        }
    }

    /**
     * Validate the object instance.
     *
     * @param object $instance The object instance
     *
     * @throws ValidationException When an error exist
     */
    public function validateObject($instance)
    {
        if (null !== $this->validator) {
            $violations = $this->validator->validate($instance);

            if (!empty($violations)) {
                throw new ValidationException($violations);
            }
        }
    }
}
