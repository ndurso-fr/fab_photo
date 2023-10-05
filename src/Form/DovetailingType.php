<?php

namespace App\Form;

use App\Entity\Dovetailing;
use App\Entity\Image;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DovetailingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Dovetailing $doveling */
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
//            ])
            // le pb avec ça c'est que le twig <li>{{ form_row(image.imageName) }}</li>
            // ne connait pas le row imageName qui n'est pas dans l'objet.
//            ->add('images', CollectionType::class, [
//                'entry_type'=> EntityType::class,
//                'allow_add' => true,
//                'allow_delete' => true,
//                'by_reference' => false,// utiliser add and remove
//                'entry_options' => [
//                    'class' => Image::class,
//                    'query_builder' => function (EntityRepository $er) {
//                        return $er->createQueryBuilder('i')
//                            ->orderBy('i.imageName', 'ASC');
//                    },
//                    'choice_label' => 'imageName',
//                    'choice_value' => 'id',
//                ],
//            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Dovetailing::class,
        ]);
    }
}
