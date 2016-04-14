<?php

namespace ArthurHoaro\RssCruncherClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TestentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('abc')
            ->add('test')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ArthurHoaro\RssCruncherClientBundle\Entity\Testent'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'arthurhoaro_rsscruncherclientbundle_testent';
    }
}
