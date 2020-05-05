<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Utilities\GestionLog;
use App\Utilities\GestionMail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    private $gestMail;
    private $log;

    public function __construct(GestionMail $gestionMail, GestionLog $log)
    {
        $this->gestMail= $gestionMail;
        $this->log = $log;
    }

    /**
     * @Route("/", name="app_accueil")
     */
    public function index(ArticleRepository $articleRepository)
    {
        //dd($articleRepository->findCarousel());
        return $this->render('accueil/index.html.twig', [
            'carousels' => $articleRepository->findCarousel(),
            'articles' => $articleRepository->findBy(['isValid'=>true, 'IsSlide'=>false],['id'=>'DESC']),
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
