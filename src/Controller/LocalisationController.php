<?php

namespace App\Controller;

use App\Entity\Localisation;
use App\Form\LocalisationType;
use App\Repository\LocalisationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/localisation')]
class LocalisationController extends AbstractController
{
    #[Route('/', name: 'app_localisation_index', methods: ['GET'])]
    public function index(LocalisationRepository $localisationRepository): Response
    {
        return $this->render('localisation/index.html.twig', [
            'localisations' => $localisationRepository->findAll(),
        ]);
    }

    #[Route('/place/{id}', name: 'place_api',methods: ['GET'])]
    public function getPlace(ManagerRegistry $doctrine,int $id): JsonResponse
    {
        $place = $doctrine->getRepository(Localisation::class)->find($id);
        if (!$place){
            return $this->json('No place is found for id' . $id,404);
        }

        $data = [
            'id' => $place->getId(),
            'latitude' => $place->getLatitude(),
            'longitude' => $place->getLongitude(),          
        ];
        return $this->json($data);
    }

    #[Route('/new', name: 'app_localisation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $localisation = new Localisation();
        $form = $this->createForm(LocalisationType::class, $localisation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($localisation);
            $entityManager->flush();

            return $this->redirectToRoute('app_localisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('localisation/new.html.twig', [
            'localisation' => $localisation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_localisation_show', methods: ['GET'])]
    public function show(Localisation $localisation): Response
    {
        return $this->render('localisation/show.html.twig', [
            'localisation' => $localisation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_localisation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Localisation $localisation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LocalisationType::class, $localisation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_localisation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('localisation/edit.html.twig', [
            'localisation' => $localisation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_localisation_delete', methods: ['POST'])]
    public function delete(Request $request, Localisation $localisation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$localisation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($localisation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_localisation_index', [], Response::HTTP_SEE_OTHER);
    }
}
