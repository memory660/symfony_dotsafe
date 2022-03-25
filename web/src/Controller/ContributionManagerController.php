<?php

namespace App\Controller;

use App\Entity\Contribution;
use App\Form\ContributionType;
use App\Repository\ContributionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/contribution-manager')]
class ContributionManagerController extends AbstractController
{
    #[Route('/', name: 'app_contribution_manager_index', methods: ['GET'])]
    public function index(ContributionRepository $contributionRepository): Response
    {
        return $this->render('contribution/index.html.twig', [
            'contributions' => $contributionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_contribution_manager_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ContributionRepository $contributionRepository): Response
    {
        $contribution = new Contribution();
        $form = $this->createForm(ContributionType::class, $contribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contributionRepository->add($contribution);
            return $this->redirectToRoute('app_contribution_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contribution/new.html.twig', [
            'contribution' => $contribution,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contribution_manager_show', methods: ['GET'])]
    public function show(Contribution $contribution): Response
    {
        return $this->render('contribution/show.html.twig', [
            'contribution' => $contribution,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_contribution_manager_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Contribution $contribution, ContributionRepository $contributionRepository): Response
    {
        $form = $this->createForm(ContributionType::class, $contribution);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contributionRepository->add($contribution);
            return $this->redirectToRoute('app_contribution_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contribution/edit.html.twig', [
            'contribution' => $contribution,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_contribution_manager_delete', methods: ['POST'])]
    public function delete(Request $request, Contribution $contribution, ContributionRepository $contributionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contribution->getId(), $request->request->get('_token'))) {
            $contributionRepository->remove($contribution);
        }

        return $this->redirectToRoute('app_contribution_index', [], Response::HTTP_SEE_OTHER);
    }
}
