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

use Sonatra\Component\DoctrineConsole\Exception\ValidationException;
use Sonatra\Component\Resource\Domain\DomainInterface;
use Sonatra\Component\Resource\ResourceInterface;

/**
 * Command Adapter for resource domain.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class ResourceAdapter extends AbstractAdapter
{
    /**
     * @var DomainInterface
     */
    protected $domain;

    /**
     * Constructor.
     *
     * @param DomainInterface $domain The resource domain
     */
    public function __construct(DomainInterface $domain)
    {
        $this->domain = $domain;
    }

    /**
     * {@inheritdoc}
     */
    public function newInstance(array $options = array())
    {
        return $this->domain->newInstance($options);
    }

    /**
     * {@inheritdoc}
     */
    public function create($instance)
    {
        $res = $this->domain->create($instance);
        $this->validateResource($res);
    }

    /**
     * {@inheritdoc}
     */
    public function get($identifier)
    {
        return $this->domain->getRepository()->findOneBy(array($this->getIdentifierField() => $identifier));
    }

    /**
     * {@inheritdoc}
     */
    public function update($instance)
    {
        $res = $this->domain->update($instance);
        $this->validateResource($res);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($instance)
    {
        $res = $this->domain->delete($instance);
        $this->validateResource($res);
    }

    /**
     * {@inheritdoc}
     */
    public function undelete($identifier)
    {
        $res = $this->domain->undelete($identifier);
        $this->validateResource($res);

        return $res->getRealData();
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        return $this->domain->getClass();
    }

    /**
     * {@inheritdoc}
     */
    public function getShortName()
    {
        return $this->domain->getShortName();
    }

    /**
     * Validate the resource.
     *
     * @param ResourceInterface $resource The resource
     *
     * @throws ValidationException When an error exist
     */
    private function validateResource(ResourceInterface $resource)
    {
        if (!$resource->isValid()) {
            throw new ValidationException($resource->getErrors());
        }
    }
}
