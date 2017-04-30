<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('name')
          ->add(
            'description',
            TextareaType::class,
            array(
              'attr' => array('class' => 'product-edit-description'),
            )
          )
          ->add('price')
          ->add('in_stock')
          ->add(
            'imageFile',
            FileType::class,
            [
              'data_class' => null,
              'multiple' => false,
              'label' => false,
              'required' => false,
            ]
          )
          ->add(
            'categoryName',
            TextType::class,
            array(
              'attr' => array(
                'class' => 'autocomplete',
                'data-source' => $options['attr']['data-source'],
              ),
            )
          );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
          array(
            'data_class' => 'AppBundle\Entity\Product',
            'attr' => [],
          )
        );
    }
}