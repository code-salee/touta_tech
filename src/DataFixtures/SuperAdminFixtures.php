<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Role;
use App\Entity\Superadmin;
use App\DataFixtures\RoleFixtures;
use App\Repository\RoleRepository;
use Symfony\Config\SecurityConfig;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SuperAdminFixtures extends Fixture
{

    private $encoder;
    private $repo;
    public function  __construct(UserPasswordHasherInterface $encoder, RoleRepository $repo)
    {
        $this->encoder=$encoder;
        $this->repo=$repo;
    }
    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');
      
        for ($i=1; $i <=5 ; $i++) { 
            # code...
        
            $Sadmin = new Superadmin();
            $role = new Role();
            $role = $this->repo->findOneBy(['libelle' => 'SUPERADMIN']);
            $password = $this->encoder->hashPassword($Sadmin, 'password');
             $Sadmin->setEmail($faker->email)
                ->setPrenom($faker->firstName)
                ->setNom($faker->LastName)
                ->setPassword($password)
                ->setAdresse($faker->address)
                ->setTelephone($faker->phoneNumber)
                ->setRole($role)
                ;
                $manager->persist($Sadmin);
        }

                $manager->flush();
        
            
    }
 
}