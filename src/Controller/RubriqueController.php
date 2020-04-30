<?php

namespace App\Controller;

use App\Entity\Rubrique;
use App\Form\RubriqueType;
use App\Repository\RubriqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/backend/rubrique")
 */
class RubriqueController extends AbstractController
{
    /**
     * @Route("/", name="rubrique_index", methods={"GET","POST"})
     */
    public function index(Request $request, RubriqueRepository $rubriqueRepository): Response
    {
        $rubrique = new Rubrique();
        $form = $this->createForm(RubriqueType::class, $rubrique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rubrique);
            $entityManager->flush();

            return $this->redirectToRoute('rubrique_index');
        }

        return $this->render('rubrique/index.html.twig', [
            'rubriques' => $rubriqueRepository->findAll(),
            'rubrique' => $rubrique,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="rubrique_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $rubrique = new Rubrique();
        $form = $this->createForm(RubriqueType::class, $rubrique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rubrique);
            $entityManager->flush();

            return $this->redirectToRoute('rubrique_index');
        }

        return $this->render('rubrique/new.html.twig', [
            'rubrique' => $rubrique,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rubrique_show", methods={"GET"})
     */
    public function show(Rubrique $rubrique): Response
    {
        return $this->render('rubrique/show.html.twig', [
            'rubrique' => $rubrique,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="rubrique_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Rubrique $rubrique, RubriqueRepository $rubriqueRepository): Response
    {
        $form = $this->createForm(RubriqueType::class, $rubrique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rubrique_index');
        }

        return $this->render('rubrique/edit.html.twig', [
            'rubriques' => $rubriqueRepository->findAll(),
            'rubrique' => $rubrique,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rubrique_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Rubrique $rubrique): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rubrique->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rubrique);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rubrique_index');
    }
}
