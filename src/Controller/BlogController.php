<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog')]
    public function index(): Response
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/blog/new', name: 'new_article'), IsGranted("ROLE_ADMIN")]
    public function newArticle(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $article = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($article);
            $em->flush();
            
            return $this->redirectToRoute("article", [
                "id" => $article->getId(),
            ]);
        }


        return $this->render('blog/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/blog/article/{id}', name: 'article')]
    public function article (Article $article)
    {
        // $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        return $this->render("blog/single.html.twig", [
            "article" => $article
        ]);
    }

    #[Route('/blog/update/{id}', name: 'update_article'), IsGranted("ROLE_ADMIN")]
    public function updateArticle(Article $article, Request $request): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            
            return $this->redirectToRoute("article", [
                "id" => $article->getId(),
            ]);
        }


        return $this->render('blog/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/blog/delete/{id}', name: 'delete_article'), IsGranted("ROLE_ADMIN")]
    public function deleteArticle(Article $article): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();

        $this->addFlash("blog-danger", "Article supprimé");
    
        return $this->redirectToRoute("blog");
    }


}
