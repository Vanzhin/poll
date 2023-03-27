<?php

namespace App\Controller\Api\Admin;

use App\Entity\Section;
use App\Entity\Test;
use App\Factory\Section\SectionFactory;
use App\Repository\SectionRepository;
use App\Service\Paginator;
use App\Service\SectionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class SectionController extends AbstractController
{
    #[Route('/api/admin/section/test/{id}', name: 'app_api_admin_section_test')]
    public function showAllByTest(Test $test, Paginator $paginator, SectionRepository $repository): JsonResponse
    {
        $pagination = $paginator->getPagination($repository->findLastUpdatedByTestQuery($test));
        if ($pagination->count() > 0) {
            $response['section'] = $pagination;

        }
        $response['pagination'] = $paginator->getInfo($pagination);
        return $this->json(
            $response,
            200,
            ['charset=utf-8'],
            [
                'groups' => 'admin_test_section',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/section/{id}', name: 'app_api_admin_section_show', methods: 'GET')]
    public function show(Section $section): JsonResponse
    {
        return $this->json(
            $section,
            200,
            ['charset=utf-8'],
            [
                'groups' => 'admin_section',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/section/create', name: 'app_api_admin_section_create', methods: 'POST')]
    public function create(Request $request, SectionFactory $factory, SectionService $sectionService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $response = $sectionService->saveIfValid($factory->createBuilder()->buildSection($data));
        return $this->json($response['response'],
            $response['status'],
            ['charset=utf-8'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/section/{id}/edit', name: 'app_api_admin_section_edit', methods: 'POST')]
    public function edit(Section $section, Request $request, SectionFactory $factory, SectionService $sectionService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $response = $sectionService->saveIfValid($factory->createBuilder()->buildSection($data, $section));
        return $this->json($response['response'],
            $response['status'],
            ['charset=utf-8'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/section/{id}/delete', name: 'app_api_admin_section_delete', methods: 'GET')]
    public function delete(Section $section, SectionService $sectionService): JsonResponse
    {
        $response = $sectionService->deleteResponse($section);
        return $this->json($response['response'],
            $response['status'],
            ['charset=utf-8'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
