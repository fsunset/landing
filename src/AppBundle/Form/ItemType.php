<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ItemType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', null, array(
                'label_format' => 'Código',
            ))
            ->add('name', null, array(
                'label_format' => 'Nombre',
            ))
            ->add('description', TextareaType::class, array(
                'label_format' => 'Descripcion',
            ))
            ->add('image', null, array(
                'label_format' => 'Foto',
            ))
            ->add('unitaryPrice', null, array(
                'label_format' => 'Precio Unitario',
            ))
            ->add('comboPrice', null, array(
                'label_format' => 'Precio Combo',
            ))
            ->add('isFeatured', null, array(
                'label_format' => 'Destacado',
            ))
            ->add('isActive', null, array(
                'label_format' => 'Activo',
            ))
            ->add('isDuo', null, array(
                'label_format' => '¿Es Combo Duo?',
            ))
            ->add('showSelections', null, array(
                'label_format' => 'Mostrar selecciones (Adiciones, Bebidas y Acompañamientos)',
            ))
            ->add('section', null, array(
                'required'   => true,
                'label_format' => 'Sección',
                ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Item'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_item';
    }


}
