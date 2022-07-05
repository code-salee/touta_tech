<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Partenaire;
use App\Entity\Superadmin;
use Symfony\Config\SecurityConfig;
use Doctrine\Persistence\ObjectManager;
use App\Repository\SuperadminRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;


class PartenaireFixtures extends Fixture
{

    private $repo;
    public function  __construct(SuperadminRepository $repo)
    {
        $this->repo=$repo;
    }
  
    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');
      
        for ($i=1; $i <=7 ; $i++) { 
            # code...
            $j = 10 + $i;
            $partenaire = new Partenaire();
            $role = new Superadmin();
            $sad = $this->repo->findOneBy(['id' => $j]);
             $partenaire
                ->setNom('partenaire_'.$i)
                ->setAdresse($faker->address)
                ->setTelephone($faker->phoneNumber)
                ->setSuperadmin($sad)
                ;
                $manager->persist($partenaire);
        }

                $manager->flush();
        
            
    }
 
}