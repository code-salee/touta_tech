<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Superadmin;
use App\Services\HelperService;
use App\Repository\UserRepository;
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


class HelperController extends AbstractController
{
    /**
     * path('api/superadmins')
     * Foction d'ajout d'un utilisateur avec comme role ROLE_SUPERADMIN
     * L'enregistrement se fait avec l'utilisation de form-data
     * */
    #[Route('api/superadmins', methods: "POST", name: 'post_super_admin')]
    public function AddSuperAdmin(Request $request, SerializerInterface $serializer, HelperService $helper)
    {
        $data= new Superadmin();
        $helper->AddPerson($request, $data);
        return new JsonResponse("vous avez ajouter un user succes",Response::HTTP_CREATED);
    }

    /**
     * path('api/admins')
     * Foction d'ajout d'un utilisateur avec comme role ROLE_ADMIN
     * L'enregistrement se fait avec l'utilisation de form-data
     * */
    #[Route('api/admins', methods: "POST", name: 'post_admin')]
    public function AddAdmin(Request $request, SerializerInterface $serializer, HelperService $helper)
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
    #[Route('api/users', methods: "POST", name: 'post_users')]
    public function AddUsers(Request $request, SerializerInterface $serializer, HelperService $helper)
    {
        $data= new User();
        $helper->AddPerson($request, $data);
        return new JsonResponse("vous avez ajouter un user succes",Response::HTTP_CREATED);
    }

    /**
     * path('api/users/{id}')
     * Fonction pour editer un utilisateur 
     * L'enregistrement se fait avec l'utilisation de form-data
     * */
    #[Route('api/users/{id}', methods: "PUT", name: 'edit_users')]
    public function EditUsers(Request $request, UserRepository $repo, HelperService $helper, $id)
    {
        $data = $repo->findOneBy(['id' => $id]);
        $helper->UpdatePerson($request, $data);
        return new JsonResponse("vous avez ajouter un user succes",Response::HTTP_OK);
    }
    
}



    
