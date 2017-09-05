<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AccompanimentType extends AbstractType
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
            ->add('unitaryPrice', null, array(
                'label_format' => 'Precio Unitario',
            ))
            ->add('isActive', null, array(
                'label_format' => 'Activo',
            ));

            $builder->add('items', ChoiceType::class, array(
                'label_format' => '¿Qué platos tienen este acompañamiento?',
                'choices'   => $options['items'],
                'multiple'  => true,
                'expanded'  => true,
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'items' => null,
            'data_class' => 'AppBundle\Entity\Accompaniment'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_accompaniment';
    }


}
