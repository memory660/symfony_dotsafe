<?php

namespace App\Form\Manager;

use App\Entity\Techno;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TechnoSelectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
dump($options['choices']);        

        $builder
            ->add('techno', ChoiceType::class, [
                'choices'  => $options['choices']['technos'],
   
            ],
   
            )
            ->add('member', ChoiceType::class, [
                'choices'  => $options['choices']['members'],
            ])            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([

            'choices' => null,
        ]);
    }
}
