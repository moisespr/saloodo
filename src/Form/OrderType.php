<?php
namespace App\Form;

use App\Entity\Order;
use App\Form\DataTransformer\OrderItemsArrayToIdsArrayTransformer;
use App\Form\DataTransformer\CustomerToIdTransformer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\DataMapperInterface;

class OrderType extends AbstractType
{
    private $customerTransformer;
    private $itemsTransformer;

    public function __construct(
        CustomerToIdTransformer $customerTransformer,
        OrderItemsArrayToIdsArrayTransformer $itemsTransformer
    )
    {
        $this->customerTransformer = $customerTransformer;
        $this->itemsTransformer = $itemsTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('customer', IntegerType::class)
            ->add('items', CollectionType::class, array(
                    'entry_type' => IntegerType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false
                )
            )
        ;

        $builder->get('customer')
            ->addModelTransformer($this->customerTransformer);
        $builder->get('items')
            ->addModelTransformer($this->itemsTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
            'csrf_protection' => false,
        ]);
    }
}

