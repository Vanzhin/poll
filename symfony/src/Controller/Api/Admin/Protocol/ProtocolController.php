<?php

namespace App\Controller\Api\Admin\Protocol;

use App\Controller\Api\Admin\Protocol\Action as Actions;
use App\Entity\Protocol\Protocol;
use App\Repository\Protocol\ProtocolRepository;
use App\Response\AppException;
use App\Security\Voter\ProtocolVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/api/admin/protocol', name: 'app_api_admin_protocol_')]
class ProtocolController extends AbstractController
{
//    todo вынести в конфиг?
// вынести файлы из public
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
        private readonly ProtocolRepository            $repository,
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

    #[Route('/generate', name: 'generate', methods: ['POST', 'GET'])]
    public function generate(Request $request): JsonResponse
    {
        $protocols = $this->getProtocol($request);
        if (!current($protocols)) {
            throw new AppException('Протоколы не найдены.');
        }
        return $this->generateAction->run(...$protocols);
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

    #[Route('/download', name: 'download', methods: ['POST'])]
    public function download(Request $request): BinaryFileResponse
    {
        $protocols = $this->getProtocol($request);
        if (!current($protocols)) {
            throw new AppException('Протоколы не найдены.');
        }
        return $this->downloadAction->run(...$protocols);

    }

    #[Route('/mass_create', name: 'mass_create', methods: ['POST'])]
    public function massCreate(Request $request): JsonResponse
    {
        return $this->creationAction->run($request);
    }

    private function getProtocol(Request $request, string $property = 'list'): array
    {
        $protocols = [];
        $data = json_decode($request->getContent(), true);
        if (isset($data[$property]) && is_array($data[$property])) {
            foreach ($data[$property] as $protocolId) {
                $protocol = $this->repository->find($protocolId);
                if ($protocol) {
                    if (!$this->isGranted(ProtocolVoter::VIEW, $protocol)) {
                        throw new AccessDeniedException();
                    };
                    $protocols[] = $protocol;
                } else {
                    throw new AppException('Протокол не найден.');
                }
            }
        };
        return $protocols;
    }
}