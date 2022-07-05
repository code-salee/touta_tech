<?php

namespace App\DataFixtures;

use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Role;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RoleFixtures extends Fixture
{
    public const Profil_Admin = 'ADMIN';
    public const Profil_SA = 'SUPERADMIN';
    public const Profil_User = 'UTILSATEUR';



    
    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');
      
        $Profil_admin = new Role();
            $Profil_admin->setLibelle("ADMIN");
            $this->addReference(self::Profil_Admin, $Profil_admin);
            $manager->persist($Profil_admin);
        
        $Profil_SA = new Role();
            $Profil_SA->setLibelle("SUPERADMIN");
            $this->addReference(self::Profil_SA, $Profil_SA);
            $manager->persist($Profil_SA);
        
        $Profil_User = new Role();
            $Profil_User->setLibelle("UTILSATEUR");
            $this->addReference(self::Profil_User, $Profil_User);
            $manager->persist($Profil_User);


                $manager->flush();
        
            
    }
 
}