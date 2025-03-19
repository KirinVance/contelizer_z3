<?php

namespace App\Form;

use App\Entity\GorestUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FindGorestUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', SearchType::class, [
                'required' => false,
                'label' => 'Search by Name',
                'attr' => ['class' => 'form-control']
            ])
            ->add('email', SearchType::class, [
                'required' => false,
                'label' => 'Search by Email',
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GorestUser::class,
        ]);
    }
}