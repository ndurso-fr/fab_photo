<?php

namespace App\Form;

use App\Entity\Image;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageName', EntityType::class, [
                'class' => Image::class,
                'choice_label' => 'imageName',
            ])
        ;
        /*
                     ->add('images', CollectionType::class, [
                'entry_type'=> EntityType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options'=>[
                    'class'=>Image::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('i')
                            ->orderBy('i.imageName', 'ASC');
                    },
                    'choice_label' => 'imageName',
                    'choice_value' => 'id'
                ]
            ])
         *
         */
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
//            'data_class' => Image::class,
//            'class' => Image::class,
//            'multiple' => true,
//            'choice_label' => 'imageName'
        ]);
    }
}
