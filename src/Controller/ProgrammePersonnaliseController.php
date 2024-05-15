<?php

namespace App\Controller;

use App\Entity\ProgrammePersonnalise;
use App\Form\ProgrammePersonnaliseType;
use App\Repository\ProgrammePersonnaliseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/programme/personnalise')]
class ProgrammePersonnaliseController extends AbstractController
{
    #[Route('/', name: 'app_programme_personnalise_index', methods: ['GET'])]
    public function index(ProgrammePersonnaliseRepository $programmePersonnaliseRepository): Response
    {
        return $this->render('programme_personnalise/index.html.twig', [
            'programme_personnalises' => $programmePersonnaliseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_programme_personnalise_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $programmePersonnalise = new ProgrammePersonnalise();
        $form = $this->createForm(ProgrammePersonnaliseType::class, $programmePersonnalise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($programmePersonnalise);
            $entityManager->flush();

            return $this->redirectToRoute('app_programme_personnalise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('programme_personnalise/new.html.twig', [
            'programme_personnalise' => $programmePersonnalise,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_programme_personnalise_show', methods: ['GET'])]
    public function show(ProgrammePersonnalise $programmePersonnalise): Response
    {
        return $this->render('programme_personnalise/show.html.twig', [
            'programme_personnalise' => $programmePersonnalise,
        ]);
    }

    #[Route('/front/{id}', name: 'app_programme_personnalise_showFront', methods: ['GET'])]
    public function showFront(ProgrammePersonnalise $programmePersonnalise): Response
    {
        return $this->render('programme_personnalise/showFront.html.twig', [
            'programme_personnalise' => $programmePersonnalise,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_programme_personnalise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProgrammePersonnalise $programmePersonnalise, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProgrammePersonnaliseType::class, $programmePersonnalise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_programme_personnalise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('programme_personnalise/edit.html.twig', [
            'programme_personnalise' => $programmePersonnalise,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_programme_personnalise_delete', methods: ['POST'])]
    public function delete(Request $request, ProgrammePersonnalise $programmePersonnalise, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $programmePersonnalise->getId(), $request->request->get('_token'))) {
            $entityManager->remove($programmePersonnalise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_programme_personnalise_index', [], Response::HTTP_SEE_OTHER);
    }
}
