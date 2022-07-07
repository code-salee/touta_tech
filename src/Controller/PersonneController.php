<?php

namespace App\Controller;

use App\Entity\Personne;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PersonneController extends AbstractController
{
    private $tokenStorage;
    private $encoder;
    public function __construct(TokenStorageInterface $tokenStorage, UserPasswordHasherInterface $encoder){

        $this->tokenStorage = $tokenStorage;
        $this->encoder = $encoder;
    }
    
    public function __invoke(Personne $personne): Personne
    {
        $data = $this->tokenStorage->getToken()->getUser();
        if($data->getId() == null || $data->getId() != null){
            $password = $personne->getPassword();
            $personne=$personne->setPassword($this->encoder->hashPassword($personne, $password));
            return $personne;
        }
    }
}
