<?php

namespace App\Controller;

use App\Repository\ContributionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/default', name: 'app_default')]
    public function index(ContributionRepository $contributionRepository): Response
    {

        $contributions = $contributionRepository->findAll();
dump($contributions);

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'contributions' => $contributions,
        ]);
    }
}
