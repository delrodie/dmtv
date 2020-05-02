<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Utilities\GestionMedia;
use Cassandra\Time;
use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/article")
 */
class ArticleController extends AbstractController
{
    private $gestionMedia;
    public function __construct(GestionMedia $gestionMedia)
    {
        $this->gestionMedia = $gestionMedia;
    }

    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="article_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // gestion du slug
            $slugify = new Slugify();
            $titre = $slugify->slugify($article->getTitre());

            //Unicité du slug
            $date = \time();
            $slug = $titre.'-'.$date;

            // Gestion des fichiers
            $mediaFile = $form->get('img480')->getData();

            // Traitement du fichier s'il a été telechargé
            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'img480');

                $article->setImg480($media);
            }

            $article->setSlug($slug);
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->persist($article);
            $entityManager->flush();

            // Si le bouton IsSlide est actif alors enregistrer le media
            if ($article->getIsSlide()) return $this->redirectToRoute('media_new',['article'=>$article->getId()]);
            else return $this->redirectToRoute('article_index');

        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // gestion du slug
            $slugify = new Slugify();
            $titre = $slugify->slugify($article->getTitre());

            //Unicité du slug
            $date = \time();
            $slug = $titre.'-'.$date;

            // Gestion des fichiers
            $mediaFile = $form->get('img480')->getData();

            // Traitement du fichier s'il a été telechargé
            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'img480');

                $article->setImg480($media);
            }

            $article->setSlug($slug);
            $this->getDoctrine()->getManager()->flush();

            // Si le bouton IsSlide est actif alors enregistrer le media
            if ($article->getIsSlide()) return $this->redirectToRoute('media_new',['article'=>$article->getId()]);
            else return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }
}
