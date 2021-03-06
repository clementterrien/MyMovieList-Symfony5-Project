<?php

namespace App\Controller;

use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MyMovieListController extends AbstractController
{
    /**
     * @Route("/mymovielist", name="my_movie_list")
     */
    public function index()
    {
        return $this->render('my_movie_list/index.html.twig', [
            'controller_name' => 'MyMovieListController',
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('my_movie_list/home.html.twig', [
            'controller_name' => 'MyMovieListController',
        ]);
    }



    /**
     * @Route("/myprofile", name="myprofile") 
     */
    public function myProfile(UserRepository $userRepo)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $user_id = $user->getId();
        $modify_user = [
            "firstname" => 0,
            "lastname" => 0,
            "email" => 0,
            "password" => 0,
        ];

        $userDB = $userRepo->find($user_id);
        return $this->render('my_movie_list/myprofile.html.twig', [
            'user' => $userDB,
            'modify' => $modify_user
        ]);
    }

    /**
     * @Route("/myprofile/modify", name="modifymyprofile") 
     */
    public function modifyMyProfile(UserRepository $userRepo, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $user_id = $user->getId();
        $userDB = $userRepo->find($user_id);

        $manager = $this->getDoctrine()->getManager();
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('my_movie_list/modifyprofile.html.twig', [
            'form' => $form->createView(),
            'userDB' => $userDB
        ]);
    }
}
