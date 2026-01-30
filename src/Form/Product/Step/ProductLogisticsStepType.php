<?php

namespace App\Form\Product\Step;

use App\Form\Product\ProductFlowData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductLogisticsStepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('weight', NumberType::class, [
                'label' => 'Poids (kg)',
                'scale' => 2,
            ])
            ->add('dimensions', null, [
                'label' => 'Dimensions',
            ])
            ->add('stock', IntegerType::class, [
                'label' => 'Stock',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductFlowData::class,
            'inherit_data' => true,
        ]);
    }
}
