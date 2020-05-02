<?php

namespace App\Controller;

use App\Entity\Media;
use App\Form\MediaType;
use App\Repository\MediaRepository;
use App\Utilities\GestionMedia;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/media")
 */
class MediaController extends AbstractController
{
    private $gestionMedia;
    private $mediaRepository;

    public function __construct(GestionMedia $gestionMedia, MediaRepository $mediaRepository)
    {
        $this->gestionMedia = $gestionMedia;
        $this->mediaRepository = $mediaRepository;
    }

    /**
     * @Route("/", name="media_index", methods={"GET"})
     */
    public function index(MediaRepository $mediaRepository): Response
    {
        return $this->render('media/index.html.twig', [
            'medias' => $mediaRepository->findBy([],['id'=>'DESC']),
        ]);
    }

    /**
     * @Route("/new/{article}", name="media_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $articleID = $request->get('article');
        $medium = new Media();
        $form = $this->createForm(MediaType::class, $medium, ['articleID'=>$articleID]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des fichiers
            $media250 = $form->get('img250')->getData();
            $media1920 = $form->get('img1920')->getData();

            // Traitement du fichier s'il a été telechargé
            if ($media250 && $media1920){
                $file250 = $this->gestionMedia->upload($media250, 'img250');
                $file1920 = $this->gestionMedia->upload($media1920, 'img1920');

                $medium->setImg250($file250);
                $medium->setImg1920($file1920);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($medium);
            $entityManager->flush();

            return $this->redirectToRoute('media_new',['article'=>$medium->getId()]);
        }

        return $this->render('media/new.html.twig', [
            'medium' => $medium,
            'form' => $form->createView(),
            'medias' => $this->mediaRepository->findBy([],['id'=>'DESC'])
        ]);
    }

    /**
     * @Route("/{id}", name="media_show", methods={"GET"})
     */
    public function show(Media $medium): Response
    {
        return $this->render('media/show.html.twig', [
            'medium' => $medium,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="media_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Media $medium): Response
    {
        $form = $this->createForm(MediaType::class, $medium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('media_index');
        }

        return $this->render('media/edit.html.twig', [
            'medium' => $medium,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="media_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Media $medium): Response
    {
        if ($this->isCsrfTokenValid('delete'.$medium->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($medium);
            $entityManager->flush();
        }

        return $this->redirectToRoute('media_index');
    }
}
