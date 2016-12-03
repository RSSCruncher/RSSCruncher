<?php

namespace ArthurHoaro\RssCruncherApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ArticleType
 *
 * Type of an article (used to validate an Article).
 *
 * @package ArthurHoaro\RssCruncherApiBundle\Form
 */
class ArticleType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
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
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
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
