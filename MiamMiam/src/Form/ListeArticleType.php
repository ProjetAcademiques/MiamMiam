<?php

namespace App\Form;

use App\Entity\ListeArticle;
use App\Entity\Article;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListeArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('article', EntityType::class, [
                'class' => Article::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisir un article',
            ])
            ->add('quantite', IntegerType::class, [
                'required' => true,
                'label' => 'Quantité',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ListeArticle::class,
        ]);
    }
}
