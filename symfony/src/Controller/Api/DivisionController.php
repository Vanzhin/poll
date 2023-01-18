<?php

namespace App\Controller\Api;

use App\Entity\Division;
use App\Repository\DivisionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


class DivisionController extends AbstractController
{
    #[Route('/api/division', name: 'app_api_division',  methods: ['GET'])]
    public function index(DivisionRepository $repository): JsonResponse
    {
        $divisions = $repository->findAll();


        return $this->json(
            $divisions,
            200,
            ['charset=utf-8'],
            ['groups' => 'main'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/division/{slug}', name: 'app_api_division_show',  methods: ['GET'])]
    public function show(Division $division): JsonResponse
    {

        return $this->json(
            $division,
            200,
            ['charset=utf-8'],
            ['groups' => 'main'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
