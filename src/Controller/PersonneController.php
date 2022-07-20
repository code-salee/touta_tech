<?php

namespace App\Controller;

use App\Entity\Personne;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PersonneController extends AbstractController
{
    private $tokenStorage;
    private $encoder;
    private $serializer;
    public function __construct(SerializerInterface $serializer, TokenStorageInterface $tokenStorage, UserPasswordHasherInterface $encoder){

        $this->tokenStorage = $tokenStorage;
        $this->encoder = $encoder;
        $this->serializer = $serializer;
    }
    
    public function __invoke(Personne $personne, Request $request): Personne
    {
        $data = $this->tokenStorage->getToken()->getUser();
        $user = $request->getContent();
        $user = $this->serializer->decode($user, "json");
       
        if($data->getId() == null || $data->getId() != null){
            if(isset($user['password']))
            {
                $password = $user['password'];
                $personne=$personne->setPassword($this->encoder->hashPassword($personne, $password));
            }
            return $personne;
        }
    }
}
