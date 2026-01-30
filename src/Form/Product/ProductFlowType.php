<?php

namespace App\Form\Product;

use App\Form\Product\Step\ProductConfirmationStepType;
use App\Form\Product\Step\ProductDetailsStepType;
use App\Form\Product\Step\ProductLicenseStepType;
use App\Form\Product\Step\ProductLogisticsStepType;
use App\Form\Product\Step\ProductSummaryStepType;
use App\Form\Product\Step\ProductTypeStepType;
use Symfony\Component\Form\Flow\AbstractFlowType;
use Symfony\Component\Form\Flow\FormFlowBuilderInterface;
use Symfony\Component\Form\Flow\Type\NavigatorFlowType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFlowType extends AbstractFlowType
{
    public function buildFormFlow(FormFlowBuilderInterface $builder, array $options): void
    {
        $builder->addStep('type', ProductTypeStepType::class);
        $builder->addStep('details', ProductDetailsStepType::class);

        $builder->addStep('confirmation', ProductConfirmationStepType::class, [], function (ProductFlowData $data): bool {
            return !$data->requiresHighPriceConfirmation();
        });

        $builder->addStep('logistics', ProductLogisticsStepType::class, [], function (ProductFlowData $data): bool {
            return !$data->isPhysical();
        });

        $builder->addStep('license', ProductLicenseStepType::class, [], function (ProductFlowData $data): bool {
            return !$data->isDigital();
        });

        $builder->addStep('summary', ProductSummaryStepType::class);

        $builder->add('navigator', NavigatorFlowType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductFlowData::class,
            'step_property_path' => 'currentStep',
        ]);
    }
}
