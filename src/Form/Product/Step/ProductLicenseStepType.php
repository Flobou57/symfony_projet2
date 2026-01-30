<?php

namespace App\Form\Product\Step;

use App\Form\Product\ProductFlowData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductLicenseStepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('licenseKey', null, [
                'label' => 'Cle de licence',
            ])
            ->add('accessUrl', UrlType::class, [
                'label' => 'URL d\'acces',
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
