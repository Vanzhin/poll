<?php

namespace App\Controller\Api\BaseAction;

use App\Service\FileUploader;
use App\Service\SerializerService;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewBaseAction
{

    public function __construct(private readonly SerializerService $serializer)
    {
    }

    public function errorResponse(array|string $data, int $status = 422): JsonResponse
    {
        if (is_string($data)) {
            $data = ["error" => $data];
        }
        return new JsonResponse($data, $status, []);

    }

    public function successResponse(array|object $object, array $groups = [], string $message = 'Успешное выполнение', string $format = 'json', int $status = 200): JsonResponse
    {
        $data = [
            'result' => 'success',
            'status' => $status,
            'data' => json_decode($this->serializer->serializeObject($object, $format, $groups), true),
            'message' => $message,
        ];
        return new JsonResponse($data, $status, []);
    }

    public function successFileResponse(array $data): Response
    {
        $response = new Response($data['content']);
        $fileName = $data['fileName'] ?? 'test' . '.xml';
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $fileName
        );

        $response->headers->set('Content-Disposition', $disposition);
        return $response;
    }

    public function streamFileResponse(SplFileInfo $fileInfo, FileUploader $fileUploader): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($fileInfo, $fileUploader) {
            $outputStream = fopen('php://output', 'wb');
            $fileStream = $fileUploader->readStream($fileInfo);
            stream_copy_to_stream($fileStream, $outputStream);
        });
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $fileInfo->getFilename()
        );

        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}