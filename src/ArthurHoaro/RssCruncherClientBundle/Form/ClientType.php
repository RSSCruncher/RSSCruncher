<?php
/**
 * ClientForm.php
 * Author: arthur
 */

namespace ArthurHoaro\RssCruncherClientBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;

class ClientType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('redirectUris', CollectionType::class,
                [
                    'entry_type' => UrlType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false
                ]
            )
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'arthurhoaro_rsscruncherclientbundle_client';
    }
}