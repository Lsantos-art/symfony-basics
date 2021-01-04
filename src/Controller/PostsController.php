<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostsController extends AbstractController
{
    /**
     * @Route("/new-post", name="new-post")
     */
    public function index(Request $request): Response
    {
        
        $post = new Posts();
        $user = $this->getUser();
        $post->setUser($user);

        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', Posts::SUCCESS_POST);
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('posts/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}  
