<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Metier;
use App\Repository\UserRepository;
use Symfony\Config\SecurityConfig;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class MetierFixtures extends Fixture
{

    
    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');

        for ($i=1; $i <=5 ; $i++) { 
            # code...
        
            $metier = new Metier();
            
            $metier->setLibelle($faker->randomElement(['Agriculture', 'Elevage', 'Aviculture', 'Vendeur', 'Maraichére']))
                ;
                $manager->persist($metier);
    }
                $manager->flush();
        
            
    }
 
}