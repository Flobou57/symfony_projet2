<?php

namespace App\Form\Product\Step;

use App\Form\Product\ProductFlowData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductConfirmationStepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('highPriceConfirmed', CheckboxType::class, [
            'label' => 'Je confirme ce prix eleve',
            'required' => true,
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
