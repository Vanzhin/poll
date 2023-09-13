<?php

namespace App\Controller\Api\Admin\Protocol;

use App\Controller\Api\Admin\Protocol\Action as Actions;
use App\Entity\Protocol\Protocol;
use App\Security\Voter\ProtocolVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/api/admin/protocol', name: 'app_api_admin_protocol_')]
class ProtocolController extends AbstractController
{
//    todo вынести в конфиг?
    public const PROTOCOL_PATH = 'protocols';
    public const TEMPLATE_PATH = 'protocols/templates';

    public function __construct(
        private readonly Actions\CreateAction          $createAction,
        private readonly Actions\UpdateAction          $updateAction,
        private readonly Actions\ShowAction            $showAction,
        private readonly Actions\DeleteAction          $deleteAction,
        private readonly Actions\GenerateAction        $generateAction,
        private readonly Actions\ListAction            $listAction,
        private readonly Actions\GetTemplateListAction $templateListAction,
        private readonly Actions\DownloadAction        $downloadAction,
        private readonly Actions\MassCreationAction    $creationAction,
    )
    {
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        return $this->createAction->run($request);
    }

    #[Route('/{id<\d+>}', name: 'update', methods: ['PUT'])]
    public function update(Protocol $protocol, Request $request): JsonResponse
    {
        if (!$this->isGranted(ProtocolVoter::EDIT, $protocol)) {
            throw new AccessDeniedException();
        };
        return $this->updateAction->run($protocol, $request);
    }

    #[Route('/{id<\d+>}', name: 'show', methods: ['GET'])]
    public function show(Protocol $protocol): JsonResponse
    {
        if (!$this->isGranted(ProtocolVoter::VIEW, $protocol)) {
            throw new AccessDeniedException();
        };
        return $this->showAction->run($protocol);
    }

    #[Route('/{id<\d+>}', name: 'delete', methods: ['DELETE'])]
    public function delete(Protocol $protocol): JsonResponse
    {
        if (!$this->isGranted(ProtocolVoter::DELETE, $protocol)) {
            throw new AccessDeniedException();
        };
        return $this->deleteAction->run($protocol);
    }

    #[Route('/generate/{id<\d+>}', name: 'generate', methods: ['GET'])]
    public function generate(Protocol $protocol, Request $request): JsonResponse
    {
        if (!$this->isGranted(ProtocolVoter::VIEW, $protocol)) {
            throw new AccessDeniedException();
        };
        return $this->generateAction->run($protocol, $request);
    }

    #[Route('/list', name: 'list', methods: ['GET', 'POST'])]
    public function list(Request $request): JsonResponse
    {
        return $this->listAction->run($request);
    }

    #[Route('/template/list', name: 'template_list', methods: ['GET'])]
    public function getTemplateList(): JsonResponse
    {
        return $this->templateListAction->run();
    }

    #[Route('/{id<\d+>}/download', name: 'download', methods: ['GET', 'POST'])]
    public function download(Protocol $protocol, Request $request): StreamedResponse
    {
        return $this->downloadAction->run($protocol, $request);
    }

    #[Route('/mass_create', name: 'mass_create', methods: ['POST'])]
    public function massCreate(Request $request): JsonResponse
    {
        return $this->creationAction->run($request);
    }
}