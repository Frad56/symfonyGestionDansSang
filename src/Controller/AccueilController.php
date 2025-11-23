<?php

namespace App\Controller;

use App\Entity\Donateur;
use App\Entity\RendezVous;
use App\Form\CollecteFilterType;
use App\Form\RendezVousType;
use App\Repository\CollecteRepository;
use App\Repository\Collecte;
use App\Repository\DonateurRepository;
use App\Repository\DonRepository;
use App\Repository\RendezVousRepository;
use App\Repository\StockRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class AccueilController extends AbstractController
{

    #[Route('/', name: 'Accueil')]
    public function Accueil(CollecteRepository $collecteRepository,StockRepository $stockRepository): Response
    {
        $collectes= $collecteRepository->findAll();
        $stock= $stockRepository->findAll();
     

        return $this->render('home/accueil.html.twig', [
            'collectes_list' => $collectes,
            'stock' => $stock,
        ]);


    }
    #[Route('/collectes', name: 'collect')]
    public function allCollectes(Request $request,
        CollecteRepository $collecteRepository): Response
    {
        //$collectes= $collecteRepository->findAll();
        $form = $this ->createForm(CollecteFilterType::class);
        $form->handleRequest($request);

        $lieu = $form->get('lieu')->getData();
        $dateDebut = $form->get('dateDebut')->getData();

        $collectes =$collecteRepository->findByFilters($lieu, $dateDebut);

     

        return $this->render('home/collectes.html.twig', [
            'collectes_list' => $collectes,
            'filterForm' => $form->createView(),
          
        ]);

    }
  


    

   
}
