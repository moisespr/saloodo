<?php

namespace App\Form;

use App\Entity\Price;
use App\Entity\Discount;

use App\Serializer\AmountFormatter;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PriceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount')
            ->add('discount', DiscountType::class)
            ->addEventListener(
                FormEvents::PRE_SUBMIT, 
                $this->decodeAmountFromIncomingAmountClosure()
            );
    }

    private function decodeAmountFromIncomingAmountClosure() 
    {
        return function (FormEvent $event) {
            $form = $event->getForm();
            $name = $form->getName();
            if($name === 'price') {
                $this->decodePrice($event);
            } else {
                $this->decodeDiscount($event);
            }
        };
    }

    private function decodePrice(FormEvent $event)
    {
        if($this->isStringEvent($event)) {
            $this->decodeFromString($event);
        } else {
            $this->decodeFromArray($event);
        }
    }

    private function decodeDiscount(FormEvent $event)
    {
        $discount = $event->getData();
        $data = ['discount' => $discount];
        $event->setData($data);
    }

    private function decodeFromArray(FormEvent $event)
    {
        $data = $event->getData();
        if(is_null($data))
            return;
        if($this->hasAmount($data)) {
            $this->decodeAmount($event);
        }
    }

    private function isStringEvent(FormEvent $event)
    {
        return is_string($event->getData());
    }

    private function hasAmount($data)
    {
        return array_key_exists('amount', $data);
    }

    private function decodeAmount(FormEvent $event)
    {
        $data = $event->getData();
        $amount = $data['amount'];
        $amountFormatter = new AmountFormatter($amount);
        $decodedAmount = $amountFormatter->getFormatted();
        $data['amount'] = $decodedAmount;
        $event->setData($data);
    }

    private function decodeFromString(FormEvent $event)
    {
        $price = $event->getData();
        $data = ['amount' => $price];
        $event->setData($data);

        $this->decodeAmount($event);
    }    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Price::class,
            'empty_data' => function (FormInterface $form) {
                if($this->hasPrice($form))
                    return null;
                return new Price();
            }
        ]);
    }

    private function hasPrice(FormInterface $form)
    {
        return is_null($form->get('amount')->getData()) && is_null($form->get('discount')->getData());
    }
}
