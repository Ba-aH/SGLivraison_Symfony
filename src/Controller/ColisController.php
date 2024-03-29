<?php

namespace App\Controller;

use App\Entity\Colis;
use App\Form\ColisType;
use App\Repository\ColisRepository;
use App\Repository\LivraisonRepository;
use App\Repository\LocalisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[Route('/colis')]
class ColisController extends AbstractController
{
    #[Route('/{id}', name: 'app_colis_index', methods: ['GET'])]
    public function index(ColisRepository $colisRepository, $id,LivraisonRepository $livraisonRepository): Response
        {
        $colis = $colisRepository->findBy(['livraison' => $id]);
        $livraisons = $livraisonRepository->findBy(['id' => $id]);
        return $this->render('colis/index.html.twig', [
            'colis' => $colis,
            'livraisons' => $livraisons,
        ]);
    }

    #[Route('/new', name: 'app_colis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $coli = new Colis();
        $form = $this->createForm(ColisType::class, $coli);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($coli);
            $entityManager->flush();

            return $this->redirectToRoute('app_colis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('colis/new.html.twig', [
            'coli' => $coli,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_colis_show', methods: ['GET'])]
    public function show(Colis $coli): Response
    {
        return $this->render('colis/show.html.twig', [
            'coli' => $coli,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_colis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Colis $coli, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ColisType::class, $coli);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_colis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('colis/edit.html.twig', [
            'coli' => $coli,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_colis_delete', methods: ['POST'])]
    public function delete(Request $request, Colis $coli, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coli->getId(), $request->request->get('_token'))) {
            $entityManager->remove($coli);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_colis_index', [], Response::HTTP_SEE_OTHER);
    }
}
