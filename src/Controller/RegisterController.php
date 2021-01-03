<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    
    public function index(Request $request, UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rp = $this->getDoctrine()->getManager();
            $user->setPassword($userPasswordEncoder->encodePassword($user, $form['password']->getData()));
            $user->setBanido('false');
            $user->setRoles(['ROLE_USER']);
            $rp->persist($user);
            $rp->flush();
            $this->addFlash('success', 'Usuário registrado com sucesso!');
            return $this->redirectToRoute("home");
        }

        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
            'form' => $form->createView()
        ]);
    }
}
