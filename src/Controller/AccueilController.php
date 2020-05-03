<?php

namespace App\Controller;

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
    public function index()
    {
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }

    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function dashboard(Request $request)
    {
        //$user = $this->getUser();
        $this->log->addLog('dashboard');
        return $this->render("accueil/index.html.twig");
    }
}
