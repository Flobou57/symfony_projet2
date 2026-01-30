<?php

namespace App\Service;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Response;

class ProductCsvExporter
{
    /**
     * @param Product[] $products
     */
    public function export(array $products): Response
    {
        $handle = fopen('php://temp', 'r+');
        if ($handle === false) {
            throw new \RuntimeException('Unable to open temporary stream for CSV export.');
        }

        fputcsv($handle, ['name', 'description', 'price']);

        foreach ($products as $product) {
            fputcsv($handle, [
                $product->getName(),
                $product->getDescription(),
                $product->getPrice(),
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        if ($csv === false) {
            throw new \RuntimeException('Unable to read CSV export contents.');
        }

        $response = new Response($csv);
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="products.csv"');

        return $response;
    }
}
