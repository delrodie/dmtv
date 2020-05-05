<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Utilities\GestionLog;
use App\Utilities\GestionMail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class AccueilController extends AbstractController
{
    private $gestMail;
    private $log;
    private $cache;

    public function __construct(GestionMail $gestionMail, GestionLog $log, CacheInterface $cache)
    {
        $this->gestMail= $gestionMail;
        $this->log = $log;
        $this->cache = $cache;
    }

    /**
     * @Route("/", name="app_accueil")
     */
    public function index(ArticleRepository $articleRepository)
    {
        // Mise en cache du carousel
        $carousels = $this->cache->get('carousel', function (ItemInterface $item) use ($articleRepository) {
            $item->expiresAfter(86400);
            return $articleRepository->findCarousel();
        });

        // Mise en cache des videos
        $articles = $this->cache->get('home_articles', function (ItemInterface $item) use ($articleRepository) {
            $item->expiresAfter(86400);
            return $articleRepository->findBy(['isValid'=>true, 'IsSlide'=>false],['id'=>'DESC']);
        });
        return $this->render('accueil/index.html.twig', [
            'carousels' => $carousels,
            'articles' => $articles,
            'divers' => $articleRepository->findListByRubrique('divers')
        ]);
    }

    /**
     * @Route("/menu", name="app_menu")
     */
    public function menu(Request $request)
    {
        return $this->render("accueil/menu.html.twig");
    }
}
