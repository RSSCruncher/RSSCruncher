<?php
/**
 * FeedType.php
 * Author: arthur
 */

namespace ArthurHoaro\FeedsApiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'ArthurHoaro\FeedsApiBundle\Entity\Feed',
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