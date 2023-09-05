<?php

namespace App\Controller\Api\Admin\Protocol;

use App\Controller\Api\Admin\Protocol\Action as Actions;
use App\Entity\Protocol;
use App\Security\Voter\ProtocolVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/api/admin/protocol', name: 'app_api_admin_protocol_')]
class ProtocolController extends AbstractController
{
    public function __construct(
        private readonly Actions\CreateAction $createAction,
        private readonly Actions\UpdateAction $updateAction,
        private readonly Actions\ShowAction   $showAction,
        private readonly Actions\DeleteAction $deleteAction,
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
}