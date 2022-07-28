<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Activite;
use App\Entity\Feedback;
use App\Entity\Superadmin;
use App\Services\HelperService;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use App\Repository\AdminRepository;
use App\Repository\ActiviteRepository;
use App\Repository\FeedbackRepository;
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

    private $repoAdmin;
    private $repoPersonne;
    private $tokenStorage;
    private $serializer;
    private $repoRole;
    private $repoUser;
    private $repoSuperadmin;
    private $helper;
    public function __construct(SerializerInterface $serializer, TokenStorageInterface $tokenStorage,
    EntityManagerInterface $manager, RoleRepository $repoRole, UserRepository $repoUser, 
    SuperadminRepository $repoSuperadmin, AdminRepository $repoAdmin, HelperService $helper,){
        $this->serializer=$serializer;
        $this->manager=$manager;
        $this->repo=$repoRole;
        $this->repoUser=$repoUser;
        $this->repoSuperadmin=$repoSuperadmin;
        $this->repoAdmin=$repoAdmin;
        $this->tokenStorage=$tokenStorage;
        $this->helper=$helper;
    }

    /**
     * path('api/superadmins')
     * Foction d'ajout d'un superadmin avec comme role ROLE_SUPERADMIN
     * L'enregistrement se fait avec l'utilisation de form-data
     * */
    #[Route('api/superadmins', methods: "POST", name: 'post_super_admin')]
    public function AddSuperAdmin(Request $request)
    {
        $data= new Superadmin();
        $this->helper->AddPerson($request, $data);
        return new JsonResponse("vous avez ajouter un user succes",Response::HTTP_CREATED);
    }

    /**
     * path('api/admins')
     * Foction d'ajout d'un admin avec comme role ROLE_ADMIN
     * L'enregistrement se fait avec l'utilisation de form-data
     * */
    #[Route('api/admins', methods: "POST", name: 'post_admin')]
    public function AddAdmin(Request $request)
    {
        $data= new Admin();
        $this->helper->AddPerson($request, $data);
        return new JsonResponse("vous avez ajouter un user succes",Response::HTTP_CREATED);
    }

    /**
     * path('api/users')
     * Foction d'ajout d'un utilisateur avec comme role ROLE_UTILISATEUR
     * L'enregistrement se fait avec l'utilisation de form-data
     * */
    #[Route('api/users', methods: "POST", name: 'post_user')]
    public function AddUsers(Request $request)
    {
        $data= new User();
        $this->helper->AddPerson($request, $data);
        return new JsonResponse("vous avez ajouter un user succes",Response::HTTP_CREATED);
    }
    
    
    /**
     * path('api/personne')
     * Fonction pour obtenir tous les d'un utilisateur avec comme role ROLE_SUPERADMIN
     * */
    #[Route('api/personne', methods: "GET", name: 'get_person')]
    public function GetCurrentUser()
    {
        $data = $this->tokenStorage->getToken()->getUser();

        return $this->json($data,200);
        //return new JsonResponse($data,Response::HTTP_OK);
    }

     /**
     * path('api/etat')
     * Fonction pour changer l'etat d'un utilisateur 
     * L'utilisateur peut être enrôlé et affecté à un admin ou refusé
     * */
    #[Route('api/users/{id}/etat', methods: "PATCH", name: 'refusé_user')]
    public function RefuseUser(Request $request, $id)
    {
        $data = $request->getContent();
        $data = $this->serializer->decode($data, "json");
        $statut = $data['statut'];
        if(isset($data['admins'])){
            $getAdmin = $data['admins'];
            $id_admin = explode('/', $getAdmin);
            $admin = $this->repoAdmin->findOneBy(['id' => $id_admin[3]]);
        }
        $id = $request->get('id');
        $users = $this->repoUser->findOneBy(['id' => $id]);
        if(!$data || $data = null){
            return new JsonResponse("L'identifiant n'existe pas",Response::HTTP_NOT_FOUND);
        }
        if ($users->getStatut() == 'en attente' && $statut != "refuse") {
            $users = $users->setStatut($statut);
            $users = $users->setAdmins($admin);
            $this->manager->persist($users);
            $this->manager->flush();
            return new JsonResponse("L'utilisateur enrolé avec succes",Response::HTTP_OK);
        }
        elseif ($users->getStatut() == 'refuse') {
            return new JsonResponse("L'utilisateur a été deja refuse",Response::HTTP_OK);
        }
        else {
            return new JsonResponse("L'utilisateur a été deja enrôlé à un admin",Response::HTTP_OK);
        }
    }


    /**
     * path('api/activites')
     * Fonction pour creer une activite avec comme role ROLE_ADMIN
     * */
    #[Route('api/activites', methods: "POST", name: 'post_activite')]
    public function CreateActivity(Request $request, ActiviteRepository  $repoActivite)
    {

        $data = $this->tokenStorage->getToken()->getUser();
        $activite = $request->getContent();
        $activite = $this->serializer->deserialize($activite , Activite::class, 'json');

        if ($data->getRoles()[0] === 'ROLE_SUPERADMIN') {
            $superadmin = $this->repoSuperadmin->findOneBy(['id' => $data->getId()]);
            $activite = $activite->setSuperadmin($superadmin);
        }

        if ($data->getRoles()[0] === 'ROLE_ADMIN') {
            $admin = $this->repoAdmin->findOneBy(['id' => $data->getId()]);
            $activite = $activite->setAdmin($admin);
        }

        $this->manager->persist($activite);
        $this->manager->flush();

        return $this->json($activite,200);
    }

     /**
     * path('api/feedbacks')
     * Fonction pour ajouter un feedback sur une activite avec comme role ROLE_UTILISATEUR
     * */
    #[Route('api/feedbacks', methods: "POST", name: 'post_feedback')]
    public function CreateFeedback(Request $request, FeedbackRepository  $repoFeed)
    {

        $data = $this->tokenStorage->getToken()->getUser();
        $user = $this->repoUser->findOneBy(['id' => $data->getId()]);
        if($data->getRoles()[0] != "ROLE_UTILISATEUR")
        {
            return new JsonResponse("Votre profil ne vous permet pas d'effectuer un commentaire",Response::HTTP_BAD_REQUEST);
        }
        $feedback = $request->getContent();
        $feedback = $this->serializer->deserialize($feedback , Feedback::class, 'json');
        $feedback = $feedback->setAdmin($user);
        $this->manager->persist($feedback);
        $this->manager->flush();

        return $this->json($data,200);
    }

}



    
