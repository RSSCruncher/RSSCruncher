<?php

namespace ArthurHoaro\FeedsApiBundle\Tests\Handler;

use ArthurHoaro\FeedsApiBundle\Entity\Feed;
use ArthurHoaro\FeedsApiBundle\Handler\FeedHandler;

class FeedHandlerTest extends \PHPUnit_Framework_TestCase {
    const FEED_CLASS = 'ArthurHoaro\FeedsApiBundle\Tests\Handler\DummyFeed';

    /** @var FeedHandler */
    protected $feedHandler;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $om;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $repository;

    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }

        $class = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $this->om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');

        $this->om->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(static::FEED_CLASS))
            ->will($this->returnValue($this->repository));
        $this->om->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo(static::FEED_CLASS))
            ->will($this->returnValue($class));
        $class->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(static::FEED_CLASS));
    }

    public function testGet()
    {
        $id = 1;
        $feed = $this->getFeed();
        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo($id))
            ->will($this->returnValue($feed));
        $this->feedHandler = $this->createFeedHandler($this->om, static::FEED_CLASS, $this->formFactory);
        $this->feedHandler->get($id);
    }

    protected function createFeedHandler($objectManager, $feedClass, $formFactory)
    {
        return new FeedHandler($objectManager, $feedClass, $formFactory);
    }
    
    protected function getFeed()
    {
        $feedClass = static::FEED_CLASS;
        return new $feedClass();
    }
}

class DummyFeed extends Feed {}