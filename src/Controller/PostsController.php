<?php

namespace App\Controller;

use App\Entity\Comentarios;
use App\Entity\Posts;
use App\Form\CommentsType;
use App\Form\PostsType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostsController extends AbstractController
{
    /**
     * @Route("posts/new-post", name="new-post")
     */
    public function index(Request $request, SluggerInterface $slugger): Response
    {
        
        $post = new Posts();
        $user = $this->getUser();
        $post->setUser($user);

        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $uploadFile = $form->get('foto')->getData();

            if ($uploadFile) {
                $originalFilename = pathinfo($uploadFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $uploadFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) { 
                    throw new \Exception('Houve um erro ao salvar o arquivo...');
                }

                // updates the 'uploadFilename' property to store the PDF file name
                // instead of its contents
                $post->setFoto($newFilename);
            }

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

    /**
     * @Route("posts/post/{id}", name="post-detail")
     */
    public function postDetail($id, Request $request)
    {
        $user = $this->getUser();
        $comentarios = new Comentarios();
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Posts::class)->find($id);

        $form = $this->createForm(CommentsType::class, $comentarios);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comentarios->setPosts($post);
            $comentarios->setUser($user);
            $em->persist($comentarios);
            $em->flush();
        }
        $comments = $em->getRepository(Comentarios::class)->findBy(['posts' => $post]);

        return $this->render('posts/post.html.twig', [
            'post' => $post,
            'comments' => $comments,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("posts/my-posts", name="my-posts")
     */
    public function myPosts(PaginatorInterface $paginator, Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Posts::class)->findBy(['user' => $user]);
        
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('posts/my-posts.html.twig', [
            'pagination' => $pagination
        ]);
    }


    /**
     * @Route("posts/likes", options={"expose"=true}, name="likes")
     */
    public function like(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $id = $request->request->get('id');
            $post = $em->getRepository(Posts::class)->find($id);
            $likes = $post->getLikes();
            $likes .= $user->getId() . ',';
            $post->setLikes($likes);
            $em->flush();
            return new JsonResponse(['likes' => $likes]);

        }else{ 
            throw new \Exception('Que feio servidor, você não pode fazer isso!');
        }
    }
}  
