<?php

namespace App\Form;

use App\Entity\Dovetailing;
use App\Entity\Image;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DovetailingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $doveling = $builder->getData();

        $builder
            ->add('name')
            ->add('title')
            ->add('description')
            ->add('images', EntityType::class, [
                'class' => Image::class,
                'choice_label' => 'imageName',
                'multiple' => true,
            ])
//            ->add('images', CollectionType::class, [
//                'entry_type'=> ImageType::class,
//                'allow_add' => true,
//                'allow_delete' => true,
//                'by_reference' => false,// utiliser add and remove
//                'entry_options' => ['label' => false],
//            ])
        ;

        if ($doveling && $doveling->getId() !== null) {
            // on affiche la miniature
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dovetailing::class,
        ]);
    }
}
