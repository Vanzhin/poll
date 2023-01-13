<?php

namespace App\Controller;

use App\Repository\ResultRepository;
use App\Service\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER')]
class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(Paginator $paginator, ResultRepository $resultRepository): Response
    {
        return $this->render('account/index.html.twig', [
            'pagination' => $paginator->getPagination($resultRepository->findLastUpdatedByUserQuery($this->getUser()), 10)
        ]);

    }
}
