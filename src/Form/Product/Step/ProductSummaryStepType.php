<?php

namespace App\Form\Product\Step;

use App\Form\Product\ProductFlowData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSummaryStepType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductFlowData::class,
            'inherit_data' => true,
        ]);
    }
}
