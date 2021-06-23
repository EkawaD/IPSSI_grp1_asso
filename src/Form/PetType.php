<?php

namespace App\Form;

use App\Entity\Pet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,  ["label" => "Nom"])
            ->add('species',TextType::class,  ["label" => "Espèce"])
            ->add('breed', TextType::class,  ["label" => "Race"])
            ->add('age', IntegerType::class,  ["label" => "Age"])
            ->add('weight',IntegerType::class,  ["label" => "Poids"])
            ->add('sex', TextType::class,  ["label" => "Sexe"])
            ->add('image', FileType::class, ["label" => "Image (jpeg ou png)", 'data_class' => null])
            ->add('Add', SubmitType::class, ["label" => "Mettre l'animal en adoption"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pet::class,
        ]);
    }
}
