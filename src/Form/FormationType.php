<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Categorie;
use App\Entity\Playlist;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('videoId', TextType::class, [
                'label' => 'ID de la vidéo YouTube'
            ])
            ->add('publishedAt', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'label' => 'Date de publication'
            ])
            ->add('description', null, [
                'required' => false,
                'label' => 'Description (facultative)'
            ])
            ->add('playlist', EntityType::class, [
                'class' => Playlist::class,
                'choice_label' => 'name',
                'label' => 'Playlist',
                'placeholder' => 'Choisir une playlist'
            ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => false,
                'required' => false,
                'label' => 'Catégories'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
