<?php

namespace App\Controller;

use App\Repository\RendezVousRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Donateur;
use App\Entity\RendezVous;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Form\DonateurType;
use App\Form\RendezVousType;
use App\Repository\StockRepository;



class DonateurDashboardController extends AbstractController
{
    #[Route('/donateur/dashboard', name: 'donateur_dashboard')]
    public function index(RendezVousRepository $rendezVousRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_DONATEUR');
        $user = $this->getUser();
      
       // dd($user);
        
       if (!$user instanceof Donateur) {
        throw $this->createAccessDeniedException('Type d\'utilisateur incorrect.');
        }
        $id= $user->getId();
        
        $ListRendezVous = $rendezVousRepository->findByDonateurId($id);

        return $this->render('donateur/dashboards.html.twig', [
            'user' => $user,
            'ListRendezVous' => $ListRendezVous
           
        ]);
    }

    #[Route('/register', name:'app_new_donateur')]
    public function AddDonateur(Request $request ,
    EntityManagerInterface $entityManager,
    LieuRepository $lieuRepository, 
    UserPasswordHasherInterface $passwordHasher):Response
    {
        $donateur = new Donateur();
        
        $form = $this->createForm(DonateurType::class,$donateur);

        $form->handleRequest($request);
        //verfier que il est de type Poste
        if($form->isSubmitted() && $form->isValid()){

            $plainPassword = $form->get('password')->getData();

            $hashedPassword = $passwordHasher->hashPassword($donateur, $plainPassword);
            
            $donateur->setPassword($hashedPassword);

            $entityManager->persist($donateur);

            $entityManager->flush();
            //redirection
            return $this->redirectToRoute('donateur_dashboard');
        }

        $lieux= $lieuRepository->findAll();

        return $this->render('home/NewDonnateur.html.twig',[

            'DonateurForm' => $form->createView(),

            'lieux' => $lieux,
        ]);

    }

    #[Route('/stock', name: 'app_stock')]
    public function stock(StockRepository $stockRepository): Response
    {
        $stock= $stockRepository->findAll();

        return $this->render('donateur/stock.html.twig', [
            'stock' => $stock,
        ]);
    }


    //Cree un rendez vous
    
    #[Route('/AddRendezVous', name: 'add_RendezVous')]
    public function NouvRendezVous(Request $request,
    EntityManagerInterface $entityManager,): Response
    {
        $rendezVous = new RendezVous();
        $form = $this->createForm(RendezVousType::class,$rendezVous);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form ->isValid()){

            $entityManager->persist($rendezVous);
            $entityManager->flush();

            return $this->redirectToRoute('donateur_dashboard');
        }
        return $this->render('donateur/RendezVous.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    
    //Annuler les rendezVous avec id de donateur 
    #[Route('/AnnulerRendezVous/{id}', name: 'annuler_RendezVous')]
    public function annulerRendezVous(
        int $id,
        RendezVousRepository $rendezVousRepository,
        EntityManagerInterface $entityManager
    ): Response {
       
        // Récupérer le rendez-vous
        $rendezVous = $rendezVousRepository->find($id);
        $this->denyAccessUnlessGranted('ROLE_DONATEUR');
        $user = $this->getUser();
        if (!$user instanceof Donateur) {
            throw $this->createAccessDeniedException('Type d\'utilisateur incorrect.');
            }
        $rendezVous->setStatut('Annulé');
        $entityManager->flush();

        $this->addFlash('success', 'Le rendez-vous a été annulé avec succès.');
        return $this->redirectToRoute('donateur_dashboard');
    }
 
    
    


}
