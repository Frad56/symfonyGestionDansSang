<?php 
namespace App\Service;

use App\Entity\Donateur;
use phpDocumentor\Reflection\Types\Null_;

class EligibiliteService
{
    private const NUM_JOUR_MIN = 56;

    public function verifierEligibilite(Donateur $donateur): array
    {
        $derniereDateDon = $donateur->getDerniereDateDon();

        //C'est pour la 1ere fois 
        if (!$derniereDateDon) {
            return [
                'eligible' => true,
                'message' => 'Vous êtes éligible pour donner du sang',
                'prochaineDate' => new \DateTime(),
                'raison' => 'Premier don'
            ];
        }
        
        
        //Vous êtes éligible pour donner du sang
        $aujourdhui = new \DateTime();
        $interval = $derniereDateDon->diff($aujourdhui);
        $joursDepuisDernierDon = $interval->days;


        if ($joursDepuisDernierDon >= self::NUM_JOUR_MIN ) {
            return [
                'eligible' => true,
                'message' => 'Vous êtes éligible pour donner du sang',
                'prochaineDate' => new \DateTime(),
                'joursRestants' => 0
            ];
        }

        // Vous n’êtes pas encore éligible pour donner du sang
        $joursRestants = self::NUM_JOUR_MIN - $joursDepuisDernierDon;
        $prochaineDate = clone $derniereDateDon;
        $prochaineDate->modify('+' . self::NUM_JOUR_MIN . ' days');

        return [
            'eligible' => false,
            'message' => 'Vous n’êtes pas encore éligible pour donner du sang',
            'prochaineDate' => $prochaineDate,
            'joursRestants' => $joursRestants,
            'raison' => 'Délai minimum non respecté'
        ];
    }
}
