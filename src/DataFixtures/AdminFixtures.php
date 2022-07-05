<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Role;
use App\Entity\Admin;
use App\DataFixtures\RoleFixtures;
use Symfony\Config\SecurityConfig;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AdminFixtures extends Fixture
{

    private $encoder;
    public function  __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder=$encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');
      
        for ($i=1; $i <=5 ; $i++) { 
            # code...
        
            $admin = new Admin();
            $password = $this->encoder->hashPassword($admin, 'password');
             $admin->setEmail($faker->email)
                ->setPrenom($faker->firstName)
                ->setNom($faker->LastName)
                ->setPassword($password)
                ->setAdresse($faker->address)
                ->setTelephone($faker->phoneNumber)
                //->setRole($this->getReference(RoleFixtures::Profil_Admin))
                ;
                $manager->persist($admin);
        }

                $manager->flush();
        
            
    }
 
}