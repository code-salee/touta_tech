<?php


namespace App\Services;

use App\Entity\User;
use App\Entity\Superadmin;
use App\Entity\Admin;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HelperService
{
    private $serializer;
    private $validator;
    private $encoder;
    private $manager;
    private $repo;
    public function __construct(SerializerInterface $serializer, 
    ValidatorInterface $validator,EntityManagerInterface $manager, UserPasswordHasherInterface $encoder,
    RoleRepository $repo){
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->encoder=$encoder;
        $this->manager=$manager;
        $this->repo=$repo;
    }

    public function AddPerson(Request $request): Response
    {
        $personnes = $request->request->all();
        $role_id = $personnes['role'];
        $role_id = explode('/', $role_id);
        $role = $this->repo->findOneBy(['id' => $role_id[3]]);
        $role = $role->getLibelle();
        if ($role == "SUPERADMIN") {
            $newProfil = "Superadmin";
            $class="App\Entity\\$newProfil";
        }
        else if ($role == "ADMIN") {
            $newProfil = "Admin";
            $class="App\Entity\\$newProfil";
        }
        else if ($role == "UTILISATEUR") {
            $newProfil = "User";
            $class="App\Entity\\$newProfil";
        }
        $data = $this->serializer->denormalize($personnes, $class, 'json');
        $errors = $this->validator->validate($data);
        if ($errors) {
            $errorsString =$serializer->serialize($errors,"json");
            return new JsonResponse( $errorsString ,Response::HTTP_BAD_REQUEST,[],true);
        }
        $password = $data->getPassword();
        $data=$data->setPassword($this->encoder->hashPassword($data, $password));
        $this->manager->persist($data);
        $this->manager->flush();
        return new JsonResponse($data);
    }
}