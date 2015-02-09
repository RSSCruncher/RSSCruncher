<?php
/**
 * FeedType.php
 * Author: arthur
 */

namespace ArthurHoaro\RssCruncherApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ArticleType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('publicationdate', 'datetime')
            ->add('modificationdate', 'datetime')
            ->add('summary')
            ->add('content')
            ->add('authorname')
            ->add('authoremail')
            ->add('link')
            ->add('feed', 'entity', array(
                    'class' => 'ArthurHoaroRssCruncherApiBundle:Feed',
                    'property' => 'id'
                ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ArthurHoaro\RssCruncherApiBundle\Entity\Article',
            'csrf_protection'   => false,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return '';
    }
} 