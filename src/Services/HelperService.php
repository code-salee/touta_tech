<?php


namespace App\Services;

use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Superadmin;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Repository\AdminRepository;
use App\Repository\PersonneRepository;
use App\Repository\SuperadminRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ApiPlatform\Core\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HelperService
{
    private $serializer;
    private $validator;
    private $encoder;
    private $manager;
    private $repo;
    private $repoUser;
    private $repoSuperadmin;
    private $repoAdmin;
    private $repoPersonne;
    private $tokenStorage;
    public function __construct(SerializerInterface $serializer, 
    ValidatorInterface $validator,EntityManagerInterface $manager, UserPasswordHasherInterface $encoder,
    RoleRepository $repo, UserRepository $repoUser, SuperadminRepository $repoSuperadmin, 
    AdminRepository $repoAdmin, PersonneRepository $repoPersonne, TokenStorageInterface $tokenStorage){
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->encoder=$encoder;
        $this->manager=$manager;
        $this->repo=$repo;
        $this->repoUser=$repoUser;
        $this->repoSuperadmin=$repoSuperadmin;
        $this->repoAdmin=$repoAdmin;
        $this->repoPersonne=$repoPersonne;
        $this->tokenStorage=$tokenStorage;
    }

    /**
     * Foction d'ajout d'une personne 
     * La fonction n'est accessible que sur le service
     * Pour faire un enregistrement avec ce service on utilise la form-data et l'iri (exp role='/api/admins')
     * sur le role de la personne pour pouvoir determiner son selon sa profil
     * */
    public function AddPerson(Request $request): Response
    {
        $personnes = $request->request->all();
        $role_id = $personnes['roles'];
//        dd($role_id);
//        $role_id = explode('/', $role_id);
        $roles = $this->repo->findOneBy(['libelle' => $role_id]);
        $role = $roles->getLibelle();
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
        $personnes['role'] = '/api/roles/'.$roles->getId();
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


    /**
     * Fonction de modification d'une personne 
     * La fonction n'est accessible que sur le service
     * Pour bloquerune personne avec ce service on change l'etat isBlocked
     * sur le role de la personne pour pouvoir determiner son selon sa profil
     * */
    public function BlockPerson(Request $request): Response
    {
  
        $id = $request->get('id');
       // $personnes = $request->request->all();
        $personnes = $request->getContent();

        $personnes = preg_split("/form-data; /", $personnes);

        $person = $this->repoPersonne->findOneBy(['id' => $id]);
        $role = $person->getRole()->getLibelle();
        if ($role == "SUPERADMIN") {
            $data = $this->repoSuperadmin->findOneBy(['id' => $id]);
            $newProfil = "Superadmin";
            $class="App\Entity\\$newProfil";
        }
        else if ($role == "ADMIN") {
            $data = $this->repoAdmin->findOneBy(['id' => $id]);
            $newProfil = "Admin";
            $class="App\Entity\\$newProfil";
        }
        else if ($role == "UTILISATEUR") {
            $data = $this->repoUser->findOneBy(['id' => $id]);
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
        // $this->manager->persist($data);
        $this->manager->flush();
        return new JsonResponse($data);

    }
}