<?php

namespace App\Form;

use App\Entity\Image;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
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
                'multiple' => true,
            ])
        ;

    }

//    public function buildView(FormView $view, FormInterface $form, array $options): void
//    {
//        $object = $form->getParent()?->getData();
//        $image = $form->getData();
//        $view->vars['vich_uri'] = null;
//
//        $image_id = 0;
//        if ($image) {
//            $view->vars['vich_uri'] = $image->getImageName();
//            $image_id = $image->getId();
//        }
//
//    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
    public function getParent(): string
    {
        return EntityType::class;
    }
}
