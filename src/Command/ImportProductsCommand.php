<?php

namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'app:products:import',
    description: 'Import products from a CSV file.'
)]
class ImportProductsCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly KernelInterface $kernel
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::OPTIONAL, 'CSV file path (relative to /public)', 'products.csv');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $file = (string) $input->getArgument('file');

        $path = $file;
        if (!str_starts_with($file, '/')) {
            $path = $this->kernel->getProjectDir().'/public/'.$file;
        }

        if (!is_file($path)) {
            $io->error(sprintf('CSV file not found: %s', $path));
            return Command::FAILURE;
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            $io->error('Unable to open CSV file.');
            return Command::FAILURE;
        }

        $header = fgetcsv($handle);
        if ($header === false) {
            fclose($handle);
            $io->error('CSV file is empty.');
            return Command::FAILURE;
        }

        $expected = ['name', 'description', 'price'];
        $normalizedHeader = array_map('strtolower', $header);
        foreach ($expected as $column) {
            if (!in_array($column, $normalizedHeader, true)) {
                fclose($handle);
                $io->error('CSV header must include: name, description, price.');
                return Command::FAILURE;
            }
        }

        $count = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if ($row === [null] || count($row) === 0) {
                continue;
            }

            $data = array_combine($normalizedHeader, $row);
            if ($data === false) {
                continue;
            }

            $product = new Product();
            $product->setName((string) ($data['name'] ?? ''));
            $product->setDescription((string) ($data['description'] ?? ''));

            $price = isset($data['price']) ? (float) $data['price'] : 0.0;
            $product->setPrice(number_format($price, 2, '.', ''));

            $this->entityManager->persist($product);
            $count++;
        }

        fclose($handle);

        $this->entityManager->flush();

        $io->success(sprintf('Imported %d products.', $count));

        return Command::SUCCESS;
    }
}
