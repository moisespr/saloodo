<?php

namespace App\Form;

use App\Entity\Bundle;
use App\Form\DataTransformer\ProductsArrayToIdsArrayTransformer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\DataMapperInterface;

class BundleType extends ProductType
{
    private $transformer;

    public function __construct(ProductsArrayToIdsArrayTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('products', CollectionType::class, array(
                    'entry_type' => IntegerType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false
                )
            )
        ;

        $builder->get('products')
            ->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bundle::class,
            'csrf_protection' => false,
        ]);
    }
}

