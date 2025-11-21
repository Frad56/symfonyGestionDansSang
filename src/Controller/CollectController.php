<?php

namespace App\Controller;

use App\Repository\CollecteRepository;
use App\Repository\Collecte;
use App\Repository\DonRepository;
use App\Repository\LieuRepository;
use App\Repository\RendezVousRepository;
use App\Repository\StockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CollectController extends AbstractController
{

    #[Route('/collect/{id} ', name: 'collect_By_Id')]
    public function allCollectes(int $id,CollecteRepository $collecteRepository,LieuRepository $lieuRepository,RendezVousRepository $rendezVousRepo): Response
    {

        $collecte = $collecteRepository->find($id);

        return $this->render('home/detailsCollecte.html.twig', [
            'collectById'=>$collecte,
            
        ]);


    }

   
   

    

   
}
