<?php

namespace App\Controller;

use App\Utilities\GestionLog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend")
 */
class BackendController extends AbstractController
{
    private $log;

    public function __construct(GestionLog $log)
    {
        $this->log = $log;
    }

    /**
     * @Route("/", name="backend_dashboard")
     */
    public function index(Request $request)
    {
        $user = $this->getUser();
        $this->log->addLog($user, 'dashboard', $request->getClientIp());

        return $this->render('backend/index.html.twig', [
            'controller_name' => 'BackendController',
        ]);
    }
}
