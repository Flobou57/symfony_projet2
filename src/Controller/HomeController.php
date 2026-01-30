<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home_index')]
    public function home(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('product_index');
        }

        return $this->redirectToRoute('app_login');
    }

    #[Route('/home', name: 'home')]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/shop', name: 'shop')]
    public function shop(): Response
    {
        return $this->render('base.html.twig');
    }
}
