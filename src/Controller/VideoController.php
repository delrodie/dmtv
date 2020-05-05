<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\RubriqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/video")
 */
class VideoController extends AbstractController
{
    private $articleRepository;
    private $rubriqueRepository;

    public function __construct(ArticleRepository $articleRepository, RubriqueRepository $rubriqueRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->rubriqueRepository = $rubriqueRepository;
    }

    /**
     * @Route("/", name="video_index")
     */
    public function index()
    {
        return $this->render('video/index.html.twig', [
            'controller_name' => 'VideoController',
        ]);
    }

    /**
     * @Route("/{slug}", name="video_show")
     */
    public function show(Article $article)
    {
        $rubriques = $this->rubriqueRepository->findByArticle($article->getId());
        //$similaires = [];
        foreach ($rubriques as $key => $rubrique){
            $id = $rubrique->getId();
            $similaires[] = $this->articleRepository->findByRubriques($id, $article->getId());
        }
        //dd($similaires);
        return $this->render("video/show.html.twig",[
            'article' => $article,
            'similaires' => $similaires
        ]);
    }

    /**
     * @Route("/rubrique/{rubrique}/", name="video_rubriques")
     */
    public function rubrique($rubrique)
    {
        //$rubriqueEntity = $this->rubriqueRepository->findByLibelle($rubrique);

        return $this->render('video/liste.html.twig',[
            'articles' => $this->articleRepository->findListByRubrique($rubrique),
            'rubrique' => $rubrique
        ]);
    }
}
