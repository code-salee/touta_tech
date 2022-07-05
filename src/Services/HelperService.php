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
    public function __construct(SerializerInterface $serializer, 
    ValidatorInterface $validator,EntityManagerInterface $manager, UserPasswordHasherInterface $encoder,
    RoleRepository $repo, UserRepository $repoUser, SuperadminRepository $repoSuperadmin, 
    AdminRepository $repoAdmin, PersonneRepository $repoPersonne){
        $this->serializer=$serializer;
        $this->validator=$validator;
        $this->encoder=$encoder;
        $this->manager=$manager;
        $this->repo=$repo;
        $this->repoUser=$repoUser;
        $this->repoSuperadmin=$repoSuperadmin;
        $this->repoAdmin=$repoAdmin;
        $this->repoPersonne=$repoPersonne;
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


    /**
     * Foction de modification d'une personne 
     * La fonction n'est accessible que sur le service
     * Pour faire un enregistrement avec ce service on utilise la form-data et l'iri (exp role='/api/admins')
     * sur le role de la personne pour pouvoir determiner son selon sa profil
     * */
    public function UpdatePerson(Request $request): Response
    {
        $id = $request->get('id');
        $personnes = $request->request->all();
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
        $this->manager->persist($data);
        $this->manager->flush();
        return new JsonResponse($data);
        // if($this->repoUser->findOneBy(['id' => $id])){
        //     $person = $this->repoUser->findOneBy(['id' => $id]);
        //     return new JsonResponse("vous etes user");
        //     //dd($person);
        // }
        // elseif ($admin->findOneBy(['id' => $id])) {
        //     $person = $user->findOneBy(['id' => $id]);
        //     return new JsonResponse("vous etes admin");
        // }
        // elseif ($superadmin->findOneBy(['id' => $id])) {
        //     $person = $user->findOneBy(['id' => $id]);
        //     return new JsonResponse("vous etes admin");
        // }
        // dd($person->getRole()->getLibelle());

        // return new JsonResponse("vous avez ajouter un user succes",Response::HTTP_CREATED);
    }
}