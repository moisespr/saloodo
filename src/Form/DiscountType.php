<?php
namespace App\Form;

use App\Entity\Price;
use App\Entity\Discount;

use App\Serializer\AmountFormatter;
use App\Serializer\DiscountFormatter;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\DataMapperInterface;

class DiscountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount')
            ->add('type')
            ->addEventListener(
                FormEvents::PRE_SUBMIT, 
                $this->decodeDiscountFromIncomingAmountClosure()
            )
        ;
    }

    private function decodeDiscountFromIncomingAmountClosure() 
    {
        return function (FormEvent $event) {
            $amount = $event->getData();
            if(is_null($amount))
                return;
            $amountFormatter = new AmountFormatter($amount);
            $formatter = new DiscountFormatter($amountFormatter);
            $decodedDiscount = [
                'amount' => $amountFormatter->getFormatted(),
                'type' => $formatter->getDiscountType()
            ];
            $event->setData($decodedDiscount);
        };
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Discount::class,
            'empty_data' => function (FormInterface $form) {
                $amount = $form->get('amount')->getData();
                if(is_null($amount)) 
                    return null;
                $type = $form->get('type')->getData();
                return new Discount($amount, $type);
            }
        ]);
    }
}
