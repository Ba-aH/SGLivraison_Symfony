<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Form\LivraisonType;
use App\Repository\LivraisonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTime; 


#[Route('/livraison')]
class LivraisonController extends AbstractController
{
    #[Route('/', name: 'app_livraison_index', methods: ['GET'])]
    public function index(LivraisonRepository $livraisonRepository): Response
    {
        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraisonRepository->findAll(),
        ]);
    }

    #[Route('/edit_byadmin', name: 'edit_byadmin', methods: ['post'])]
    public function edit_byadmin( Request $request ,EntityManagerInterface $entityManager,LivraisonRepository $livraisonRepository): Response
    {
        
        $livraisonIdd = $request->request->get('livraisonId');
        // Check if the Livraison ID exists in the request data
     
            // Retrieve the Livraison ID
           
    
            $livraison = $entityManager->getRepository(Livraison::class)->find($livraisonIdd);
            $dateLivraisonString = $request->request->get('date'); // Assuming 'date_livraison' is the name of your form field
        
            // Convert the string to a DateTime object
            $dateLivraison = DateTime::createFromFormat('Y-m-d', $dateLivraisonString);
            // Check if the Livraison entity exists
            if ($livraison) {
                // Update the Livraison entity with the provided data
                $livraison->setDateLivraison($dateLivraison);
                $livraison->setIntervalleTemp($request->request->get('intervalle'));
    
                // Persist the changes to the database
                $entityManager->flush();
    
                // Redirect or render a response as needed
                return $this->render('admin/admin.html.twig', [
                    'livraisons' => $livraisonRepository->findAll(),
                ]);
            } else {
                // Handle the case where the Livraison entity is not found
                // You can return an error response or handle it based on your application logic
                return new Response('Livraison entity not found', Response::HTTP_NOT_FOUND);
            }
        
    }



    #[Route('/ad-liv', name: 'ad-liv', methods: ['GET'])]
    public function adliv(LivraisonRepository $livraisonRepository): Response
    {
        $livraisonsEchec = $livraisonRepository->findBy(['statut_coursier' => 'echec']);


        return $this->render('admin/admin.html.twig',[
            'livraisons' => $livraisonsEchec,
        ]);
    }




    #[Route('/{id}/confirm', name: 'confirm', methods: ['GET'])]
    public function confirm(EntityManagerInterface $entityManager,livraisonRepository $livraisonRepository,$id): Response
    {
        $livraison = $livraisonRepository->find($id);

        $livraison = $livraison->setStatutCoursier('confirme');
        $entityManager->flush();

        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraisonRepository->findAll(),
        ]);
    }

    #[Route('/{id}/echec', name: 'echec', methods: ['GET'])]
    public function echec(EntityManagerInterface $entityManager,livraisonRepository $livraisonRepository,$id): Response
    {
        $livraison = $livraisonRepository->find($id);

        $livraison = $livraison->setStatutCoursier('echec');
        $entityManager->flush();

        return $this->render('livraison/index.html.twig', [
            'livraisons' => $livraisonRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_livraison_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livraison = new Livraison();
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($livraison);
            $entityManager->flush();

            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livraison/new.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livraison_show', methods: ['GET'])]
    public function show(Livraison $livraison): Response
    {
        return $this->render('livraison/show.html.twig', [
            'livraison' => $livraison,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_livraison_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livraison $livraison, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livraison/edit.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livraison_delete', methods: ['POST'])]
    public function delete(Request $request, Livraison $livraison, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livraison->getId(), $request->request->get('_token'))) {
            $entityManager->remove($livraison);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_livraison_index', [], Response::HTTP_SEE_OTHER);
    }
}
