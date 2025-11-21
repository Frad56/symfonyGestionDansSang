<?php

namespace App\Controller;

use App\Repository\DonateurRepository;
use App\Repository\RendezVousRepository;
use App\Repository\StockRepository;
use App\Repository\CollecteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(
        DonateurRepository $donateurRepo,
        RendezVousRepository $rdvRepo,
        StockRepository $stockRepo,
        CollecteRepository $collecteRepo
    ): Response {
        // s'assurer que l'utilisateur a le bon role
        $this->denyAccessUnlessGranted('ROLE_ADMINISTRATEUR');

        $stats = [
            'totalDonateurs' => $donateurRepo->count([]),
            'rdvEffectues' => $rdvRepo->count(['statut' => 'Effectué']),
            'stockCritique' => $stockRepo->count(['niveauAlerte' => 'Critique']),
            'totalCollectes' => $collecteRepo->count([]),
        ];

        $stocksCritiques = $stockRepo->findBy(['niveauAlerte' => 'Critique']);

        return $this->render('admin/dashboard.html.twig', [
            'stats' => $stats,
            'stocksCritiques' => $stocksCritiques,
        ]);
    }
}
