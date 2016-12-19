<?php

namespace ArthurHoaro\RssCruncherClientBundle\Form;


use ArthurHoaro\RssCruncherApiBundle\Entity\FeedGroup;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Validator\Constraints\NotBlank;

class ClientType extends AbstractType
{
    protected $securityContext;

    protected $user;

    /**
     * ClientType constructor.
     * @param TokenStorage $securityContext
     */
    public function __construct(TokenStorage $securityContext)
    {
        $this->securityContext = $securityContext;
        $this->user = $this->securityContext->getToken()->getUser();
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->user;
        $tmp = new FeedGroup();
        $tmp->setName('fsd');
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
            ->add('mainFeedGroup', EntityType::class, [
                'label' => 'Sync my feeds with group',
                'class' => 'ArthurHoaro\RssCruncherApiBundle\Entity\FeedGroup',
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('fg')
                        ->join('fg.proxyUsers', 'pu')
                        ->where('pu.user = :user')
                        ->orderBy('fg.id', 'ASC')
                        ->setParameter('user', $user);
                },
                'mapped' => false,
                'required' => false,
                'choice_label' => 'name',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('createNewGroup', CheckboxType::class, [
                'label' => 'Or create new feed group',
                'mapped' => false,
                'required' => false,
            ])
            //->add('redirectUri', UrlType::class)
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $client = $event->getData();
            $form = $event->getForm();

            // If createNewGroup is checked, we remove the NotBlank validation constraint on mainFeedGroup
            if (isset($client['createNewGroup']) && $client['createNewGroup'] == true) {
                $field = 'mainFeedGroup';
                $type = get_class($form->get($field)->getConfig()->getType()->getInnerType());
                $options = $form->get($field)->getConfig()->getOptions();
                $form->add($field, $type, array_merge(
                    $options,
                    ['constraints' => []]
                ));
            }
        });
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'arthurhoaro_rsscruncherclientbundle_client';
    }
}