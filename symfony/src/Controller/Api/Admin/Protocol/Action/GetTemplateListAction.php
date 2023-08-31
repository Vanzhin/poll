<?php

namespace App\Controller\Api\Admin\Protocol\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Service\FileHandler;
use App\Service\SerializerService;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetTemplateListAction extends NewBaseAction
{
    public function __construct(SerializerService $serializer, private readonly FileHandler $fileHandler)
    {
        parent::__construct($serializer);
    }

    public function run(): JsonResponse
    {
        $list = array_map(
            fn(string $file) => preg_replace('/\.\w+$/', '', $file),
            $this->fileHandler->getFilesList('protocols/templates', 'docx')
        );
        return $this->successResponse($list);
    }
}