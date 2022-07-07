<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Superadmin;
use App\Services\HelperService;
use App\Repository\UserRepository;
use App\Repository\AdminRepository;
use App\Repository\SuperadminRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class HelperController extends AbstractController
{
    /**
     * path('api/superadmins')
     * Foction d'ajout d'un superadmin avec comme role ROLE_SUPERADMIN
     * L'enregistrement se fait avec l'utilisation de form-data
     * */
    #[Route('api/superadmins', methods: "POST", name: 'post_super_admin')]
    public function AddSuperAdmin(Request $request, HelperService $helper)
    {
        $data= new Superadmin();
        $helper->AddPerson($request, $data);
        return new JsonResponse("vous avez ajouter un user succes",Response::HTTP_CREATED);
    }

    /**
     * path('api/admins')
     * Foction d'ajout d'un admin avec comme role ROLE_ADMIN
     * L'enregistrement se fait avec l'utilisation de form-data
     * */
    #[Route('api/admins', methods: "POST", name: 'post_admin')]
    public function AddAdmin(Request $request, HelperService $helper)
    {
        $data= new Admin();
        $helper->AddPerson($request, $data);
        return new JsonResponse("vous avez ajouter un user succes",Response::HTTP_CREATED);
    }

    /**
     * path('api/users')
     * Foction d'ajout d'un utilisateur avec comme role ROLE_UTILISATEUR
     * L'enregistrement se fait avec l'utilisation de form-data
     * */
    #[Route('api/users', methods: "POST", name: 'post_user')]
    public function AddUsers(Request $request, HelperService $helper)
    {
        $data= new User();
        $helper->AddPerson($request, $data);
        return new JsonResponse("vous avez ajouter un user succes",Response::HTTP_CREATED);
    }

    /**
     * path('api/superadmins')
     * Foction pour obtenir tous les d'un utilisateur avec comme role ROLE_SUPERADMIN
     * */
    #[Route('api/superadmins', methods: "GET", name: 'get_super_admin')]
    public function GetSuperAdmin(SuperadminRepository $repo)
    {
        
        $data= $repo->findAll();
        return $this->json($data,200);
        //return new JsonResponse($data,Response::HTTP_OK);
    }
    
    /**
     * path('api/personne')
     * Fonction pour obtenir tous les d'un utilisateur avec comme role ROLE_SUPERADMIN
     * */
    #[Route('api/personne', methods: "GET", name: 'get_person')]
    public function GetCurrentUser(TokenStorageInterface $tokenStorage)
    {
        $data = $tokenStorage->getToken()->getUser();

        return $this->json($data,200);
        //return new JsonResponse($data,Response::HTTP_OK);
    }

     /**
     * path('api/etat')
     * Fonction pour changer l'etat d'un utilisateur 
     * L'utilisateur peut être enrôlé et affecté à un admin ou refusé
     * */
    #[Route('api/users/{id}/etat', methods: "PATCH", name: 'refusé_user')]
    public function RefuseUser(Request $request, $id, UserRepository $repo, AdminRepository $repoAdmin, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $data = $request->getContent();
        $data = $serializer->decode($data, "json");
        $statut = $data['statut'];
        if(isset($data['admins'])){
            $getAdmin = $data['admins'];
            $id_admin = explode('/', $getAdmin);
            $admin = $repoAdmin->findOneBy(['id' => $id_admin[3]]);
        }
        $id = $request->get('id');
        $users = $repo->findOneBy(['id' => $id]);
        if(!$data || $data = null){
            return new JsonResponse("L'identifiant n'existe pas",Response::HTTP_NOT_FOUND);
        }
        if ($users->getStatut() == 'en attente') {
           $users = $users->setStatut($statut);
           if(isset($admin) && $statut != "refuse"){
            $users = $users->setAdmins($admin);
           }
           $em->flush();
            return new JsonResponse("L'utilisateur enrolé avec succes",Response::HTTP_OK);
        }
        elseif ($users->getStatut() == 'refuse') {
            return new JsonResponse("L'utilisateur a été deja refuse",Response::HTTP_OK);
        }
        else {
            return new JsonResponse("L'utilisateur a été deja enrôlé à un admin",Response::HTTP_OK);
        }
    }
}



    
