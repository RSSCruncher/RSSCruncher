<?php
/**
 * FeedType.php
 * Author: arthur
 */

namespace ArthurHoaro\RssCruncherApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeedType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sitename')
            ->add('siteurl')
            ->add('feedname')
            ->add('feedurl')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ArthurHoaro\RssCruncherApiBundle\Entity\Feed',
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