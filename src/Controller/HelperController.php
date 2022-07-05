<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Superadmin;
use App\Services\HelperService;
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
    #[Route('api/superadmins', methods: "POST", name: 'post_super_admin')]
    public function AddSuperAdmin(Request $request, SerializerInterface $serializer, HelperService $helper)
    {
        $data= new Superadmin();
        $helper->AddPerson($request, $data);
        return new JsonResponse("vous avez ajouter un user succes",Response::HTTP_CREATED);
    }

    #[Route('api/admins', methods: "POST", name: 'post_admin')]
    public function AddAdmin(Request $request, SerializerInterface $serializer, HelperService $helper)
    {
        $data= new Admin();
        $helper->AddPerson($request, $data);
        return new JsonResponse("vous avez ajouter un user succes",Response::HTTP_CREATED);
    }
}



    
