<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Techno;
use App\Form\Manager\TechnoSelectType;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/projects-manager')]
class ProjectManagerController extends AbstractController
{

    #[Route('/', name: 'app_projects_manager_index', methods: ['GET', 'POST'])]
    public function index(Request $request, ProjectRepository $projectRepository): Response
    {
        $techno = new Techno();

        $technos = [''=> null, 'java' => 'java', 'angular' => 'angular',];
        $members = [''=> null, 'm1' => 'm1', 'm2' => 'm2',];
        $form = $this->createForm(TechnoSelectType::class, null, array('choices' => ['technos' => $technos, 'members' => $members]));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            dump($form->getData());
            //$projectRepository->add($techno);
            //return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('manager/projects_manager.html.twig', [
            'project' => $techno,
            'form' => $form,
        ]);
    }


}
