<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Activite;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class ActiviteFixtures extends Fixture
{

    private $repo;
    public function  __construct(UserRepository $repo)
    {
        $this->repo=$repo;
    }
    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');
      
        for ($i=1; $i <=5 ; $i++) { 
            # code...
        
            $activite = new Activite();
            $user = new User();
            $user = $this->repo->findOneBy(['id' => $i+16]);
             $activite->setDescription('Description_'.$i)
                ->setDate($faker->DateTime)
                ->setLieu($faker->address)
                ->setUser($user)
                ;
                $manager->persist($activite);
        }

                $manager->flush();
        
            
    }
 
}