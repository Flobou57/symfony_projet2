<?php

namespace App\Tests\Service;

use App\Entity\Product;
use App\Service\ProductCsvExporter;
use PHPUnit\Framework\TestCase;

class ProductCsvExporterTest extends TestCase
{
    public function testExportBuildsCsvResponse(): void
    {
        $product = new Product();
        $product->setName('Starter Pack');
        $product->setDescription('Basic bundle');
        $product->setPrice('10.00');

        $exporter = new ProductCsvExporter();
        $response = $exporter->export([$product]);

        $this->assertSame('text/csv; charset=UTF-8', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('products.csv', (string) $response->headers->get('Content-Disposition'));

        $csv = trim((string) $response->getContent());
        $rows = array_map('str_getcsv', explode("\n", $csv));

        $this->assertSame(['name', 'description', 'price'], $rows[0]);
        $this->assertSame(['Starter Pack', 'Basic bundle', '10.00'], $rows[1]);
    }
}
