<?php

namespace RabbitMqModule\Service;

use PHPUnit_Framework_TestCase;
use Zend\ServiceManager\ServiceManager;

class AbstractServiceFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $serviceManager;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->serviceManager = new ServiceManager();
        $this->serviceManager->setService(
            'config',
            [
                'rabbitmq' => [
                    'connection' => [
                        'default' => [],
                    ],
                    'producer' => [
                        'foo' => [
                            'exchange' => [],
                        ],
                    ],
                    'foo' => [
                        'bar' => [

                        ],
                    ],
                ],
                'rabbitmq_factories' => [
                    'foo' => 'fooFactory',
                    'producer' => 'RabbitMqModule\\Service\\ServiceFactoryMock',
                ],
            ]
        );
    }

    public function testCanCreateServiceWithName()
    {
        $sm = $this->serviceManager;
        $factory = new AbstractServiceFactory();
        static::assertTrue($factory->canCreateServiceWithName($sm, 'rabbitmq.foo.bar', 'rabbitmq.foo.bar'));
        static::assertFalse($factory->canCreateServiceWithName($sm, 'rabbitmq.foo.bar', 'rabbitmq.foo.bar2'));
    }

    /**
     * @expectedException \Interop\Container\Exception\ContainerException
     */
    public function testCreateServiceUnknown()
    {
        $sm = $this->serviceManager;
        $factory = new AbstractServiceFactory();
        $factory->createServiceWithName($sm, 'rabbitmq.unknown-key.foo', 'rabbitmq.unknown-key.foo');
    }
}
