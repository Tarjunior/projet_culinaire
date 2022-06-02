<?php

namespace App\Controller\Customer;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MentionsLegalesController extends AbstractController
{
    #[Route('/mentions/legales', name: 'mentions_legales')]
    public function index(): Response
    {
        return $this->render('customer\mentions_legales/index.html.twig');
    }
}
