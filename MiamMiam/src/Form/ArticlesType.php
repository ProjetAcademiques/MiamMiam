<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Magasin;
use App\Entity\Type;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prix')
            ->add('quantitee')
            ->add('DateAjout', null, [
                'widget' => 'single_text',
            ])
            ->add('types', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('magasins', EntityType::class, [
                'class' => Magasin::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
