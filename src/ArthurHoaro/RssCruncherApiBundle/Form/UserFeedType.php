<?php

namespace ArthurHoaro\RssCruncherApiBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Url;

/**
 * Class UserFeedType
 *
 * Type of a user feed (used to validate a UserFeed).
 *
 * @package ArthurHoaro\RssCruncherApiBundle\Form
 */
class UserFeedType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('site_name')
            ->add('site_url', UrlType::class, [
                'default_protocol' => 'http',
                'required' => false,
            ])
            ->add('feed_name')
            ->add('feed_url', UrlType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                    new Url(),
                    new Length([
                        'min' => 5,
                        'max' => 2000,
                        'minMessage' => 'Your siteUrl must be at least {{ limit }} characters length',
                        'maxMessage' => 'Your siteUrl cannot be longer than {{ limit }} characters length',
                    ])
                ]
            ]);
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ArthurHoaro\RssCruncherApiBundle\Entity\UserFeed',
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
