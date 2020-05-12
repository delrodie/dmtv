<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\RubriqueRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @Route("/video")
 */
class VideoController extends AbstractController
{
    private $articleRepository;
    private $rubriqueRepository;
    private $paginator;
    private $cache;

    public function __construct(ArticleRepository $articleRepository, RubriqueRepository $rubriqueRepository, PaginatorInterface $paginator, CacheInterface $cache)
    {
        $this->articleRepository = $articleRepository;
        $this->rubriqueRepository = $rubriqueRepository;
        $this->paginator = $paginator;
        $this->cache = $cache;
    }

    /**
     * @Route("/", name="video_index")
     */
    public function index(Request $request)
    {
        // Mise en cache de la liste des videos
        /*
        $articlesCache = $this->cache->get('articles_liste', function (ItemInterface $item){
            $item->expiresAfter(86400);
            return $this->articleRepository->findBy(['isValid'=>true],['id'=>'DESC']);
        });
        */
        $articlesCache = $this->articleRepository->findBy(['isValid'=>true],['publieLe'=>'DESC']);

        // Pagination
        $articles = $this->paginator->paginate(
            $articlesCache,
            $request->query->getInt('page', 1), 12
        );

        return $this->render('video/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/{slug}", name="video_show")
     */
    public function show(Article $article)
    {

        //$similaires = [];

        // Mise en cache des articles similaires
        /*
        $similaires = $this->cache->get($article->getSlug(),function (ItemInterface $item) use ($article) {
            $item->expiresAfter(86400); // Expire après 24h
            $rubriques = $this->rubriqueRepository->findByArticle($article->getId());
            foreach ($rubriques as $key => $rubrique){
                $id = $rubrique->getId();
                $similaires[] = $this->articleRepository->findByRubriques($id, $article->getId());
            }

            return $similaires;
        });
        */
        $rubriques = $this->rubriqueRepository->findByArticle($article->getId());
        foreach ($rubriques as $key => $rubrique){
            $id = $rubrique->getId();
            $similaires[] = $this->articleRepository->findByRubriques($id, $article->getId());
        }

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
        // Mise en cache de la liste des rubriques
        /*
        $articleListe = $this->cache->get($rubrique,function (ItemInterface $item) use ($rubrique) {
            $item->expiresAfter(86400); // Expires après 24h
            return $this->articleRepository->findListByRubrique($rubrique);
        } );
        */
        $articleListe = $this->articleRepository->findListByRubrique($rubrique);

        // Pagination du resultat
        $articles = $this->paginator->paginate(
            $articleListe,
            $request->query->getInt('page', 1), 9
        );

        return $this->render('video/liste.html.twig',[
            'articles' => $articles,
            'rubrique' => $rubrique
        ]);
    }
}
