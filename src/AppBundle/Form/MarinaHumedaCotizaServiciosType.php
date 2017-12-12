<?php
/**
 * Created by PhpStorm.
 * User: Luiz
 * Date: 09/10/2017
 * Time: 04:35 PM
 */

namespace AppBundle\Form;


use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\MarinaHumedaTarifa;

class MarinaHumedaCotizaServiciosType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            //->add('servicio')
//            ->add('cantidad',null,[
//                'attr' => ['class' => 'esdecimal']
//            ])
            ->add('precio', EntityType::class, [
                'class' => 'AppBundle:MarinaHumedaTarifa',
                'label' => 'Precio',
                'placeholder' => '0',
                'required' => false,
                'choice_value' => 'costo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->andWhere('t.tipo = 1');
                }
            ])
            ->add('precioAux', EntityType::class, [
                'class' => 'AppBundle:MarinaHumedaTarifa',
                'label' => 'Precio',
                'placeholder' => '0',
                'required' => false,
                'choice_value' => 'costo',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->andWhere('t.tipo = 2');
                }
            ]);

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {

                $data = $event->getData();

                $costo = $data->getPrecio();

                $form = $event->getForm();
                $form
                    ->add('precio', EntityType::class, [
                    'class' => 'AppBundle:MarinaHumedaTarifa',
                    'label' => 'Precio',
                    'placeholder' => '0',
                    'required' => false,
                    'choice_value' => 'costo',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('t')
                            ->andWhere('t.tipo = 1');
                    },
                    'choice_attr' => function ($objeto) use ($costo) {
                        return $objeto->getCosto() === $costo ? ['selected' => 'selected'] : [''];
                    }
                ])
                    ->add('precioAux', EntityType::class, [
                        'class' => 'AppBundle:MarinaHumedaTarifa',
                        'label' => 'Precio',
                        'placeholder' => '0',
                        'required' => false,
                        'choice_value' => 'costo',
                        'query_builder' => function (EntityRepository $er) {
                            return $er->createQueryBuilder('t')
                                ->andWhere('t.tipo = 2');
                        },
                        'choice_attr' => function ($objeto) use ($costo) {
                            return $objeto->getCosto() === $costo ? ['selected' => 'selected'] : [''];
                        }
                    ]);
                ;
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\MarinaHumedaCotizaServicios'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_marinahumedacotizaservicios';
    }
}