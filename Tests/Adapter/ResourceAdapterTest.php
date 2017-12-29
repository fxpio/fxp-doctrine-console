<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\DoctrineConsole\Tests\Adapter;

use Doctrine\Common\Persistence\ObjectRepository;
use Fxp\Component\DoctrineConsole\Adapter\ResourceAdapter;
use Fxp\Component\Resource\Domain\DomainInterface;
use Fxp\Component\Resource\ResourceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Resource Adapter Tests.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class ResourceAdapterTest extends TestCase
{
    /**
     * @var DomainInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $domain;

    /**
     * @var ResourceAdapter
     */
    protected $adapter;

    protected function setUp()
    {
        $this->domain = $this->getMockBuilder(DomainInterface::class)->getMock();
        $this->adapter = new ResourceAdapter($this->domain);
    }

    protected function tearDown()
    {
        $this->domain = null;
        $this->adapter = null;
    }

    public function testNewInstance()
    {
        $options = array();
        $object = new \stdClass();

        $this->domain->expects($this->once())
            ->method('newInstance')
            ->with($options)
            ->willReturn($object);

        $res = $this->adapter->newInstance($options);
        $this->assertSame($object, $res);
    }

    public function getActions()
    {
        return array(
            array('create'),
            array('update'),
            array('delete'),
            array('undelete'),
        );
    }

    /**
     * @dataProvider getActions
     *
     * @param string $action The action
     */
    public function testAction($action)
    {
        $object = new \stdClass();

        $this->domain->expects($this->once())
            ->method($action)
            ->with($object)
            ->willReturn($this->getResource(true));

        $this->adapter->$action($object);
    }

    /**
     * @dataProvider getActions
     *
     * @param string $action The action
     *
     * @expectedException \Fxp\Component\DoctrineConsole\Exception\ValidationException
     */
    public function testCreateWithViolations($action)
    {
        $object = new \stdClass();

        $this->domain->expects($this->once())
            ->method($action)
            ->with($object)
            ->willReturn($this->getResource(false));

        $this->adapter->$action($object);
    }

    public function testGet()
    {
        $object = new \stdClass();
        $object->id = 42;
        $repo = $this->getMockBuilder(ObjectRepository::class)->getMock();

        $this->domain->expects($this->once())
            ->method('getRepository')
            ->willReturn($repo);

        $repo->expects($this->once())
            ->method('findOneBy')
            ->with(array(
                'id' => $object->id,
            ))
            ->willReturn($object);

        $this->adapter->setIdentifierField('id');
        $res = $this->adapter->get($object->id);

        $this->assertSame($res, $object);
    }

    public function testGetClass()
    {
        $this->domain->expects($this->once())
            ->method('getClass')
            ->willReturn(\stdClass::class);

        $this->assertSame(\stdClass::class, $this->adapter->getClass());
    }

    public function testGetShortName()
    {
        $this->domain->expects($this->once())
            ->method('getShortName')
            ->willReturn('NAME');

        $this->assertSame('NAME', $this->adapter->getShortName());
    }

    /**
     * Get the resource.
     *
     * @param bool $valid The valid result
     *
     * @return ResourceInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getResource($valid = true)
    {
        $resource = $this->getMockBuilder(ResourceInterface::class)->getMock();

        $resource->expects($this->atLeastOnce())
            ->method('isValid')
            ->willReturn($valid);

        if (!$valid) {
            $violations = $this->getMockBuilder(ConstraintViolationListInterface::class)->getMock();

            $resource->expects($this->once())
                ->method('getErrors')
                ->willReturn($violations);
        }

        return $resource;
    }
}
