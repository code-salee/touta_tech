<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Feedback;
use App\Repository\UserRepository;
use App\Repository\ActiviteRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class FeedbackFixtures extends Fixture
{

    private $repo;
    private $db;
    public function  __construct(UserRepository $repo, ActiviteRepository $db)
    {
        $this->repo=$repo;
        $this->db=$db;
    }
    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');
      
        for ($i=1; $i <=5 ; $i++) { 
            # code...
        
            $feedback = new Feedback();
            $user = $this->repo->findOneBy(['id' => $i+16]);
            $activite = $this->db->findOneBy(['id' => $i]);
             $feedback->setLibelle('Feedback_'.$i)
                ->setActivite($activite)
                ->setUser($user)
                ;
                $manager->persist($feedback);
        }

                $manager->flush();
        
            
    }
 
}