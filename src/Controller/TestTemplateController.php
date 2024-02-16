<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestTemplateController extends AbstractController
{
    #[Route('/test/template', name: 'app_test_template')]
    public function index(): Response
    {
        return $this->render('test_template/index.html.twig', [
            'controller_name' => 'TestTemplateController',
        ]);
    }
}
