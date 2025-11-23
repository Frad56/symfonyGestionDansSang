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
use App\Repository\CollecteRepository;
use App\Repository\DonateurRepository;
use App\Repository\DonRepository;
use App\Repository\StockRepository;
use App\Service\EligibiliteService;

class DonateurDashboardController extends AbstractController
{
    #[Route('/donateur/dashboard', name: 'donateur_dashboard')]
    public function index(RendezVousRepository $rendezVousRepository,EligibiliteService $eligibiliteService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_DONATEUR');
        $user = $this->getUser();
      
       // dd($user);
        
       if (!$user instanceof Donateur) {
        throw $this->createAccessDeniedException('Type d\'utilisateur incorrect.');
        }
        $id= $user->getId();

        //Rv
        $ListRendezVous = $rendezVousRepository->findByDonateurId($id);
        //eligiblite
        $eligibilite = $eligibiliteService->verifierEligibilite($user);
      
        return $this->render('donateur/dashboards.html.twig', [
            'user' => $user,
            'ListRendezVous' => $ListRendezVous,
            'eligibilite' =>$eligibilite
           
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
 
    //
    #[Route('/donateur/rdv/new/{collecteId}',name:'Crre_RV_Par_Donateur_Id')]
    public function AddRV(int $collecteId,
    Request $request,
    CollecteRepository $collecteRepository,
    EntityManagerInterface $entityManager,
    EligibiliteService $eligibiliteService):Response{

        $this->denyAccessUnlessGranted('ROLE_DONATEUR');
        $user = $this->getUser();

        if (!$user instanceof Donateur) {
            throw $this->createAccessDeniedException('Type d\'utilisateur incorrect.');
            }

        $collecte = $collecteRepository->find($collecteId);

        //dd('DEBUG 1 - Collecte trouvée:', $collecte->getId(), $collecte->getNom());


        if (!$collecte) {
            throw $this->createNotFoundException('Collecte non trouvée.');
        }

        //Verifier l'egitibilite de donateur
        $eligibilite = $eligibiliteService->verifierEligibilite($user);


        //dd('DEBUG 2 - Éligibilité:', $eligibilite);
        if(!$eligibilite['eligible']){
            $this->addFlash('error','vous n\'est pas eligible pour donner du sang!');
            return $this->redirectToRoute('donateur_dashboard');
        }

        $collecte = $collecteRepository->find($collecteId);
    
        if (!$collecte) {
            throw $this->createNotFoundException('Collecte non trouvée.');
            return $this->redirectToRoute('donateur_dashboard');
        }

        if(!$collecte->YaDePlace()){
            $this->addFlash('error', 'Désolé, cette collecte est complète.');
            return $this->redirectToRoute('donateur_dashboard');

        }
       

        $rendezVous = new RendezVous();
        $rendezVous->setDonateur($user);
        $rendezVous->setCollecte($collecte);
        $rendezVous->setStatut('En attente');
        $form = $this ->createForm(RendezVousType::class,$rendezVous);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $dateDebutRV = $rendezVous->getDateHeureDebut();
            $dateFinRV = $rendezVous->getDateheureFin();
            // Vérifier que les dates sont dans la plage de la collecte
            if ($dateDebutRV < $collecte->getDateDebut() || $dateFinRV > $collecte->getDateFin()) {
                $this->addFlash('FautDate', 
                    'Les dates du rendez-vous doivent être comprises entre ' .
                    $collecte->getDateDebut()->format('d/m/Y H:i') . ' et ' .
                    $collecte->getDateFin()->format('d/m/Y H:i')
                    
                );
            return $this->render('donateur/Rendezvous.html.twig', [
                'form' => $form->createView(),
                'collecte' => $collecte,
                'user' => $user
                ]);
                
            }   


            $collecte->diminuerCapacite();
            $entityManager->persist($rendezVous);
            $entityManager->flush();
            return $this->redirectToRoute('donateur_dashboard');
            
        }
        
    
        return $this->render('donateur/Rendezvous.html.twig', [
            'form' => $form->createView(),
            'collecte' => $collecte,
            'user' => $user
        ]);
        
    }

    #[Route('/donateur/historique',name:'historique_Don')]
    public function historiqueDon(Request $request,DonRepository $donRepository,
    EntityManagerInterface $entityManager):Response{
        $this->denyAccessUnlessGranted('ROLE_DONATEUR');
        $user = $this->getUser();
        if (!$user instanceof Donateur) {
            throw $this->createAccessDeniedException('Type d\'utilisateur incorrect.');
        }
        $dons = $donRepository->findDonByDonateur($user);
        return $this->render('donateur/mes_dons.html.twig',['dons' => $dons]);
    }
    
    


}
