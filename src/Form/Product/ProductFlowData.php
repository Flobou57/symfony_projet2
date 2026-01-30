<?php

namespace App\Form\Product;

use Symfony\Component\Validator\Constraints as Assert;

class ProductFlowData
{
    public const TYPE_PHYSICAL = 'physical';
    public const TYPE_DIGITAL = 'digital';

    public const HIGH_PRICE_THRESHOLD = 1000;

    #[Assert\NotBlank(groups: ['type'])]
    public ?string $productType = null;

    #[Assert\NotBlank(groups: ['details'])]
    public ?string $name = null;

    #[Assert\NotBlank(groups: ['details'])]
    public ?string $description = null;

    #[Assert\NotBlank(groups: ['details'])]
    #[Assert\Positive(groups: ['details'])]
    public ?float $price = null;

    #[Assert\NotBlank(groups: ['logistics'])]
    #[Assert\Positive(groups: ['logistics'])]
    public ?float $weight = null;

    #[Assert\NotBlank(groups: ['logistics'])]
    public ?string $dimensions = null;

    #[Assert\NotBlank(groups: ['logistics'])]
    #[Assert\PositiveOrZero(groups: ['logistics'])]
    public ?int $stock = null;

    #[Assert\NotBlank(groups: ['license'])]
    public ?string $licenseKey = null;

    #[Assert\NotBlank(groups: ['license'])]
    #[Assert\Url(groups: ['license'])]
    public ?string $accessUrl = null;

    #[Assert\IsTrue(groups: ['confirmation'])]
    public ?bool $highPriceConfirmed = null;

    public ?string $currentStep = null;

    public function isPhysical(): bool
    {
        return $this->productType === self::TYPE_PHYSICAL;
    }

    public function isDigital(): bool
    {
        return $this->productType === self::TYPE_DIGITAL;
    }

    public function requiresHighPriceConfirmation(): bool
    {
        return $this->price !== null && $this->price > self::HIGH_PRICE_THRESHOLD;
    }
}
