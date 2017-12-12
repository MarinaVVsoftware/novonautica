<?php

namespace AppBundle\Form;

//use Doctrine\DBAL\Types\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PagoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('metodopago',ChoiceType::class,[
                'choices'  => ['Efectivo' => 'Efectivo',
                    'Transferencia' => 'Transferencia',
                    'Tarjeta de crédito' => 'Tarjeta de crédito'
                    ],
                'placeholder' => 'Seleccionar...',
                'label' => 'Método de pago',
                'required' => false
            ])
            ->add('cantidad',MoneyType::class,[
                'label' => 'Pago',
                'required' => false,
                'currency' => 'USD',
                'divisor' => 100,
                'grouping' => true,
                'attr' => ['class' => 'esdecimal']
            ])
            ->add('fecharealpago',DateType::class,[
                'label' => 'Fecha de pago',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'datepicker-solo input-calendario',
                    'readonly' => true],
                'format' => 'yyyy-MM-dd'
            ])
            ->add('dolar',MoneyType::class,[
                'label' => 'Valor del dolar',
                'required' => false,
                'currency' => 'USD',
                'divisor' => 100,
                'grouping' => true,
                'attr' => ['class' => 'esdecimal']
            ])
//            ->add('fechalimitepago')
//            ->add('titular')
//            ->add('banco')
//            ->add('numcuenta')
//            ->add('codigoseguimiento')
//            ->add('fecharealpago')
//            ->add('mhcotizacion')
//            ->add('cuentabancaria')
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Pago'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_pago';
    }


}
