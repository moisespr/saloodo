<?php
namespace App\Form;

use App\Entity\Price;
use App\Entity\Discount;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DiscountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $preSubmitListener = function (FormEvent $event) {
            $inData = $event->getData();
            $outData = ['amount' => $inData, 'type' => Discount::CONCRETE];
            $event->setData($outData);
        };

        $builder
            ->add('amount')
            ->add('type')
            ->addEventListener(FormEvents::PRE_SUBMIT, $preSubmitListener)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Discount::class
        ]);
    }
}
