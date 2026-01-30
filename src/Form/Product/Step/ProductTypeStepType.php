<?php

namespace App\Form\Product\Step;

use App\Form\Product\ProductFlowData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductTypeStepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('productType', ChoiceType::class, [
            'label' => 'Type de produit',
            'choices' => [
                'Physique' => ProductFlowData::TYPE_PHYSICAL,
                'Numerique' => ProductFlowData::TYPE_DIGITAL,
            ],
            'expanded' => true,
            'multiple' => false,
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
