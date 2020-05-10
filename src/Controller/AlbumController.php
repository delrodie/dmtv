<?php

namespace App\Controller;

use App\Entity\Album;
use App\Form\AlbumType;
use App\Repository\AlbumRepository;
use App\Utilities\GestionLog;
use App\Utilities\GestionMedia;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/album")
 */
class AlbumController extends AbstractController
{
    private $gestionMedia;
    private $log;
    private $paginator;

    public function __construct(GestionMedia $gestionMedia, GestionLog $gestionLog, PaginatorInterface $paginator)
    {
        $this->gestionMedia = $gestionMedia;
        $this->log = $gestionLog;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/", name="album_index", methods={"GET","POST"})
     */
    public function index(Request $request, AlbumRepository $albumRepository): Response
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            // Gestion des fichiers
            $mediaFile = $form->get('cover')->getData();

            // Traitement du fichier s'il a été telechargé
            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'cover');

                $album->setCover($media);
            }

            $entityManager->persist($album);
            $entityManager->flush();

            // Enregistrement du log
            $action = $album->getTitre().' de '.$album->getAuteur();
            $this->log->addLog('backendAlbumSave', $action);

            return $this->redirectToRoute('album_index');
        }

        // Enregistrement du log
        $this->log->addLog('backendAlbumList');

        $albums = $this->paginator->paginate(
            $albumRepository->findBy([],['id'=>'DESC']),
            $request->query->getInt('page', 1), 6
        );

        return $this->render('album/index.html.twig', [
            'albums' => $albums,
            'album' => $album,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="album_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($album);
            $entityManager->flush();

            return $this->redirectToRoute('album_index');
        }

        return $this->render('album/new.html.twig', [
            'album' => $album,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="album_show", methods={"GET"})
     */
    public function show(Album $album): Response
    {
        return $this->render('album/show.html.twig', [
            'album' => $album,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="album_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Album $album, AlbumRepository $albumRepository): Response
    {
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Gestion des fichiers
            $mediaFile = $form->get('cover')->getData();

            // Traitement du fichier s'il a été telechargé
            if ($mediaFile){
                $media = $this->gestionMedia->upload($mediaFile, 'cover');

                $album->setCover($media);
            }
            $this->getDoctrine()->getManager()->flush();

            // Enregistrement du log
            $action = $album->getTitre().' de '.$album->getAuteur();
            $this->log->addLog('backendAlbumUpdate', $action);

            return $this->redirectToRoute('album_index');
        }

        // Enregistrement du log
        $action = $album->getTitre().' de '.$album->getAuteur();
        $this->log->addLog('backendAlbumEdit', $action);

        $albums = $this->paginator->paginate(
            $albumRepository->findBy([],['id'=>'DESC']),
            $request->query->getInt('page', 1), 6
        );

        return $this->render('album/edit.html.twig', [
            'albums' => $albums,
            'album' => $album,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="album_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Album $album): Response
    {
        if ($this->isCsrfTokenValid('delete'.$album->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $action = $album->getTitre().' de '.$album->getAuteur();
            $entityManager->remove($album);
            $entityManager->flush();

            // Enregistrement du log
            $action = $album->getTitre().' de '.$album->getAuteur();
            $this->log->addLog('backendAlbumDelete', $action);
        }

        return $this->redirectToRoute('album_index');
    }
}
