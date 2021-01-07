<?php

namespace App\Controller;

use App\Entity\Comentarios;
use App\Form\CommentsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComentariosController extends AbstractController
{
    /**
     * @Route("/add-comment", name= "add-comment")
     */ 
    public function index(Request $request)
    {
        $comentarios = new Comentarios();
        $id = $request->request->get('id');

        $form = $this->createForm(CommentsType::class, $comentarios);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            dd('ok');
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($comentarios);
        $em->flush();

        
        return $this->render('comentarios/index.html.twig', [
            'controller_name' => 'ComentariosController',
        ]);
    }
}
