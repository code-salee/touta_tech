<?php

// src/DataPersister

namespace App\DataPersisters;

use function Matrix\trace;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Admin;

class AdminDataPersister implements ContextAwareDataPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    private $passwordEncoder;


    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager=$em;

    }
    public function supports($data, array $context = []): bool
    {
        //L'opérateur instanceof permet de vérifier si tel objet est une instance de telle classe.
        return $data instanceof Admin;
    }

    public function persist($data, array $context = [])
    {
      
        $this->entityManager->persist($data);
        $this->entityManager->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {
        
        $etat=$data;
        $etat->setIsBlocked(true);
        $this->entityManager->persist($etat);
        $this->entityManager->flush();
        return new Response("Admin bloqué avec succes");
    }

}