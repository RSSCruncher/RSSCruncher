<?php

namespace ArthurHoaro\RssCruncherClientBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('redirectUri', TextType::class, ['required' => false])
            ->add('allowedGrantType', ChoiceType::class, [
                'choices' => [
                    'My application manages its user authentication'
                    => 'client_credentials',
                    'Users use their RSSCruncher account'
                    => 'authorization_code',
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('feedGroups', EntityType::class, [
                'class' => 'ArthurHoaro\RssCruncherApiBundle\Entity\FeedGroup',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.id', 'ASC');
                },
                'choice_label' => 'test'
            ])
            //->add('redirectUri', UrlType::class)
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