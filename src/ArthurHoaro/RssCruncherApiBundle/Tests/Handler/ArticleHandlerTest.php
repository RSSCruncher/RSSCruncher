<?php

namespace ArthurHoaro\RssCruncherApiBundle\Tests\Handler;

use ArthurHoaro\RssCruncherApiBundle\Entity\Article;
use ArthurHoaro\RssCruncherApiBundle\Form\ArticleType;
use ArthurHoaro\RssCruncherApiBundle\Handler\ArticleHandler;

class ArticleHandlerTest extends \PHPUnit_Framework_TestCase {
    const ARTICLE_CLASS = 'ArthurHoaro\RssCruncherApiBundle\Tests\Handler\DummyArticle';
    const ARTICLE_TYPE_CLASS = 'ArthurHoaro\RssCruncherApiBundle\Tests\Handler\DummyArticleType';

    /** @var ArticleHandler */
    protected $articleHandler;
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
            ->with($this->equalTo(static::ARTICLE_CLASS))
            ->will($this->returnValue($this->repository));
        $this->om->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo(static::ARTICLE_CLASS))
            ->will($this->returnValue($class));
        $class->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(static::ARTICLE_CLASS));
    }

    public function testGet()
    {
        $id = 1;
        $article = $this->getArticle();
        $this->repository->expects($this->once())->method('find')
            ->with($this->equalTo($id))
            ->will($this->returnValue($article));
        $this->articleHandler = $this->createArticleHandler($this->om, static::ARTICLE_CLASS, $this->formFactory, static::ARTICLE_TYPE_CLASS);
        $this->articleHandler->get($id);
    }

    protected function createArticleHandler($objectManager, $articleClass, $formFactory, $articleTypeClass)
    {
        return new ArticleHandler($objectManager, $articleClass, $formFactory, $articleTypeClass);
    }
    
    protected function getArticle()
    {
        $articleClass = static::ARTICLE_CLASS;
        return new $articleClass();
    }
}

class DummyArticle extends Article {}

class DummyArticleType extends ArticleType {}