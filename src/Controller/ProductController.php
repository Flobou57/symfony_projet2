<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\Product\ProductFlowData;
use App\Form\Product\ProductFlowType;
use App\Repository\ProductRepository;
use App\Security\Voter\ProductVoter;
use App\Service\ProductCsvExporter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Flow\DataStorage\SessionDataStorage;
use Symfony\Component\Form\Flow\FormFlowInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/products')]
#[IsGranted('ROLE_USER')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAllOrderByPriceDesc(),
            'can_manage' => $this->isGranted(ProductVoter::MANAGE),
        ]);
    }

    #[Route('/export', name: 'product_export', methods: ['GET'])]
    public function export(ProductRepository $productRepository, ProductCsvExporter $exporter): Response
    {
        return $exporter->export($productRepository->findAllOrderByPriceDesc());
    }

    #[Route('/new', name: 'product_new', methods: ['GET', 'POST'])]
    #[IsGranted(ProductVoter::MANAGE)]
    public function new(
        Request $request,
        RequestStack $requestStack,
        EntityManagerInterface $entityManager
    ): Response {
        $data = new ProductFlowData();

        /** @var FormFlowInterface $flow */
        $flow = $this->createForm(ProductFlowType::class, $data, [
            'data_storage' => new SessionDataStorage('product_flow_new', $requestStack),
        ])->handleRequest($request);

        if ($flow->isSubmitted() && $flow->isValid() && $flow->isFinished()) {
            $product = new Product();
            $this->applyProductData($product, $flow->getData());

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Produit cree.');

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/flow.html.twig', [
            'form' => $flow->getStepForm(),
            'data' => $flow->getData(),
            'page_title' => 'Ajouter un produit',
            'TYPE_PHYSICAL' => ProductFlowData::TYPE_PHYSICAL,
            'TYPE_DIGITAL' => ProductFlowData::TYPE_DIGITAL,
        ]);
    }

    #[Route('/{id}/edit', name: 'product_edit', methods: ['GET', 'POST'])]
    #[IsGranted(ProductVoter::MANAGE)]
    public function edit(
        Product $product,
        Request $request,
        RequestStack $requestStack,
        EntityManagerInterface $entityManager
    ): Response {
        $data = $this->createFlowDataFromProduct($product);

        /** @var FormFlowInterface $flow */
        $flow = $this->createForm(ProductFlowType::class, $data, [
            'data_storage' => new SessionDataStorage('product_flow_edit_' . $product->getId(), $requestStack),
        ])->handleRequest($request);

        if ($flow->isSubmitted() && $flow->isValid() && $flow->isFinished()) {
            $this->applyProductData($product, $flow->getData());

            $entityManager->flush();

            $this->addFlash('success', 'Produit mis a jour.');

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/flow.html.twig', [
            'form' => $flow->getStepForm(),
            'data' => $flow->getData(),
            'page_title' => 'Modifier un produit',
            'TYPE_PHYSICAL' => ProductFlowData::TYPE_PHYSICAL,
            'TYPE_DIGITAL' => ProductFlowData::TYPE_DIGITAL,
        ]);
    }

    #[Route('/{id}', name: 'product_delete', methods: ['POST'])]
    #[IsGranted(ProductVoter::MANAGE)]
    public function delete(
        Product $product,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('delete_product_' . $product->getId(), $request->request->get('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();

            $this->addFlash('success', 'Produit supprime.');
        }

        return $this->redirectToRoute('product_index');
    }

    private function applyProductData(Product $product, ProductFlowData $data): void
    {
        $product->setName((string) $data->name);
        $product->setDescription((string) $data->description);

        $price = $data->price !== null ? number_format($data->price, 2, '.', '') : '0.00';
        $product->setPrice($price);
    }

    private function createFlowDataFromProduct(Product $product): ProductFlowData
    {
        $data = new ProductFlowData();
        $data->name = $product->getName();
        $data->description = $product->getDescription();
        $data->price = $product->getPrice() !== null ? (float) $product->getPrice() : null;

        return $data;
    }
}
