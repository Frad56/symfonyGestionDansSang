<?php

namespace App\DataFixtures;

use App\Entity\Collecte;
use App\Entity\Don;
use App\Entity\Donateur;
use App\Entity\Lieu;
use App\Entity\RendezVous;
use App\Entity\Stock;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use PgSql\Lob;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {

        $GroupeSanguinPossible = ['A+', 'A-',' B+',' B-', 'AB+', 'AB-', 'O+',' O-'];
        $GroupeSanguin =[];

        $rolesDisponibles = ['ROLE_DONATEUR'];
        $typeDonListe = ['Sangtotal','plasma','plaquettes'];

        $collecteStatut =['lanifiée','Terminée'];

        $Statut_Renez_Vous = ['confirmém','Annulé','Effectué'];

        $faker = Factory::create('fr_FR');
        for($i=0; $i <20; $i++){
          $donateur = new Donateur();
          $don = new Don();
          $stock = new Stock();
          $collecte = new Collecte();
          $lieu = new Lieu();
          $rendezVous = new RendezVous();


         //**************      Don      ***************************** */

         $don->setDonateurId($donateur);

         $don->setRendezVous($rendezVous);

         $rendezVous->setDonateur($donateur);
         $rendezVous->setCollecte($collecte);

         $collecte->setLieu($lieu);
         

         //date
         $don->setDatedon($faker->dateTime);
         //quantity
         $don-> setQuantite($faker->numberBetween(1,100));
        
        //
        $don->setTypeDon($faker -> randomElement($typeDonListe));
        
        //apte

        $don ->setApte($faker->boolean());

        //Commentaire
        $don->setCommentaire($faker->realText(100));



        //**************      Donateur       ***************************** */
         //email
         $composerEmail = $faker->unique()->userName() . '@gmail.com';
          $donateur->setEmail($composerEmail);
        //prenom
         $donateur->setPrenom($faker->lastName());

        //password
        // 
        //transfer d'une mot claire a une autre hache pour des raison de securites
        $hashedPassword = $this->passwordHasher->hashPassword($donateur, 'user123');
        $donateur->setPassword($hashedPassword);

        //groupe Sanguin 
        $donateur->setGroupeSanguin($faker->randomElement($GroupeSanguinPossible));
        
        //dernierDateDon
        $donateur->setDerniereDateDon($faker->dateTime);
        
        //role de donateur
      


/*    **************************     Collect      ******************************** */

        //nom
        $collecte->setNom($faker->firstName());

        //Date Debut 
        $dateDebut = $faker->dateTimeBetween('now', '+1 month');
        //date fin
        $dateFin =$faker->dateTimeBetween($dateDebut,'+2 month');

        $collecte->setDateDebut($dateDebut);
        $collecte->setDateFin($dateFin);

        //Capacite Maximale
        $collecte->setCapaciteMaximale($faker->numberBetween(10,100));

        //statut
        $collecte->setStatut($faker ->randomElement($collecteStatut));



      /* *******************************      Lieu          *************************************************/  
        

        //nom lieu
        $lieu->setNomLieu($faker->country());
        //adresse 
        $lieu->setAdresse($faker->address());
        //ville
        $lieu->setVille($faker->city());
        //code Postal
        $lieu->setCodePostal($faker->postcode());
        
      /* *******************************     Rendez-Vous      *************************************************/  

      //Date Debut 
      $dateDebut_RV = $faker->dateTimeBetween('now', '+1 month');
      //date fin
      $dateFin_RV =$faker->dateTimeBetween($dateDebut_RV ,'+2 month');

      $rendezVous->setDateHeureDebut($dateDebut_RV);
      $rendezVous->setDateheureFin($dateFin_RV);


      $rendezVous->setStatut($faker->randomElement($Statut_Renez_Vous));
        $manager->persist($donateur);
        
        
      /* *******************************    Stock     *************************************************/  

      $stock->setGroupeSanguin($faker->randomElement( $GroupeSanguinPossible));

     // Générer un niveau actuel aléatoire entre 0.5 et 5 litres
    $niveauActuel = $faker->randomFloat(1, 0.5, 5);
    $stock->setNiveauActuel($niveauActuel);

      // Déterminer automatiquement le niveau d'alerte
      if ($niveauActuel <= 1.5) {
          $stock->setNiveauAlerte("Critique");
      } elseif ($niveauActuel < 3) {
          $stock->setNiveauAlerte("Alerte");
      } else {
          $stock->setNiveauAlerte("Normal");
      }

    $manager->persist($lieu);
    $manager->persist($collecte);
    $manager->persist($rendezVous);
    $manager->persist($don);
    $manager->persist($donateur);
    $manager->persist($stock);
    $stock->setDernierMiseAJour($faker->dateTimeThisYear());
      $stock;
        $manager->flush();
    }
  }
}
