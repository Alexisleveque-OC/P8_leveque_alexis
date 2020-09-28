<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\User\RegisterService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user_list")
     * @param UserRepository $userRepository
     * @return Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function listUser(UserRepository $userRepository)
    {
        $users = $userRepository->findAllUsers();

        return $this->render('user/list.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/users/create", name="user_create")
     * @param Request $request
     * @param RegisterService $registerService
     * @return RedirectResponse|Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function createUser(Request $request, RegisterService $registerService)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()) {
            $registerService->registerUser($user);

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/users/{id}/edit", name="user_edit")
     * @param User $user
     * @param Request $request
     * @return RedirectResponse|Response
     * @IsGranted("ROLE_ADMIN")
     */
    public function editUser(User $user, Request $request, RegisterService $registerService)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()) {
            $registerService->registerUser($user);

            $this->addFlash('success', "L'utilisateur a bien été modifié");

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }
}
