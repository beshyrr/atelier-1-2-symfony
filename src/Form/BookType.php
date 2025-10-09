<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType; // <-- important d'ajouter
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class BookType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('title')
        ->add('category', ChoiceType::class, [
            'choices' => [
                'Sciences-Fiction' => 'Sciences-Fiction',
                'Mystery' => 'Mystery',
                'Autobiography' => 'Autobiography'
            ]
        ])
        ->add('publicationDate', DateType::class)
        ->add('published', CheckboxType::class, [
            'label' => 'Published ?',
            'required' => false, // décoché = false
        ])
        ->add('author', EntityType::class, [
            'class' => Author::class,
            'choice_label' => 'username',
        ])
    ;
}
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
