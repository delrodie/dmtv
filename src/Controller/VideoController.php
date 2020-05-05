<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\RubriqueRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/video")
 */
class VideoController extends AbstractController
{
    private $articleRepository;
    private $rubriqueRepository;
    private $paginator;

    public function __construct(ArticleRepository $articleRepository, RubriqueRepository $rubriqueRepository, PaginatorInterface $paginator)
    {
        $this->articleRepository = $articleRepository;
        $this->rubriqueRepository = $rubriqueRepository;
        $this->paginator = $paginator;
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
    public function rubrique($rubrique, Request $request)
    {
        $articles = $this->paginator->paginate(
            $this->articleRepository->findListByRubrique($rubrique),
            $request->query->getInt('page', 1), 9
        );

        return $this->render('video/liste.html.twig',[
            'articles' => $articles,
            'rubrique' => $rubrique
        ]);
    }
}
