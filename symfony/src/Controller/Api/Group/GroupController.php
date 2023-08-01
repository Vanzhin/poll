<?php

namespace App\Controller\Api\Group;

use App\Entity\Group;
use App\Security\Voter\GroupVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Controller\Api\Group\Action as Actions;

#[Route('/api/group', name: 'app_api_group_')]
#[IsGranted('ROLE_USER')]
class GroupController extends AbstractController
{
    public function __construct(
        private readonly Actions\CreateAction      $createAction,
        private readonly Actions\ShowAction        $showAction,
        private readonly Actions\UpdateAction      $updateAction,
        private readonly Action\DeleteAction       $deleteAction,
        private readonly Actions\ChangeOwnerAction $changeOwnerAction,
        private readonly Actions\ListAction        $listAction,
    )
    {
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        if (!$this->isGranted('ROLE_TUTOR')) {
            throw new AccessDeniedException();
        };
        return $this->createAction->run($request, $this->getUser());
    }

    #[Route('/{id<\d+>}', name: 'update', methods: ['PUT'])]
    public function update(Group $group, Request $request): JsonResponse
    {
        if (!$this->isGranted(GroupVoter::MANAGE, $group)) {
            throw new AccessDeniedException();
        };
        return $this->updateAction->run($request, $group, $this->getUser());
    }

    #[Route('/{id<\d+>}/change-owner', name: 'change-owner', methods: ['PUT'])]
    public function changeOwner(Group $group, Request $request): JsonResponse
    {
        if (!$this->isGranted(GroupVoter::MANAGE, $group)) {
            throw new AccessDeniedException();
        };
        return $this->changeOwnerAction->run($request, $group);
    }

    #[Route('/{id<\d+>}', name: 'show', methods: ['GET'])]
    public function show(Group $group): JsonResponse
    {
        if (!$this->isGranted(GroupVoter::VIEW, $group)) {
            throw new AccessDeniedException();
        };
        return $this->showAction->run($group);
    }

    #[Route('/{id<\d+>}', name: 'delete', methods: ['DELETE'])]
    public function delete(Group $group): JsonResponse
    {
        return $this->deleteAction->run($group);
    }

    #[Route('/list', name: 'list', methods: ['GET', 'POST'])]
    public function list(Request $request): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        };
        $data = array_merge($request->query->all() ?? [], json_decode($request->getContent(), true) ?? []);

        return $this->listAction->run($data);
    }

    #[Route('/my/list', name: 'my_list', methods: ['GET', 'POST'])]
    public function myList(Request $request): JsonResponse
    {
        if (!$this->isGranted('ROLE_TUTOR')) {
            throw new AccessDeniedException();
        };
        $data = array_merge($request->query->all() ?? [], json_decode($request->getContent(), true) ?? []);
        $data['filter']['owner'] = (string)$this->getUser()->getId();
        return $this->listAction->run($data);
    }


}