<?php


namespace App\Utilities;


use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Security\Core\Security;

class GestionLog
{
    private $logger;
    private $kernel;
    private $security;
    private $request;

    public function __construct(LoggerInterface $logger, KernelInterface $kernel, Security $security, RequestStack $request)
    {
        $this->logger = $logger;
        $this->kernel = $kernel;
        $this->security = $security;
        $this->request = $request;
    }

    /**
     * @param $user
     * @param $rubrique
     * @param $ip
     * @return bool
     */
    public function addLog($rubrique, $action = null)
    {
        $username = $this->security->getUser()->getUsername();
        //$request = $this->request->getCurrentRequest()->getClientIp();

        $this->logger->info($this->action($rubrique, $action),['username'=>$username, 'ip'=>$this->request->getCurrentRequest()->getClientIp()]);

        return true;
    }

    /**
     * Ouverture du fichier log monitoring en fonction de la date etde l'environnement
     *
     * @param $date
     * @return array|bool|false
     */
    public function monitoring($date)
    {
        // Recuperer la date puis affecter l'extension .log a la date
        // recuperer l'environnement encours puis chercher le chemin du repertoire
        $extension = $date.'.log';
        $env = $this->kernel->getEnvironment(); //dd($env);
        $racine = $this->kernel->getProjectDir().'/var/log/'.$env.'.monitoring-'.$extension;

        // Si le fichier n'existe pas alors retourner false
        // Sinon renvoyer le fichier ouvert
        if (!file_exists($racine))return false;
        else{
            $fichier = file($racine);

            return $fichier;
        }

    }

    /**
     * Formattage des actions du log
     *
     * @param $rubrique
     * @return string
     */
    protected function action($rubrique, $action = null)
    {
        $username = $this->security->getUser()->getUsername();
        // Formalisation des actions ainsi que les rubriques;
        $dashboard_action = $username."a affiché le tableau de bord";
        $backend_articleListe = $username." a affiché la liste des activités dans le backoffice";
        $backend_articleNew = $username." a tenté d'enregistrer un article";
        $backend_articleSave = $username." a enregistré l'article ".$action;
        $backend_articleUpdate = $username." a modifié l'article ".$action;
        $backend_articleEdit = $username." a tenté de modifier l'article ".$action;
        $backend_articleDelete = $username." a supprimé l'article ".$action;
        $backend_articleShow = $username." a affiché dans le backoffice l'article ".$action;
        $backend_mediaListe = $username." a affiché la liste des medias";
        $backend_mediaSave = $username." a enregistré le media id: ".$action;
        $backend_mediaUpdate = $username." a modifié le media id: ".$action;
        $backend_mediaEdit = $username." a tenté de modifier le media id: ".$action;
        $backend_RubriqueListe = $username." a affiché la liste des rubriques";
        $backend_RubriqueSave = $username." a enregistré la rubrique ".$action;
        $backend_RubriqueEdit = $username." a tenté de modifier la rubrique ".$action;
        $backend_RubriqueUpdate= $username." a modifé la rubrique ".$action;


        // Affectaion des actions au resultat en fonction de la rubrique
        switch ($rubrique)
        {
            case 'dashboard':
                $result = $dashboard_action;
                break;
            case 'backendArticleListe':
                $result = $backend_articleListe;
                break;
            case 'backendArticleNew':
                $result = $backend_articleNew;
                break;
            case 'backendArticleSave':
                $result = $backend_articleSave;
                break;
            case 'backendArticleUpdate':
                $result = $backend_articleUpdate;
                break;
            case 'backendArticleEdit':
                $result = $backend_articleEdit;
                break;
            case 'backendArticleDelete':
                $result = $backend_articleDelete;
                break;
            case 'backendArticleShow':
                $result = $backend_articleShow;
                break;
            case 'backendMediaListe':
                $result = $backend_mediaListe;
                break;
            case 'backendMediaSave':
                $result = $backend_mediaSave;
                break;
            case 'backendMediaUpdate':
                $result = $backend_mediaUpdate;
                break;
            case 'backendMediaEdit':
                $result = $backend_mediaEdit;
                break;
            case 'backendRubriqueListe':
                $result = $backend_RubriqueListe;
                break;
            case 'backendRubriqueSave':
                $result = $backend_RubriqueSave;
                break;
            case 'backendRubriqueEdit':
                $result = $backend_RubriqueEdit;
                break;
            case 'backendRubriqueUpdate':
                $result = $backend_RubriqueUpdate;
                break;
            default:
                $result = 'Accueil';
        }

        return $result;
    }
}